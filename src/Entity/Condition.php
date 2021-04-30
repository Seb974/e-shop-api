<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ConditionRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ConditionRepository::class)
 * @ORM\Table(name="`condition`")
 * @ApiResource(
 *      normalizationContext={"groups"={"conditions_read"}},
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
class Condition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"conditions_read", "cities_read", "city_write"})
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class)
     * @Groups({"conditions_read", "cities_read", "city_write"})
     */
    private $userGroups;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"conditions_read", "cities_read", "city_write"})
     */
    private $days = [];

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"conditions_read", "cities_read", "city_write"})
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Tax::class)
     * @Groups({"conditions_read", "cities_read", "city_write"})
     */
    private $tax;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"conditions_read", "cities_read", "city_write"})
     */
    private $minForFree;

    public function __construct()
    {
        $this->userGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Group[]
     */
    public function getUserGroups(): Collection
    {
        return $this->userGroups;
    }

    public function addUserGroup(Group $userGroup): self
    {
        if (!$this->userGroups->contains($userGroup)) {
            $this->userGroups[] = $userGroup;
        }

        return $this;
    }

    public function removeUserGroup(Group $userGroup): self
    {
        $this->userGroups->removeElement($userGroup);

        return $this;
    }

    public function getDays(): ?array
    {
        return $this->days;
    }

    public function setDays(?array $days): self
    {
        $this->days = $days;

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

    public function getMinForFree(): ?float
    {
        return $this->minForFree;
    }

    public function setMinForFree(?float $minForFree): self
    {
        $this->minForFree = $minForFree;

        return $this;
    }
}
