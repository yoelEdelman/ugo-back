<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getOrders"])]
    private ?string $purchase_identifier = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getOrders"])]
    private ?Customer $customer_id = null;

    #[ORM\Column]
    #[Groups(["getOrders"])]
    private ?int $product_id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getOrders"])]
    private ?string $quantity = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getOrders"])]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getOrders"])]
    private ?string $currency = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getOrders"])]
    private ?string $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPurchaseIdentifier(): ?string
    {
        return $this->purchase_identifier;
    }

    public function setPurchaseIdentifier(string $purchase_identifier): static
    {
        $this->purchase_identifier = $purchase_identifier;

        return $this;
    }

    public function getCustomerId(): ?Customer
    {
        return $this->customer_id;
    }

    public function setCustomerId(?Customer $customer_id): static
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): static
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }
}
