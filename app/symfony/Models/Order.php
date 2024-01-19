<?php

namespace App\symfony\Models;

/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="order_id", type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="OrderDetails", mappedBy="order")
     */
    protected $orderDetails;

    /**
     * @ORM\OneToOne(targetEntity="Payment", mappedBy="order")
     */
    protected $payment;

    /**
     * @ORM\OneToOne(targetEntity="Address", mappedBy="order")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    protected $shippingAddress;

    // Constructor, getters, setters, and other methods...
}