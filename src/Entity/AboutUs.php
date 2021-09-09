<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AboutUsRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AboutUsRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"about_read"}},
 *      denormalizationContext={"groups"={"about_write"}},
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
class AboutUs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"about_read", "about_write"})
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $summary;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $vision;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $mission;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $goal;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"about_read", "about_write"})
     */
    private $servicePicture;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"about_read", "about_write"})
     */
    private $productPicture;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"about_read", "about_write"})
     */
    private $supportPicture;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getVision(): ?string
    {
        return $this->vision;
    }

    public function setVision(?string $vision): self
    {
        $this->vision = $vision;

        return $this;
    }

    public function getMission(): ?string
    {
        return $this->mission;
    }

    public function setMission(?string $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(?string $goal): self
    {
        $this->goal = $goal;

        return $this;
    }

    public function getServicePicture(): ?Picture
    {
        return $this->servicePicture;
    }

    public function setServicePicture(?Picture $servicePicture): self
    {
        $this->servicePicture = $servicePicture;

        return $this;
    }

    public function getProductPicture(): ?Picture
    {
        return $this->productPicture;
    }

    public function setProductPicture(?Picture $productPicture): self
    {
        $this->productPicture = $productPicture;

        return $this;
    }

    public function getSupportPicture(): ?Picture
    {
        return $this->supportPicture;
    }

    public function setSupportPicture(?Picture $supportPicture): self
    {
        $this->supportPicture = $supportPicture;

        return $this;
    }
}
