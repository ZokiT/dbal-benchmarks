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

    public static function update(array $params): void {
        /** @var BaseConnection $db */
        $db = $params[0];
        $userId = $params[1];

        $db->table('users')
            ->update(
                ['email' => uniqid() . '@codeigniter_update_example.com'],
                ['user_id' => $userId]
            );
    }
}
