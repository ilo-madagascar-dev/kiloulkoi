<?php

namespace App\Controller\Annonce;

use App\Entity\Location;
use App\Entity\StatutLocation;
use App\Form\LocationType;
use App\Repository\AnnoncesRepository;
use App\Repository\LocationRepository;
use App\Repository\StatutLocationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MangoPay;

/**
 * @Route("/location")
 */
class LocationController extends AbstractController
{

    private $mangoPayApi;

    public function __construct()
    {
        $this->mangoPayApi = new MangoPay\MangoPayApi();
        $this->mangoPayApi->Config->ClientId = 'admin-kiloukoi';
        $this->mangoPayApi->Config->ClientPassword = 'MNHcmbW6FE5XMeG1M6KgzHZXfAUdAJdeZjmoNDOAQAoi6spMqF';
        $this->mangoPayApi->Config->TemporaryFolder = '/Temp/tmp';    
        $this->mangoPayApi->Config->BaseUrl = 'https://api.sandbox.mangopay.com';
    }
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
    public function new(Request $request, LocationRepository $locationRepository, AnnoncesRepository $annoncesRepository, StatutLocationRepository $statutReposistory): Response
    {
        $token = $request->request->get('token');
        if ($this->isCsrfTokenValid('new_location', $token))
        {
            $user    = $this->getUser();
            $annonce = $annoncesRepository->find( $request->request->get('annonce'));
            $em      = $this->getDoctrine()->getManager();
            
            $reservations = json_decode($request->request->get('reservations'));
            $disponible   = $locationRepository->checkDates($reservations, $annonce->getId());

            $locations = new ArrayCollection();
            if( $disponible && $annonce )
            {
                $statut    = $statutReposistory->find(1); // Statut en attente
                foreach( $reservations as $reservation )
                {
                    $location = new Location();
    
                    $location->setDateDebut( new DateTime($reservation->debut) );
                    $location->setDateFin( new DateTime($reservation->fin) );
                    $location->setAnnonce($annonce);
                    $location->setStatutLocation($statut);
                    $location->setUser($user);

                    $em->persist($location);
                    $locations->add($location);
                }
                $em->flush();
            }
        }

        return $this->redirectToRoute('location_en_cours');
    }


    /**
     * @Route("/action/{id}/{etat}", name="location_accept", methods={"GET"})
     */
    public function accept(Request $request, Location $location, string $etat, StatutLocationRepository $statutReposistory): Response
    {
        if( $etat == "accepter" )
        {
            $locataire = $location->getUser();
            /*dd($locataire->getMangoPayId());*/
            // create pay-in CARD DIRECT direct paying card
            $waletUserId = $this->mangoPayApi->Users->GetWallets($locataire->getMangoPayId());
            $walletId;
            foreach ($waletUserId as $value) {
                $walletId = $value->Id;
            }
            $payIn = new \MangoPay\PayIn();
            $payIn->CreditedWalletId = $walletId;
            $payIn->AuthorId = $locataire->getMangoPayId();
            $payIn->DebitedFunds = new \MangoPay\Money();
            $payIn->DebitedFunds->Amount = 1000;
            $payIn->DebitedFunds->Currency = "EUR";
            $payIn->Fees = new \MangoPay\Money();
            $payIn->Fees->Amount = 0;
            $payIn->Fees->Currency = "EUR";
            
            $cards = $this->mangoPayApi->Users->GetCards($locataire->getMangoPayId());
            $cardId;
            $cardType;
            foreach ($cards as $value) {
                $cardId = $value->Id;
                $cardType = $value->CardType;
            }
            //active card
            /*$Card = new \MangoPay\Card();
            $Card->Id = $cardId;
            $Card->Active = true;
            $this->mangoPayApi->Cards->Update($Card);*/

            // payment type as CARD
            $payIn->PaymentDetails = new \MangoPay\PayInPaymentDetailsCard();
            $payIn->PaymentDetails->CardType = $cardType;
            $payIn->PaymentDetails->CardId = $cardId;
            
            // execution type as DIRECT
            $payIn->ExecutionDetails = new \MangoPay\PayInExecutionDetailsDirect();
            $payIn->ExecutionDetails->SecureModeReturnURL = 'http://test.com';

            // create Pay-In
            $createdPayIn = $this->mangoPayApi->PayIns->Create($payIn);
            if ($createdPayIn->Status == \MangoPay\PayInStatus::Succeeded) {
                /*$createdPayIn->Id; 
                $createdWallet->Id;*/
                $waletproprietaire = $this->mangoPayApi->Users->GetWallets($this->getUser()->getMangoPayId());
                $WIdproprietaire;
                foreach ($waletproprietaire as $value) {
                    $WIdproprietaire = $value->Id;
                }
                $Transfer = new \MangoPay\Transfer();
                $Transfer->AuthorId = $locataire->getMangoPayId();
                $Transfer->DebitedFunds = new \MangoPay\Money();
                $Transfer->DebitedFunds->Currency = "EUR";
                $Transfer->DebitedFunds->Amount = 500;
                $Transfer->Fees = new \MangoPay\Money();
                $Transfer->Fees->Currency = "EUR";
                $Transfer->Fees->Amount = 100;
                $Transfer->DebitedWalletID = $walletId;
                $Transfer->CreditedWalletId = $WIdproprietaire;
                $result = $this->mangoPayApi->Transfers->Create($Transfer);
                
                //status
                $statut    = $statutReposistory->find(2); // Statut en cours
                $location->setStatutLocation( $statut );
                $this->getDoctrine()->getManager()->flush();
                
                return $this->render('location/successLocation.html.twig', [
                    'reponse' => $result,
                    'proprio' => $this->getUser()->getNomComplet(),
                    'locataire' => $location->getUser()->getNomComplet()
                ]);
            }
            else {
                dd($createdPayIn->Status);
                /*$createdPayIn->ResultCode;*/
            }
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
