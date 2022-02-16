<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TouringRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=TouringRepository::class)
 * @ApiResource(
 *     mercure={"private": false},
 *     denormalizationContext={
 *          "disable_type_enforcement"=true,
 *          "groups"={"touring_write"}
 *     },
 *     normalizationContext={"groups"={"tourings_read"}},
 *     collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_USER')"},
 *          "POST"={"security"="is_granted('ROLE_TEAM')"},
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_USER')"},
 *          "PUT"={"security"="is_granted('ROLE_TEAM')"},
 *          "PATCH"={"security"="is_granted('ROLE_ADMIN')"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 * )
 * @ApiFilter(SearchFilter::class, properties={"deliverer"="exact", "orderEntities"="exact"})
 * @ApiFilter(DateFilter::class, properties={"start"=DateFilter::EXCLUDE_NULL, "end"=DateFilter::EXCLUDE_NULL})
 * @ApiFilter(BooleanFilter::class, properties={"isOpen"})
 * @ApiFilter(ExistsFilter::class, properties={"position"})
 * @ApiFilter(OrderFilter::class, properties={"start", "end"})
 */
class Touring
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"tourings_read", "touring_write", "admin:orders_read"})
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=OrderEntity::class, mappedBy="touring")
     * @Groups({"tourings_read", "touring_write"})
     */
    private $orderEntities;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"tourings_read", "touring_write", "orders_read"})
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"tourings_read", "touring_write", "admin:orders_read"})
     */
    private $end;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"tourings_read", "touring_write", "orders_read"})
     */
    private $isOpen;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"tourings_read", "touring_write", "orders_read"})
     */
    private $position = [];

    /**
     * @ORM\ManyToOne(targetEntity=Deliverer::class)
     * @Groups({"tourings_read", "touring_write", "admin:orders_read"})
     */
    private $deliverer;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"tourings_read", "touring_write", "admin:orders_read"})
     */
    private $regulated;

    public function __construct()
    {
        $this->orderEntities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|OrderEntity[]
     */
    public function getOrderEntities(): Collection
    {
        return $this->orderEntities;
    }

    public function addOrderEntity(OrderEntity $orderEntity): self
    {
        if (!$this->orderEntities->contains($orderEntity)) {
            $this->orderEntities[] = $orderEntity;
            $orderEntity->setTouring($this);
        }

        return $this;
    }

    public function removeOrderEntity(OrderEntity $orderEntity): self
    {
        if ($this->orderEntities->removeElement($orderEntity)) {
            // set the owning side to null (unless already changed)
            if ($orderEntity->getTouring() === $this) {
                $orderEntity->setTouring(null);
            }
        }

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getIsOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function setIsOpen(?bool $isOpen): self
    {
        $this->isOpen = $isOpen;

        return $this;
    }

    public function getPosition(): ?array
    {
        return $this->position;
    }

    public function setPosition(?array $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getDeliverer(): ?Deliverer
    {
        return $this->deliverer;
    }

    public function setDeliverer(?Deliverer $deliverer): self
    {
        $this->deliverer = $deliverer;

        return $this;
    }

    public function getRegulated(): ?bool
    {
        return $this->regulated;
    }

    public function setRegulated(?bool $regulated): self
    {
        $this->regulated = $regulated;

        return $this;
    }
}
