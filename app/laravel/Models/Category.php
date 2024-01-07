<?php

namespace App\laravel\Models;
use Illuminate;

class Category extends Illuminate\Database\Eloquent\Model
{
    protected $primaryKey = 'category_id';

    public function products(): Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    // Define additional properties and relationships
}