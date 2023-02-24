<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Register;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/registerUser', name: 'app_registration', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository , SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(Register::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(['ROLE_USER']);
            $userRepository->save($user, true);
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('registration/register.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }



}
