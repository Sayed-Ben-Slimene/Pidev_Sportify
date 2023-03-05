<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/create-checkout-session/{reference}', name: 'app_stripe')]
    public function index(Panier $panier,Commande $commande,EntityManagerInterface $em): Response
    {
       $YOUR_DOMAIN='http/localhost:8000';
       $user->$this->getUser();
       if(!$panier){
        return $this->redirectToRoute('add_commande');
       }
       
    }
   
}
