<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LostRepository;
use App\Filter\Lost\LostFilterByStore;
use App\Filter\Lost\LostFilterBySeller;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;

// mercure={"private": false},
/**
 * @ORM\Entity(repositoryClass=LostRepository::class)
 * @ApiResource(
 *      denormalizationContext={
 *          "groups"={"lost_write"},
 *          "disable_type_enforcement"=true
 *      },
 *      normalizationContext={"groups"={"losts_read"}},
 *      collectionOperations={
 *          "GET",
 *          "POST"={"security"="is_granted('ROLE_TEAM')"},
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_TEAM')"},
 *          "PATCH"={"security"="is_granted('ROLE_TEAM')"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @ApiFilter(LostFilterByStore::class, properties={"store"="exact"})
 * @ApiFilter(LostFilterBySeller::class, properties={"seller"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"lostDate", "number"})
 * @ApiFilter(SearchFilter::class, properties={"number"="word_start"})
 * @ApiFilter(DateFilter::class, properties={"lostDate"})
 */
class Lost
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"losts_read", "lost_write"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @Groups({"losts_read", "lost_write"})
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Variation::class)
     * @Groups({"losts_read", "lost_write"})
     */
    private $variation;

    /**
     * @ORM\ManyToOne(targetEntity=Size::class)
     * @Groups({"losts_read", "lost_write"})
     */
    private $size;

    /**
     * @ORM\ManyToOne(targetEntity=Stock::class)
     * @Groups({"losts_read", "lost_write"})
     */
    private $stock;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"losts_read", "lost_write"})
     */
    private $number;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"losts_read", "lost_write"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"losts_read", "lost_write"})
     */
    private $lostDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"losts_read", "lost_write"})
     */
    private $comments;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVariation(): ?Variation
    {
        return $this->variation;
    }

    public function setVariation(?Variation $variation): self
    {
        $this->variation = $variation;

        return $this;
    }

    public function getSize(): ?Size
    {
        return $this->size;
    }

    public function setSize(?Size $size): self
    {
        $this->size = $size;

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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getLostDate(): ?\DateTimeInterface
    {
        return $this->lostDate;
    }

    public function setLostDate(?\DateTimeInterface $lostDate): self
    {
        $this->lostDate = $lostDate;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }
}
