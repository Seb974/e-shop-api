<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BannerRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BannerRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"banners_read"}},
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
class Banner
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"banners_read", "homepages_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"banners_read", "homepages_read"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"banners_read", "homepages_read"})
     */
    private $subtitle;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @Groups({"banners_read", "homepages_read"})
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"banners_read", "homepages_read"})
     */
    private $link;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"banners_read", "homepages_read"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Homepage::class, inversedBy="banners")
     * @Groups({"banners_read"})
     */
    private $homepage;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"banners_read", "homepages_read"})
     */
    private $main;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"banners_read", "homepages_read"})
     */
    private $bannerNumber;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

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

    public function getMain(): ?bool
    {
        return $this->main;
    }

    public function setMain(?bool $main): self
    {
        $this->main = $main;

        return $this;
    }

    public function getBannerNumber(): ?int
    {
        return $this->bannerNumber;
    }

    public function setBannerNumber(?int $bannerNumber): self
    {
        $this->bannerNumber = $bannerNumber;

        return $this;
    }
}
