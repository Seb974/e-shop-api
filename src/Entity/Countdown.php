<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CountdownRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CountdownRepository::class)
 * @ApiResource(
 *      mercure={"private": false},
 *      denormalizationContext={
 *          "disable_type_enforcement"=true,
 *          "groups"={"countdown_write"}
 *     },
 *      normalizationContext={"groups"={"countdowns_read"}},
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
class Countdown
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"countdown_write", "homepage_write", "countdowns_read", "homepages_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"countdown_write", "homepage_write", "countdowns_read", "homepages_read"})
     */
    private $date;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"countdown_write", "homepage_write", "countdowns_read", "homepages_read"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Homepage::class, inversedBy="countdowns")
     * @Groups({"countdown_write", "countdowns_read"})
     */
    private $homepage;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @Groups({"countdown_write", "homepage_write", "countdowns_read", "homepages_read"})
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"countdown_write", "homepage_write", "countdowns_read", "homepages_read"})
     */
    private $textColor;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"countdown_write", "homepage_write", "countdowns_read", "homepages_read"})
     */
    private $textShadow;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"countdown_write", "homepage_write", "countdowns_read", "homepages_read"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"countdown_write", "homepage_write", "countdowns_read", "homepages_read"})
     */
    private $buttonText;

    /**
     * @ORM\ManyToMany(targetEntity=Catalog::class)
     * @Groups({"countdown_write", "homepage_write", "countdowns_read", "homepages_read"})
     */
    private $catalogs;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @Groups({"countdown_write", "homepage_write", "countdowns_read", "homepages_read"})
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getTextShadow(): ?bool
    {
        return $this->textShadow;
    }

    public function setTextShadow(?bool $textShadow): self
    {
        $this->textShadow = $textShadow;

        return $this;
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

    public function getButtonText(): ?string
    {
        return $this->buttonText;
    }

    public function setButtonText(?string $buttonText): self
    {
        $this->buttonText = $buttonText;

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
