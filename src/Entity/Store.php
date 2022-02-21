<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StoreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ORM\Entity(repositoryClass=StoreRepository::class)
 * @ApiResource(
 *      mercure={"private": false},
 *      denormalizationContext={
 *          "groups"={"store_write"},
 *          "disable_type_enforcement"=true
 *      },
 *      normalizationContext={"groups"={"stores_read"}},
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
 * @ApiFilter(SearchFilter::class, properties={"name"="word_start"})
 * @ApiFilter(OrderFilter::class, properties={"name"})
 */
class Store
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"stores_read", "store_write", "sellers_read", "sales_read", "platforms_read", "provisions_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"stores_read", "store_write", "sellers_read", "sales_read", "platforms_read", "provisions_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"stores_read", "store_write", "sellers_read", "sales_read", "platforms_read", "provisions_read"})
     */
    private $main;

    /**
     * @ORM\OneToOne(targetEntity=Meta::class, cascade={"persist", "remove"})
     * @Groups({"stores_read", "store_write", "sellers_read", "sales_read", "provisions_read"})
     */
    private $metas;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @Groups({"stores_read", "store_write"})
     */
    private $managers;

    /**
     * @ORM\ManyToOne(targetEntity=Seller::class, inversedBy="stores")
     * @Groups({"stores_read", "store_write", "sales_read", "platforms_read", "provisions_read"})
     */
    private $seller;

    /**
     * @ORM\ManyToOne(targetEntity=Platform::class, inversedBy="stores")
     * @Groups({"stores_read", "store_write", "sales_read", "provisions_read"})
     */
    private $platform;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"stores_read", "store_write", "platforms_read"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"store_write"})
     */
    private $apiKey;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"stores_read", "store_write", "platforms_read"})
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class)
     * @Groups({"stores_read", "store_write"})
     */
    private $storeGroup;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"stores_read", "store_write", "sellers_read"})
     */
    private $isTaxIncluded;

    public function __construct()
    {
        $this->managers = new ArrayCollection();
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

    public function getMain(): ?bool
    {
        return $this->main;
    }

    public function setMain(?bool $main): self
    {
        $this->main = $main;

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
    public function getManagers(): Collection
    {
        return $this->managers;
    }

    public function addManager(User $manager): self
    {
        if (!$this->managers->contains($manager)) {
            $this->managers[] = $manager;
        }

        return $this;
    }

    public function removeManager(User $manager): self
    {
        $this->managers->removeElement($manager);

        return $this;
    }

    public function getSeller(): ?Seller
    {
        return $this->seller;
    }

    public function setSeller(?Seller $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    public function getPlatform(): ?Platform
    {
        return $this->platform;
    }

    public function setPlatform(?Platform $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(?string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(?string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getStoreGroup(): ?Group
    {
        return $this->storeGroup;
    }

    public function setStoreGroup(?Group $storeGroup): self
    {
        $this->storeGroup = $storeGroup;

        return $this;
    }

    public function getIsTaxIncluded(): ?bool
    {
        return $this->isTaxIncluded;
    }

    public function setIsTaxIncluded(?bool $isTaxIncluded): self
    {
        $this->isTaxIncluded = $isTaxIncluded;

        return $this;
    }
}
