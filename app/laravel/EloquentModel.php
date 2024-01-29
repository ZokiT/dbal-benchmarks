<?php

namespace App\laravel;

use App\Benchmark\Params;
use App\laravel\Models\User;

class EloquentModel
{
    public static function insert(): void {
        User::insert(User::fake());
    }

    public static function select(Params $params): void {
        $limit = $params->getParam('selectLimit');
        User::where('is_active', true)->limit($limit)->get();
    }

    public static function update(User $user): void {
        $user->email = uniqid() . '@orm_laravel@example.com';
        $user->save();
    }
}