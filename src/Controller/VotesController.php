<?php

namespace App\Controller;

use App\Entity\Votes;
use App\Form\VotesType;
use App\Repository\VotesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

#[Route('/votes')]
class VoteController extends AbstractController
{

    #[Route('/', name: 'app_votes_index', methods: ['GET'])]
    public function index(VotesRepository $votesRepository): Response
    {
        return $this->render('votes/index.html.twig', [
            'votes' => $votesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_votes_new', methods: ['GET', 'POST'])]
    public function vote(Request $request, VotesRepository $votesRepository): Response
    {
        $vote = new Votes();
        $form = $this->createForm(VotesType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $votesRepository->save($vote, true);
            $this->addFlash('success', 'Your vote has been submitted.');

            return $this->redirectToRoute('app_house', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('votes/new.html.twig', [
            'votes' => $vote,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_votes_show', methods: ['GET'])]
    public function show(Votes $vote): Response
    {
        return $this->render('votes/show.html.twig', [
            'votes' => $vote,
        ]);
    }
}
