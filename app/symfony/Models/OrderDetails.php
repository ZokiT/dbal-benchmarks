<?php

namespace App\symfony\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "order_details")]
class OrderDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(name: "detail_id", type: "bigint", nullable: false)]
    protected ?int $detailId = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "product_id", nullable: false, onDelete: "SET NULL")]
    protected Product $product;

    #[ORM\ManyToOne(targetEntity: Order::class)]
    #[ORM\JoinColumn(name: "order_id", referencedColumnName: "order_id", nullable: false, onDelete: "SET NULL")]
    protected Order $order;

    #[ORM\Column(name: "quantity", type: "integer", nullable: false)]
    protected int $quantity;

    #[ORM\Column(name: "subtotal", type: "decimal", precision: 10, scale: 2, nullable: false)]
    protected float $subtotal;

    public function getDetailId(): ?int
    {
        return $this->detailId;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }
}