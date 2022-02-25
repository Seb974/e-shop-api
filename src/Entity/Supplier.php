<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SupplierRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ORM\Entity(repositoryClass=SupplierRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"suppliers_read"}},
 *      collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_TEAM')"},
 *          "POST"={"security"="is_granted('ROLE_SELLER')"},
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_TEAM')"},
 *          "PUT"={"security"="is_granted('ROLE_SELLER')"},
 *          "PATCH"={"security"="is_granted('ROLE_SELLER')"},
 *          "DELETE"={"security"="is_granted('ROLE_SELLER')"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"name"="word_start", "seller"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"name"})
 */
class Supplier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"seller:products_read", "suppliers_read", "provisions_read", "provision_write", "batches_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"seller:products_read", "suppliers_read", "provisions_read", "batches_read"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Seller::class)
     * @Groups({"suppliers_read", "provisions_read"})
     */
    private $seller;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"seller:products_read", "suppliers_read", "provisions_read", "provision_write", "batches_read"})
     * @Assert\Email(message="L'adresse email saisie n'est pas valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Groups({"seller:products_read", "suppliers_read", "provisions_read", "provision_write", "batches_read"})
     * @Assert\Regex(
     *     pattern="/^(?:(?:\+|00)262|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/",
     *     match=true,
     *     message="Le numéro de téléphone saisi n'est pas valide."
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"suppliers_read", "provisions_read"})
     */
    private $accountingId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"suppliers_read", "provisions_read"})
     */
    private $accountingCompanyId;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="suppliers")
     * @Groups({"suppliers_read"})
     */
    private $products;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"suppliers_read"})
     */
    private $provisionMin;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"suppliers_read"})
     */
    private $deliveryMin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"suppliers_read"})
     */
    private $dayInterval;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"suppliers_read"})
     */
    private $maxHour;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"suppliers_read"})
     */
    private $days = [];

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

    public function getSeller(): ?Seller
    {
        return $this->seller;
    }

    public function setSeller(?Seller $seller): self
    {
        $this->seller = $seller;

        return $this;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAccountingId(): ?int
    {
        return $this->accountingId;
    }

    public function setAccountingId(?int $accountingId): self
    {
        $this->accountingId = $accountingId;

        return $this;
    }

    public function getAccountingCompanyId(): ?int
    {
        return $this->accountingCompanyId;
    }

    public function setAccountingCompanyId(?int $accountingCompanyId): self
    {
        $this->accountingCompanyId = $accountingCompanyId;

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
            $product->addSupplier($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeSupplier($this);
        }

        return $this;
    }

    public function getProvisionMin(): ?float
    {
        return $this->provisionMin;
    }

    public function setProvisionMin(?float $provisionMin): self
    {
        $this->provisionMin = $provisionMin;

        return $this;
    }

    public function getDeliveryMin(): ?float
    {
        return $this->deliveryMin;
    }

    public function setDeliveryMin(?float $deliveryMin): self
    {
        $this->deliveryMin = $deliveryMin;

        return $this;
    }

    public function getDayInterval(): ?int
    {
        return $this->dayInterval;
    }

    public function setDayInterval(?int $dayInterval): self
    {
        $this->dayInterval = $dayInterval;

        return $this;
    }

    public function getMaxHour(): ?\DateTimeInterface
    {
        return $this->maxHour;
    }

    public function setMaxHour(?\DateTimeInterface $maxHour): self
    {
        $this->maxHour = $maxHour;

        return $this;
    }

    public function getDays(): ?array
    {
        return $this->days;
    }

    public function setDays(?array $days): self
    {
        $this->days = $days;

        return $this;
    }
}
