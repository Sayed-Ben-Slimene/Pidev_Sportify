<?php

namespace App\Controller;

use App\Entity\UGame;
use App\Form\UGameType;
use App\Repository\GameRepository;
use App\Repository\UGameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ugame')]
class UGameController extends AbstractController
{
    #[Route('/', name: 'app_ugame_index', methods: ['GET'])]
    public function index(UGameRepository $gameRepository): Response
    {
        return $this->render('ugame/index.html.twig', [
            'ugames' => $gameRepository->findAll(),
        ]);
    }


    #[Route('/timetable', name: 'app_time_front', methods: ['GET'])]
    public function Calendar(UgameRepository $ugameRepository, GameRepository $gameRepository): Response
    {
        $event1 = $ugameRepository->findAll();
        $event2 = $gameRepository->findAll();
        $rdvs = [];

        foreach ($event2 as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getDateTime()->format('Y-m-d') . " " . $event->getDateTime()->format('H:i'),
                'end' => $event->getDateTime()->format('Y-m-d') . " " . $event->getDateTime()->format('H:i'),
                'title' => $event->getTeam1()->getName() . "     ||" . $event->getScoreTeam1() . "|" . $event->getScoreTeam2() . "||     " . $event->getTeam2()->getName(),

                //'backgroundColor'=>$event->getBackgroundColor(),
                //'borderColor'=>$event->getBorderColor(),
                //'textColor'=>$event->getTextColor(),
                //'allDay'=>$event->getAllDay()
            ];
        }
        foreach ($event1 as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getDateTime()->format('Y-m-d') . " " . $event->getDateTime()->format('H:i'),
                'end' => $event->getDateTime()->format('Y-m-d') . " " . $event->getDateTime()->format('H:i'),
                'title' => $event->getTeam1()->getName() . "   || VS ||   " . $event->getTeam2()->getName(),
                //'description'=>$event->getTrainer()->getFirstName()." ".$event->getTrainer()->getLastName(),
                //'backgroundColor'=>$event->getBackgroundColor(),
                //'borderColor'=>$event->getBorderColor(),
                //'textColor'=>$event->getTextColor(),
                //'allDay'=>$event->getAllDay()
            ];
        }
        $data = json_encode($rdvs);
        return $this->render('ugame\time_table.html.twig', compact('data'));
    }

    #[Route('/new', name: 'app_ugame_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UGameRepository $gameRepository): Response
    {
        $game = new UGame();
        $form = $this->createForm(UGameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gameRepository->save($game, true);

            return $this->redirectToRoute('app_ugame_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ugame/new.html.twig', [
            'ugame' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ugame_show', methods: ['GET'])]
    public function show(UGame $game): Response
    {
        return $this->render('ugame/show.html.twig', [
            'ugame' => $game,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ugame_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UGame $game, UGameRepository $ugameRepository): Response
    {
        $form = $this->createForm(UGameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ugameRepository->save($game, true);

            return $this->redirectToRoute('app_ugame_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ugame/edit.html.twig', [
            'ugame' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ugame_delete', methods: ['POST'])]
    public function delete(Request $request, UGame $game, UGameRepository $gameRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $game->getId(), $request->request->get('_token'))) {
            $gameRepository->remove($game, true);
        }

        return $this->redirectToRoute('app_ugame_index', [], Response::HTTP_SEE_OTHER);
    }
}
