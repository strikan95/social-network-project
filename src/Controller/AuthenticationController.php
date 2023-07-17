<?php

namespace App\Controller;

use App\Entity\Factory\UserFactory;
use App\Form\RegistrationFormType;
use App\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    )
    {
    }

    #[Route('/register', name: 'social.register')]
    public function register(Request $request): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = UserFactory::factory()
                ->buildOrUpdate($form->getData());

            $this->repository->save($user, true);

            return $this->redirectToRoute('app_feed');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
