<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Ex2Controller extends AbstractController
{
    #[Route('/ex2', name: 'app_ex2')]
    public function index(): Response
    {
        return $this->render('ex2/index.html.twig', [
            'controller_name' => 'Ex2Controller',
        ]);
    }
}
