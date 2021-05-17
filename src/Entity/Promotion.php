<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromotionRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"promotions_read"}},
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
 * @ApiFilter(SearchFilter::class, properties={"code"="exact"})
 */
class Promotion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"promotions_read", "relaypoints_read", "orders_read", "relaypoint_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(min = 4, minMessage = "Le code doit contenir au moins {{ limit }} caractères.",
     *                max = 15, maxMessage = "Le code ne peut contenir plus de {{ limit }} caractères.")
     * @Groups({"promotions_read", "relaypoints_read", "orders_read", "relaypoint_write"})
     */
    private $code;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"promotions_read", "relaypoints_read", "orders_read", "relaypoint_write"})
     */
    private $percentage;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"promotions_read", "relaypoints_read", "orders_read", "relaypoint_write"})
     */
    private $discount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"promotions_read", "relaypoints_read", "orders_read", "relaypoint_write"})
     */
    private $maxUsage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"promotions_read", "relaypoints_read", "orders_read", "relaypoint_write"})
     */
    private $used;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"promotions_read", "relaypoints_read", "orders_read", "relaypoint_write"})
     */
    private $endsAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPercentage(): ?bool
    {
        return $this->percentage;
    }

    public function setPercentage(?bool $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getMaxUsage(): ?int
    {
        return $this->maxUsage;
    }

    public function setMaxUsage(?int $maxUsage): self
    {
        $this->maxUsage = $maxUsage;

        return $this;
    }

    public function getUsed(): ?int
    {
        return $this->used;
    }

    public function setUsed(?int $used): self
    {
        $this->used = $used;

        return $this;
    }

    public function getEndsAt(): ?\DateTimeInterface
    {
        return $this->endsAt;
    }

    public function setEndsAt(?\DateTimeInterface $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }
}
