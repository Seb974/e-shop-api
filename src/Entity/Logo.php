<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LogoRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

// mercure={"private": false},
/**
 * @ORM\Entity(repositoryClass=LogoRepository::class)
 * @ApiResource(
 *      attributes={
 *          "force_eager"=false,
 *          "pagination_client_enabled"=true,
 *          "pagination_client_items_per_page"=true,
 *      },
 *      denormalizationContext={
 *          "groups"={"logo_write"},
 *          "disable_type_enforcement"=true
 *      },
 *      normalizationContext={"groups"={"logos_read"}},
 *      collectionOperations={
 *          "GET",
 *          "POST"={"security"="is_granted('ROLE_TEAM')"},
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_TEAM')"},
 *          "PATCH"={"security"="is_granted('ROLE_TEAM')"},
 *          "DELETE"={"security"="is_granted('ROLE_TEAM')"}
 *     },
 * )
 */
class Logo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"logos_read", "platforms_read", "logo_write", "platform_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"logos_read", "platforms_read", "logo_write", "platform_write"})
     */
    private $type;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"logos_read", "platforms_read", "logo_write", "platform_write"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Platform::class, inversedBy="logos")
     * @Groups({"logos_read"})
     */
    private $platform;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?Picture
    {
        return $this->image;
    }

    public function setImage(?Picture $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPlatform(): ?Platform
    {
        return $this->platform;
    }

    public function setPlatform(?Platform $platform): self
    {
        $this->platform = $platform;

        return $this;
    }
}
