<?php

namespace App\Controller\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountsUsersController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET'])]
    public function loginUser(UserRepository $userRepository): Response
    {
        return $this->render('AccountsUsers/login.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/regissssster', name: 'app_registsssser')]
    public function registerUser(): Response
    {
        return $this->render('AccountsUsers/register.html.twig');
    }
    #[Route('/forgetpass', name: 'pass_forget')]
    public function forgotpass(): Response
    {
        return $this->render('AccountsUsers/forget_password.html.twig');
    }




}
