<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(ProduitsRepository $produitsRepository)
    {
        return $this->render('shop/index.html.twig', [
            'products' => $produitsRepository->findAll()
        ]);
    }
}
