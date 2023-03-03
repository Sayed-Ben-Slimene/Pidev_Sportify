<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Entity\Game;
use App\Form\GameType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HouseController extends AbstractController
{
    #[Route('/house', name: 'app_house', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('house/index.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }
}
