<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ActualiteRepository;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setDateHeur(new \DateTimeImmutable());
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'blog', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->save($commentaire, true);
            return $this->redirectToRoute('blog', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
            $commentaireRepository->remove($commentaire, true);
        }

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/likeCommentaire/{id}', name: 'likeCommentaire', methods: ['GET', 'POST'])]
    public function likeCommentaire(Request $request, CommentaireRepository $commentaireRepository, Commentaire $commentaire): Response
    {
        $commentaire->setNbLike($commentaire->getNbLike() + 1);
        $commentaireRepository->save($commentaire, true);
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/dislikeCommentaire/{id}', name: 'dislikeCommentaire', methods: ['GET', 'POST'])]
    public function dislikeCommentaire(Request $request, CommentaireRepository $commentaireRepository, Commentaire $commentaire): Response
    {
        $commentaire->setNbDislike($commentaire->getNbDislike() + 1);
        $commentaireRepository->save($commentaire, true);
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }
    #[Route('/blogCommentaire/{id}', name: 'blogCommentaire', methods: ['GET', 'POST'])]
    public function blogCommentaire(Request $request, CommentaireRepository $commentaireRepository,ActualiteRepository $blogRepository, Actualite $Actualite): Response
    
    {      
        $Listecommentaire = $commentaireRepository->findBy(array('blog' => $Actualite->getId()), array('blog' => 'ASC'));
        return $this->render('front/Listecommentaire.html.twig', [
            'commentaires' => $Listecommentaire,
            'actualite'=>$Actualite
        ]);
    }
}
