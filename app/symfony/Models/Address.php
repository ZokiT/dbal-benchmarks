<?php

namespace App\symfony\Models;

/**
 * @ORM\Entity
 * @ORM\Table(name="addresses")
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="address_id", type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="addresses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $state;

    /**
     * @ORM\Column(name="postal_code", type="string", length=20)
     */
    protected $postalCode;

    // Constructor, getters, setters, and other methods...
}