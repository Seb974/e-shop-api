<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateMediaObjectAction;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ApiResource(
 *     iri="http://schema.org/Picture",
 *     normalizationContext={ "groups"={"picture_read"} },
 *     collectionOperations={
 *         "post"={
 *             "controller"=App\Controller\CreatePictureAction::class,
 *             "deserialize"=false,
 *             "security"="is_granted('ROLE_TEAM')",
 *             "validation_groups"={"Default", "picture_create"},
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "file"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         },
 *         "get"
 *     },
 *     itemOperations={
 *          "get",
 *          "put"={
 *             "controller"=App\Controller\CreatePictureAction::class,
 *             "deserialize"=false,
 *             "access_control"="is_granted('ROLE_TEAM')",
 *             "validation_groups"={"Default", "picture_create"},
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "file"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         },
 *          "patch"={
 *             "controller"=App\Controller\CreatePictureAction::class,
 *             "deserialize"=false,
 *             "access_control"="is_granted('ROLE_TEAM')",
 *             "validation_groups"={"Default", "picture_create"},
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "file"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         },
 *          "delete"={"security"="is_granted('ROLE_TEAM')"},
 *    }
 * )
 * @Vich\Uploadable
 */
class Picture
{
    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Groups({"picture_read", "products_read", "articles_read", "heroes_read", "homepages_read", "banners_read", "countdowns_read"})
     * @ORM\Id
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"picture_read", "products_read", "articles_read", "heroes_read", "homepages_read", "banners_read", "countdowns_read"})
     */
    public $contentUrl;

    /**
     * @var File|null
     *
     * @Assert\NotNull(groups={"picture_create"})
     * @Vich\UploadableField(mapping="picture", fileNameProperty="filePath")
     */
    public $file;

    /**
     * @var string|null
     *
     * @ORM\Column(nullable=true)
     * @Groups({"picture_read", "products_read", "articles_read", "heroes_read", "homepages_read", "banners_read", "countdowns_read"})
     */
    public $filePath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"picture_read", "products_read", "articles_read", "heroes_read", "homepages_read", "banners_read", "countdowns_read"})
     */
    private $imgPath;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"picture_read", "products_read", "articles_read", "heroes_read", "homepages_read", "banners_read", "countdowns_read"})
     */
    private $linkInstance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(?string $imgPath): self
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    public function getLinkInstance(): ?string
    {
        return $this->linkInstance;
    }

    public function setLinkInstance(?string $linkInstance): self
    {
        $this->linkInstance = $linkInstance;

        return $this;
    }
}