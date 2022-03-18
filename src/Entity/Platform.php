<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlatformRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlatformRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"platforms_read"}},
 *      denormalizationContext={"groups"={"platform_write"}},
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
class Platform
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"platforms_read", "platform_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"platforms_read", "platform_write"})
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Meta::class, cascade={"persist", "remove"})
     * @Groups({"platforms_read", "platform_write"})
     */
    private $metas;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @Groups({"platforms_read", "platform_write"})
     */
    private $pickers;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"platforms_read", "platform_write"})
     */
    private $notices;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"platforms_read", "platform_write"})
     */
    private $terms;

    /**
     * @ORM\OneToMany(targetEntity=Social::class, mappedBy="platform", cascade={"persist", "remove"})
     * @Groups({"platforms_read", "platform_write"})
     */
    private $socials;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"platforms_read", "platform_write"})
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Store::class, mappedBy="platform")
     * @Groups({"platforms_read"})
     */
    private $stores;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"platforms_read", "platform_write"})
     */
    private $siret;

    public function __construct()
    {
        $this->pickers = new ArrayCollection();
        $this->socials = new ArrayCollection();
        $this->stores = new ArrayCollection();
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

    public function getMetas(): ?Meta
    {
        return $this->metas;
    }

    public function setMetas(?Meta $metas): self
    {
        $this->metas = $metas;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getPickers(): Collection
    {
        return $this->pickers;
    }

    public function addPicker(User $picker): self
    {
        if (!$this->pickers->contains($picker)) {
            $this->pickers[] = $picker;
        }

        return $this;
    }

    public function removePicker(User $picker): self
    {
        $this->pickers->removeElement($picker);

        return $this;
    }

    public function getNotices(): ?string
    {
        return $this->notices;
    }

    public function setNotices(?string $notices): self
    {
        $this->notices = $notices;

        return $this;
    }

    public function getTerms(): ?string
    {
        return $this->terms;
    }

    public function setTerms(?string $terms): self
    {
        $this->terms = $terms;

        return $this;
    }

    /**
     * @return Collection|Social[]
     */
    public function getSocials(): Collection
    {
        return $this->socials;
    }

    public function addSocial(Social $social): self
    {
        if (!$this->socials->contains($social)) {
            $this->socials[] = $social;
            $social->setPlatform($this);
        }

        return $this;
    }

    public function removeSocial(Social $social): self
    {
        if ($this->socials->removeElement($social)) {
            // set the owning side to null (unless already changed)
            if ($social->getPlatform() === $this) {
                $social->setPlatform(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Store[]
     */
    public function getStores(): Collection
    {
        return $this->stores;
    }

    public function addStore(Store $store): self
    {
        if (!$this->stores->contains($store)) {
            $this->stores[] = $store;
            $store->setPlatform($this);
        }

        return $this;
    }

    public function removeStore(Store $store): self
    {
        if ($this->stores->removeElement($store)) {
            // set the owning side to null (unless already changed)
            if ($store->getPlatform() === $this) {
                $store->setPlatform(null);
            }
        }

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }
}
