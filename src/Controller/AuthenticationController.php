<?php

namespace App\Controller;

use App\Form\Auth\RegistrationForm;
use App\Security\Handlers\UserRegistrationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/register', name: 'app.register')]
    public function register(UserRegistrationHandler $registrationHandler): Response
    {
        $form = $this->createForm(RegistrationForm::class);
        $user = $registrationHandler->handle($form);
        if (null !== $user)
            return $this->redirectToRoute('app.feed.show');

        return $this->render('auth/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'app.login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'controller_name' => 'AuthenticationController',
            'lastUsername' => $lastUsername,
            'error'         => $error,
        ]);
    }
}
