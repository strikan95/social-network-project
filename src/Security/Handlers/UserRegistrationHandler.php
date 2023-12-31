<?php

namespace App\Security\Handlers;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationHandler
{
    private FormInterface $form;
    private User $user;
    private Request $request;

    public function __construct(
        private readonly ContainerInterface $container,
        private readonly UserRepositoryInterface $repository,
        private readonly Security $security,
        private readonly UserPasswordHasherInterface $passwordHasher
    ){
        $this->request = $this->container->get('request_stack')->getCurrentRequest();
    }

    public function handle(FormInterface $form): ?User
    {
        $this->form = $form->handleRequest($this->request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->onSuccess();
            return $this->user;
        }

        return null;
    }

    public function onSuccess(): void
    {
        // Create and store
        $this->user = User::create($this->form->getData(), $this->passwordHasher);
        $this->repository->save($this->user, true);

        // Login
        $this->security->login($this->user);
    }
}