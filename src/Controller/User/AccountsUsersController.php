<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountsUsersController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function loginUser(): Response
    {
        return $this->render('AccountsUsers/login.html.twig');
    }
    #[Route('/register', name: 'app_register')]
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
