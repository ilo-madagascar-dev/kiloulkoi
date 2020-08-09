<?php

namespace App\Controller\Annonce;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

/**
 * @Route("/annonces")
 */
class AnnoncesController extends AbstractController
{
    /**
     * @Route("/", name="annonces_index", methods={"GET"})
     */
    public function index(AnnoncesRepository $annoncesRepository): Response
    {
        $repoCategorie = $this->getDoctrine()->getRepository(Categories::class);
        $repoAnnonce   = $this->getDoctrine()->getRepository(Annonces::class);
        
        $categories = $repoCategorie->findAll();
        $annonces   = $repoAnnonce->findAll();
        
        return $this->render('annonces/index.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
        ]);
    }


    /**
     * @Route("/mes_annonces", name="mes_annonces_index", methods={"GET"})
     */
    public function mesAnnonces(): Response
    {
        $user = $this->getUser();
        if ($user == null) 
        {
            return $this->redirectToRoute('securitylogin');
        }
        
        $repository = $this->getDoctrine()->getRepository(Categories::class);
        $categories = $repository->findAll();
        $annonces   = $user->getAnnonces();

        return $this->render('annonces/mesAnnonces.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces
        ]);
    }


    /**
     * @Route("/filter", name="annonces_filtre", methods={"GET"})
     */
    public function filter(Request $request): Response
    {
        $repCategorie = $this->getDoctrine()->getRepository(Categories::class);
        $repAnnonce   = $this->getDoctrine()->getRepository(Annonces::class);
        
        $categories = $repCategorie->findAll();
        $annonces   = $repAnnonce->findAnnonces($request->query->all());
        
        $annonce_titre = $request->query->has('titre') ? "Résultats des recherches" : "Catégorie: ";
        $annonce_titre = "Résultats des recherches";
        
        return $this->render('annonces/mesAnnonces.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
            'annonce_title' => $annonce_titre
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

        $repCategorie = $this->getDoctrine()->getRepository(Categories::class);
        $nomClasse    = ucfirst($request->query->get('categorie'));
        $categorie    = $repCategorie->findOneBy(['className' => $nomClasse]);
        if( $categorie == null)
        {
            // Category not found....
            return $this->redirectToRoute('annonces_new');
        }

        $class    = 'App\Entity\\' . $nomClasse;
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
            $annonce->setType($request->query->get('type'));

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
     * @Route("/{id}", name="annonces_show", methods={"GET"})
     */
    public function show(Annonces $annonce): Response
    {
        $user = $this->getUser();
        if ($user == null || ($user !== null && $user->getId() !== $annonce->getUser()->getId()) ) 
        {
            $annonce->setVisite( intval($annonce->getVisite()) + 1);
        }
        
        $this->getDoctrine()->getManager()->flush();

        return $this->render('annonces/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/{id}/modification", name="annonces_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Annonces $annonce, FileUploader $fileUploader): Response
    {
        if ($this->getUser() == null) 
        {
            return $this->redirectToRoute('securitylogin');
        }

        $class = 'App\Form\Category\\' . substr(get_class($annonce), 11) . 'Type';
        $form  = $this->createForm($class, $annonce);
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

            $annonce->setDateModification();
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annonces_index');
        }

        return $this->render('annonces/edit.html.twig', [
            'annonce' => $annonce,
            'photos' => $annonce->getPhoto(),
            'categorie' => $annonce->getCategorie(),
            'form' => $form->createView(),
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
