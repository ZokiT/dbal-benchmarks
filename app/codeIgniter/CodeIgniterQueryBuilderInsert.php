<?php

namespace App\codeIgniter;

use App\User;
use CodeIgniter\Database\BaseConnection;

class CodeIgniterQueryBuilderInsert
{
    use User;
    public static function insert(BaseConnection $db): void {
        $db->table('users')->insert(self::fake());
    }
}
