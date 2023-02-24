<?php

namespace App\Controller;

use App\Entity\match;
use App\Form\matchType;
use App\Repository\matchRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/match')]
class matchController extends AbstractController
{
    #[Route('/', name: 'app_match_index', methods: ['GET'])]
    public function index(matchRepository $matchRepository): Response
    {
        return $this->render('match/index.html.twig', [
            'matchs' => $matchRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_match_new', methods: ['GET', 'POST'])]
    public function new(Request $request, matchRepository $matchRepository): Response
    {
        $match = new ExprMatch_();
        $form = $this->createForm(matchType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //upload image
            $uploadedFile = $form['img']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $match->setImg($newFilename);
            }
            $matchRepository->save($match, true);

            return $this->redirectToRoute('app_match_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('match/new.html.twig', [
            'match' => $match,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_match_show', methods: ['GET'])]
    public function show(Match_ $match): Response
    {
        return $this->render('match/show.html.twig', [
            'match' => $match,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_match_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Match_ $match, matchRepository $matchRepository): Response
    {
        $form = $this->createForm(matchType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //upload image
            $uploadedFile = $form['img']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $match->setImg($newFilename);
            }
            $matchRepository->save($match, true);

            return $this->redirectToRoute('app_match_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('match/edit.html.twig', [
            'match' => $match,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_match_delete', methods: ['POST'])]
    public function delete(Request $request, match_ $match, matchRepository $matchRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $match->getId(), $request->request->get('_token'))) {
            $matchRepository->remove($match, true);
        }

        return $this->redirectToRoute('app_match_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/showBlogs', name: 'showBlogs', methods: ['GET'])]
    public function showFront(matchRepository $matchRepository): Response
    {
        return $this->render('match/blogfront.html.twig', [
            'ac' => $matchRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_match_new', methods: ['GET', 'POST'])]
    function new(Request $request, matchRepository $matchRepository): Response
    {
        $match = new Match_();
        $form = $this->createForm(matchType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //upload image
            $uploadedFile = $form['img']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $match->setImg($newFilename);
            }
            $matchRepository->save($match, true);

            return $this->redirectToRoute('app_match_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('match/new.html.twig', [
            'match' => $match,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_match_show', methods: ['GET'])]
    function show(match_ $match): Response
    {
        return $this->render('match/show.html.twig', [
            'match' => $match,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_match_edit', methods: ['GET', 'POST'])]
    function edit(Request $request, match_ $match, matchRepository $matchRepository): Response
    {
        $form = $this->createForm(matchType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //upload image
            $uploadedFile = $form['img']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $match->setImg($newFilename);
            }
            $matchRepository->save($match, true);

            return $this->redirectToRoute('app_match_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('match/edit.html.twig', [
            'match' => $match,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_match_delete', methods: ['POST'])]
    function delete(Request $request, match_ $match, matchRepository $matchRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $match->getId(), $request->request->get('_token'))) {
            $matchRepository->remove($match, true);
        }

        return $this->redirectToRoute('app_match_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/showBlogs', name: 'showBlogs', methods: ['GET'])]
    function showFront(matchRepository $matchRepository): Response
    {
        return $this->render('match/blogfront.html.twig', [
            'ac' => $matchRepository->findAll(),
        ]);
    }
}
