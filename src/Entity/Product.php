<?php

namespace App\Entity;

use App\Entity\Picture;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource(
 *      attributes={"force_eager"=false},
 *      mercure={"private": false},
 *      denormalizationContext={
 *          "groups"={"product_write"},
 *          "disable_type_enforcement"=true
 *      },
 *      normalizationContext={
 *          "groups"={"products_read"}
 *      },
 *      collectionOperations={
 *          "GET",
 *          "POST"={"security"="is_granted('ROLE_TEAM')"}
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_TEAM')"},
 *          "PATCH"={"security"="is_granted('ROLE_TEAM')"},
 *          "DELETE"={"security"="is_granted('ROLE_TEAM')"}
 *     }
 * )
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
     * @Groups({"products_read", "product_write"})
     * @Assert\NotBlank(message="Un nom est obligatoire.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $sku;

    /**
     * @var Picture|null
     * 
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"products_read", "product_write"})
     */
    private $image;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $prices = [];

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $discount;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $offerEnd;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $fullDescription;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $saleCount;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $new;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $available;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $stockManaged;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $requireLegalAge;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $userGroups = [];

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $unit;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $productGroup;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $isMixed;

    /**
     * @ORM\ManyToOne(targetEntity=Tax::class)
     * @Groups({"products_read", "product_write"})
     */
    private $tax;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class)
     * @Groups({"products_read", "product_write"})
     */
    private $categories;

    /**
     * @ORM\OneToOne(targetEntity=Stock::class, cascade={"persist", "remove"})
     * @Groups({"products_read", "product_write"})
     */
    private $stock;

    /**
     * @ORM\OneToMany(targetEntity=Variation::class, mappedBy="product")
     * @Groups({"products_read", "product_write"})
     */
    private $variations;

    /**
     * @ORM\OneToMany(targetEntity=Component::class, mappedBy="owner", cascade={"persist", "remove"})
     * @Groups({"products_read", "product_write"})
     */
    private $components;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->variations = new ArrayCollection();
        $this->components = new ArrayCollection();
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

    public function getTax(): ?Tax
    {
        return $this->tax;
    }

    public function setTax(?Tax $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection|Variation[]
     */
    public function getVariations(): Collection
    {
        return $this->variations;
    }

    public function addVariation(Variation $variation): self
    {
        if (!$this->variations->contains($variation)) {
            $this->variations[] = $variation;
            $variation->setProduct($this);
        }

        return $this;
    }

    public function removeVariation(Variation $variation): self
    {
        if ($this->variations->removeElement($variation)) {
            // set the owning side to null (unless already changed)
            if ($variation->getProduct() === $this) {
                $variation->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Component[]
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(Component $component): self
    {
        if (!$this->components->contains($component)) {
            $this->components[] = $component;
            $component->setOwner($this);
        }

        return $this;
    }

    public function removeComponent(Component $component): self
    {
        if ($this->components->removeElement($component)) {
            // set the owning side to null (unless already changed)
            if ($component->getOwner() === $this) {
                $component->setOwner(null);
            }
        }

        return $this;
    }
}
