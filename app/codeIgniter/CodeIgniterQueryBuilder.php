<?php

namespace App\codeIgniter;

use App\Benchmark\Params;
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
            ->limit(100000);

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

    public static function delete(Params $params): Params {
        /** @var BaseConnection $baseConnection */
        $baseConnection = $params->getParam('codeigniterBaseConnection');
        $minUserId = $params->getParam('minUserId');

        $baseConnection->table('users')->where('user_id =', $minUserId)->delete();
        $params->addParam('minUserId', $minUserId + 1);

        return $params;
    }
}
