<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

// mercure={"private"=true, "normalization_context"={"group"="users_read"}},
/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 * @UniqueEntity(
 *     fields={"label"},
 *     message="Un groupe possédant ce nom existe déjà."
 * )
 * @ApiResource(
 *     attributes={"enable_max_depth"=true},
 *     normalizationContext={
 *          "groups"={"groups_read"}
 *     },
 *     collectionOperations={
 *          "GET",
 *          "POST"={"security"="is_granted('ROLE_ADMIN')"},
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_ADMIN')"},
 *          "PATCH"={"security"="is_granted('ROLE_ADMIN')"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 * )
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"groups_read", "priceGroups_read", "products_read", "categories_read", "dayOff_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(max = 30, maxMessage = "Le nom ne peut dépasser {{ limit }} caractères.")
     * @Groups({"groups_read", "priceGroups_read", "products_read", "categories_read", "dayOff_read"})
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     * @Groups({"groups_read", "priceGroups_read", "products_read", "categories_read", "dayOff_read"})
     */
    private $value;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"groups_read", "priceGroups_read", "products_read", "categories_read", "dayOff_read"})
     */
    private $isFixed;

    /**
     * @ORM\ManyToOne(targetEntity=PriceGroup::class, inversedBy="userGroup")
     * @Groups({"groups_read"})
     */
    private $priceGroup;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"groups_read", "priceGroups_read"})
     */
    private $subjectToTaxes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"groups_read", "priceGroups_read"})
     */
    private $dayInterval;

    /**
     * @ORM\Column(type="time", nullable=true)
     * @Groups({"groups_read", "priceGroups_read"})
     */
    private $hourLimit;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"groups_read", "priceGroups_read"})
     */
    private $onlinePayment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getIsFixed(): ?bool
    {
        return $this->isFixed;
    }

    public function setIsFixed(?bool $isFixed): self
    {
        $this->isFixed = $isFixed;

        return $this;
    }

    public function getPriceGroup(): ?PriceGroup
    {
        return $this->priceGroup;
    }

    public function setPriceGroup(?PriceGroup $priceGroup): self
    {
        $this->priceGroup = $priceGroup;

        return $this;
    }

    public function getSubjectToTaxes(): ?bool
    {
        return $this->subjectToTaxes;
    }

    public function setSubjectToTaxes(?bool $subjectToTaxes): self
    {
        $this->subjectToTaxes = $subjectToTaxes;

        return $this;
    }

    public function getDayInterval(): ?int
    {
        return $this->dayInterval;
    }

    public function setDayInterval(?int $dayInterval): self
    {
        $this->dayInterval = $dayInterval;

        return $this;
    }

    public function getHourLimit(): ?\DateTimeInterface
    {
        return $this->hourLimit;
    }

    public function setHourLimit(?\DateTimeInterface $hourLimit): self
    {
        $this->hourLimit = $hourLimit;

        return $this;
    }

    public function getOnlinePayment(): ?bool
    {
        return $this->onlinePayment;
    }

    public function setOnlinePayment(?bool $onlinePayment): self
    {
        $this->onlinePayment = $onlinePayment;

        return $this;
    }
}
