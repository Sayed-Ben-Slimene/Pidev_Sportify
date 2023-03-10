<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TimeTableController extends AbstractController
{
    #[Route('/time/table', name: 'app_time_table')]
    public function index(): Response
    {
        return $this->render('time_table/time_table.html.twig', [
            'controller_name' => 'TimeTableController',
        ]);
    }
}
