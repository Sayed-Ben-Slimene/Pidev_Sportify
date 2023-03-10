<?php

namespace App\Controller;


use id;
use Stripe\Stripe;
use Stripe\Customer;
use Twilio\Rest\Client;
use App\Entity\Commande;
use App\Entity\Produits;
use Stripe\Checkout\Session;
use Symfony\Component\Mime\Address;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
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
public function success(Request $request,SessionInterface $session): Response
{
    // Récupérer le numéro de téléphone du client
    //$numeroTelephone = $session->get('numero_telephone');

    

    // Envoyer le SMS de confirmation
    $twilioSID = 'AC571ca3659d5ac729764975c897129e09';
    $twilioToken = 'MG629480f1c7a3abe999108e651606726f';
    $twilio = new Client($twilioSID, $twilioToken);
    $message = $twilio->messages->create(
       
       "+21652492642",
        array(
            "messagingServiceSid" => "AC571ca3659d5ac729764975c897129e09",
            'body' => 'Votre paiement a été accepté. Merci!'
        )
    );

    return $this->render('paiement/success.html.twig');
}

/*#[Route('/success', name: 'success')]
public function success(Request $request, SessionInterface $session, CommandeRepository $commandeRepository): Response
{
    // Récupérer la dernière commande créée
    $commande = $commandeRepository->findOneBy([], ['id' => 'desc']);

    // Récupérer le numéro de téléphone du client
    $numeroTelephone = $commande->getNumTel();

    // Envoyer le SMS de confirmation
    $twilioSID = 'MG629480f1c7a3abe999108e651606726f';
    $twilioToken = 'c818bd86648a519b38f1757871be4fe4';
    $twilio = new Client($twilioSID, $twilioToken);
    $message = $twilio->messages->create(
        $numeroTelephone,
        array(
            "messagingServiceSid" => "MG629480f1c7a3abe999108e651606726f",
            'body' => 'Votre paiement a été accepté. Merci!'
        )
    );

    return $this->render('paiement/success.html.twig');
}*/











#[Route('/echec', name: 'echec')]
public function echec(){
    return $this->render('paiement/echec.html.twig');
}


}
