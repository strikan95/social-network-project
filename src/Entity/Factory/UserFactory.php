<?php

namespace App\Entity\Factory;

use App\DTO\User\CreateUserRequest;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserFactory extends AbstractEntityFactory
{
    private static ?UserFactory $_instance = null;

    private function __construct() { }

    public static function factory(): ?UserFactory
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    private function getPasswordHasher(): UserPasswordHasher
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

    function getEntityClassName(): string
    {
        return User::class;
    }

    /** @param User $target
     * @param CreateUserRequest $source
     */
    function onCreatePreLoad($source, mixed $target, ?array $settings): void
    {
        $target->setPassword(
            $this->getPasswordHasher()->hashPassword($target, $source->plainTextPassword)
        );
    }
}