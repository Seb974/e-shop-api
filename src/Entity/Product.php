<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource(
 *      mercure={"private": false}),
 *      denormalizationContext={"disable_type_enforcement"=true},
 *      normalizationContext={
 *          "groups"={"products_read"}
 *      },
 *      collectionOperations={"GET", "POST"},
 *      itemOperations={"GET", "PUT", "PATCH", "DELETE"}
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"products_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"products_read"})
     * @Assert\NotBlank(message="Un nom est obligatoire.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"products_read"})
     */
    private $sku;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"products_read"})
     */
    private $image;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"products_read"})
     */
    private $prices = [];

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"products_read"})
     */
    private $discount;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"products_read"})
     */
    private $offerEnd;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"products_read"})
     */
    private $fullDescription;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"products_read"})
     */
    private $saleCount;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read"})
     */
    private $new;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read"})
     */
    private $available;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read"})
     */
    private $stockManaged;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read"})
     */
    private $requireLegalAge;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"products_read"})
     */
    private $userGroups = [];

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     * @Groups({"products_read"})
     */
    private $unit;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"products_read"})
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"products_read"})
     */
    private $productGroup;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read"})
     */
    private $isMixed;

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

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;

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

    public function getPrices(): ?array
    {
        return $this->prices;
    }

    public function setPrices(?array $prices): self
    {
        $this->prices = $prices;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getOfferEnd(): ?\DateTimeInterface
    {
        return $this->offerEnd;
    }

    public function setOfferEnd(?\DateTimeInterface $offerEnd): self
    {
        $this->offerEnd = $offerEnd;

        return $this;
    }

    public function getFullDescription(): ?string
    {
        return $this->fullDescription;
    }

    public function setFullDescription(?string $fullDescription): self
    {
        $this->fullDescription = $fullDescription;

        return $this;
    }

    public function getSaleCount(): ?int
    {
        return $this->saleCount;
    }

    public function setSaleCount(?int $saleCount): self
    {
        $this->saleCount = $saleCount;

        return $this;
    }

    public function getNew(): ?bool
    {
        return $this->new;
    }

    public function setNew(?bool $new): self
    {
        $this->new = $new;

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

    public function getStockManaged(): ?bool
    {
        return $this->stockManaged;
    }

    public function setStockManaged(?bool $stockManaged): self
    {
        $this->stockManaged = $stockManaged;

        return $this;
    }

    public function getRequireLegalAge(): ?bool
    {
        return $this->requireLegalAge;
    }

    public function setRequireLegalAge(?bool $requireLegalAge): self
    {
        $this->requireLegalAge = $requireLegalAge;

        return $this;
    }

    public function getUserGroups(): ?array
    {
        return $this->userGroups;
    }

    public function setUserGroups(?array $userGroups): self
    {
        $this->userGroups = $userGroups;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getProductGroup(): ?string
    {
        return $this->productGroup;
    }

    public function setProductGroup(?string $productGroup): self
    {
        $this->productGroup = $productGroup;

        return $this;
    }

    public function getIsMixed(): ?bool
    {
        return $this->isMixed;
    }

    public function setIsMixed(?bool $isMixed): self
    {
        $this->isMixed = $isMixed;

        return $this;
    }
}
