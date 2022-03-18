<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SellerRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Filter\Seller\SellerFilterByImage;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=SellerRepository::class)
 * @ApiResource(
 *      denormalizationContext={"groups"={"seller_write"}},
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
 * @ApiFilter(OrderFilter::class, properties={"name"})
 * @ApiFilter(SearchFilter::class, properties={"name"="word_start"})
 * @ApiFilter(BooleanFilter::class, properties={"needsRecovery", "isActive"})
 * @ApiFilter(SellerFilterByImage::class, properties={"existingImage"="exact"})
 */
class Seller
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"sellers_read", "seller_write", "products_read", "admin:orders_read", "suppliers_read", "provisions_read", "stores_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Assert\Length(min = 3, minMessage = "Le nom doit contenir au moins {{ limit }} caractères.",
     *                max = 120, maxMessage = "Le nom ne peut contenir plus de {{ limit }} caractères.")
     * @Groups({"sellers_read", "seller_write", "products_read", "admin:orders_read", "suppliers_read", "provisions_read", "stores_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"sellers_read", "seller_write", "products_read"})
     */
    private $delay;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"sellers_read", "seller_write", "admin:orders_read"})
     */
    private $ownerRate;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"sellers_read", "seller_write"})
     */
    private $turnover;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"sellers_read", "seller_write"})
     */
    private $totalToPay;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @Groups({"sellers_read", "seller_write", "seller:products_read", "admin:orders_read", "provisions_read"})
     */
    private $users;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"sellers_read", "seller_write", "seller:products_read", "admin:orders_read"})
     */
    private $needsRecovery;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"sellers_read", "seller_write", "seller:products_read", "admin:orders_read"})
     */
    private $delayInDays;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"sellers_read", "seller_write", "seller:products_read", "admin:orders_read"})
     */
    private $recoveryDelay;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"sellers_read", "seller_write"})
     */
    private $turnoverTTC;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"sellers_read", "seller_write"})
     */
    private $totalToPayTTC;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"sellers_read", "seller_write"})
     */
    private $image;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"sellers_read", "seller_write"})
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=Store::class, mappedBy="seller")
     * @Groups({"sellers_read", "seller_write"})
     */
    private $stores;

    /**
     * @ORM\ManyToOne(targetEntity=Meta::class, cascade={"persist"})
     * @Groups({"sellers_read", "seller_write"})
     */
    private $metas;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->stores = new ArrayCollection();
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

    public function getNeedsRecovery(): ?bool
    {
        return $this->needsRecovery;
    }

    public function setNeedsRecovery(?bool $needsRecovery): self
    {
        $this->needsRecovery = $needsRecovery;

        return $this;
    }

    public function getDelayInDays(): ?bool
    {
        return $this->delayInDays;
    }

    public function setDelayInDays(?bool $delayInDays): self
    {
        $this->delayInDays = $delayInDays;

        return $this;
    }

    public function getRecoveryDelay(): ?int
    {
        return $this->recoveryDelay;
    }

    public function setRecoveryDelay(?int $recoveryDelay): self
    {
        $this->recoveryDelay = $recoveryDelay;

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

    public function getTotalToPayTTC(): ?float
    {
        return $this->totalToPayTTC;
    }

    public function setTotalToPayTTC(?float $totalToPayTTC): self
    {
        $this->totalToPayTTC = $totalToPayTTC;

        return $this;
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|Store[]
     */
    public function getStores(): Collection
    {
        return $this->stores;
    }

    public function addStore(Store $store): self
    {
        if (!$this->stores->contains($store)) {
            $this->stores[] = $store;
            $store->setSeller($this);
        }

        return $this;
    }

    public function removeStore(Store $store): self
    {
        if ($this->stores->removeElement($store)) {
            // set the owning side to null (unless already changed)
            if ($store->getSeller() === $this) {
                $store->setSeller(null);
            }
        }

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
}
