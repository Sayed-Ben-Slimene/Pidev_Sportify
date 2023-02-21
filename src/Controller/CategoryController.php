<?php

namespace App\Controller;

use id;
use doctrine;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
    #[Route('category/add', name: 'add_category')]
    public function new(Request $request,CategoryRepository $repository ,ManagerRegistry $doctrine)
    {
        $category=new Category();
        $form1 =$this->createForm(CategoryType::class,$category);
        $form1 ->handleRequest($request);
        if($form1->isSubmitted()  ){
           $em =$this->getDoctrine()->getManager();
           $em->persist($category);
           $em->flush();
            return $this->redirectToRoute( route: 'list_category');
        }
        return $this->render('category/new.html.twig',[
            "form1"=>$form1->createView()
        ] );
    }
    #[Route('category/list', name: 'list_category')]
    public function list(CategoryRepository $repository)
    {
        return $this->render('category/list.html.twig', [
            'category' => $repository->findAll()
        ]);
    }
    #[Route('/updatecategory/{id}', name: 'update_category')]
    public function updateStudentForm($id,CategoryRepository  $repository,Request  $request,ManagerRegistry $doctrine)
    {
        $category= $repository->find($id);
        $form= $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request) ;
        if($form->isSubmitted()){
            $em= $doctrine->getManager();
            $em->flush();
            return  $this->redirectToRoute("list_category");
        }
        return $this->renderForm("category/update.html.twig",array("FormCategory"=>$form));
    }
    
    
    #[Route('category/remove/{id}', name: 'remove_category')]
    public function remove(ManagerRegistry $doctrine,$id,CategoryRepository $repository)
    {
        $category= $repository->find($id);
        $em= $doctrine->getManager();
        $em->remove( $category);
        $em->flush();
        return $this->redirectToRoute("list_category");
    }
   
}

