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
     * @Groups({"stores_read", "store_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"stores_read", "store_write"})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"stores_read", "store_write"})
     */
    private $main;

    /**
     * @ORM\OneToOne(targetEntity=Meta::class, cascade={"persist", "remove"})
     * @Groups({"stores_read", "store_write"})
     */
    private $metas;

    public function __construct()
    {
        $this->warehouses = new ArrayCollection();
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
}
