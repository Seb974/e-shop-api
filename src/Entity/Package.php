<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PackageRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Filter\Package\PackageFilterNeedingReturns;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ORM\Entity(repositoryClass=PackageRepository::class)
 * @ApiResource(
 *      denormalizationContext={
 *          "groups"={"order_write", "package_write"},
 *          "disable_type_enforcement"=true
 *      },
 *      normalizationContext={"groups"={"packages_read"}},
 *      collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_TEAM') or object.orderEntity.getUser() == user"},
 *          "POST",
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_TEAM') or object.orderEntity.getUser() == user"},
 *          "PUT"={"security"="is_granted('ROLE_TEAM')"},
 *          "PATCH"={"security"="is_granted('ROLE_TEAM')"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @ApiFilter(OrderFilter::class, properties={"id"})
 * @ApiFilter(PackageFilterNeedingReturns::class, properties={"needsReturn"="exact"})
 */
class Package
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  @Groups({"packages_read", "orders_read", "order_write", "tourings_read", "touring_write", "package_write"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Container::class)
     * @Groups({"packages_read", "orders_read", "order_write", "tourings_read", "touring_write", "package_write"})
     */
    private $container;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"packages_read", "orders_read", "order_write", "tourings_read", "touring_write", "package_write"})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=OrderEntity::class, inversedBy="packages")
     * @Groups({"packages_read"})
     */
    private $orderEntity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"packages_read", "package_write"})
     */
    private $returned;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContainer(): ?Container
    {
        return $this->container;
    }

    public function setContainer(?Container $container): self
    {
        $this->container = $container;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrderEntity(): ?OrderEntity
    {
        return $this->orderEntity;
    }

    public function setOrderEntity(?OrderEntity $orderEntity): self
    {
        $this->orderEntity = $orderEntity;

        return $this;
    }

    public function getReturned(): ?int
    {
        return $this->returned;
    }

    public function setReturned(?int $returned): self
    {
        $this->returned = $returned;

        return $this;
    }
    /**
     * Gives the total of containers to return
     *
     * @Groups({"packages_read"})
     * @return integer|null
     */
    public function getQuantityToReturn(): ?int
    {
        if ($this->getContainer()->getIsReturnable()) {
            $returned = !is_null($this->getReturned()) ? $this->getReturned() : 0;
            return $this->getQuantity() - $returned;
        }
        return 0;
    }
}
