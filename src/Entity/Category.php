<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ApiResource(
 *      mercure={"private": false}),
 *      denormalizationContext={"disable_type_enforcement"=true},
 *      normalizationContext={
 *          "groups"={"categories_read"}
 *      },
 *      collectionOperations={
 *          "GET"
 *          "POST"={"security"="is_granted('ROLE_TEAM')"},
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_TEAM') or object == user"},
 *          "PATCH"={"security"="is_granted('ROLE_TEAM') or object == user"},
 *          "DELETE"={"security"="is_granted('ROLE_TEAM') or object == user"}
 *     },
 * )
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"categories_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"categories_read", "products_read"})
     * @Assert\NotBlank(message="Un nom est obligatoire.")
     */
    private $name;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"categories_read"})
     */
    private $userGroups = [];

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

    public function getUserGroups(): ?array
    {
        return $this->userGroups;
    }

    public function setUserGroups(?array $userGroups): self
    {
        $this->userGroups = $userGroups;

        return $this;
    }
}
