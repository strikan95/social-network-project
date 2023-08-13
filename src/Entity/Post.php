<?php

namespace App\Entity;
use App\DTO\Post\CreatePostRequest;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 10)]
    private string $access;

    #[ORM\Column(length: 2000)]
    private string $content;

    #[ORM\Column(type: "datetime")]
    private DateTime $createdAt;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    public function __construct(
        string $access,
        string $content,
        User $author
    )
    {
        $this->access = $access;
        $this->content = $content;
        $this->user = $author;

        // Set default values in the constructor
        $this->createdAt = new DateTime();
    }

    public static function create(User $author, CreatePostRequest $createPostRequest): Post
    {
        return new self($createPostRequest->access, $createPostRequest->content, $author);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function access(): string
    {
        return $this->access;
    }
    public function content(): string
    {
        return $this->content;
    }
    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }
    public function user(): User
    {
        return $this->user;
    }

    public function createdAtinUnixTime() : int
    {
        return $this->createdAt->getTimestamp();
    }
}
