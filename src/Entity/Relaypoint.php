<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RelaypointRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ORM\Entity(repositoryClass=RelaypointRepository::class)
 * @ApiResource(
 *      mercure={"private": false},
 *      denormalizationContext={
 *          "groups"={"relaypoint_write"},
 *          "disable_type_enforcement"=true
 *      },
 *      normalizationContext={"groups"={"relaypoints_read"}},
 *      collectionOperations={
 *          "GET",
 *          "POST"={"security"="is_granted('ROLE_RELAYPOINT')"},
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_RELAYPOINT')"},
 *          "PATCH"={"security"="is_granted('ROLE_RELAYPOINT')"},
 *          "DELETE"={"security"="is_granted('ROLE_RELAYPOINT')"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"name"="word_start"})
 * @ApiFilter(OrderFilter::class, properties={"name"})
 */
class Relaypoint
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"relaypoints_read", "relaypoint_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"relaypoints_read", "relaypoint_write"})
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Meta::class, cascade={"persist", "remove"})
     * @Groups({"relaypoints_read", "relaypoint_write"})
     */
    private $metas;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"relaypoints_read", "relaypoint_write"})
     */
    private $available;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"relaypoints_read", "relaypoint_write"})
     */
    private $private;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups({"relaypoints_read", "relaypoint_write"})
     */
    private $accessCode;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"relaypoints_read", "relaypoint_write"})
     */
    private $informations;

    /**
     * @ORM\ManyToMany(targetEntity=Condition::class, cascade={"persist", "remove"})
     * @Groups({"relaypoints_read", "relaypoint_write"})
     */
    private $conditions;

    /**
     * @ORM\OneToOne(targetEntity=Promotion::class, cascade={"persist", "remove"})
     * @Groups({"relaypoints_read", "relaypoint_write"})
     */
    private $promotion;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @Groups({"relaypoints_read", "relaypoint_write"})
     */
    private $managers;

    public function __construct()
    {
        $this->conditions = new ArrayCollection();
        $this->managers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMetas(): ?Meta
    {
        return $this->metas;
    }

    public function setMetas(?Meta $metas): self
    {
        $this->metas = $metas;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(?bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(?bool $private): self
    {
        $this->private = $private;

        return $this;
    }

    public function getAccessCode(): ?string
    {
        return $this->accessCode;
    }

    public function setAccessCode(?string $accessCode): self
    {
        $this->accessCode = $accessCode;

        return $this;
    }

    public function getInformations(): ?string
    {
        return $this->informations;
    }

    public function setInformations(?string $informations): self
    {
        $this->informations = $informations;

        return $this;
    }

    /**
     * @return Collection|Condition[]
     */
    public function getConditions(): Collection
    {
        return $this->conditions;
    }

    public function addCondition(Condition $condition): self
    {
        if (!$this->conditions->contains($condition)) {
            $this->conditions[] = $condition;
        }

        return $this;
    }

    public function removeCondition(Condition $condition): self
    {
        $this->conditions->removeElement($condition);

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getManagers(): Collection
    {
        return $this->managers;
    }

    public function addManager(User $manager): self
    {
        if (!$this->managers->contains($manager)) {
            $this->managers[] = $manager;
        }

        return $this;
    }

    public function removeManager(User $manager): self
    {
        $this->managers->removeElement($manager);

        return $this;
    }
}
