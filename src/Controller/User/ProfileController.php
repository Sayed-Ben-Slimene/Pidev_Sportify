<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        //si no internet connection the page profile is not available !! and redirect to home page
        if (!$this->getUser()) {
            return $this->redirectToRoute('homepage');
        }
        return $this->render('profile/profile.html.twig',[
        'user' => $this->getUser(),
         ]);
    }

    
}

