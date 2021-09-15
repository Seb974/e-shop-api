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

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $visionTitle;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $missionTitle;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $goalTitle;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $serviceTitle;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $productTitle;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $supportTitle;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $visionColor;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $missionColor;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $goalColor;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $serviceColor;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $productColor;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $supportColor;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @Groups({"about_read", "about_write"})
     */
    private $headerPicture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $headerTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $headerSubtitle;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $headerTitleColor;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"about_read", "about_write"})
     */
    private $headerSubtitleColor;


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

    public function getVisionTitle(): ?string
    {
        return $this->visionTitle;
    }

    public function setVisionTitle(?string $visionTitle): self
    {
        $this->visionTitle = $visionTitle;

        return $this;
    }

    public function getMissionTitle(): ?string
    {
        return $this->missionTitle;
    }

    public function setMissionTitle(?string $missionTitle): self
    {
        $this->missionTitle = $missionTitle;

        return $this;
    }

    public function getGoalTitle(): ?string
    {
        return $this->goalTitle;
    }

    public function setGoalTitle(?string $goalTitle): self
    {
        $this->goalTitle = $goalTitle;

        return $this;
    }

    public function getServiceTitle(): ?string
    {
        return $this->serviceTitle;
    }

    public function setServiceTitle(?string $serviceTitle): self
    {
        $this->serviceTitle = $serviceTitle;

        return $this;
    }

    public function getProductTitle(): ?string
    {
        return $this->productTitle;
    }

    public function setProductTitle(?string $productTitle): self
    {
        $this->productTitle = $productTitle;

        return $this;
    }

    public function getSupportTitle(): ?string
    {
        return $this->supportTitle;
    }

    public function setSupportTitle(?string $supportTitle): self
    {
        $this->supportTitle = $supportTitle;

        return $this;
    }

    public function getVisionColor(): ?string
    {
        return $this->visionColor;
    }

    public function setVisionColor(?string $visionColor): self
    {
        $this->visionColor = $visionColor;

        return $this;
    }

    public function getMissionColor(): ?string
    {
        return $this->missionColor;
    }

    public function setMissionColor(?string $missionColor): self
    {
        $this->missionColor = $missionColor;

        return $this;
    }

    public function getGoalColor(): ?string
    {
        return $this->goalColor;
    }

    public function setGoalColor(?string $goalColor): self
    {
        $this->goalColor = $goalColor;

        return $this;
    }

    public function getServiceColor(): ?string
    {
        return $this->serviceColor;
    }

    public function setServiceColor(?string $serviceColor): self
    {
        $this->serviceColor = $serviceColor;

        return $this;
    }

    public function getProductColor(): ?string
    {
        return $this->productColor;
    }

    public function setProductColor(?string $productColor): self
    {
        $this->productColor = $productColor;

        return $this;
    }

    public function getSupportColor(): ?string
    {
        return $this->supportColor;
    }

    public function setSupportColor(?string $supportColor): self
    {
        $this->supportColor = $supportColor;

        return $this;
    }

    public function getHeaderPicture(): ?Picture
    {
        return $this->headerPicture;
    }

    public function setHeaderPicture(?Picture $headerPicture): self
    {
        $this->headerPicture = $headerPicture;

        return $this;
    }

    public function getHeaderTitle(): ?string
    {
        return $this->headerTitle;
    }

    public function setHeaderTitle(?string $headerTitle): self
    {
        $this->headerTitle = $headerTitle;

        return $this;
    }

    public function getHeaderSubtitle(): ?string
    {
        return $this->headerSubtitle;
    }

    public function setHeaderSubtitle(?string $headerSubtitle): self
    {
        $this->headerSubtitle = $headerSubtitle;

        return $this;
    }

    public function getHeaderTitleColor(): ?string
    {
        return $this->headerTitleColor;
    }

    public function setHeaderTitleColor(?string $headerTitleColor): self
    {
        $this->headerTitleColor = $headerTitleColor;

        return $this;
    }

    public function getHeaderSubtitleColor(): ?string
    {
        return $this->headerSubtitleColor;
    }

    public function setHeaderSubtitleColor(?string $headerSubtitleColor): self
    {
        $this->headerSubtitleColor = $headerSubtitleColor;

        return $this;
    }
}
