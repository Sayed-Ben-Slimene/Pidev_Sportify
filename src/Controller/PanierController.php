<?php

namespace App\Controller;

use id;
use App\Entity\Panier;


use App\Repository\PanierRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\session;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier_index')]
    public function index(SessionInterface $session, ProduitsRepository $productrepository, EntityManagerInterface $entityManager)
{
    $panier = $session->get('panier', []);
    $panierWithData = [];
    foreach ($panier as $id => $quantity) {
        $produit = $productrepository->find($id);
        if ($produit) {
            $panierItem = new Panier();
            $panierItem->setProduit($produit);
            $panierItem->setQuantite((int)$quantity);
            $total = $quantity * $produit->getPrix();
            $panierItem->setTotal($total);
            $entityManager->persist($panierItem);
            $panierWithData[] = $panierItem;
        }
    }
    $entityManager->flush();
    $total = 0;
    foreach ($panierWithData as $item) {
        $totalItem = $item->getProduit()->getPrix() * $item->getQuantite();
        $total += $totalItem;
        
    }

    return $this->render('panier/index.html.twig', [
        'items' => $panierWithData,
        'total' => $total,
    ]);
}

#[Route('/panier/add{id}', name: 'add_panier')]
public function add($id, SessionInterface $session, PanierRepository $panierRepository)
{
    $panier = $session->get('panier', []);
    $quantity = isset($panier[$id]) ? (int)$panier[$id] : 0;
    $quantity++;
    $panier[$id] = $quantity;
    $session->set('panier', $panier);
    return $this->redirectToRoute("panier_index");
}
#[Route('/panier/dec{id}', name: 'dec_panier')]
public function decrease(int $id,SessionInterface $session,){
    $panier = $session->get('panier',[]);
    if($panier[$id] > 1){
        $panier[$id]--;

    }else{
        unset($panier[$id]);
    }
    $session->set('panier', $panier);
    return $this->redirectToRoute("panier_index");

}

#[Route('/panier/remove/{id}', name: 'remove_panier')]
public function remove($id, SessionInterface $session, EntityManagerInterface $entityManager)
{
    $panier = $session->get('panier', []);
    if (isset($panier[$id])) {
        // Remove the Panier entity from the database
        $panierItem = $entityManager->getRepository(Panier::class)->findOneBy(['produit' => $id]);
        if ($panierItem) {
            $entityManager->remove($panierItem);
            $entityManager->flush();
        }
        
        // Remove the product from the cart
        unset($panier[$id]);
        $session->set('panier', $panier);
        $entityManager->flush();
    }
    return $this->redirectToRoute("panier_index");
}





   
}



