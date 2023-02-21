<?php

namespace App\Controller;

use id;
use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\CategoryRepository;
use App\Repository\ProduitsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProduitsController extends AbstractController
{
    #[Route('/add_produits', name: 'add_produits')]
    public function new(Request $request,ProduitsRepository $repository ,ManagerRegistry $doctrine)
    {
        $produit=new Produits();
        $form =$this->createForm(ProduitsType::class,$produit);
        $form ->handleRequest($request);
        if($form->isSubmitted()  ){
           $em =$this->getDoctrine()->getManager();
           $em->persist($produit);
           $em->flush();
            return $this->redirectToRoute( route: 'app_list');
        }
        return $this->render('produits/new.html.twig',[
            "form"=>$form->createView()
        ] );
    }
    #[Route('/list', name: 'app_list')]
    public function list(ProduitsRepository $repository)
    {
        return $this->render('produits/list.html.twig', [
            'produits' => $repository->findBy(["published"=>1])
        ]);
    }
    
    #[Route('produits/update/{id}', name: 'update_produit')]
    public function update($id,ProduitsRepository $repository,Request  $request,ManagerRegistry $doctrine)
    {
        $produit= $repository->find($id);
        $form= $this->createForm(ProduitsType::class,$produit);
        $form->handleRequest($request) ;
        if($form->isSubmitted()){
            $em= $doctrine->getManager();
           
            $em->flush();
            return  $this->redirectToRoute("app_list");
        }
        return $this->renderForm("produits/update.html.twig",array("produits"=>$form));
    }
    #[Route('produits/remove/{id}', name: 'remove_produit')]
    public function remove(ManagerRegistry $doctrine,$id,ProduitsRepository $repository)
    {
        $produit= $repository->find($id);
        $em= $doctrine->getManager();
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute("app_list");
    }
    #[Route('/showCategory/{id}', name: 'showCategory')]
    public function showCategory(ProduitsRepository $repo,$id,CategoryRepository $repository)
    {
        $category= $repository->find($id);
        $produits= $repo->getStudentsByClassroom($id);
        return $this->render("produits/showcategory.html.twig",
        array("category"=>$category,
            "produits"=>$produit));
    }
   
}
