<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SupervisorRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SupervisorRepository::class)
 * @ApiResource(
 *      denormalizationContext={
 *          "groups"={"supervisor_write"},
 *          "disable_type_enforcement"=true
 *      },
 *      normalizationContext={
 *          "groups"={"supervisors_read"},
 *          "enable_max_depth"=true
 *      },
 *      collectionOperations={
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
class Supervisor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"supervisors_read", "supervisor_write", "users_read"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="supervisorAuthority", cascade={"persist"})
     * @Groups({"supervisors_read", "supervisor_write"})
     */
    private $supervisor;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @Groups({"supervisors_read", "supervisor_write", "users_read"})
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"supervisors_read", "supervisor_write", "users_read"})
     */
    private $role;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupervisor(): ?User
    {
        return $this->supervisor;
    }

    public function setSupervisor(?User $supervisor): self
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
