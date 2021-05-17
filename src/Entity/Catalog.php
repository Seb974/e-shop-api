<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CatalogRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CatalogRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"catalogs_read"}},
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
class Catalog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"catalogs_read", "catalogTaxes_read", "catalogPrices_read", "taxes_read", "containers_read", "products_read", "conditions_read", "cities_read", "orders_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"catalogs_read", "catalogTaxes_read", "catalogPrices_read", "taxes_read", "containers_read", "products_read", "conditions_read", "cities_read", "orders_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"catalogs_read", "catalogTaxes_read", "catalogPrices_read", "taxes_read", "containers_read", "products_read", "conditions_read", "cities_read", "orders_read"})
     */
    private $code;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"catalogs_read", "catalogTaxes_read", "catalogPrices_read", "taxes_read", "containers_read", "products_read", "conditions_read", "cities_read", "orders_read"})
     */
    private $needsParcel;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"catalogs_read", "catalogTaxes_read"})
     */
    private $center = [];

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"catalogs_read", "catalogTaxes_read"})
     */
    private $minLat;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"catalogs_read", "catalogTaxes_read"})
     */
    private $maxLat;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"catalogs_read", "catalogTaxes_read"})
     */
    private $minLng;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"catalogs_read", "catalogTaxes_read"})
     */
    private $maxLng;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"catalogs_read", "catalogTaxes_read", "catalogPrices_read", "taxes_read", "containers_read", "products_read", "conditions_read", "cities_read", "orders_read"})
     */
    private $isDefault;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"catalogs_read", "catalogTaxes_read"})
     */
    private $zoom;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getNeedsParcel(): ?bool
    {
        return $this->needsParcel;
    }

    public function setNeedsParcel(?bool $needsParcel): self
    {
        $this->needsParcel = $needsParcel;

        return $this;
    }

    public function getCenter(): ?array
    {
        return $this->center;
    }

    public function setCenter(?array $center): self
    {
        $this->center = $center;

        return $this;
    }

    public function getMinLat(): ?float
    {
        return $this->minLat;
    }

    public function setMinLat(?float $minLat): self
    {
        $this->minLat = $minLat;

        return $this;
    }

    public function getMaxLat(): ?float
    {
        return $this->maxLat;
    }

    public function setMaxLat(?float $maxLat): self
    {
        $this->maxLat = $maxLat;

        return $this;
    }

    public function getMinLng(): ?float
    {
        return $this->minLng;
    }

    public function setMinLng(?float $minLng): self
    {
        $this->minLng = $minLng;

        return $this;
    }

    public function getMaxLng(): ?float
    {
        return $this->maxLng;
    }

    public function setMaxLng(?float $maxLng): self
    {
        $this->maxLng = $maxLng;

        return $this;
    }

    public function getIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(?bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function getZoom(): ?int
    {
        return $this->zoom;
    }

    public function setZoom(?int $zoom): self
    {
        $this->zoom = $zoom;

        return $this;
    }
}
