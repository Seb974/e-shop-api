<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SellerRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SellerRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"sellers_read"}},
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
class Seller
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"sellers_read", "products_read", "admin:orders_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Assert\Length(min = 3, minMessage = "Le nom doit contenir au moins {{ limit }} caractères.",
     *                max = 120, maxMessage = "Le nom ne peut contenir plus de {{ limit }} caractères.")
     * @Groups({"sellers_read", "products_read", "admin:orders_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"sellers_read", "products_read"})
     */
    private $delay;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"sellers_read"})
     */
    private $ownerRate;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"sellers_read"})
     */
    private $turnover;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"sellers_read"})
     */
    private $totalToPay;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @Groups({"sellers_read", "seller:products_read", "admin:orders_read"})
     */
    private $users;

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

    public function getDelay(): ?int
    {
        return $this->delay;
    }

    public function setDelay(?int $delay): self
    {
        $this->delay = $delay;

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

    public function getTurnover(): ?float
    {
        return $this->turnover;
    }

    public function setTurnover(?float $turnover): self
    {
        $this->turnover = $turnover;

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
}
