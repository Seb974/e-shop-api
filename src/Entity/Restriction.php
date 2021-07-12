<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RestrictionRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RestrictionRepository::class)
 * @ApiResource(
 *      mercure={"private": false}),
 *      denormalizationContext={
 *          "groups"={"restriction_write"},
 *          "disable_type_enforcement"=true
 *      },
 *      normalizationContext={
 *          "groups"={"restrictions_read"}
 *      },
 *      collectionOperations={
 *          "GET",
 *          "POST"={"security"="is_granted('ROLE_ADMIN')"},
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_ADMIN')"},
 *          "PATCH"={"security"="is_granted('ROLE_ADMIN')"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 * )
 */
class Restriction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"restrictions_read", "restriction_write", "categories_read", "category_write"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Catalog::class)
     * @Groups({"restrictions_read", "restriction_write", "categories_read", "category_write"})
     */
    private $catalog;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"restrictions_read", "restriction_write", "categories_read", "category_write"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Groups({"restrictions_read", "restriction_write", "categories_read", "category_write"})
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="restrictions")
     * @Groups({"restrictions_read", "restriction_write"})
     */
    private $category;

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

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
