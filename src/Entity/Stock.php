<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\StockRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Filter\Stock\StockProductFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 * @ApiResource(
 *      attributes={"force_eager"=false},
 *      mercure={"private": false},
 *      normalizationContext={"groups"={"stocks_read"}},
 *      denormalizationContext={"groups"={"stock_write"}},
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
 * @ApiFilter(SearchFilter::class, properties={"name"="word_start", "platform"="exact", "store"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"name"})
 * @ApiFilter(StockProductFilter::class, properties={"productSearch"="exact"})
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"stocks_read", "products_read", "containers_read", "product_write", "variation_write", "container_write", "admin:orders_read", "batches_read", "provisions_read", "stock_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"stocks_read", "products_read", "containers_read", "product_write", "variation_write", "container_write", "admin:orders_read", "batches_read", "provisions_read", "stock_write"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"stocks_read", "products_read", "containers_read", "product_write", "variation_write", "container_write", "admin:orders_read", "stock_write"})
     */
    private $security;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"stocks_read", "products_read", "containers_read", "product_write", "variation_write", "container_write", "admin:orders_read", "stock_write"})
     */
    private $alert;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"stocks_read", "product_write", "variation_write", "container_write", "stock_write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     * @Groups({"stocks_read", "product_write", "variation_write", "container_write", "stock_write"})
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="stocks")
     * @Groups({"stocks_read", "batches_read", "stock_write"})
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Size::class, inversedBy="stocks")
     * @Groups({"stocks_read", "batches_read", "stock_write"})
     */
    private $size;

    /**
     * @ORM\ManyToOne(targetEntity=Platform::class)
     * @Groups({"stocks_read", "seller:products_read", "product_write","variation_write", "batches_read", "stock_write"})
     */
    private $platform;

    /**
     * @ORM\ManyToOne(targetEntity=Store::class)
     * @Groups({"stocks_read", "seller:products_read", "product_write", "variation_write", "batches_read", "stock_write"})
     */
    private $store;

    /**
     * @ORM\OneToMany(targetEntity=Batch::class, mappedBy="stock", cascade={"persist", "remove"})
     * @Groups({"stocks_read", "seller:products_read", "product_write", "variation_write", "admin:orders_read", "stock_write"})
     */
    private $batches;

    public function __construct()
    {
        $this->warehouses = new ArrayCollection();
        $this->batches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSecurity(): ?float
    {
        return $this->security;
    }

    public function setSecurity(?float $security): self
    {
        $this->security = $security;

        return $this;
    }

    public function getAlert(): ?float
    {
        return $this->alert;
    }

    public function setAlert(?float $alert): self
    {
        $this->alert = $alert;

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

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getSize(): ?Size
    {
        return $this->size;
    }

    public function setSize(?Size $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getPlatform(): ?Platform
    {
        return $this->platform;
    }

    public function setPlatform(?Platform $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): self
    {
        $this->store = $store;

        return $this;
    }

    /**
     * @return Collection|Batch[]
     */
    public function getBatches(): Collection
    {
        return $this->batches;
    }

    public function addBatch(Batch $batch): self
    {
        if (!$this->batches->contains($batch)) {
            $this->batches[] = $batch;
            $batch->setStock($this);
        }

        return $this;
    }

    public function removeBatch(Batch $batch): self
    {
        if ($this->batches->removeElement($batch)) {
            // set the owning side to null (unless already changed)
            if ($batch->getStock() === $this) {
                $batch->setStock(null);
            }
        }

        return $this;
    }
}
