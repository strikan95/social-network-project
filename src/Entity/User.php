<?php

namespace App\Entity;

use App\DTO\User\CreateUserRequest;
use App\Entity\Embeddables\Profile;
use App\Repository\UserRepository;
use App\Security\PasswordHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // ------------------------------------ AUTH ------------------------------------
    #[ORM\Column(length: 255)]
    private string $username;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column]
    private string $password;

    #[ORM\Column (nullable:true)]
    private array $roles = ['ROLE_USER']; // Default user role for all users

    

    // ------------------------------------ Personal info ------------------------------------
    #[ORM\Column(length: 255)]
    private string $firstName;

    #[ORM\Column(length: 255)]
    private string $lastName;

    // ------------------------------------ Profile info ------------------------------------
    #[Embedded(class: Profile::class)]
    private Profile $profile;

    // ------------------------------------ Relationships ------------------------------------
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class, cascade: ['persist'])]
    private Collection $posts;

    #[ManyToMany(targetEntity: Conversation::class, inversedBy: 'users')]
    private Collection $conversations;

    #[ManyToMany(targetEntity: User::class, mappedBy: 'following', fetch: "EXTRA_LAZY")]
    private Collection $followers;

    #[JoinTable(name: 'followers')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'follower_id', referencedColumnName: 'id')]
    #[ManyToMany(targetEntity: 'User', inversedBy: 'followers')]
    private Collection $following;

    #[ORM\ManyToMany(targetEntity: Message::class, mappedBy: 'user_id')]
    private Collection $messages;

    // ------------------------------------ DateTime ------------------------------------

    public function __construct(
        string $username,
        string $email,
        string $firstName,
        string $lastName,
        array $roles = null,
        Profile $profile = null
    )
    {
        $this->username = $username;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;

        $this->roles[] = 'ROLE_USER';
        if (null !== $roles)
            $this->roles = array_merge($this->roles, $roles);

        if (null !== $profile) {
            $this->profile = $profile;
        } else {
            $this->profile = Profile::defaultProfile();
        }

        $this->posts = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->followers = new ArrayCollection();
        return $this;
        $this->messages = new ArrayCollection();
    }

    // -------------------------------- Factory -------------------------------- //
    public static function create(CreateUserRequest $createUserRequest, UserPasswordHasherInterface $passwordHasher = null, string $preHashedPassword = null): User
    {
        $_instance = new self($createUserRequest->username, $createUserRequest->email, $createUserRequest->firstName, $createUserRequest->lastName);

        if (null !== $preHashedPassword) {
            $_instance->password = $preHashedPassword;
        } else {
            if (!$passwordHasher)
                throw new \LogicException('Password or pre hashed password must be given');
            $_instance->password = $passwordHasher->hashPassword($_instance, $createUserRequest->plainTextPassword);
        }

        return $_instance;
    }

    // -------------------------------- Behavior -------------------------------- //
    public function follow(User $toFollow): void
    {
        $this->following->add($toFollow);
        $toFollow->followers->add($this);
    }

    public function unfollow(User $toUnfollow): void
    {
        $this->following->removeElement($toUnfollow);
    }

    // -------------------------------- Properties -------------------------------- //
    public function id(): ?int
    {
        return $this->id;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function info(): array
    {
        return [
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName
        ];
    }

    public function profile(): Profile
    {
        return $this->profile;
    }

    public function posts(): Collection
    {
        return $this->posts;
    }

    public function conversations(): Collection
    {
        return $this->conversations;
    }

    public function followers()
    {
        return $this->followers;
    }

    public function following()
    {
        return $this->following;
    }

    // ------------------------------------ Auth stuff ------------------------------------
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->addUserId($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            $message->removeUserId($this);
        }

        return $this;
    }
}
