<?php

namespace App\Controller\services;

use App\Entity\Panier;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierServiceController extends AbstractController
{
    #[Route('/panier/get', name: 'app_panier_service')]
    public function index(): Response
    {
        return $this->render('panier_service/index.html.twig', [
            'controller_name' => 'PanierServiceController',
        ]);
    }
    

#[Route('/panier/get', name: 'panier')]
public function getPanier(SessionInterface $session, ProduitsRepository $productrepository, EntityManagerInterface $entityManager, SerializerInterface $serializer)
{
    $panier = $session->get('panier', []);
    $panierItems = $entityManager->getRepository(Panier::class)->findBy(['id' => array_keys($panier)]);
    $panierWithData = [];
    foreach ($panierItems as $item) {
        $produit = $item->getProduit();
        $quantity = $panier[$produit->getId()];
        $item->setQuantite((int) $quantity);
        $total = $quantity * $produit->getPrix();
        $item->setTotal($total);
        $panierWithData[] = $item;
    }
    $entityManager->flush();
    
    $total = 0;
    foreach ($panierWithData as $item) {
        $totalItem = $item->getProduit()->getPrix() * $item->getQuantite();
        $total += $totalItem;
    }
    
    $json = $serializer->serialize($panierWithData, 'json',['groups'=>'panier']);
    return new Response($json, 200, [
        'Content-Type' => 'application/json'
    ]);
    
}
#[Route('/panier/add/{id}', name: 'add_panier')]
public function addProduit($id, SessionInterface $session, ProduitsRepository $produitRepository, SerializerInterface $serializer)
{
    $panier = $session->get('panier', []);
    $produit = $produitRepository->find($id);

    if (!$produit) {
        throw $this->createNotFoundException('Produit non trouvé');
    }

    $quantity = isset($panier[$id]) ? (int)$panier[$id] : 0;
    $quantity++;
    $panier[$id] = $quantity;
    $session->set('panier', $panier);

    $panierItems = [];
    foreach ($panier as $id => $quantity) {
        $produit = $produitRepository->find($id);
        if ($produit) {
            $panierItem = new Panier();
            $panierItem->setProduit($produit);
            $panierItem->setQuantite((int)$quantity);
            $total = $quantity * $produit->getPrix();
            $panierItem->setTotal($total);
            $panierItems[] = $panierItem;
        }
    }

    $json = $serializer->serialize($panierItems, 'json', ['groups' => 'panier']);

    return new JsonResponse($json, 200, [], true);
}


}

