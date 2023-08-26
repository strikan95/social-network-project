<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Message::class, mappedBy: 'conversation_id')]
    private Collection $messages;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $first_user = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $second_user = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $message->addConversationId($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            $message->removeConversationId($this);
        }

        return $this;
    }

    public function getFirstUser(): ?User
    {
        return $this->first_user;
    }

    public function setFirstUser(?User $first_user): static
    {
        $this->first_user = $first_user;

        return $this;
    }

    public function getSecondUser(): ?User
    {
        return $this->second_user;
    }

    public function setSecondUser(?User $second_user): static
    {
        $this->second_user = $second_user;

        return $this;
    }
}
