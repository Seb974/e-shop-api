<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BatchRepository;
use App\Filter\Batch\BatchFilterByStore;
use App\Filter\Batch\BatchFilterBySeller;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=BatchRepository::class)
 * @ApiResource(
 *      mercure={"private": false},
 *      denormalizationContext={
 *          "groups"={"batch_write"},
 *          "disable_type_enforcement"=true
 *      },
 *      normalizationContext={"groups"={"batches_read"}},
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
 * @ApiFilter(BatchFilterByStore::class, properties={"store"="exact"})
 * @ApiFilter(BatchFilterBySeller::class, properties={"seller"="exact"})
 * @ApiFilter(SearchFilter::class, properties={"number"="word_start"})
 * @ApiFilter(OrderFilter::class, properties={"number", "endDate"})
 * @ApiFilter(DateFilter::class, properties={"endDate"})
 */
class Batch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"batches_read", "batch_write", "stocks_read", "seller:products_read", "provisions_read", "provision_write", "goods_read", "admin:orders_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"batches_read", "batch_write", "stocks_read", "seller:products_read", "provisions_read", "provision_write", "goods_read", "admin:orders_read", "stock_write"})
     */
    private $number;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"batches_read", "batch_write", "stocks_read", "seller:products_read", "provisions_read", "provision_write", "goods_read", "admin:orders_read", "stock_write"})
     */
    private $endDate;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"batches_read", "batch_write", "stocks_read", "seller:products_read", "provisions_read", "provision_write", "goods_read", "admin:orders_read", "stock_write"})
     */
    private $initialQty;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"batches_read", "batch_write", "stocks_read", "seller:products_read", "provisions_read", "provision_write", "goods_read", "admin:orders_read", "stock_write"})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Stock::class, inversedBy="batches")
     * @Groups({"batches_read", "batch_write", "provisions_read", "provision_write", "goods_read"})
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity=Good::class, inversedBy="batches")
     * @Groups({"batches_read", "batch_write"})
     */
    private $good;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getInitialQty(): ?float
    {
        return $this->initialQty;
    }

    public function setInitialQty(?float $initialQty): self
    {
        $this->initialQty = $initialQty;

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

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getGood(): ?Good
    {
        return $this->good;
    }

    public function setGood(?Good $good): self
    {
        $this->good = $good;

        return $this;
    }
}
