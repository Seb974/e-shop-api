<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CatalogPriceRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CatalogPriceRepository::class)
 * @ApiResource(
 *      mercure={"private": false},
 *      normalizationContext={"groups"={"catalogPrices_read"}},
 *      collectionOperations={
 *          "GET",
 *          "POST"={"security"="is_granted('ROLE_ADMIN')"},
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_ADMIN')"},
 *          "PATCH"={"security"="is_granted('ROLE_ADMIN')"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 */
class CatalogPrice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"catalogPrices_read", "containers_read", "container_write"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Catalog::class)
     * @Groups({"catalogPrices_read", "containers_read", "container_write"})
     */
    private $catalog;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"catalogPrices_read", "containers_read", "container_write"})
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=Container::class, inversedBy="catalogPrices")
     * @Groups({"catalogPrices_read"})
     */
    private $container;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatalog(): ?Catalog
    {
        return $this->catalog;
    }

    public function setCatalog(?Catalog $catalog): self
    {
        $this->catalog = $catalog;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getContainer(): ?Container
    {
        return $this->container;
    }

    public function setContainer(?Container $container): self
    {
        $this->container = $container;

        return $this;
    }
}
