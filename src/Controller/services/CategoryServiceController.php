<?php

namespace App\Controller\services;
use DateTime;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CategoryServiceController extends AbstractController
{
    #[Route('/category/services', name: 'app_category_services')]
    public function index(): Response
    {
        return $this->render('produits_category/index.html.twig', [
            'controller_name' => 'categoryServicesController',
        ]);
    }
    #[Route('/category/get', name: 'get_category_services',methods:'GET')]
    public function getCategory(CategoryRepository $repo,SerializerInterface $serializerInterface)
    {
        $category=$repo->findall();
        $json=$serializerInterface->serialize($category,'json',['groups'=>'category']);
        $response=new Response ($json,200,[
            "content-Type"=>"application/json"
        ]);
        return $response;
    }
   
    #[Route('/category/add', name: 'add_category_services', methods: ['POST'])]
    public function addCategory(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
    $content = $request->getContent();
    $post = $serializer->deserialize($content, Category::class, 'json');
    $category = $post->getCategory();

    // Fetch the existing categories array
    $categories = $em->getRepository(Category::class)->findAll();

    // Check if the category already exists
    $categoryExists = false;
    foreach ($categories as $cat) {
        if ($cat->getName() === $category->getName()) {
            $categoryExists = true;
            break;
        }
    }

    if (!$categoryExists) {
        // Add the new category to the array
        $categories[] = $category;

        // Update the categories array in the database
        foreach ($categories as $cat) {
            $em->persist($cat);
        }
        $em->flush();
    }

    return $this->json($categories, 201, [], ['groups' => 'category']);
}

    

#[Route('/category/{id}', name: 'delete_category_services', methods: ['DELETE'])]
public function deleteCategory(EntityManagerInterface $em, $id)
{
    $category = $em->getRepository(Category::class)->find($id);

    if (!$category) {
        throw $this->createNotFoundException(
            'No category found for id '.$id
        );
    }
    $products = $category->getProduits();
    foreach ($products as $product) {
    $em->remove($product);
}

    $em->remove($category);
    $em->flush();

    return $this->json(['message' => 'Product deleted'], 200);
}

#[Route('/category/{id}', name: 'update_category_services', methods: ['PUT'])]
public function updateCategory(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, $id)
{
    $category = $em->getRepository(Category::class)->find($id);

    if (!$category) {
        throw $this->createNotFoundException(
            'No category found for id '.$id
        );
    }

    // désérialisation de la requête
    $content = $request->getContent();
    $updatedCategory = $serializer->deserialize($content, Category::class, 'json');

    // mise à jour des propriétés de la catégorie
   
    if ($updatedCategory->getLabel() !== null) {
        $category->setLabel($updatedCategory->getLabel());
    }
    // mise à jour de la base de données
    $em->flush();

    return $this->json($category, 200, [], ['groups' => 'category']);
}








}
