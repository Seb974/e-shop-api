<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderEntityRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=OrderEntityRepository::class)
 * @ApiResource(
 *     denormalizationContext={
 *          "disable_type_enforcement"=true,
 *          "groups"={"order_write"}
 *     },
 *     normalizationContext={"groups"={"orders_read", "admin:orders_read"}},
 *     collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_TEAM') or object.getUser() == user"},
 *          "POST"
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_TEAM') or object.getUser() == user"},
 *          "PUT"={"security"="is_granted('ROLE_TEAM') or object.isOwner(request, object)"},
 *          "PATCH"={"security"="is_granted('ROLE_ADMIN')"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN') or object.isOwner(request, object)"}
 *     },
 * )
 * @ApiFilter(SearchFilter::class, properties={"status"="exact"})
 * @ApiFilter(DateFilter::class, properties={"deliveryDate"})
 */
class OrderEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"orders_read", "order_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"orders_read", "order_write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"orders_read", "order_write"})
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Meta::class, cascade={"persist", "remove"})
     * @Groups({"admin:orders_read", "order_write"})
     */
    private $metas;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="orderEntity", cascade={"persist", "remove"})
     * @Groups({"orders_read", "order_write"})
     */
    private $items;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"orders_read", "order_write"})
     */
    private $deliveryDate;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"orders_read", "order_write"})
     */
    private $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"admin:orders_read", "order_write"})
     */
    private $isRemains;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"orders_read", "order_write"})
     */
    private $totalHT;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"orders_read", "order_write"})
     */
    private $totalTTC;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @Groups({"admin:orders_read", "order_write"})
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"orders_read", "order_write"})
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=Catalog::class)
     * @Groups({"admin:orders_read", "order_write"})
     */
    private $catalog;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class)
     * @Groups({"admin:orders_read", "order_write"})
     */
    private $promotion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"orders_read", "order_write"})
     */
    private $paymentId;

    /**
     * @ORM\Column(type="guid", nullable=true)
     * @Groups({"admin:orders_read", "order_write"})
     */
    private $uuid;

    /**
     * @ORM\ManyToOne(targetEntity=Condition::class)
     * @Groups({"admin:orders_read", "order_write"})
     */
    private $appliedCondition;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function isOwner($request, $object)
    {
        $data = $request->query->get('id');
        return $object->getUuid() === $data;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setOrderEntity($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getOrderEntity() === $this) {
                $item->setOrderEntity(null);
            }
        }

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(?\DateTimeInterface $deliveryDate): self
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIsRemains(): ?bool
    {
        return $this->isRemains;
    }

    public function setIsRemains(?bool $isRemains): self
    {
        $this->isRemains = $isRemains;

        return $this;
    }

    public function getTotalHT(): ?float
    {
        return $this->totalHT;
    }

    public function setTotalHT(?float $totalHT): self
    {
        $this->totalHT = $totalHT;

        return $this;
    }

    public function getTotalTTC(): ?float
    {
        return $this->totalTTC;
    }

    public function setTotalTTC(?float $totalTTC): self
    {
        $this->totalTTC = $totalTTC;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCatalog(): ?Catalog
    {
        return $this->catalog;
    }

    public function setCatalog(?Catalog $catalog): self
    {
        $this->catalog = $catalog;

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    public function setPaymentId(?string $paymentId): self
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getAppliedCondition(): ?Condition
    {
        return $this->appliedCondition;
    }

    public function setAppliedCondition(?Condition $appliedCondition): self
    {
        $this->appliedCondition = $appliedCondition;

        return $this;
    }
}
