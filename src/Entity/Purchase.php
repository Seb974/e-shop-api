<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PurchaseRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PurchaseRepository::class)
 * @ApiResource(
 *     denormalizationContext={
 *          "disable_type_enforcement"=true,
 *          "groups"={"sales_write"}
 *     },
 *     normalizationContext={"groups"={"purchases_read"}},
 *     collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_TEAM')"},
 *          "POST"={"security"="is_granted('ROLE_TEAM')"}
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_TEAM')"},
 *          "PUT"={"security"="is_granted('ROLE_TEAM')"},
 *          "PATCH"={"security"="is_granted('ROLE_TEAM')"},
 *          "DELETE"={"security"="is_granted('ROLE_TEAM')"}
 *     },
 * )
 */
class Purchase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"purchases_read", "sales_read", "sale_write"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @Groups({"purchases_read", "sales_read", "sale_write"})
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Variation::class)
     * @Groups({"purchases_read", "sales_read", "sale_write"})
     */
    private $variation;

    /**
     * @ORM\ManyToOne(targetEntity=Size::class)
     * @Groups({"purchases_read", "sales_read", "sale_write"})
     */
    private $size;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"purchases_read", "sales_read", "sale_write"})
     */
    private $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"purchases_read", "sales_read", "sale_write"})
     */
    private $taxRate;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"purchases_read", "sales_read", "sale_write"})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Sale::class, inversedBy="purchases")
     * @Groups({"purchases_read"})
     */
    private $sale;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVariation(): ?Variation
    {
        return $this->variation;
    }

    public function setVariation(?Variation $variation): self
    {
        $this->variation = $variation;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTaxRate(): ?float
    {
        return $this->taxRate;
    }

    public function setTaxRate(?float $taxRate): self
    {
        $this->taxRate = $taxRate;

        return $this;
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

    public function getSale(): ?Sale
    {
        return $this->sale;
    }

    public function setSale(?Sale $sale): self
    {
        $this->sale = $sale;

        return $this;
    }
}
