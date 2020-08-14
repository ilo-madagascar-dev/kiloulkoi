<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utilisateur")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/profil", name="user_profil", methods={"GET"})
     */
    public function profil(): Response
    {
        $user = $this->getUser();
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(User $user, Request $request, FileUploader $uploader): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            if( $form->get('avatar')->getData() )
            {
                $uploader->setTargetDirectory($this->getParameter('avatar_directory'));
                $avatar_file = $form->get('avatar')->getData();
                $avatar_url  = $uploader->upload($avatar_file);
                $user->setAvatar($avatar_url);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_profil');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
