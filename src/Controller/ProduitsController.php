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
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProduitsController extends AbstractController
{
    
    #[Route('/add_produits', name: 'add_produits')]
    public function add(ProduitsRepository $repository,SluggerInterface $slugger,Request  $request,ManagerRegistry $doctrine)
    {
        $produit= new  Produits();
        $form= $this->createForm(ProduitsType::class,$produit);
        $form->handleRequest($request) ;
        if($form->isSubmitted() && $form->isValid()){
            $brochureFile = $form->get('photo')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
    
                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('produits_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $produit->setPhoto($newFilename);
            }
             $repository->save($produit,true);
             return  $this->redirectToRoute("list_produits");
         }
         
        return $this->renderForm("produits/new.html.twig",["form"=>$form]);
    }
    #[Route('/list', name: 'list_produits')]
    public function list(ProduitsRepository $repository)
    {
        return $this->render('produits/list.html.twig', [
            'produits' => $repository->findAll()
        ]);
    }
    
    #[Route('produits/update/{id}', name: 'update_produit')]
    public function update($id,ProduitsRepository $repository,SluggerInterface $slugger,Request  $request,ManagerRegistry $doctrine)
    {
        $produit= $repository->find($id);
        $form= $this->createForm(ProduitsType::class,$produit);
        $form->handleRequest($request) ;
        if($form->isSubmitted() && $form->isValid()){
            $brochureFile = $form->get('photo')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
    
                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('produits_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $produit->setPhoto($newFilename);
            }
            

            $em= $doctrine->getManager();
            $em->flush();
            return  $this->redirectToRoute("list_produits");
        }
        return $this->renderForm("produits/update.html.twig",["form"=>$form]);
    }
    #[Route('produits/remove/{id}', name: 'remove_produit')]
    public function remove(ManagerRegistry $doctrine,$id,ProduitsRepository $repository)
    {
        $produit= $repository->find($id);
        $em= $doctrine->getManager();
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute("list_produits");
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
