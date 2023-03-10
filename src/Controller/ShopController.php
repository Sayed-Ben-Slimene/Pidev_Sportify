<?php

namespace App\Controller;

use id;
use App\Repository\CategoryRepository;
use App\Repository\ProduitsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopController extends AbstractController
{
    
    

    #[Route('/shop', name: 'app_shop')]
public function index(ProduitsRepository $produitsRepository, CategoryRepository $categoriesRepository, PaginatorInterface $paginator, Request $request)
{
    $minPrice = $request->query->get('min');
    $maxPrice = $request->query->get('max');

    if ($minPrice !== null && $maxPrice !== null) {
        // Si les paramètres de recherche sont définis, exécute la méthode search pour récupérer les produits correspondants
        $products = $produitsRepository->searchByPrix($minPrice,$maxPrice);
    } else {
        // Sinon, récupère tous les produits
        $products = $produitsRepository->findAll();
    }

    $categories=$categoriesRepository->findAll();

    $pagination = $paginator->paginate(
        $products,
        $request->query->getInt('page', 1),
        5
    );

    return $this->render('shop/index.html.twig', [
        'pagination' => $pagination,
        'categories' => $categories,
    ]);
}

    #[Route('/shop/search', name: 'app_shop_search')]
    public function search(ProduitsRepository $produitsRepository, CategoryRepository $categoriesRepository, PaginatorInterface $paginator, Request $request)
    {
        $minPrice = $request->query->get('min');
        $maxPrice = $request->query->get('max');

        $products = $produitsRepository->searchByPrix($minPrice,$maxPrice);
        $categories = $categoriesRepository->findAll();

        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('shop/index.html.twig', [
            'pagination' => $pagination,
            'categories' => $categories,
        ]);
    }
   
  
    

   
    
}




   

