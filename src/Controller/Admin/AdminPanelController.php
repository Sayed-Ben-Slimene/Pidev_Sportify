<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]

    public function index(): Response
    {
        return $this->render('admin_panel/admin.html.twig');
    }
    #[Route('/loginAuth', name: 'Auth_login')]
    public function loginAuth(): Response
    {
        return $this->render('admin_panel/loginAuth.html.twig');
    }
    #[Route('/registerAuth', name: 'Auth_register')]
    public function registerAuth(): Response
    {
        return $this->render('admin_panel/registerAuth.html.twig');
    }
    #[Route('/Auth_forgot_pass', name: 'Auth_forgot_pass')]
    public function forgot_passAuth(): Response
    {
        return $this->render('admin_panel/Auth_forgot_pass.html.twig');
    }



}
