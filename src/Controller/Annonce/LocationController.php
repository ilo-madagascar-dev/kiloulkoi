<?php

namespace App\Controller\Annonce;

use App\Entity\Annonces;
use App\Entity\Location;
use App\Entity\StatutLocation;
use App\Entity\User;
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
            'locations' => $locationRepository->findAll(),
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
        
        return $this->render('location/new.html.twig', [
            'locations' => $locations,
        ]);
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