<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Conversation::class, inversedBy: 'messages')]
    private Collection $conversation_id;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'messages')]
    private Collection $user_id;

    public function __construct()
    {
        $this->conversation_id = new ArrayCollection();
        $this->user_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversationId(): Collection
    {
        return $this->conversation_id;
    }

    public function addConversationId(Conversation $conversationId): static
    {
        if (!$this->conversation_id->contains($conversationId)) {
            $this->conversation_id->add($conversationId);
        }

        return $this;
    }

    public function removeConversationId(Conversation $conversationId): static
    {
        $this->conversation_id->removeElement($conversationId);

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(User $userId): static
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id->add($userId);
        }

        return $this;
    }

    public function removeUserId(User $userId): static
    {
        $this->user_id->removeElement($userId);

        return $this;
    }
}
