<?php

namespace App\Controller;

use App\DTO\Profile\UpdateProfileRequest;
use App\Entity\User;
use App\Form\EditProfileForm;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Services\FileUploader;
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
        $currentUser = $this->getUser();
        $followers = $currentUser->followers();
        $following = $currentUser->following();
        $followerCount = count($followers);
        $followingCount = count($following);
        $posts = $currentUser->posts();

        return $this->render('pages/profile_page.html.twig', [
            'user' => $this->getUser(),
            'followerCount' => $followerCount,
            'followingCount' => $followingCount,
            'posts' => $posts,
            'time' =>  time()
        ]);
    }

    #[Route('/profile/show/{id}', name: 'app_profile_show')]
    public function show(int $id): Response
    {
        if($id === $this->getUser()->id())
            return $this->redirect('/profile');

        $isFollowing = $this->userRepository->isFollowing($this->getUser()->id(), $id);
        $user = $this->userRepository->find($id);

        $followers = $user->followers();
        $following = $user->following();
        $followerCount = count($followers);
        $followingCount = count($following);
        $posts = $user->posts();

        return $this->render('pages/profile_page.html.twig', [
            'user' => $user,
            'isFollowing' => $isFollowing,
            'show' => 'true',
            'followerCount' => $followerCount,
            'followingCount' => $followingCount,
            'id' => $id,
            'posts' => $posts,
            'time' =>  time()
        ]);
    }

    #[Route('/settings', name: 'app_settings')]
    public function updateProfile(Request $request, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(EditProfileForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UpdateProfileRequest $dto */
            $dto = $form->getData();
            if (isset($dto->profileImage))
            {
                $dto->setUploadedProfileImageUri(
                    $fileUploader->upload($dto->profileImage)
                );
            }

            if (isset($dto->backgroundImage))
            {
                $dto->setUploadedBackgroundImageUri(
                    $fileUploader->upload($dto->backgroundImage)
                );
            }


            /** @var User $user */
            $user = $this->getUser();
            $user->profile()->update($dto);

            $this->userRepository->save($user, true);
        }

        return $this->render('pages/settings_page.html.twig', [
            'user' => $this->getUser(),
            'form' => $form,
        ]);
    }
}
