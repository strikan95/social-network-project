<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Profile;

class ProfileController extends AbstractController
{

    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $userProfile = $user->getProfile();

        return $this->render('pages/profile_page.html.twig', [
            'user' => $user,
            'profile' => $userProfile
        ]);
    }
}
