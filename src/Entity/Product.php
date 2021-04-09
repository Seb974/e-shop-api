<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource(
 *      mercure={"private": false}),
 *      denormalizationContext={"disable_type_enforcement"=true},
 *      normalizationContext={
 *          "groups"={"products_read"}
 *      },
 *      collectionOperations={"GET", "POST"},
 *      itemOperations={"GET", "PUT", "PATCH", "DELETE"}
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"products_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"products_read"})
     * @Assert\NotBlank(message="Un nom est obligatoire.")
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"products_read"})
     */
    private $code;

    // @Assert\NotBlank(message="Au moins un prix est obligatoire.")
    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"products_read"})
     */
    private $prices = [];

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"products_read"})
     */
    private $picture;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"products_read"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read"})
     */
    private $isAvailable;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read"})
     */
    private $isOnTop;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"products_read"})
     */
    private $isStockManaged;

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

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(?int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPrices(): ?array
    {
        return $this->prices;
    }

    public function setPrices(?array $prices): self
    {
        $this->prices = $prices;

        return $this;
    }

    public function getPicture(): ?Picture
    {
        return $this->picture;
    }

    public function setPicture(?Picture $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(?bool $isAvailable): self
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getIsOnTop(): ?bool
    {
        return $this->isOnTop;
    }

    public function setIsOnTop(?bool $isOnTop): self
    {
        $this->isOnTop = $isOnTop;

        return $this;
    }

    public function getIsStockManaged(): ?bool
    {
        return $this->isStockManaged;
    }

    public function setIsStockManaged(?bool $isStockManaged): self
    {
        $this->isStockManaged = $isStockManaged;

        return $this;
    }
}
