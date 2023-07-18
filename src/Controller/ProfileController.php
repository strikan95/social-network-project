<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Profile;
use App\Entity\User;

class ProfileController extends AbstractController
{

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();
        $userProfile = $user->getProfile();

        return $this->render('pages/profile_page.html.twig', [
            'user' => $user,
            'profile' => $userProfile
        ]);
    }

    #[Route('/profile/show/{id}', name: 'app_profile_show')]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        $userProfile = $user->getProfile();
        $currentId = $this->getUser()->getId();

        if($id == $currentId ) return $this->redirect('/profile');

        return $this->render('pages/profile_page.html.twig', [
            'user' => $user,
            'profile' => $userProfile,
            'show' => 'true'
        ]);
    }
}
