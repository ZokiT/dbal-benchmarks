<?php

namespace App\laravel\Models;
use Illuminate;

class Payment extends Illuminate\Database\Eloquent\Model
{
    protected $primaryKey = 'payment_id';

    public function order(): Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Define additional properties and relationships
}