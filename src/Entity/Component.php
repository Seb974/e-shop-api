<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ComponentRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ComponentRepository::class)
 * @ApiResource(
 *      attributes={"enable_max_depth"=true},
 *      normalizationContext={"groups"={"components_read"}},
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
class Component
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"components_read", "products_read", "product_write"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @Groups({"components_read", "products_read", "product_write"})
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Variation::class)
     * @Groups({"components_read", "products_read", "product_write"})
     */
    private $variation;

    /**
     * @ORM\ManyToOne(targetEntity=Size::class)
     * @Groups({"components_read", "products_read", "product_write"})
     */
    private $size;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"components_read", "products_read", "product_write"})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="components")
     * @Groups({"components_read"})
     */
    private $owner;

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

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOwner(): ?Product
    {
        return $this->owner;
    }

    public function setOwner(?Product $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
