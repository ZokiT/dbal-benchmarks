<?php

namespace App\laravel;

use App\Benchmark\Params;
use App\laravel\Models\User;

class EloquentModel
{
    public static function insert(Params $params): Params {
        User::insert(User::fake());

        return $params;
    }

    public static function select(Params $params): Params {
        $limit = $params->getParam('selectLimit');
        User::where('is_active', true)->limit($limit)->get();

        return $params;
    }

    public static function update(Params $params): Params {
        $user = $params->getParam('user');
        $user->email = uniqid() . '@orm_laravel@example.com';
        $user->save();

        return $params;
    }

    public static function delete(Params $params): Params {
        $user = User::find($params->getParam('minUserId'));
        $user->delete();
        $params->addParam('minUserId', $params->getParam('minUserId') + 1);

        return $params;
    }
}