<?php

namespace App\laravel\Models;
use Illuminate;

class User extends Illuminate\Database\Eloquent\Model
{
    protected $primaryKey = 'user_id';
    protected $table = 'users';
    protected $fillable = ['username', 'email','registration_date', 'is_active', 'birth_date'];

    public function orders(): Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function addresses(): Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    // Define additional properties and relationships
}