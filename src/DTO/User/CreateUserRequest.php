<?php

namespace App\DTO\User;

class CreateUserRequest
{
    public string $firstName = '';
    public string $lastName = '';

    public string $email = '';

    public string $username = '';
    public string $plainTextPassword = '';
}