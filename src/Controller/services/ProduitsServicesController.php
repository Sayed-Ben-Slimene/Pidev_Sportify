<?php

namespace App\Controller\services;

use DateTime;
use App\Entity\Panier;

use App\Entity\Category;
use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProduitsServicesController extends AbstractController
{
    #[Route('/produits/services', name: 'app_produits_services')]
    public function index(): Response
    {
        return $this->render('produits_services/index.html.twig', [
            'controller_name' => 'ProduitsServicesController',
        ]);
    }
    #[Route('/produits/get', name: 'app_produits_services',methods:'GET')]
    public function getProduits(ProduitsRepository $repo,SerializerInterface $serializerInterface)
    {
        $produits=$repo->findall();
        $json=$serializerInterface->serialize($produits,'json',['groups'=>'produit']);
        $response=new Response ($json,200,[
            "content-Type"=>"application/json"
        ]);
        return $response;
    }
    #[Route('/produits/add', name: 'add_produits_services',methods:'POST')]
    public function addProduits(Request $request,SerializerInterface $serializer,EntityManagerInterface $em)
    {
        $content=$request->getcontent();
        $post=$serializer->deserialize($content,Produits::class,'json');
        $category = $post->getCategory();
        $em->persist($category);
        $em->persist($post);
        $em->flush();
        
       
    

        return $this->json($post,201,[],['groups'=>'produit']);
        
    }
    

    #[Route('/produits/{id}', name: 'delete_produits_services', methods: ['DELETE'])]
    public function deleteProduits(EntityManagerInterface $em, $id)
    {
        $produit = $em->getRepository(Produits::class)->find($id);
    
        if (!$produit) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
    
        // Supprimer toutes les entrées dans la table panier qui référencent ce produit
        $paniers = $em->getRepository(Panier::class)->findBy(['produit' => $produit]);
        foreach ($paniers as $panier) {
            $em->remove($panier);
        }
    
        // Supprimer le produit lui-même
        $em->remove($produit);
        $em->flush();
    
        return $this->json(['message' => 'Product deleted'], 200);
    }
    
#[Route('/produits/{id}', name: 'update_produits_services', methods: ['PUT'])]
public function updateProduits(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, $id)
{
    $produit = $em->getRepository(Produits::class)->find($id);

    if (!$produit) {
        throw $this->createNotFoundException(
            'No product found for id '.$id
        );
    }

    // désérialisation de la requête
    $content = $request->getContent();
    $updatedProduit = $serializer->deserialize($content, Produits::class, 'json');

    // récupération de la catégorie
    $category = $updatedProduit->getCategory();

    // si la catégorie est différente, on l'ajoute à la base de données
    if (!$em->contains($category)) {
        $em->persist($category);
    }

    // mise à jour des propriétés du produit
    $produit->setTitle($updatedProduit->getTitle());
    $produit->setDescription($updatedProduit->getDescription());
    $produit->setPrix($updatedProduit->getPrix());
    $produit->setPublished($updatedProduit->isPublished());
    $produit->setPhoto($updatedProduit->getPhoto());
    

    // association avec la nouvelle catégorie
    $produit->setCategory($category);

    // mise à jour de la base de données
    $em->flush();

    return $this->json($produit, 200, [], ['groups' => 'produit']);
}









}
