<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\session;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier_index')]
    public function index(SessionInterface $session ,ProduitsRepository $repository )
    {
        $panier = $session->get('panier',[]);
        $panierWithData=[];
        foreach($panier as $id=>$quantity){
            $panierWithData[]=[
                'produit'=>$repository->find($id),
                'quantity'=>$quantity

            ];
        }
        $total=0;
        foreach ($panierWithData as $item ) {

            $totalItem=$item['produit']->getPrix()* $item['quantity'];
            $total+=$totalItem;
        }
        
        return $this->render('panier/index.html.twig', [
            'items' => $panierWithData,
            'total'=>$total,
        ]);
    }
    #[Route('/panier/add{id}', name: 'add_panier')]
    public function add($id,SessionInterface $session)

    {
        
        $panier=$session->get('panier',[]);
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id]=1;
        }
        
        $session->set('panier',$panier);
        return $this->redirectToRoute("panier_index");
        
        
        
    }
    #[Route('/panier/remove{id}', name: 'remove_panier')]
    public function remove($id,SessionInterface $session)

    {
        
        $panier=$session->get('panier',[]);
        if(!empty($panier[$id])){
           unset($panier[$id]) ;
        }
        
        $session->set('panier',$panier);
        return $this->redirectToRoute("panier_index");
        
        
        
    }
    
    
   
}
