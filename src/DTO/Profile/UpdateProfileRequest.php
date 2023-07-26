<?php

namespace App\DTO\Profile;

use Symfony\Component\HttpFoundation\File\File;

class UpdateProfileRequest
{
    public string $briefDescription;

    public string $description;

    public File $backgroundImage;

    public File $profileImage;
}