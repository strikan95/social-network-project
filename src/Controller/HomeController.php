<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app.home.show')]
    public function __invoke(): Response
    {
        return $this->render('pages/home_page.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
