<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContainerRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContainerRepository::class)
 * @ApiResource(
 *      denormalizationContext={"groups"={"container_write"}},
 *      normalizationContext={"groups"={"containers_read"}},
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
class Container
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"containers_read", "container_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"containers_read", "container_write"})
     * @Assert\NotBlank(message="Un nom est obligatoire.")
     */
    private $name;

    // @Assert\PositiveOrZero(message="Le prix du colis doit être un nombre positif.")
    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"containers_read", "container_write"})
     */
    private $max;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"containers_read", "container_write"})
     */
    private $tare;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"containers_read", "container_write"})
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Tax::class)
     * @Groups({"containers_read", "container_write"})
     */
    private $tax;

    /**
     * @ORM\OneToOne(targetEntity=Stock::class, cascade={"persist", "remove"})
     * @Groups({"containers_read", "container_write"})
     */
    private $stock;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"containers_read", "container_write"})
     */
    private $height;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"containers_read", "container_write"})
     */
    private $width;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"containers_read", "container_write"})
     */
    private $length;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"containers_read", "container_write"})
     */
    private $available;

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

    public function getMax(): ?float
    {
        return $this->max;
    }

    public function setMax(?float $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getTare(): ?float
    {
        return $this->tare;
    }

    public function setTare(?float $tare): self
    {
        $this->tare = $tare;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTax(): ?Tax
    {
        return $this->tax;
    }

    public function setTax(?Tax $tax): self
    {
        $this->tax = $tax;

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

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function setLength(?float $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(?bool $available): self
    {
        $this->available = $available;

        return $this;
    }
}
