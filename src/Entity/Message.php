<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MessageRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 * @ApiResource(
 *      mercure={"private": true},
 *      normalizationContext={"groups"={"messages_read"}},
 *      denormalizationContext={"groups"={"message_write"}},
 *      collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN')"},
 *          "POST",
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_ADMIN')"},
 *          "PATCH"={"security"="is_granted('ROLE_ADMIN')"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"messages_read", "message_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"messages_read", "message_write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"messages_read", "message_write"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"messages_read", "message_write"})
     */
    private $subject;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"messages_read", "message_write"})
     */
    private $message;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"messages_read", "message_write"})
     */
    private $sentAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"messages_read", "message_write"})
     */
    private $isRead;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"messages_read", "message_write"})
     */
    private $response;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"messages_read", "message_write"})
     */
    private $replied;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(?\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(?bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(?string $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getReplied(): ?bool
    {
        return $this->replied;
    }

    public function setReplied(?bool $replied): self
    {
        $this->replied = $replied;

        return $this;
    }
}
