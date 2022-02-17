<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SizeRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SizeRepository::class)
 * @ApiResource(
 *       attributes={
 *          "enable_max_depth"=true
 *      },
 *      normalizationContext={"groups"={"sizes_read"}},
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
class Size
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"sizes_read", "variations_read", "variation_write", "products_read", "orders_read", "provisions_read", "goods_read", "purchases_read", "sales_read", "stocks_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"sizes_read", "variations_read", "variation_write", "products_read", "orders_read", "provisions_read", "goods_read", "purchases_read", "sales_read", "stocks_read"})
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Stock::class, cascade={"persist", "remove"})
     * @Groups({"sizes_read", "variations_read", "variation_write", "products_read", "orders_read", "admin:orders_read", "purchases_read", "sales_read"})
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity=Variation::class, inversedBy="sizes")
     * @Groups({"sizes_read", "stocks_read"})
     */
    private $variation;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="size", cascade={"persist", "remove"})
     * @Groups({"sizes_read", "variations_read", "variation_write", "products_read"})
     */
    private $stocks;

    public function __construct()
    {
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

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getVariation(): ?Variation
    {
        return $this->variation;
    }

    public function setVariation(?Variation $variation): self
    {
        $this->variation = $variation;

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
            $stock->setSize($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getSize() === $this) {
                $stock->setSize(null);
            }
        }

        return $this;
    }
}
