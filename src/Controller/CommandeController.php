<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
   
    
    #[Route('/commande', name: 'add_commande')]
public function new(Request $request, CommandeRepository $commandeRepository, ProduitsRepository $productRepository, SessionInterface $session)
{
    // Récupérez le panier de la session
    $panier = $session->get('panier', []);
    
    // Initialisez une variable pour le prix total
    $total = 0;
    
    // Parcourez les éléments du panier
    foreach ($panier as $id => $quantity) {
        $produit = $productRepository->find($id);
        if ($produit) {
            // Calculez le prix total de l'élément
            $totalItem = $quantity * $produit->getPrix();
            $total += $totalItem;
        }
    }
    
    // Créez le formulaire de commande
    $commande = new Commande();
    $form = $this->createForm(CommandeType::class, $commande);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Sauvegardez la commande dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commande);
        $entityManager->flush();

        // Videz le panier
        $session->set('panier', []);

        return $this->redirectToRoute('app_shop');
    }

    return $this->render('commande/index.html.twig', [
        'form' => $form->createView(),
        'total' => $total, // Passez le prix total à la vue
    ]);
}


}
