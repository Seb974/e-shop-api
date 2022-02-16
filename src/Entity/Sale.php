<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SaleRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ORM\Entity(repositoryClass=SaleRepository::class)
 * @ApiResource(
 *     denormalizationContext={
 *          "disable_type_enforcement"=true,
 *          "groups"={"sale_write"}
 *     },
 *     normalizationContext={
 *          "groups"={"sales_read"},
 *          "enable_max_depth"=true
 *     },
 *     collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_TEAM')"},
 *          "POST"={"security"="is_granted('ROLE_TEAM')"}
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_TEAM')"},
 *          "PUT"={"security"="is_granted('ROLE_TEAM')"},
 *          "PATCH"={"security"="is_granted('ROLE_PICKER')"},
 *          "DELETE"={"security"="is_granted('ROLE_PICKER')"}
 *     },
 * )
 * @ApiFilter(SearchFilter::class, properties={"store"="exact"})
 * @ApiFilter(DateFilter::class, properties={"date"})
 * @ApiFilter(OrderFilter::class, properties={"date"})
 */
class Sale
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"sales_read", "sale_write"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Store::class)
     * @Groups({"sales_read", "sale_write"})
     */
    private $store;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"sales_read", "sale_write"})
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=Purchase::class, mappedBy="sale")
     * @Groups({"sales_read", "sale_write"})
     */
    private $purchases;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"sales_read", "sale_write"})
     */
    private $numberOfSales;

    public function __construct()
    {
        $this->purchases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): self
    {
        $this->store = $store;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Purchase[]
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    public function addPurchase(Purchase $purchase): self
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases[] = $purchase;
            $purchase->setSale($this);
        }

        return $this;
    }

    public function removePurchase(Purchase $purchase): self
    {
        if ($this->purchases->removeElement($purchase)) {
            // set the owning side to null (unless already changed)
            if ($purchase->getSale() === $this) {
                $purchase->setSale(null);
            }
        }

        return $this;
    }

    public function getNumberOfSales(): ?int
    {
        return $this->numberOfSales;
    }

    public function setNumberOfSales(?int $numberOfSales): self
    {
        $this->numberOfSales = $numberOfSales;

        return $this;
    }
}
