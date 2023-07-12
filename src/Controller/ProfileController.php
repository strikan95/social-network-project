<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function __invoke(): Response
    {
        return $this->render('pages/profile_page.html.twig', [
            'username' => 'Slavko',
        ]);
    }
}
