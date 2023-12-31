<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class FollowersController extends AbstractController
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    )
    {
    }

    #[Route('/users/{id}/follow', name: 'app.followers.follow', methods: ['POST'])]
    public function follow(int $id): RedirectResponse
    {
        $userToFollow = $this->userRepository->find($id);
        $currentUser = $this->getUser();
        $currentUser->follow($userToFollow);
        $this->userRepository->save($currentUser, true);

        $this->addFlash('success', 'You followed the user');

        return $this->redirectToRoute('app_profile_show', ['id' => $id]);
    }

    #[Route('/users/{id}/unfollow', name: 'app.followers.unfollow', methods: ['POST'])]
    public function unfollow(int $id): RedirectResponse
    {
        $userToUnfollow = $this->userRepository->find($id);
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $currentUser->unfollow($userToUnfollow);
        $this->userRepository->save($currentUser, true);

        $this->addFlash('success', 'You unfollowed the user');

        return $this->redirectToRoute('app_profile_show', ['id' => $id]);
    }
}