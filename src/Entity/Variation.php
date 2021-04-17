<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VariationRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VariationRepository::class)
 * @ApiResource(
 *      denormalizationContext={
 *          "groups"={"variation_write"},
 *          "disable_type_enforcement"=true
 *      },
 *      normalizationContext={"groups"={"variations_read"}},
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
class Variation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"variations_read", "variation_write", "products_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"variations_read", "variation_write", "products_read"})
     */
    private $color;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"variations_read", "variation_write", "products_read"})
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Size::class, mappedBy="variation")
     * @Groups({"variations_read", "variation_write", "products_read"})
     */
    private $sizes;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="variations")
     * @Groups({"variations_read"})
     */
    private $product;

    public function __construct()
    {
        $this->sizes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getImage(): ?Picture
    {
        return $this->image;
    }

    public function setImage(?Picture $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Size[]
     */
    public function getSizes(): Collection
    {
        return $this->sizes;
    }

    public function addSize(Size $size): self
    {
        if (!$this->sizes->contains($size)) {
            $this->sizes[] = $size;
            $size->setVariation($this);
        }

        return $this;
    }

    public function removeSize(Size $size): self
    {
        if ($this->sizes->removeElement($size)) {
            // set the owning side to null (unless already changed)
            if ($size->getVariation() === $this) {
                $size->setVariation(null);
            }
        }

        return $this;
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
}
