<?php
namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequest
{
    #[Assert\NotBlank]
    public string $firstName = '';
    #[Assert\NotBlank]
    public string $lastName = '';
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email = '';
    #[Assert\NotBlank]
    public string $username = '';
    #[Assert\NotBlank]
    #[Assert\Length(min: 4)]
    public string $plainTextPassword = '';
}