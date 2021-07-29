<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\HomepageRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=HomepageRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"homepages_read"}},
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
class Homepage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"homepages_read", "heroes_read", "banners_read", "countdowns_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Groups({"homepages_read", "heroes_read", "banners_read", "countdowns_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"homepages_read", "heroes_read", "banners_read", "countdowns_read"})
     */
    private $selected;

    /**
     * @ORM\OneToMany(targetEntity=Hero::class, mappedBy="homepage")
     * @Groups({"homepages_read"})
     */
    private $heroes;

    /**
     * @ORM\OneToMany(targetEntity=Banner::class, mappedBy="homepage")
     * @Groups({"homepages_read"})
     */
    private $banners;

    /**
     * @ORM\OneToMany(targetEntity=Countdown::class, mappedBy="homepage")
     * @Groups({"homepages_read"})
     */
    private $countdowns;

    public function __construct()
    {
        $this->heroes = new ArrayCollection();
        $this->banners = new ArrayCollection();
        $this->countdowns = new ArrayCollection();
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

    public function getSelected(): ?bool
    {
        return $this->selected;
    }

    public function setSelected(?bool $selected): self
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * @return Collection|Hero[]
     */
    public function getHeroes(): Collection
    {
        return $this->heroes;
    }

    public function addHero(Hero $hero): self
    {
        if (!$this->heroes->contains($hero)) {
            $this->heroes[] = $hero;
            $hero->setHomepage($this);
        }

        return $this;
    }

    public function removeHero(Hero $hero): self
    {
        if ($this->heroes->removeElement($hero)) {
            // set the owning side to null (unless already changed)
            if ($hero->getHomepage() === $this) {
                $hero->setHomepage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Banner[]
     */
    public function getBanners(): Collection
    {
        return $this->banners;
    }

    public function addBanner(Banner $banner): self
    {
        if (!$this->banners->contains($banner)) {
            $this->banners[] = $banner;
            $banner->setHomepage($this);
        }

        return $this;
    }

    public function removeBanner(Banner $banner): self
    {
        if ($this->banners->removeElement($banner)) {
            // set the owning side to null (unless already changed)
            if ($banner->getHomepage() === $this) {
                $banner->setHomepage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Countdown[]
     */
    public function getCountdowns(): Collection
    {
        return $this->countdowns;
    }

    public function addCountdown(Countdown $countdown): self
    {
        if (!$this->countdowns->contains($countdown)) {
            $this->countdowns[] = $countdown;
            $countdown->setHomepage($this);
        }

        return $this;
    }

    public function removeCountdown(Countdown $countdown): self
    {
        if ($this->countdowns->removeElement($countdown)) {
            // set the owning side to null (unless already changed)
            if ($countdown->getHomepage() === $this) {
                $countdown->setHomepage(null);
            }
        }

        return $this;
    }
}