<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app.test')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Testing something',
            'path' => 'bla bla',
        ]);
    }
}
