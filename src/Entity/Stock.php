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
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 * @ApiResource(
 *      mercure={"private": false},
 *      normalizationContext={"groups"={"stocks_read"}},
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
 * @ApiFilter(SearchFilter::class, properties={"name"="word_start"})
 * @ApiFilter(OrderFilter::class, properties={"name"})
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"stocks_read", "products_read", "containers_read", "product_write", "variation_write", "container_write", "admin:orders_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"stocks_read", "products_read", "containers_read", "product_write", "variation_write", "container_write", "admin:orders_read"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"stocks_read", "products_read", "containers_read", "product_write", "variation_write", "container_write", "admin:orders_read"})
     */
    private $security;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"stocks_read", "products_read", "containers_read", "product_write", "variation_write", "container_write", "admin:orders_read"})
     */
    private $alert;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"stocks_read", "product_write", "variation_write", "container_write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     * @Groups({"stocks_read", "product_write", "variation_write", "container_write"})
     */
    private $unit;

    public function __construct()
    {
        $this->warehouses = new ArrayCollection();
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
}
