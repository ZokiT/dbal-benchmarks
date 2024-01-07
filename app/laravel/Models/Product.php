<?php

namespace App\laravel\Models;
use Illuminate;

class Product extends Illuminate\Database\Eloquent\Model
{
    protected $primaryKey = 'product_id';

    public function category(): Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orderDetails(): Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderDetails::class, 'product_id');
    }

    // Define additional properties and relationships
}