<?php

namespace App\symfony\Models;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="category_id", type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    protected $products;

    // Constructor, getters, setters, and other methods...
}