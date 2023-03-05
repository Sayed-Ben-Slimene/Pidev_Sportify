<?php

namespace App\Controller;

use App\Entity\Commande;
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaiementController extends AbstractController
{
    #[Route('/paiement', name: 'paiement')]
    public function payer(Request $request): Response
    {
        // Configurez la clé secrète de l'API Stripe
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        // Récupérez les informations du formulaire de paiement
        $nom = $request->request->get('nom');
        $email = $request->request->get('email');
        $montant = $request->request->get('montant');
        $token = $request->request->get('stripeToken');

        // Créez un objet de charge avec les informations de carte de crédit
        $charge = Charge::create([
            'amount' => $montant * 100,
            'currency' => 'eur',
            'description' => 'Paiement en ligne',
            'source' => $token,
        ]);

        // Enregistrez le résultat de la charge dans votre base de données
        $commande = new Commande();
        $commande->setNom($nom);
        $commande->setEmail($email);
        $commande->setMontant($montant);
        $commande->setChargeId($charge->id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist;
    }
}