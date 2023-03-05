<?php

namespace App\Controller;

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
    public function index(ProduitsRepository $produitsRepository,CategoryRepository $categoriesRepository, PaginatorInterface $paginator, Request $request)
    {
        $products = $produitsRepository->findAll();
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
    
    

   
   
}




   

