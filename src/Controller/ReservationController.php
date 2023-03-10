<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    public function places_dispo($reservationRepository, $evenementRepository, $event): int
    {
        $list_occupied = $reservationRepository->findBy(array('event' => $event), array('event' => 'ASC'));
        $places_occupied = 0;
        foreach ($list_occupied as $p) {
            $places_occupied += $p->getNbPlace();
        }
        $event = $evenementRepository->find($event->getId());
        $places = $event->getCapacite();
        $free_places = $places - $places_occupied;
        return $free_places;
    }

    #[Route('/new/{id}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository, EvenementRepository $evenementRepository, Evenement $event): Response
    {
        $reservation = new Reservation();
        $reservation->setEvent($event);
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $free_places = $this->places_dispo($reservationRepository, $evenementRepository, $reservation->getEvent());
            $reservation->setDateRes(new \DateTimeImmutable());
            if ($reservation->getNbPlace() <= $free_places) {
                $reservationRepository->save($reservation, true);
            } else {
                return $this->renderForm('reservation/new.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form,
                    'msg' => $free_places,
                    'event' => $event->getNomEvenement()
                ]);
            }
            return $this->redirectToRoute('reservationEvent', ['id' => $event->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            'msg' => -1,
            'event' => $event->getNomEvenement()
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $places_occupied = $reservationRepository->findBy(array('event' => $reservation->getEvent()), array('event' => 'ASC'));
            $free_places = 15;
            foreach ($places_occupied as $p) {
                $free_places = $free_places - $p->getNbPlace();
            }
            if ($free_places + $reservation->getNbPlace() <= 14) {
                $reservationRepository->save($reservation, true);
            } else {
                //throw new \Exception('Places disponibles insuffisants, il reste ' . $free_places);
                return $this->redirectToRoute('app_reservation_new', ['msg' => $free_places]);
            }
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }
        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
