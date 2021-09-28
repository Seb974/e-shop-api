<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\HeroRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=HeroRepository::class)
 * @ApiResource(
 *      mercure={"private": false},
 *      normalizationContext={"groups"={"heroes_read"}},
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
class Hero
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"heroes_read", "homepages_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"heroes_read", "homepages_read"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"heroes_read", "homepages_read"})
     */
    private $subtitle;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"heroes_read", "homepages_read"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Homepage::class, inversedBy="heroes")
     * @Groups({"heroes_read"})
     */
    private $homepage;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @Groups({"heroes_read", "homepages_read"})
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"heroes_read", "homepages_read"})
     */
    private $textColor;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"heroes_read", "homepages_read"})
     */
    private $titleColor;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"heroes_read", "homepages_read"})
     */
    private $textShadow;

    /**
     * @ORM\ManyToMany(targetEntity=Catalog::class)
     * @Groups({"heroes_read", "homepages_read"})
     */
    private $catalogs;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @Groups({"heroes_read", "homepages_read"})
     */
    private $category;

    public function __construct()
    {
        $this->catalogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

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

    public function getHomepage(): ?Homepage
    {
        return $this->homepage;
    }

    public function setHomepage(?Homepage $homepage): self
    {
        $this->homepage = $homepage;

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

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor): self
    {
        $this->textColor = $textColor;

        return $this;
    }

    public function getTitleColor(): ?string
    {
        return $this->titleColor;
    }

    public function setTitleColor(?string $titleColor): self
    {
        $this->titleColor = $titleColor;

        return $this;
    }

    public function getTextShadow(): ?bool
    {
        return $this->textShadow;
    }

    public function setTextShadow(?bool $textShadow): self
    {
        $this->textShadow = $textShadow;

        return $this;
    }

    /**
     * @return Collection|Catalog[]
     */
    public function getCatalogs(): Collection
    {
        return $this->catalogs;
    }

    public function addCatalog(Catalog $catalog): self
    {
        if (!$this->catalogs->contains($catalog)) {
            $this->catalogs[] = $catalog;
        }

        return $this;
    }

    public function removeCatalog(Catalog $catalog): self
    {
        $this->catalogs->removeElement($catalog);

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
