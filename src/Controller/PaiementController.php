<?php

namespace App\Controller;


use Stripe\Stripe;
use Stripe\Customer;
use App\Entity\Produits;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaiementController extends AbstractController
{
    
  
    #[Route('/checkout', name: 'app_checkout')]
    public function checkout(SessionInterface $session): Response
{
    Stripe::setApiKey('sk_test_51MZfm3GsPvc9FYkvuVjIT8w89ZcGO7UpXvkpu6rlIwDMTsUxvZx2fhgjz5z4JyUJgRyWjlSa41yaU4awBgJnx4FA00sSIYpMPn');

    $panier = $session->get('panier', []);
    $items = [];
    $total = 0;
    foreach ($panier as $id => $quantity) {
        $product = $this->getDoctrine()->getRepository(Produits::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé pour id '.$id);
        }
        $price = $product->getPrix();
        $total += $price * $quantity;
        $items[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $product->getTitle(),
                ],
                'unit_amount' => $price * 100,
            ],
            'quantity' => $quantity,
        ];
    }

    $session = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $items,
        'mode' => 'payment',
        'success_url' => $this->generateUrl('success', [], UrlGeneratorInterface::ABSOLUTE_URL),
        'cancel_url' => $this->generateUrl('echec', [], UrlGeneratorInterface::ABSOLUTE_URL),
    ]);

    return $this->redirect($session->url, 303);
}

#[Route('/success', name: 'success')]
public function success(){
    return $this->render('paiement/success.html.twig');
}
#[Route('/echec', name: 'echec')]
public function echec(){
    return $this->render('paiement/echec.html.twig');
}


}
