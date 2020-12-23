<?php

namespace App\Controller\Annonce;

use App\Entity\Location;
use App\Entity\Notification;
use App\Entity\StatutLocation;
use App\Form\LocationType;
use App\Repository\AnnoncesRepository;
use App\Repository\LocationRepository;
use App\Repository\StatutLocationRepository;
use App\Service\MangoPayService;
use App\Service\NotificationService;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MangoPay;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

/**
 * @Route("/location")
 */
class LocationController extends AbstractController
{

    /**
     * @Route("/", name="location_index", methods={"GET"})
     */
    public function index(LocationRepository $locationRepository): Response
    {
        return $this->render('location/index.html.twig', [
            'locations' => $locationRepository->findLocations( $this->getUser()->getId() ),
        ]);
    }

    function cast($instance, $className)
    {
        return unserialize(sprintf(
            'O:%d:"%s"%s',
            \strlen($className),
            $className,
            strstr(strstr(serialize($instance), '"'), ':')
        ));
    }

    /**
     * @Route("/en-cours", name="location_en_cours", methods={"GET"})
     */
    public function enCours(LocationRepository $locationRepository): Response
    {
        return $this->render('location/enCours.html.twig', [
            'mesBiens'    => $locationRepository->findMesBiens( $this->getUser()->getId() ),
            'mesEmprunts' => $locationRepository->findMesEmprunts( $this->getUser()->getId() ),
        ]);
    }

    /**
     * @Route("/new", name="location_new", methods={"POST"})
     */
    public function new(Request $request, LocationRepository $locationRepository, AnnoncesRepository $annoncesRepository, StatutLocationRepository $statutReposistory, MangoPayService $mangoPayService, NotificationService $notificationService): Response
    {
        //vérify éligibility
        $eligibility = $mangoPayService->verifyKYCBANK($this->getUser()->getMangoPayId());

        //vérify amount in wallet user
        $amountWalletUser = $mangoPayService->getWallet($this->getUser()->getMangoPayId());
        $prixLocation = intval($annoncesRepository->find( $request->request->get('annonce'))->getPrix()*100);

        
        
        if( $eligibility == true ) 
        {
            if ($amountWalletUser[0]->Balance->Amount >= $prixLocation) {
           
        
                $token = $request->request->get('token');
                if ($this->isCsrfTokenValid('new_location', $token))
                {
                    $user    = $this->getUser();
                    $annonce = $annoncesRepository->find( $request->request->get('annonce'));
                    $em      = $this->getDoctrine()->getManager();
                    
                    $reservations = json_decode($request->request->get('reservations'));
                    $disponible   = $locationRepository->checkDates($reservations, $annonce->getId());

                    if( $disponible && $annonce )
                    {
                        $statut = $statutReposistory->find(1); // Statut en attente
                        foreach( $reservations as $reservation )
                        {
                            $location = new Location();
            
                            $location->setDateDebut( new DateTime($reservation->debut) );
                            $location->setDateFin( new DateTime($reservation->fin) );
                            $location->setAnnonce($annonce);
                            $location->setStatutLocation($statut);
                            $location->setUser($user);

                            $em->persist($location);
                        }

                        $destinataire = $annonce->getUser();

                        $notification = new Notification();
                        $photo        = $annonce->getPhoto()[0] ? '/uploads/'. $annonce->getPhoto()[0]->getUrl() : '/image/logo-fond-blanc.png';
                        $notification->setDeclencheur( $user );
                        $notification->setDestinataire( $destinataire );
                        $notification->setMessage('Demande de location de l\'annonce <strong>'. $annonce->getTitre() .'</strong> par <strong>'. $user->getNomComplet() .'</strong>');
                        $notification->setRoute( $this->generateUrl('location_en_cours') );
                        $notification->setPhoto( $photo );

                        $em->persist($notification);
                        $em->flush();

                        // send notification
                        $notificationService->send($notification, $destinataire);
                    }
                }

                return $this->redirectToRoute('location_en_cours');
            }else{
                $this->addFlash('notCards', 'Le Solde dans votre portefeuille est insuffisant pour cette location');
                return $this->redirectToRoute('compte_portefeuille');
            }
        }
        else
        {
            $this->addFlash('notCards', 'Vous n avez pas encore un moyen de paiement pour faire une location. ou vos documents KYC ne sont pas encore valides');
            return $this->redirectToRoute('compte_portefeuille');
        }

    }


    /**
     * @Route("/action/{id}/{etat}", name="location_accept", methods={"GET"})
     */
    public function accept(Request $request, Location $location, string $etat, StatutLocationRepository $statutReposistory, MangoPayService $mangoPayService, NotificationService $notificationService): Response
    {
        if( $etat == "accepter" )
        {
            $locataire = $location->getUser();
            $annonce   = $location->getAnnonce();
            
            $periodeTotal = date_diff($location->getDateDebut(),$location->getDateFin());
            if ($annonce->getType()->getId() == 1) {
                $difference = $periodeTotal->format('%h') + 1;
            }elseif ($annonce->getType()->getId() == 2) {
                $difference = $periodeTotal->format('%a') + 1;
            }elseif ($annonce->getType()->getId() == 3) {
                $difference = $periodeTotal->format('%a')/7 + 1;
            }else{
                $difference = $periodeTotal->format('%m') + 1;
            }
            $floatPrix = $difference * $annonce->getPrix();
            
            
                //do transfer prix location wallet buyer -> seller
                $transfert = $mangoPayService->doTransferWalet($locataire->getMangoPayId(),$this->getUser()->getMangoPayId(),$floatPrix);

                
                $destinataire = $locataire;
                $user         = $this->getUser();

                $notification = new Notification();
                $photo        =  $annonce->getPhoto()[0] ? '/uploads/'. $annonce->getPhoto()[0]->getUrl() : '/image/logo-fond-blanc.png';
                $notification->setDeclencheur( $user );
                $notification->setDestinataire( $destinataire );
                $notification->setMessage('Votre demande de location sur " <strong>'. $annonce->getTitre() .'</strong>" est acceptée!');
                $notification->setRoute( $this->generateUrl('location_en_cours') );
                $notification->setPhoto( $photo );

                $this->getDoctrine()->getManager()->persist($notification);

                //status
                $statut    = $statutReposistory->find(2); // Statut en cours
                $location->setStatutLocation( $statut );
                $this->getDoctrine()->getManager()->flush();
                
                // send notification
                $notificationService->send($notification, $destinataire);

                return $this->render('location/successLocation.html.twig', [
                    // 'reponse' => $result,
                    'proprio' => $this->getUser()->getNomComplet(),
                    'locataire' => $location->getUser()->getNomComplet()
                ]);
            
        }
        else
        {
            $statut    = $statutReposistory->find(4); // Statut interrompue
            $location->setStatutLocation( $statut );
        }
        
        return $this->redirectToRoute('location_en_cours');
    }

    /**
     * @Route("/{id}", name="location_show", methods={"GET"})
     */
    public function show(Location $location): Response
    {
        return $this->render('location/show.html.twig', [
            'location' => $location,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="location_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Location $location): Response
    {
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('location_index');
        }

        return $this->render('location/edit.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="location_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Location $location): Response
    {
        if ($this->isCsrfTokenValid('delete'.$location->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($location);
            $entityManager->flush();
        }

        return $this->redirectToRoute('location_index');
    }
}
