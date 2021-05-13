<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CatalogTaxRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CatalogTaxRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"catalogTaxes_read"}},
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
class CatalogTax
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"catalogTaxes_read", "tax_write"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Catalog::class)
     * @Groups({"catalogTaxes_read", "taxes_read", "products_read", "conditions_read", "cities_read", "containers_read", "tax_write"})
     */
    private $catalog;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"catalogTaxes_read", "taxes_read", "products_read", "conditions_read", "cities_read", "containers_read", "tax_write"})
     */
    private $percent;

    /**
     * @ORM\ManyToOne(targetEntity=Tax::class, inversedBy="catalogTaxes")
     * @Groups({"catalogTaxes_read"})
     */
    private $tax;

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

    public function getPercent(): ?float
    {
        return $this->percent;
    }

    public function setPercent(?float $percent): self
    {
        $this->percent = $percent;

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
}
