<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=CostRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"costs_read"}},
 *      collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_TEAM')"},
 *          "POST"={"security"="is_granted('ROLE_SELLER')"},
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_TEAM')"},
 *          "PUT"={"security"="is_granted('ROLE_SELLER')"},
 *          "PATCH"={"security"="is_granted('ROLE_SELLER')"},
 *          "DELETE"={"security"="is_granted('ROLE_SELLER')"}
 *     }
 * )
 */
class Cost
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"costs_read", "seller:products_read", "product_write", "provisions_read", "provision_write", "admin:orders_read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="costs")
     * @Groups({"costs_read"})
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Supplier::class)
     * @Groups({"costs_read", "seller:products_read", "provisions_read", "product_write", "admin:orders_read"})
     */
    private $supplier;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"costs_read", "seller:products_read", "product_write", "provisions_read", "provision_write", "admin:orders_read"})
     */
    private $value;

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

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }
}
