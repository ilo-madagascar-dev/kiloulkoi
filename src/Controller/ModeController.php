<?php

namespace App\Controller;

use App\Entity\Mode;
use App\Form\ModeType;
use App\Repository\ModeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mode")
 */
class ModeController extends AbstractController
{
    /**
     * @Route("/", name="mode_index", methods={"GET"})
     */
    public function index(ModeRepository $modeRepository): Response
    {
        return $this->render('mode/index.html.twig', [
            'modes' => $modeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="mode_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $mode = new Mode();
        $form = $this->createForm(ModeType::class, $mode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mode);
            $entityManager->flush();

            return $this->redirectToRoute('mode_index');
        }

        return $this->render('mode/new.html.twig', [
            'mode' => $mode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mode_show", methods={"GET"})
     */
    public function show(Mode $mode): Response
    {
        return $this->render('mode/show.html.twig', [
            'mode' => $mode,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="mode_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Mode $mode): Response
    {
        $form = $this->createForm(ModeType::class, $mode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mode_index');
        }

        return $this->render('mode/edit.html.twig', [
            'mode' => $mode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mode_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Mode $mode): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mode->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mode_index');
    }
}
