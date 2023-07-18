<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Profile;
use App\Entity\User;
use App\Form\SettingsType;

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

    #[Route('/settings', name: 'app_settings')]
    public function getSettings(EntityManagerInterface $entityManager, Request $request): Response
    {

        $user = $this->getUser();
        $profile = $user->getProfile();

        $form = $this->createForm(SettingsType::class, $profile);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $profile = $form->getData();
            $entityManager->persist($profile);
            $entityManager->flush();
            return $this->redirect($request->getUri());
        }

        return $this->render('pages/settings_page.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }
}
