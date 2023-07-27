<?php

namespace App\Entity\Embeddables;

use App\DTO\Profile\UpdateProfileRequest;
use App\Services\ImageManagerService;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Profile
{
    #[Column(type: "string")]
    private string $briefDescription;

    #[Column(type: "string")]
    private string $description;

    #[Column(type: "string", nullable: true)]
    private ?string $profileImage;

    #[Column(type: "string", nullable: true)]
    private ?string $backgroundImage;

    public function __construct(
        string $briefDescription,
        string $description,
        string $profileImage,
        string $backgroundImage
    )
    {
        $this->briefDescription = $briefDescription;
        $this->description = $description;
        $this->profileImage = $profileImage;
        $this->backgroundImage = $backgroundImage;
    }

    public static function defaultProfile(): Profile
    {
        return new self('This is my brief description.', 'This is my detailed description.', '', '');
    }

    public static function create(string $briefDescription, string $description, string $profileImage, string $backgroundImage): Profile
    {
        return new self($briefDescription, $description, $profileImage, $backgroundImage);
    }

    public function update(UpdateProfileRequest $updateProfileRequest): void
    {
        foreach (get_object_vars($updateProfileRequest) as $param => $value)
        {
            if (null == $param)
                continue;

            if ('backgroundImage' === $param)
            {
                $this->backgroundImage = $updateProfileRequest->getUploadedBackgroundImageUri();
                continue;
            }

            if ('profileImage' === $param)
            {
                $this->profileImage = $updateProfileRequest->getUploadedProfileImageUri();
                continue;
            }

            $this->$param = $value;
        }
    }

    public function briefDescription(): string
    {
        return $this->briefDescription;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function profileImage(): string
    {
        return $this->profileImage;
    }

    public function backgroundImage(): string
    {
        return $this->backgroundImage;
    }
}