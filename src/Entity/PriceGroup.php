<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PriceGroupRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PriceGroupRepository::class)
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Un groupe possédant ce nom existe déjà."
 * )
 * @ApiResource(
 *     normalizationContext={
 *          "groups"={"priceGroups_read"}
 *     },
 *     collectionOperations={
 *          "GET",
 *          "POST"={"security"="is_granted('ROLE_SUPERVISOR')"},
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_SUPERVISOR')"},
 *          "PATCH"={"security"="is_granted('ROLE_SUPERVISOR')"},
 *          "DELETE"={"security"="is_granted('ROLE_SUPERVISOR')"}
 *     },
 * )
 */
class PriceGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"priceGroups_read", "groups_read", "products_read", "price_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\Length(max = 60, maxMessage = "Le nom ne peut dépasser {{ limit }} caractères.")
     * @Groups({"priceGroups_read", "groups_read", "products_read", "price_read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Group::class, mappedBy="priceGroup")
     * @Groups({"priceGroups_read", "products_read", "price_read"})
     */
    private $userGroup;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"priceGroups_read", "price_read"})
     */
    private $rate;

    public function __construct()
    {
        $this->userGroup = new ArrayCollection();
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

    /**
     * @return Collection|Group[]
     */
    public function getUserGroup(): Collection
    {
        return $this->userGroup;
    }

    public function addUserGroup(Group $userGroup): self
    {
        if (!$this->userGroup->contains($userGroup)) {
            $this->userGroup[] = $userGroup;
            $userGroup->setPriceGroup($this);
        }

        return $this;
    }

    public function removeUserGroup(Group $userGroup): self
    {
        if ($this->userGroup->removeElement($userGroup)) {
            // set the owning side to null (unless already changed)
            if ($userGroup->getPriceGroup() === $this) {
                $userGroup->setPriceGroup(null);
            }
        }

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
