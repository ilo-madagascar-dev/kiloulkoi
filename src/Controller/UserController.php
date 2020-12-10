<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use App\Service\MangoPayService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

/**
 * @Route("/profil")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_profil", methods={"GET"})
     */
    public function profil(MangoPayService $mangoPayService): Response
    {
        $user = $this->getUser();
        $userMangoId = $this->getUser()->getMangoPayId();
        $usersmango =  $mangoPayService->getUser($userMangoId);
        $getkycdoc = $mangoPayService->getKYCDocs($userMangoId);
        $discr = strpos(get_class($user), 'Professionnel');
        
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'discr' => $discr,
            'usersmango' => $usersmango,
            'getkycdoc' => $getkycdoc
        ]);
    }

    /**
     * @Route("/popupkayc", name="user_popkyc", methods={"GET"})
     */
    public function popupKyc(MangoPayService $mangoPayService,MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $userMangoId = $this->getUser()->getMangoPayId();
        $usersmango =  $mangoPayService->getUser($userMangoId);
        $getkycdoc = $mangoPayService->getKYCDocs($userMangoId);
        $etat      = 'EMPTY';

        if( $getkycdoc == null )
        {
            $reponse = [
                'status' => 0,
                'titre' => 'KYC Document',
                'message' => 'Uploader votre KYC document',
                'lien' => $this->generateUrl('user_profil')
            ];
        }
        elseif( $usersmango->KYCLevel == 'LIGHT' )
        {
            $reponse = [
                'status' => $usersmango->KYCLevel,
                'titre' => 'KYC Document',
                'message' => 'La validation de votre KYC est en attente',
                'lien' => $this->generateUrl('user_profil')
            ];
        }
        else
        {
            $messages = 'KYC Document validé(s)';
            $email = (new TemplatedEmail())
                        ->from(new Address('njanahary46@gmail.com', 'Kiloukoi'))
                        ->to($this->getUser()->getEmail())
                        ->subject('Validation KYC')
                        ->htmlTemplate('user/email.html.twig')
                        ->context([
                            'messages' => $messages,
                        ])
            ;

            $mailer->send($email);
            $reponse = [
                'status' => 'SUCCESS',
                'titre' => 'KYC Document',
                'message' => 'KYC Document validé(s)',
                'lien' => $this->generateUrl('user_profil')
            ];
        }
        
        return new Response( json_encode($reponse) );
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
        $class = get_class($user);
        $formType = str_replace('Entity', 'Form', $class) . 'UserType' ;

        $form = $this->createForm($formType, $user);
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

            $user->setDateMiseAJour();

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_profil');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/uploadkyc", name="uploadkyc")
     */
    public function uploadfileKYC(MangoPayService $mangoPayService, Request $request, FileUploader $uploader)
    {
        /*$filePath = filePath('\www\projetkiloukoi\Kiloukoi.WEBAPP\var\mangopay\FLYER_FR.pdf');*/
        /*$file = base64_encode (file_get_contents($filePath));*/
        
        $file = $_FILES['kycfile']['tmp_name'];/*$uploader->upload($request->files->get('kycfile'));*/
        
        $mguId = $this->getUser()->getMangoPayId();
        
        $mangoPayService->setUserMangoPayKYC($mguId,$file);
        $this->addFlash('addKYC', 'Merci ! la verification de votre compte peut prendre une semaine, une notification vous sera envoye par email .');
        return $this->redirectToRoute('user_profil');
       
    }
}
