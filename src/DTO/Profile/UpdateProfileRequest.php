<?php

namespace App\DTO\Profile;

use Symfony\Component\HttpFoundation\File\File;

class UpdateProfileRequest
{
    public string $briefDescription;

    public string $description;

    public File $backgroundImage;

    public File $profileImage;

    private ?string $uploadedProfileImageUri;

    private ?string $uploadedBackgroundImageUri;

    public function getUploadedProfileImageUri(): ?string
    {
        return $this->uploadedProfileImageUri;
    }

    public function setUploadedProfileImageUri(?string $uploadedProfileImageUri): void
    {
        $this->uploadedProfileImageUri = $uploadedProfileImageUri;
    }

    public function getUploadedBackgroundImageUri(): ?string
    {
        return $this->uploadedBackgroundImageUri;
    }

    public function setUploadedBackgroundImageUri(?string $uploadedBackgroundImageUri): void
    {
        $this->uploadedBackgroundImageUri = $uploadedBackgroundImageUri;
    }

}