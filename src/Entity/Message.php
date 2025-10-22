<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Conversation;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sender = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $recipient = null;

    #[ORM\ManyToOne(targetEntity: Conversation::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conversation $conversation = null;
    #[ORM\Column(type: 'boolean')]
    private bool $isRead = false;

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): static
    {
        $this->isRead = $isRead;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        $this->sender = $sender;
        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): static
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): static
    {
        $this->conversation = $conversation;
        return $this;
    }

    // ✅ Avatar du sender
    public function getSenderAvatar(): string
    {
        return $this->sender && $this->sender->getAvatar()
            ? $this->sender->getAvatar()
            : 'images/avatars/default.jpg';
    }

    // ✅ Avatar du destinataire
    public function getRecipientAvatar(): string
    {
        return $this->recipient && $this->recipient->getAvatar()
            ? $this->recipient->getAvatar()
            : 'images/avatars/default.jpg';
    }

    // ✅ Est-ce que ce message a été envoyé par l'utilisateur courant ?
    public function isMine(User $currentUser): bool
    {
        return $this->sender && $this->sender->getId() === $currentUser->getId();
    }

    // ✅ Classes CSS pour la bulle
    public function getBubbleClasses(User $currentUser): string
    {
        return $this->isMine($currentUser)
            ? 'bg-primary text-white justify-content-end'
            : 'bg-light justify-content-start';
    }

    // ✅ Date formatée pour affichage
    public function getFormattedDate(): string
    {
        return $this->createdAt ? $this->createdAt->format('H:i d/m/Y') : '';
    }
}
