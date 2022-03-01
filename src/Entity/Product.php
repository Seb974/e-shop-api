<?php

namespace App\Entity;

use App\Entity\Seller;
use App\Entity\Picture;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Filter\Product\ProductFilterByGroupFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;

// "pagination_enabled"=true, "pagination_items_per_page"=30 word_start
/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource(
 *      attributes={
 *          "force_eager"=false,
 *          "pagination_client_enabled"=true,
 *          "pagination_client_items_per_page"=true,
 *      },
 *      mercure={"private": false},
 *      denormalizationContext={
 *          "groups"={"product_write"},
 *          "disable_type_enforcement"=true
 *      },
 *      normalizationContext={
 *          "groups"={"products_read", "seller:products_read"}
 *      },
 *      collectionOperations={
 *          "GET",
 *          "POST"={"security"="is_granted('ROLE_TEAM')"},
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_TEAM')"},
 *          "PATCH"={"security"="is_granted('ROLE_TEAM')"},
 *          "DELETE"={"security"="is_granted('ROLE_TEAM')"}
 *     },
 * )
 * @ApiFilter(ProductFilterByGroupFilter::class, properties={"group"="exact"})
 * @ApiFilter(SearchFilter::class, properties={"categories"="exact", "catalogs"="exact", "name"="partial", "seller"="exact", "suppliers"="exact", "id"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"saleCount", "name"})
 * @ApiFilter(BooleanFilter::class, properties={"new", "available", "storeAvailable"})
 * @ApiFilter(RangeFilter::class, properties={"discount", "offerEnd", "saleCcount"})
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"products_read", "orders_read", "tourings_read", "provisions_read", "goods_read", "heroes_read", "homepages_read", "countdowns_read", "banners_read", "purchases_read", "sales_read", "stocks_read", "traceabilities_read", "batches_read", "banners_read", "countdowns_read", "heroes_read", "homepages_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"products_read", "product_write", "orders_read", "tourings_read", "provisions_read", "goods_read", "heroes_read", "homepages_read", "countdowns_read", "banners_read", "purchases_read", "sales_read", "stocks_read", "traceabilities_read", "batches_read", "banners_read", "countdowns_read", "heroes_read", "homepages_read"})
     * @Assert\NotBlank(message="Un nom est obligatoire.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"products_read", "product_write", "traceabilities_read", "batches_read"})
     */
    private $sku;

    /**
     * @var Picture|null
     * 
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"products_read", "product_write", "orders_read", "tourings_read"})
     */
    private $image;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"products_read", "product_write", "heroes_read", "homepages_read", "countdowns_read", "banners_read"})
     */
    private $discount;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"products_read", "product_write", "heroes_read", "homepages_read", "countdowns_read", "banners_read"})
     */
    private $offerEnd;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"products_read", "product_write", "heroes_read", "homepages_read", "countdowns_read", "banners_read"})
     */
    private $fullDescription;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $saleCount;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read", "product_write", "heroes_read", "homepages_read", "countdowns_read", "banners_read"})
     */
    private $new;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read", "product_write", "heroes_read", "homepages_read", "countdowns_read", "banners_read", "orders_read", "tourings_read"})
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
     * @ORM\Column(type="string", length=12, nullable=true)
     * @Groups({"products_read", "product_write", "orders_read", "tourings_read", "provisions_read", "goods_read", "heroes_read", "homepages_read", "countdowns_read", "banners_read", "purchases_read", "sales_read", "stocks_read", "batches_read", "traceabilities_read", "banners_read", "countdowns_read", "heroes_read", "homepages_read"})
     */
    private $unit;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"products_read", "product_write", "orders_read", "purchases_read", "sales_read"})
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
     * @Groups({"products_read", "product_write", "orders_read", "items_read", "tourings_read", "purchases_read", "sales_read"})
     */
    private $tax;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class)
     * @Groups({"products_read", "product_write"})
     */
    private $categories;

    // @Groups({"products_read", "product_write", "admin:orders_read"})
    /**
     * @ORM\OneToOne(targetEntity=Stock::class, cascade={"persist", "remove"})
     * 
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

    /**
     * @ORM\OneToMany(targetEntity=Price::class, mappedBy="product", cascade={"persist", "remove"})
     * @Groups({"products_read", "product_write", "admin:orders_read"})
     */
    private $prices;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"products_read", "product_write"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class)
     * @Groups({"products_read", "product_write"})
     */
    private $userGroups;

    /**
     * @ORM\ManyToMany(targetEntity=Catalog::class)
     * @Groups({"products_read", "product_write"})
     */
    private $catalogs;

    /**
     * @ORM\ManyToOne(targetEntity=Seller::class)
     * @Groups({"products_read", "product_write", "orders_read", "purchases_read", "sales_read"})
     */
    private $seller;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"seller:products_read", "product_write", "provisions_read", "goods_read"})
     */
    private $isFabricated;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"seller:products_read", "product_write", "provisions_read", "goods_read"})
     */
    private $isSold;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"seller:products_read", "provisions_read", "goods_read"})
     */
    private $lastCost;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"seller:products_read", "product_write"})
     */
    private $requireDeclaration;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"products_read", "product_write", "orders_read", "purchases_read", "sales_read"})
     */
    private $contentWeight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"seller:products_read", "product_write", "admin:orders_read"})
     */
    private $accountingId;

    /**
     * @ORM\ManyToMany(targetEntity=Supplier::class, inversedBy="products")
     * @Groups({"seller:products_read", "product_write", "admin:orders_read"})
     */
    private $suppliers;

    // , "admin:orders_read"
    /**
     * @ORM\OneToMany(targetEntity=Cost::class, mappedBy="product", cascade={"persist", "remove"})
     * @Groups({"seller:products_read", "product_write", "provisions_read", "provision_write"})
     */
    private $costs;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read", "product_write", "heroes_read", "homepages_read", "countdowns_read", "banners_read", "orders_read", "tourings_read", "purchases_read", "sales_read"})
     */
    private $storeAvailable;

    // "admin:orders_read"
    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="product", cascade={"persist", "remove"})
     * @Groups({"products_read", "product_write", "provisions_read", "admin:orders_read"})
     */
    private $stocks;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="products")
     * @Groups({"products_read", "product_write"})
     */
    private $department;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read", "product_write", "orders_read", "admin:orders_read", "provisions_read", "purchases_read", "sales_read", "tourings_read"})
     */
    private $needsTraceability;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->variations = new ArrayCollection();
        $this->components = new ArrayCollection();
        $this->prices = new ArrayCollection();
        $this->userGroups = new ArrayCollection();
        $this->catalogs = new ArrayCollection();
        $this->suppliers = new ArrayCollection();
        $this->costs = new ArrayCollection();
        $this->stocks = new ArrayCollection();
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

    /**
     * @return Collection|Price[]
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): self
    {
        if (!$this->prices->contains($price)) {
            $this->prices[] = $price;
            $price->setProduct($this);
        }

        return $this;
    }

    public function removePrice(Price $price): self
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getProduct() === $this) {
                $price->setProduct(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getUserGroups(): Collection
    {
        return $this->userGroups;
    }

    public function addUserGroup(Group $userGroup): self
    {
        if (!$this->userGroups->contains($userGroup)) {
            $this->userGroups[] = $userGroup;
        }

        return $this;
    }

    public function removeUserGroup(Group $userGroup): self
    {
        $this->userGroups->removeElement($userGroup);

        return $this;
    }

    /**
     * @return Collection|Catalog[]
     */
    public function getCatalogs(): Collection
    {
        return $this->catalogs;
    }

    public function addCatalog(Catalog $catalog): self
    {
        if (!$this->catalogs->contains($catalog)) {
            $this->catalogs[] = $catalog;
        }

        return $this;
    }

    public function removeCatalog(Catalog $catalog): self
    {
        $this->catalogs->removeElement($catalog);

        return $this;
    }

    public function getSeller(): ?Seller
    {
        return $this->seller;
    }

    public function setSeller(?Seller $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    public function getIsFabricated(): ?bool
    {
        return $this->isFabricated;
    }

    public function setIsFabricated(?bool $isFabricated): self
    {
        $this->isFabricated = $isFabricated;

        return $this;
    }

    public function getIsSold(): ?bool
    {
        return $this->isSold;
    }

    public function setIsSold(?bool $isSold): self
    {
        $this->isSold = $isSold;

        return $this;
    }

    public function getLastCost(): ?float
    {
        return $this->lastCost;
    }

    public function setLastCost(?float $lastCost): self
    {
        $this->lastCost = $lastCost;

        return $this;
    }

    public function getRequireDeclaration(): ?bool
    {
        return $this->requireDeclaration;
    }

    public function setRequireDeclaration(?bool $requireDeclaration): self
    {
        $this->requireDeclaration = $requireDeclaration;

        return $this;
    }

    public function getContentWeight(): ?float
    {
        return $this->contentWeight;
    }

    public function setContentWeight(?float $contentWeight): self
    {
        $this->contentWeight = $contentWeight;

        return $this;
    }

    public function getAccountingId(): ?int
    {
        return $this->accountingId;
    }

    public function setAccountingId(?int $accountingId): self
    {
        $this->accountingId = $accountingId;

        return $this;
    }

    /**
     * @return Collection|Supplier[]
     */
    public function getSuppliers(): Collection
    {
        return $this->suppliers;
    }

    public function addSupplier(Supplier $supplier): self
    {
        if (!$this->suppliers->contains($supplier)) {
            $this->suppliers[] = $supplier;
        }

        return $this;
    }

    public function removeSupplier(Supplier $supplier): self
    {
        $this->suppliers->removeElement($supplier);

        return $this;
    }

    /**
     * @return Collection|Cost[]
     */
    public function getCosts(): Collection
    {
        return $this->costs;
    }

    public function addCost(Cost $cost): self
    {
        if (!$this->costs->contains($cost)) {
            $this->costs[] = $cost;
            $cost->setProduct($this);
        }

        return $this;
    }

    public function removeCost(Cost $cost): self
    {
        if ($this->costs->removeElement($cost)) {
            // set the owning side to null (unless already changed)
            if ($cost->getProduct() === $this) {
                $cost->setProduct(null);
            }
        }

        return $this;
    }

    public function getStoreAvailable(): ?bool
    {
        return $this->storeAvailable;
    }

    public function setStoreAvailable(?bool $storeAvailable): self
    {
        $this->storeAvailable = $storeAvailable;

        return $this;
    }

    /**
     * @return Collection|Stock[]
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks[] = $stock;
            $stock->setProduct($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getProduct() === $this) {
                $stock->setProduct(null);
            }
        }

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getNeedsTraceability(): ?bool
    {
        return $this->needsTraceability;
    }

    public function setNeedsTraceability(?bool $needsTraceability): self
    {
        $this->needsTraceability = $needsTraceability;

        return $this;
    }
}
