<?php

namespace App\symfony\Models;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "orders")]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(name: "order_id", type: "bigint", nullable: false)]
    protected ?int $orderId = null;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "user_id", nullable: false, onDelete: "SET NULL")]
    protected User $user;

    #[ORM\Column(name: "order_date", type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    protected DateTimeInterface $orderDate;

    #[ORM\Column(name: "status", type: "string", length: 255)]
    protected string $status;

    #[ORM\Column(name: "total_amount", type: "float", options: ["default" => 0])]
    protected float $totalAmount;

    #[ORM\Column(name: "shipping_information", type: "string", length: 255, options: ["default" => ""])]
    protected string $shippingInformation;

    #[ORM\Column(name: "created_at", type: "datetime")]
    protected DateTimeInterface $createdAt;

    #[ORM\Column(name: "updated_at", type: "datetime")]
    protected DateTimeInterface $updatedAt;

    #[ORM\OneToMany(mappedBy: "order", targetEntity: OrderDetails::class)]
    protected Collection $orderDetails;

    #[ORM\OneToMany(mappedBy: "order", targetEntity: Product::class)]
    protected Collection $products;

    public function __construct(
        User $user,
        DateTimeInterface $orderDate,
        string $status,
        float $totalAmount = 0,
        string $shippingInformation = ''
    ) {
        $this->user = $user;
        $this->orderDate = $orderDate;
        $this->status = $status;
        $this->totalAmount = $totalAmount;
        $this->shippingInformation = $shippingInformation;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->orderId;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->orderId = $id;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getOrderDate(): DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(DateTimeInterface $orderDate): void
    {
        $this->orderDate = $orderDate;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return float|int
     */
    public function getTotalAmount(): float|int
    {
        return $this->totalAmount;
    }

    /**
     * @param float|int $totalAmount
     */
    public function setTotalAmount(float|int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * @return string
     */
    public function getShippingInformation(): string
    {
        return $this->shippingInformation;
    }

    /**
     * @param string $shippingInformation
     */
    public function setShippingInformation(string $shippingInformation): void
    {
        $this->shippingInformation = $shippingInformation;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     */
    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(?int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getOrderDetails(): OrderDetails
    {
        return $this->orderDetails;
    }

    public function setOrderDetails(OrderDetails $orderDetails): void
    {
        $this->orderDetails = $orderDetails;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


}
