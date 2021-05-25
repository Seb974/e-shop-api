<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ItemRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

// 'admin:items_read';
// 'admin:item_write';

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"items_read"}},
 *     collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or object.orderEntity.getUser() == user"},
 *          "POST"
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or object.orderEntity.getUser() == user"},
 *          "PUT"={"security"="is_granted('ROLE_ADMIN')"},
 *          "PATCH"={"security"="is_granted('ROLE_ADMIN')"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 * )
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"items_read", "orders_read", "order_write"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @Groups({"items_read", "orders_read", "order_write"})
     */
    private $product;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"items_read", "orders_read", "order_write"})
     */
    private $orderedQty;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"items_read", "orders_read", "admin:order_write"})
     */
    private $preparedQty;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"items_read", "orders_read", "admin:order_write"})
     */
    private $deliveredQty;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"items_read", "orders_read", "admin:order_write"})
     */
    private $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"items_read", "orders_read", "admin:order_write"})
     */
    private $taxRate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"admin:items_read", "admin:orders_read", "admin:order_write"})
     */
    private $isAdjourned;

    /**
     * @ORM\ManyToOne(targetEntity=OrderEntity::class, inversedBy="items")
     * @Groups({"admin:items_read"})
     */
    private $orderEntity;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"items_read", "orders_read", "order_write"})
     */
    private $isPrepared;

    /**
     * @ORM\ManyToOne(targetEntity=Variation::class)
     * @Groups({"items_read", "orders_read", "order_write"})
     */
    private $variation;

    /**
     * @ORM\ManyToOne(targetEntity=Size::class)
     * @Groups({"items_read", "orders_read", "order_write"})
     */
    private $size;

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

    public function getOrderedQty(): ?float
    {
        return $this->orderedQty;
    }

    public function setOrderedQty(?float $orderedQty): self
    {
        $this->orderedQty = $orderedQty;

        return $this;
    }

    public function getPreparedQty(): ?float
    {
        return $this->preparedQty;
    }

    public function setPreparedQty(?float $preparedQty): self
    {
        $this->preparedQty = $preparedQty;

        return $this;
    }

    public function getDeliveredQty(): ?float
    {
        return $this->deliveredQty;
    }

    public function setDeliveredQty(?float $deliveredQty): self
    {
        $this->deliveredQty = $deliveredQty;

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

    public function getTaxRate(): ?float
    {
        return $this->taxRate;
    }

    public function setTaxRate(?float $taxRate): self
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    public function getIsAdjourned(): ?bool
    {
        return $this->isAdjourned;
    }

    public function setIsAdjourned(?bool $isAdjourned): self
    {
        $this->isAdjourned = $isAdjourned;

        return $this;
    }

    public function getOrderEntity(): ?OrderEntity
    {
        return $this->orderEntity;
    }

    public function setOrderEntity(?OrderEntity $orderEntity): self
    {
        $this->orderEntity = $orderEntity;

        return $this;
    }

    public function getIsPrepared(): ?bool
    {
        return $this->isPrepared;
    }

    public function setIsPrepared(?bool $isPrepared): self
    {
        $this->isPrepared = $isPrepared;

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
}
