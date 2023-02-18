<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends AbstractController
{
    #[Route('/admin/panel', name: 'app_admin_panel')]
    public function index()
    {
        return $this->render('admin_panel/index.html.twig');
    }
}
