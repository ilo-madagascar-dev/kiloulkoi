<?php

namespace App\Controller;

<<<<<<< HEAD
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesController extends AbstractController
{
    /**
     * @Route("/annonces",name="annonces")
     */
    public function index()
    {
        return $this->render('annonces/index.html.twig', [
            'controller_name' => 'AnnoncesController',
        ]);
    }
=======
use App\Entity\Annonces;
use App\Entity\Categories;
use App\Form\AnnoncesType;
use App\Form\VehiculeType;
use App\Form\ImmobilierType;
use App\Form\AnnoncesType_test;
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
        return $this->render('annonces/index.html.twig', [
            'annonces' => $annoncesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="annonces_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
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
        
//        if ($request->isXmlHttpRequest()) {
//            $repository = $this->getDoctrine()->getRepository(Categories::class);
//            $annonce = new Annonces();
//            annonces[categorie]
//            $idCate = $request->request->get('id');
//            $categorie = $repository->find(intval($idCate));
//            $annonce->setCategorie($categorie);
//            $form = $this->createForm(AnnoncesType::class, $annonce);
//            
//            $form->handleRequest($request);
//            $formHtml = $this->renderView('annonces/_form.html.twig', array(
//                'form' => $form->createView(),
//            ));
//            return new JsonResponse(
//                array(
//                    'formHtml' => $formHtml
//                )
//            );
//        }
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
>>>>>>> 5d39ba55b5ac1b79d7adc01fbd9ef9a6a0fcfe80
}
