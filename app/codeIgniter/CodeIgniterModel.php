<?php

namespace App\codeIgniter;

use CodeIgniter\Database\BaseConnection;
use App\codeIgniter\Models\User;

class CodeIgniterModel
{
    public static function insert(BaseConnection $db): void {
        $user = new User($db);
        $user->insert(User::fake());
    }

    public static function select(BaseConnection $db): void {
        $user = new User($db);
        $user->where('is_active', true)->first();
    }
}
