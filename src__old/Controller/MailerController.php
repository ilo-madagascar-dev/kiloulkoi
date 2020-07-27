<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
 

}
