<?php

namespace App\symfony\Models;
use Illuminate;

class OrderDetails extends Illuminate\Database\Eloquent\Model
{
    protected $primaryKey = 'detail_id';

    public function order(): Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(): Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Define additional properties and relationships
}