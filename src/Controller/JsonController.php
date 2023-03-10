<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Entity\Commentaire;
use App\Repository\ActualiteRepository;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JsonController extends AbstractController
{
    #[Route('/json', name: 'app_json')]
    public function index(): Response
    {
        return $this->render('json/index.html.twig', [
            'controller_name' => 'JsonController',
        ]);
    }


    #[Route('/actualite/json/getAll', name: 'app_actualite_JSON', methods: ['GET'])]
    public function index_JSON(SerializerInterface $serializer, ActualiteRepository $actualiteRepository): JsonResponse
    {
        $actualites = $actualiteRepository->findAll();
        $json = $serializer->serialize($actualites, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => [],
        ]);

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/commentaire/getAll', name: 'commentaire_index_JSON', methods: ['GET'])]
    public function index_JSON_c(CommentaireRepository $commentaireRepository, SerializerInterface $serializer): JsonResponse
    {
        $commentaires = $commentaireRepository->findAll();
        $json = $serializer->serialize($commentaires, 'json',[
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['id'],
        ]);

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/JSON/new-comment', name: 'create_comment', methods: ['GET'])]
    public function createCommentAction(Request $request,ActualiteRepository $actualiteRepository, UserRepository $userRepository,ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
    {
       // $data = json_decode($request->getContent(), true);

        // Create a new Commentaire entity with the form data
        $commentaire = new Commentaire();
        $commentaire->setComment($request->get('comment'));
        $commentaire->setUser($userRepository->find($request->get('idUser')));
        $commentaire->setDateHeur(new \DateTimeImmutable());
        $commentaire->setBlog($actualiteRepository->find($request->get('idActualite')));

        // Validate the entity
        $errors = $validator->validate($commentaire);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Save the entity to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commentaire);
        $entityManager->flush();

        // Return a JSON response with the serialized entity data
        $jsonContent = $serializer->serialize($commentaire, 'json');
        return new JsonResponse($jsonContent, JsonResponse::HTTP_CREATED, [], true);
    }

    #[Route('/commentaire/edit/{id}', name: 'edit_commentaire', methods: ['GET'])]
    public function editCommentaireAction(Request $request, ValidatorInterface $validator, SerializerInterface $serializer, CommentaireRepository $commentaireRepository, int $id): JsonResponse
    {
        // Find the Commentaire entity by its id
        $commentaire = $commentaireRepository->find($id);

        // If the Commentaire entity doesn't exist, return a 404 response
        if (!$commentaire) {
            return new JsonResponse(['error' => 'Commentaire not found'], Response::HTTP_NOT_FOUND);
        }


        // Update the Commentaire entity with the form data
        $commentaire->setComment($request->get('comment'));

        // Validate the entity
        $errors = $validator->validate($commentaire);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        // Save the updated entity to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Return a JSON response with the serialized entity data
        $jsonContent = $serializer->serialize($commentaire, 'json');
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }

    #[Route('/JSON/comment/delete/{id}', name: 'delete_comment', methods: ['GET'])]
    public function deleteCommentAction(Request $request, CommentaireRepository $commentaireRepository): JsonResponse
    {
        $id = $request->get('id');

        $commentaire = $commentaireRepository->find($id);

        if($commentaire != null) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentaire);
            $entityManager->flush();

            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize("Commentaire has been deleted successfully.");
            return new JsonResponse($formatted);
        }
        $formatted = ["error" => "Invalid commentaire ID."];
        return new JsonResponse($formatted, JsonResponse::HTTP_NOT_FOUND);
    }

    #[Route('/commentaire/JSON/getByActualiteId/{id}', name: 'actualite_commentaires_index_JSON', methods: ['GET'])]
    public function index_JSON_commentaires(Actualite $actualite, CommentaireRepository $commentaireRepository, SerializerInterface $serializer): JsonResponse
    {
        $commentaires = $commentaireRepository->findBy(['blog' => $actualite]);
        $json = $serializer->serialize($commentaires, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['blog'],
        ]);

        return new JsonResponse($json, 200, [], true);
    }

}