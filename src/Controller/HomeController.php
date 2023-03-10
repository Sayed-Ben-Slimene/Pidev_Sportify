<?php

namespace App\Controller;

use App\Repository\ActualiteRepository;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/blog', name: 'blog', methods: ['GET'])]
    public function showBlogs(ActualiteRepository $actualiteRepository): Response
    {
        return $this->render('front/blogs.html.twig', [
            'actualites' => $actualiteRepository->findAll(),
        ]);
    }
    #[Route('/event', name: 'event', methods: ['GET'])]
    public function showevent(EvenementRepository $EvenementRepository): Response
    {
        return $this->render('front/events.html.twig', [
            'event' => $EvenementRepository->findAll(),
        ]);
    }
}
