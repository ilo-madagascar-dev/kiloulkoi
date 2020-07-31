<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Entity\Mode;
use App\Entity\User;
use App\Entity\Vehicule;
use App\Entity\VetementMaternite;
use App\Form\AnnoncesType;
use App\Form\ModeType;
use App\Form\VehiculeEditType;
use App\Form\VehiculeType;
use App\Form\ImmobilierType;
use App\Form\AnnoncesType_test;
use App\Form\VetementMaterniteType;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $userconnect = null;
        if ($this->getUser() == null) {
            return $this->redirectToRoute('accueil');
        }
        $userconnect = $this->getUser()->getId();
        $repository = $this->getDoctrine()->getRepository(Categories::class);
        $categories = $repository->findAll();
        $repositoryAnnonces = $this->getDoctrine()->getRepository(Annonces::class);
        $userconnect=$this->getUser()->getId();
        $annonces = $repositoryAnnonces->findOtherAnnonceById($userconnect);
        //dump($userconnect);
        //dump($annonces);die;
        return $this->render('annonces/index.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces
        ]);
    }

    /**
     * @Route("/mes_annonces", name="mes_annonces_index", methods={"GET"})
     */
    public function mesAnnonces(AnnoncesRepository $annoncesRepository): Response
    {
        $userconnect = null;
        if ($this->getUser() == null) {
            return $this->redirectToRoute('accueil');
        }
        $userconnect = $this->getUser()->getId();
        $repository = $this->getDoctrine()->getRepository(Categories::class);
        $categories = $repository->findAll();
        $repositoryAnnonces = $this->getDoctrine()->getRepository(Annonces::class);
        $userconnect=$this->getUser()->getId();
        $annonces = $repositoryAnnonces->findMesAnnonces($userconnect);
        //dump($userconnect);
        //dump($annonces);die;
        return $this->render('annonces/mesAnnonces.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces
        ]);
    }

    /**
     * @Route("/new", name="annonces_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('accueil');
        }
        $nomClasse = null;
        $classNameType = null;
        $annonce = null;
        $repositoryCategories = $this->getDoctrine()->getRepository(Categories::class);
        $repositoryUser = $this->getDoctrine()->getRepository(User::class);
        $user = null;
        $userconnect = null;
        if ($this->getUser() == null) {
            return $this->redirectToRoute('accueil');

        }
        $userconnect = $this->getUser()->getId();


        if ($request->isMethod("GET")) {
            $nomClasse = ucfirst($request->query->get('categorie'));
            //$userId = ucfirst($request->query->get('user'));
            $class = 'App\Entity\\' . $nomClasse;
            $annonce = new $class();

            if ($nomClasse != null) {
                $classNameType = 'App\Form\\' . $nomClasse . 'Type';
            }
            $categorie = $repositoryCategories->findOneBy(['className' => $nomClasse]);

        }
        $user = $repositoryUser->find($userconnect);
        if ($request->isMethod("POST")) {
            $requestForm = $request->request->all();
            foreach ($requestForm as $reqForm) {
                //categorie
                //dump($reqForm);
                $categorie = $repositoryCategories->find($reqForm['categorie']);
                $nomClasse = $categorie->getClassName();
                $classNameType = 'App\Form\\' . $nomClasse . 'Type';
                $class = 'App\Entity\\' . $nomClasse;
                $annonce = new $class();
            }
        }
        $annonce->setUser($user);
        $annonce->setCategorie($categorie);
        $form = $this->createForm($classNameType, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photos = $form->get('photo')->getData();
            for ($i = 0; $i < count($annonce->getPhoto()); $i++) {
                $photo = $annonce->getPhoto()[$i];
                $fileName = $fileUploader->upload($photo->getFile());
                $photo->setUrl($fileName) ;
                //dump();
            }
            //dump($annonce);
            //die;
            //$fileName = $fileUploader->upload($photos->getFile());
            //$annonce->setPhoto($annonce->getPhoto()->setUrl($fileName));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('annonces/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}", name="annonces_show", methods={"GET"})
     */
    public function show(Annonces $annonce): Response
    {
        //dump($annonce);
        $repositoryAnnonces = $this->getDoctrine()->getRepository('App\Entity\\' . substr(get_class($annonce), 11));
        $annonce = $repositoryAnnonces->findOneArrayById($annonce->getId());
        //
        return $this->render('annonces/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="annonces_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Annonces $annonce, FileUploader $fileUploader): Response
    {
        /*
        $class = substr(get_class($annonce), 11);
        $repositoryAnnonces = $this->getDoctrine()->getRepository('App\Entity\\' . $class);
        $annonce = $repositoryAnnonces->findOneById($annonce->getId());
   */
        $photo = $this->getDoctrine()->getRepository('App\Entity\Photo')->findBy(['annonces'=>$annonce->getId()]);
        //dump($photo);
        $annonce->setPhoto();
        if ($this->getUser() == null) {
            return $this->redirectToRoute('accueil');
        }
        $form = $this->createForm('App\Form\\' . substr(get_class($annonce), 11) . 'Type', $annonce);
        $form->handleRequest($request);
        //dump($annonce);
        if ($form->isSubmitted() && $form->isValid()) {
            $photos = $form->get('photo')->getData();
            for ($i = 0; $i < count($annonce->getPhoto()); $i++) {
                $photo = $annonce->getPhoto()[$i];
                $fileName = $fileUploader->upload($photo->getFile());
                $photo->setUrl($fileName) ;
                //dump();
            }
            $annonce->setDateModification();
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annonces_index');
        }

        return $this->render('annonces/edit.html.twig', [
            'annonce' => $annonce,
            'photoAnnonce' => $photo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annonces_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Annonces $annonce): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('accueil');
        }
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annonces_index');
    }


}
