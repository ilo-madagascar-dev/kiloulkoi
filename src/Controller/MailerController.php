<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


class MailerController extends AbstractController
{
      /**
     * @var MailerInterface $mailer
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/email")
     */
    public function sendEmailMdp(User $userMdp)
    {
        $email = (new TemplatedEmail())
            ->from('protestproject007@gmail.com')
            ->to($userMdp->getUsername())
            ->subject('Modification de mot de votre mot de passe')
            ->htmlTemplate('email/email.html.twig')
            ->context([
        'userMdp' => $userMdp->getPassword(),
            ]);

        $this->mailer->send($email);
    }
    
    // /**
    //  * @Route("/rendez-vous/email/confirmation")
    //  */
    // public function sendEmailRDV($nom, $prenom , $mail,$listeTeams)
    // {
    //     /*$email = (new TemplatedEmail())
    //         ->from('fndmfindme@gmail.com')
    //         ->to($userMdp->getUsername())
    //         ->subject('Modification de mot de votre mot de passe')
    //         ->htmlTemplate('admin/emailMdp.html.twig')
    //         ->context([
    //     'userMdp' => $userMdp->getPassword(),
    //         ]);

    //     $this->mailer->send($email);*/
    //     Dump($nom);
    //     Dump($prenom);
    //     Dump($mail);
    //     Dump($listeTeams);
    //     return $this->render('testdump/testdump.html.twig',['listeTeams'=>$listeTeams]);
    // }

}
