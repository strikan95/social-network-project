<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class PasswordHasher
{
    public static function hashPassword(User $user, string $plainTextPassword): string
    {
        return self::getPasswordHasher()->hashPassword($user, $plainTextPassword);
    }

    private static function getPasswordHasher(): UserPasswordHasher
    {
        $passwordHasherFactory = new PasswordHasherFactory(
            [
                PasswordAuthenticatedUserInterface::class => [
                    'algorithm' => 'auto'
                ]
            ]
        );
        return new UserPasswordHasher($passwordHasherFactory);
    }
}