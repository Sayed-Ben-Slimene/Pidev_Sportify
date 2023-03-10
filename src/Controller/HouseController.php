<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Entity\Game;
use App\Form\GameType;
use App\Repository\UGameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;


class HouseController extends AbstractController
{
    #[Route('/house', name: 'app_house', methods: ['GET'])]
    public function index(GameRepository $gameRepository, UGameRepository $ugameRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $game = $gameRepository->findAll();
        $gamepagination = $paginator->paginate($game, $request->query->getInt('page', 1), 1);
        return $this->render('house\index.html.twig', [
            'courses' => $gamepagination,
            'games' => $gameRepository->findAll(),
            'ugames' => $ugameRepository->findAll(),
        ]);
    }
}
