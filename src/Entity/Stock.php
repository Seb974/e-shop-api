<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\StockRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 * @ApiResource(
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
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"stocks_read", "products_read", "containers_read", "product_write", "variation_write", "container_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"stocks_read", "products_read", "containers_read", "product_write", "variation_write", "container_write"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"stocks_read", "products_read", "containers_read", "product_write", "variation_write", "container_write"})
     */
    private $security;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"stocks_read", "products_read", "containers_read", "product_write", "variation_write", "container_write"})
     */
    private $alert;

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
}
