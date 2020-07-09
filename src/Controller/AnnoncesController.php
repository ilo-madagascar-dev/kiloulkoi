<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Entity\Mode;
use App\Entity\Vehicule;
use App\Entity\VetementMaternite;
use App\Form\AnnoncesType;
use App\Form\ModeType;
use App\Form\Vehicule1Type;
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
        $repository = $this->getDoctrine()->getRepository(Categories::class);
        $categories = $repository->findAll();
        return $this->render('annonces/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new", name="annonces_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $nomClasse = null;
        $classNameType = null;
        $annonce = null;
        $repositoryCategories = $this->getDoctrine()->getRepository(Categories::class);
        if ($request->isMethod("GET")) {
            $nomClasse = ucfirst($request->query->get('categorie'));
            $class = 'App\Entity\\' . $nomClasse;
            $annonce = new $class();

            if ($nomClasse != null) {
                $classNameType = 'App\Form\\' . $nomClasse . 'Type';
            }
            $categorie = $repositoryCategories->findOneBy(['libelle' => $nomClasse]);

        }

        if ($request->isMethod("POST")) {
            $requestForm = $request->request->all();
            foreach ($requestForm as $reqForm) {
                //categorie
                $categorie = $repositoryCategories->find($reqForm['categorie']);
                $nomClasse = $categorie->getLibelle();
                $classNameType = 'App\Form\\' . $nomClasse . 'Type';
                $class = 'App\Entity\\' . $nomClasse;
                $annonce = new $class();
            }
        }
        $annonce->setCategorie($categorie);
        $form = $this->createForm($classNameType, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('categories_index');
        }

        return $this->render('annonces/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/newe", name="annonces_newe", methods={"GET","POST"})
     */
    public function newe(Request $request): Response
    {
        $nomClasse = null;
        $lowerName = null;
        if ($request->isMethod("GET")) {
            $nomClasse = $request->query->get('categorie');
            $lowerName = lcfirst($nomClasse);
        }
        $repositoryCategories = $this->getDoctrine()->getRepository(Categories::class);
        $categorie = $repositoryCategories->findOneBy(['libelle' => $nomClasse]);
        $annonce = new Annonces();
        $annonce->setCategorie($categorie);
        //$form = $this->createForm(AnnoncesType::class, $annonce);
        //mety olana oe tsy nitov y le form nandefa sy namerena ka na sarahana le methode rehetra na le form mihitsy no amboarina
        $formBuilder = $this->createFormBuilder($annonce);
        
        $formBuilder->add('categorie', EntityType::class, [
                    // looks for choices from this entity
                    'class' => Categories::class,
                    'choice_label' => 'libelle',
                    ]);
        if ($nomClasse != null) {
            $classNameType = 'App\Form\\' . $nomClasse . 'Type';
            $formBuilder->add($lowerName, $classNameType);
        }
        if ($request->isMethod("POST")) {
            $requestForm = $request->request->all();
            $requetFormField = array_keys($requestForm["form"]);
            
            foreach ($requetFormField as $reqForm) {
                if (!$formBuilder->has($reqForm) && $reqForm != "_token") {
                    $classNameType = 'App\Form\\' . ucfirst($reqForm) . 'Type';
                    $formBuilder->add($reqForm, $classNameType);
                }
            }
//            $classNameType = 'App\Form\VehiculeType';
//            $formBuilder->add("vehicule", $classNameType);
        }
        $form = $formBuilder->getForm();
        
        $form->handleRequest($request);
        //dump($form->has('vehicule'));
        if ($form->isSubmitted() && $form->isValid()) {
            //dump($form);die;
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('annonces_index');
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
        return $this->render('annonces/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="annonces_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Annonces $annonce): Response
    {
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annonces_index');
        }

        return $this->render('annonces/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annonces_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Annonces $annonce): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annonces_index');
    }
}
