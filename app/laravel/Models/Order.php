<?php

namespace App\laravel\Models;
use Illuminate;

class Order extends Illuminate\Database\Eloquent\Model
{
    protected $primaryKey = 'order_id';
    protected $table = 'orders';
//    protected $fillable = ['order_date', 'status','total_amount', 'shipping_information'];

    public function user(): Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderDetails(): Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderDetails::class, 'order_id');
    }

    public function payment(): Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    public function shippingAddress(): Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Address::class, 'user_id', 'user_id');
    }

    // Define additional properties and relationships
}