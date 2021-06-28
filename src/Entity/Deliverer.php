<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DelivererRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DelivererRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"deliverers_read"}},
 *      collectionOperations={
 *          "GET",
 *          "POST"={"security"="is_granted('ROLE_TEAM')"},
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_TEAM')"},
 *          "PATCH"={"security"="is_granted('ROLE_TEAM')"},
 *          "DELETE"={"security"="is_granted('ROLE_TEAM')"}
 *     }
 * )
 */
class Deliverer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"deliverers_read", "admin:orders_read", "tourings_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"deliverers_read", "admin:orders_read", "tourings_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"deliverers_read"})
     */
    private $isIntern;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"deliverers_read"})
     */
    private $cost;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"deliverers_read"})
     */
    private $isPercent;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"deliverers_read"})
     */
    private $totalToPay;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @Groups({"deliverers_read", "admin:orders_read", "tourings_read"})
     */
    private $users;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"deliverers_read"})
     */
    private $totalToPayTTC;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"deliverers_read"})
     */
    private $turnover;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"deliverers_read"})
     */
    private $turnoverTTC;

    /**
     * @ORM\ManyToOne(targetEntity=Tax::class)
     * @Groups({"deliverers_read"})
     */
    private $tax;

    /**
     * @ORM\ManyToOne(targetEntity=Catalog::class)
     * @Groups({"deliverers_read"})
     */
    private $catalog;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"deliverers_read"})
     */
    private $ownerRate;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getIsIntern(): ?bool
    {
        return $this->isIntern;
    }

    public function setIsIntern(?bool $isIntern): self
    {
        $this->isIntern = $isIntern;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getIsPercent(): ?bool
    {
        return $this->isPercent;
    }

    public function setIsPercent(?bool $isPercent): self
    {
        $this->isPercent = $isPercent;

        return $this;
    }

    public function getTotalToPay(): ?float
    {
        return $this->totalToPay;
    }

    public function setTotalToPay(?float $totalToPay): self
    {
        $this->totalToPay = $totalToPay;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getTotalToPayTTC(): ?float
    {
        return $this->totalToPayTTC;
    }

    public function setTotalToPayTTC(?float $totalToPayTTC): self
    {
        $this->totalToPayTTC = $totalToPayTTC;

        return $this;
    }

    public function getTurnover(): ?float
    {
        return $this->turnover;
    }

    public function setTurnover(?float $turnover): self
    {
        $this->turnover = $turnover;

        return $this;
    }

    public function getTurnoverTTC(): ?float
    {
        return $this->turnoverTTC;
    }

    public function setTurnoverTTC(?float $turnoverTTC): self
    {
        $this->turnoverTTC = $turnoverTTC;

        return $this;
    }

    public function getTax(): ?Tax
    {
        return $this->tax;
    }

    public function setTax(?Tax $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getCatalog(): ?Catalog
    {
        return $this->catalog;
    }

    public function setCatalog(?Catalog $catalog): self
    {
        $this->catalog = $catalog;

        return $this;
    }

    public function getOwnerRate(): ?float
    {
        return $this->ownerRate;
    }

    public function setOwnerRate(?float $ownerRate): self
    {
        $this->ownerRate = $ownerRate;

        return $this;
    }
}
