<?php

namespace App\codeIgniter;

use CodeIgniter\Database\BaseConnection;
use App\codeIgniter\Models\User;

class CodeIgniterModelInsert
{
    public static function insert(BaseConnection $db): void {
        $user = new User($db);
        $user->insert(User::fake());
    }
}
