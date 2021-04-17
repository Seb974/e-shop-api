<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SizeRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SizeRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"sizes_read"}},
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
class Size
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"sizes_read", "variations_read", "variation_write", "products_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"sizes_read", "variations_read", "variation_write", "products_read"})
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Stock::class, cascade={"persist", "remove"})
     * @Groups({"sizes_read", "variations_read", "variation_write", "products_read"})
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity=Variation::class, inversedBy="sizes")
     * @Groups({"sizes_read"})
     */
    private $variation;

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

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

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
}
