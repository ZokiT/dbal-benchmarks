<?php

namespace App\laravel\Models;
use Illuminate;

class Address extends Illuminate\Database\Eloquent\Model
{
    protected $primaryKey = 'address_id';
    protected $table = 'addresses';
    protected $fillable = ['user_id', 'address','city', 'state', 'postal_code'];

    public function user(): Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders(): Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    // Define additional properties and relationships
}