<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Repository\ResetPasswordRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=ResetPasswordRepository::class)
 * @ApiResource(
 *      denormalizationContext={
 *          "disable_type_enforcement"=true,
 *          "groups"={"reset_write"}
 *     },
 *      normalizationContext={"groups"={"resets_read"}},
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
 * @ApiFilter(SearchFilter::class, properties={"token"="exact"})
 * @ApiFilter(BooleanFilter::class, properties={"isUsed"})
 */
class ResetPassword
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"reset_write", "resets_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"reset_write", "resets_read"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"reset_write", "resets_read"})
     */
    private $token;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"reset_write", "resets_read"})
     */
    private $isUsed;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getIsUsed(): ?bool
    {
        return $this->isUsed;
    }

    public function setIsUsed(?bool $isUsed): self
    {
        $this->isUsed = $isUsed;

        return $this;
    }
}
