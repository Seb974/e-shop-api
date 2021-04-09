<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Cette adresse e-mail est déjà associée à un compte."
 * )
 * @ApiResource(
 *     denormalizationContext={
 *          "disable_type_enforcement"=true,
 *          "groups"={"user_write"}
 *     },
 *     normalizationContext={
 *          "groups"={"users_read"}
 *     },
 *     collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN')"},
 *          "POST"
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or object == user"},
 *          "PUT"={"security"="is_granted('ROLE_ADMIN') or object == user"},
 *          "PATCH"={"security"="is_granted('ROLE_ADMIN') or object == user"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN') or object == user"}
 *     },
 *     mercure={"private"=true, "normalization_context"={"group"="users_read"}},
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users_read", "user_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"users_read", "user_write"})
     * @Assert\Email(message="L'adresse email saisie n'est pas valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"users_read", "user_write"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min = 6, minMessage = "Le mot de passe doit contenir au moins {{ limit }} caractères.")
     * @Groups({"user_write"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"users_read", "user_write"})
     * @Assert\Length(min = 3, minMessage = "Le nom doit contenir au moins {{ limit }} caractères.",
     *                max = 100, maxMessage= "Le nom ne peut dépasser {{ limit }} caractères.")
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Meta::class, inversedBy="user", cascade={"persist", "remove"})
     * @Groups({"users_read", "user_write"})
     */
    private $metas;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getMetas(): ?Meta
    {
        return $this->metas;
    }

    public function setMetas(?Meta $metas): self
    {
        $this->metas = $metas;

        return $this;
    }
}
