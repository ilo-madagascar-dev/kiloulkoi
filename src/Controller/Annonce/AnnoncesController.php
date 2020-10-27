<?php

namespace App\Controller\Annonce;

use App\Entity\Annonces;
use App\Repository\AbonnementRepository;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/annonces")
 */
class AnnoncesController extends AbstractController
{
    private $repAnnonce;
    private $repCategorie;

    public function __construct(AnnoncesRepository $repAnnonce, CategoriesRepository $repCategorie)
    {
        $this->repAnnonce = $repAnnonce;
        $this->repCategorie = $repCategorie;
    }

    /**
     * @Route("/", name="annonces_index", methods={"GET"})
     */
    public function index(): Response
    {
        $categories = $this->repCategorie->findParents();
        $annonces   = $this->repAnnonce->findAllAnnonces()->getResult();

        return $this->render('annonces/index.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
        ]);
    }

    /**
     * @Route("/heart/{id}", name="annonces_heart", methods={"GET"})
     */
    public function heart(Annonces $annonce): Response
    {
        $user      = $this->getUser();
        $isFavoris = $this->repAnnonce->checkFavoris($user->getId(), $annonce->getId());
        if( $isFavoris < 1 )
        {
            if( $user->getId() !== $annonce->getUser()->getId() )
            {
                $user->addFavori( $annonce );
                $isFavoris = 1;
                $this->getDoctrine()->getManager()->flush();
            }
        }
        else
        {
            $user->removeFavori( $annonce );
            $isFavoris = 0;
            $this->getDoctrine()->getManager()->flush();
        }

        return new Response( $isFavoris > 0 ? 1 : 0);
    }
    
    /**
     * @Route("/mes-annonces", name="mes_annonces_index", methods={"GET"})
     */
    public function mesAnnonces(): Response
    {
        $user = $this->getUser();
        if ($user == null) 
        {
            return $this->redirectToRoute('securitylogin');
        }

        $categories = $this->repCategorie->findParents();
        $annonces   = $this->repAnnonce->findMesAnnonces($user->getId())->getResult();
        $annonce_titre = "Mes annonces";

        return $this->render('annonces/index.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
            'annonce_titre' => $annonce_titre
        ]);
    }
    
    /**
     * @Route("/filter", name="annonces_filter", methods={"GET"})
     */
    public function filter(Request $request): Response
    {
        $categories    = $this->repCategorie->findParents();
        $annonces      = $this->repAnnonce->findAnnonces($request->query->all())->getResult();
        $annonce_titre = "Résultats des recherches";
        
        return $this->render('annonces/mesAnnonces.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
            'annonce_titre' => $annonce_titre
        ]);
    }

    
    /**
     * @Route("/favoris", name="annonces_favoris", methods={"GET"})
     */
    public function favoris(Request $request): Response
    {
        $categories    = $this->repCategorie->findParents();
        $annonces      = $this->repAnnonce->findFavoris( $this->getUser()->getId() )->getResult();
        $annonce_titre = "Mes Favoris";

        return $this->render('annonces/mesAnnonces.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
            'annonce_titre' => $annonce_titre
        ]);
    }

    /**
     * @Route("/categorie/{categorie_slug}/{sous_categorie_slug}", name="annonces_categorie", methods={"GET"})
     */
    public function categorie(string $categorie_slug, string $sous_categorie_slug)
    {
        $criteria         = [
            'categorie' => $categorie_slug,
            'sous_categorie' => $sous_categorie_slug,
        ];

        $categories = $this->repCategorie->findAllWithSousCategorie();
        $sous_cat   = $this->repCategorie->findOneBy(['slug' => $sous_categorie_slug]);
        $annonces   = $this->repAnnonce->findAnnonces($criteria)->getResult();

        foreach($categories as $c)
        {
            if( $c->getClassName() == $categorie_slug)
            {
                $annonce_titre = $sous_cat->getLibelle() . ' (' . $c->getLibelle()  .')';
            }
        }

        $annonce_titre = empty($annonce_titre) ? "Résultats des recherches" : $annonce_titre;
        
        return $this->render('annonces/mesAnnonces.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
            'annonce_titre' => $annonce_titre
        ]);
    }

    /**
     * @Route("/creation", name="annonces_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $user = $this->getUser();
        if ($user == null) 
        {
            return $this->redirectToRoute('securitylogin');
        }

        $nomClasse    = ucfirst($request->query->get('categorie'));
        $categorie    = $this->repCategorie->findOneBy(['className' => $nomClasse]);

        if( $categorie == null)
        {
            // Category not found....
            return $this->redirectToRoute('annonces_index');
        }

        $class    = 'App\Entity\Annonce' . $nomClasse;
        $formType = 'App\Form\Category\\' . trim($nomClasse) . 'Type';
        $annonce  = new $class();

        $form = $this->createForm($formType, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $photos = $form->get('photo')->getData();
            foreach ($photos as $photo )
            {
                if( $photo->getFile() )
                {
                    $fileName = $fileUploader->upload($photo->getFile());
                    $photo->setUrl($fileName);

                    $annonce->getPhoto()->add($photo);
                }
            }

            $annonce->setUser($user);
            $annonce->setCategorie($categorie);
            $annonce->setValidationAdmin(false);
            $annonce->setVisite(0);
            // $annonce->setType($request->query->get('type'));
            $annonce->setSlug();

            $annonce->setProucentageTva(0.5);
            $annonce->setDateModification();

            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('mes_annonces_index');
        }

        return $this->render('annonces/edit.html.twig', [
            'annonce'   => $annonce,
            'form'      => $form->createView(),
            'photos'    => $annonce->getPhoto(),
            'categorie' => $categorie
        ]);
    }

    /**
     * @Route("/details/{id}/{slug}", name="annonces_show", methods={"GET"})
     */
    public function show(int $id, string $slug, AbonnementRepository $repoAbonnement, SerializerInterface $serializer): Response
    {
        $user    = $this->getUser();
        $annonce = $this->repAnnonce->findAnnonceById($id);

        $abonnement = $repoAbonnement->findOneBy( ['user' => $user->getId() ]);
        $photoMax   = ($abonnement && $abonnement->getId()) == 2 ? 6 : 3;

        if ($user == null || ($user !== null && $user->getId() !== $annonce->getUser()->getId()) ) 
        {
            $annonce->setVisite( intval($annonce->getVisite()) + 1);
            $this->getDoctrine()->getManager()->flush();
        }

        $annonce_serialized = $serializer->normalize($annonce, null, [AbstractNormalizer::IGNORED_ATTRIBUTES => [
            'photo', 'user', 'conversations', 'categorie', 'sousCategorie', 'locations',  // Relation to ignore
            'id', 'titre', 'description', 'prix', 'proucentageTva',                       //////////////
            'dateCreation', 'dateModification', 'statut',                                 // Annonce's parent attributes
            'visite', 'slug', 'validationAdmin', 'type', 'userFavoris'                    //////////////
        ]]);

        return $this->render('annonces/show.html.twig', [
            'annonce'  => $annonce,
            'photoMax' => $photoMax,
            'annonce_serialized' => $annonce_serialized,
            'user_annonces'      => $annonce->getUser()->getAnnonces()->count()
        ]); 
    }

    /**
     * @Route("/{id}/modification", name="annonces_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Annonces $annonce, AbonnementRepository $repoAbonnement, FileUploader $fileUploader): Response
    {
        $user = $this->getUser();
        if ( $user == null) 
        {
            return $this->redirectToRoute('securitylogin');
        }

        $formType = str_replace('Annonce', '', substr(get_class($annonce), 11) );
        $class    = 'App\Form\Category\\' . $formType . 'Type';
        $form     = $this->createForm($class, $annonce);
        
        $abonnement = $repoAbonnement->findOneBy( ['user' => $user->getId() ]);
        $photoMax   = ($abonnement && $abonnement->getId()) == 2 ? 6 : 3;
        
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) 
        {
            $max    = 0;
            $photos = $form->get('photo')->getData();
            foreach ($photos as $photo )
            {
                if( $photo->getFile() )
                {
                    $fileName = $fileUploader->upload($photo->getFile());
                    $photo->setUrl($fileName);

                    $annonce->getPhoto()->add($photo);
                }

                if( $max >= $photoMax )
                    break;
                
                $max++;
            }

            $annonce->setSlug();
            $annonce->setDateModification();
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annonces_index');
        }

        return $this->render('annonces/edit.html.twig', [
            'annonce'  => $annonce,
            'photos'   => $annonce->getPhoto(),
            'categorie'=> $annonce->getCategorie(),
            'form'     => $form->createView(),
            'photoMax' => $photoMax,
        ]);
    }

    /**
     * @Route("/{id}", name="annonces_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Annonces $annonce): Response
    {
        if ($this->getUser() == null) 
        {
            return $this->redirectToRoute('securitylogin');
        }
        
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) 
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annonces_index');
    }


}
