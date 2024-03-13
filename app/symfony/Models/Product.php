<?php

namespace App\symfony\Models;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "products")]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(name: "product_id", type: "bigint", nullable: false)]
    protected ?int $productId = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: "category_id", referencedColumnName: "id", nullable: false, onDelete: "SET NULL")]
    protected Category $category;

    #[ORM\Column(name: "product_name", type: "string", length: 255, nullable: false)]
    protected string $productName;

    #[ORM\Column(name: "price", type: "decimal", precision: 10, scale: 2, nullable: false)]
    protected float $price;

    #[ORM\Column(name: "description", type: "string", length: 255, nullable: true)]
    protected ?string $description;

    #[ORM\Column(name: "stock_quantity", type: "integer", nullable: false)]
    protected int $stockQuantity;

    #[ORM\OneToMany(mappedBy: "order_details", targetEntity: "OrderDetails")]
    protected OrderDetails $orderDetails;

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStockQuantity(): int
    {
        return $this->stockQuantity;
    }

    public function setStockQuantity(int $stockQuantity): self
    {
        $this->stockQuantity = $stockQuantity;

        return $this;
    }
}