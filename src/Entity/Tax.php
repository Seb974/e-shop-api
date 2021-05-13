<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaxRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TaxRepository::class)
 * @ApiResource(
 *      denormalizationContext={
 *          "groups"={"tax_write"},
 *          "disable_type_enforcement"=true
 *      },
 *      normalizationContext={
 *          "groups"={"taxes_read"}
 *      },
 *      collectionOperations={
 *          "GET",
 *          "POST"={"security"="is_granted('ROLE_TEAM')"},
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_TEAM')"},
 *          "PATCH"={"security"="is_granted('ROLE_TEAM')"},
 *          "DELETE"={"security"="is_granted('ROLE_TEAM')"}
 *     },
 * )
 */
class Tax
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"taxes_read", "products_read", "conditions_read", "cities_read", "containers_read", "tax_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"taxes_read", "products_read", "conditions_read", "cities_read", "containers_read", "tax_write"})
     * @Assert\NotBlank(message="Un nom est obligatoire.")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=CatalogTax::class, mappedBy="tax", cascade={"persist", "remove"})
     * @Groups({"taxes_read", "products_read", "conditions_read", "cities_read", "containers_read", "tax_write"})
     */
    private $catalogTaxes;

    public function __construct()
    {
        $this->catalogTaxes = new ArrayCollection();
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

    /**
     * @return Collection|CatalogTax[]
     */
    public function getCatalogTaxes(): Collection
    {
        return $this->catalogTaxes;
    }

    public function addCatalogTax(CatalogTax $catalogTax): self
    {
        if (!$this->catalogTaxes->contains($catalogTax)) {
            $this->catalogTaxes[] = $catalogTax;
            $catalogTax->setTax($this);
        }

        return $this;
    }

    public function removeCatalogTax(CatalogTax $catalogTax): self
    {
        if ($this->catalogTaxes->removeElement($catalogTax)) {
            // set the owning side to null (unless already changed)
            if ($catalogTax->getTax() === $this) {
                $catalogTax->setTax(null);
            }
        }

        return $this;
    }
}
