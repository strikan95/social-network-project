<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileForm;
use App\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    )
    {
    }

    #[Route('/profile', name: 'app_profile')]
    public function me(): Response
    {
        return $this->render('pages/profile_page.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/profile/show/{id}', name: 'app_profile_show')]
    public function show(int $id): Response
    {
        $user = $this->userRepository->find($id);

        if($user === $this->getUser())
            return $this->redirect('/profile');

        return $this->render('pages/profile_page.html.twig', [
            'user' => $user,
            'show' => 'true'
        ]);
    }

    #[Route('/settings', name: 'app_settings')]
    public function settings(Request $request): Response
    {
        $form = $this->createForm(EditProfileForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $user->profile()->update($form->getData());

            $this->userRepository->save($user, true);
        }

        return $this->render('pages/settings_page.html.twig', [
            'user' => $this->getUser(),
            'form' => $form,
        ]);
    }
}
