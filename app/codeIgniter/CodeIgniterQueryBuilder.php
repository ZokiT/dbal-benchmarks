<?php

namespace App\codeIgniter;

use App\User;
use CodeIgniter\Database\BaseConnection;

class CodeIgniterQueryBuilder
{
    use User;
    public static function insert(BaseConnection $db): void {
        $db->table('users')->insert(self::fake());
    }

    public static function select(BaseConnection $db): void {
        $builder = $db->table('users')
            ->select()
            ->where('is_active', 'true')
            ->limit(1);

        $builder->get()->getResultArray();
    }
}
