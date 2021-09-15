<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AgentRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AgentRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"agents_read"}},
 *      denormalizationContext={"groups"={"agent_write"}},
 *      collectionOperations={
 *          "GET",
 *          "POST"={"security"="is_granted('ROLE_ADMIN')"},
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_ADMIN')"},
 *          "PATCH"={"security"="is_granted('ROLE_ADMIN')"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 */
class Agent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"agents_read", "agent_write"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"agents_read", "agent_write"})
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"agents_read", "agent_write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"agents_read", "agent_write"})
     */
    private $role;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?Picture
    {
        return $this->image;
    }

    public function setImage(?Picture $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
