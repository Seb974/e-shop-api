<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DayOffRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;

/**
 * @ORM\Entity(repositoryClass=DayOffRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"dayOff_read"}},
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
 * @ApiFilter(DateFilter::class, properties={"date"})
 */
class DayOff
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"dayOff_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"dayOff_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"dayOff_read"})
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class)
     * @Groups({"dayOff_read"})
     */
    private $openedFor;

    public function __construct()
    {
        $this->openedFor = new ArrayCollection();
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
     * @return Collection|Group[]
     */
    public function getOpenedFor(): Collection
    {
        return $this->openedFor;
    }

    public function addOpenedFor(Group $openedFor): self
    {
        if (!$this->openedFor->contains($openedFor)) {
            $this->openedFor[] = $openedFor;
        }

        return $this;
    }

    public function removeOpenedFor(Group $openedFor): self
    {
        $this->openedFor->removeElement($openedFor);

        return $this;
    }
}
