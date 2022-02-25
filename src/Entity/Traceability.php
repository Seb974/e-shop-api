<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Repository\TraceabilityRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Filter\Traceability\TraceabilityFilterBySeller;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=TraceabilityRepository::class)
 * @ApiResource(
 *      mercure={"private": false},
 *      denormalizationContext={
 *          "groups"={"traceability_write"},
 *          "disable_type_enforcement"=true
 *      },
 *      normalizationContext={"groups"={"traceabilities_read"}},
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
 * @ApiFilter(TraceabilityFilterBySeller::class, properties={"seller"="exact"})
 * @ApiFilter(SearchFilter::class, properties={"number"="word_start"})
 * @ApiFilter(OrderFilter::class, properties={"number", "endDate"})
 * @ApiFilter(DateFilter::class, properties={"endDate"})
 */
class Traceability
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"traceabilities_read", "traceability_write", "items_read", "orders_read", "order_write", "tourings_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"traceabilities_read", "traceability_write", "items_read", "orders_read", "order_write", "tourings_read"})
     */
    private $number;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"traceabilities_read", "traceability_write", "items_read", "orders_read", "order_write", "tourings_read"})
     */
    private $endDate;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"traceabilities_read", "traceability_write", "items_read", "orders_read", "order_write", "tourings_read"})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="traceabilities")
     * @Groups({"traceabilities_read", "traceability_write"})
     */
    private $item;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"traceabilities_read", "traceability_write", "items_read", "orders_read", "order_write", "tourings_read"})
     */
    private $initialQty;

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

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

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
}
