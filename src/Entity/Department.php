<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 * @ApiResource(
 *      denormalizationContext={"groups"={"department_write"}},
 *      normalizationContext={"groups"={"departments_read"}},
 *      collectionOperations={
 *          "GET",
 *          "POST"={"security"="is_granted('ROLE_ADMIN')"},
 *     },
 *     itemOperations={
 *          "GET",
 *          "PUT"={"security"="is_granted('ROLE_ADMIN')"},
 *          "PATCH"={"security"="is_granted('ROLE_ADMIN')"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *      mercure={"private": false})
 * )
 * @ApiFilter(SearchFilter::class, properties={"name"="word_start"})
 * @ApiFilter(OrderFilter::class, properties={"name"})
 */
class Department
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"departments_read", "department_write", "products_read", "product_write", "parentDepartments_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"departments_read", "department_write", "products_read", "product_write", "parentDepartments_read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="department")
     * @Groups({"departments_read", "department_write", "parentDepartments_read"})
     */
    private $products;

    /**
     * @ORM\ManyToOne(targetEntity=ParentDepartment::class, inversedBy="departments")
     * @Groups({"departments_read", "department_write"})
     */
    private $parentDepartment;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setDepartment($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getDepartment() === $this) {
                $product->setDepartment(null);
            }
        }

        return $this;
    }

    public function getParentDepartment(): ?ParentDepartment
    {
        return $this->parentDepartment;
    }

    public function setParentDepartment(?ParentDepartment $parentDepartment): self
    {
        $this->parentDepartment = $parentDepartment;

        return $this;
    }
}
