<?php

namespace App\codeIgniter;

use App\Benchmark\Params;
use App\User;
use CodeIgniter\Database\BaseConnection;

class CodeIgniterQueryBuilder
{
    use User;
    public static function insert(Params $params): Params {
        $db = $params->getParam('codeigniterBaseConnection');
        $db->table('users')->insert(self::fake());

        return $params;
    }

    public static function select(Params $params): Params {
        $db = $params->getParam('codeigniterBaseConnection');

        $builder = $db->table('users')
            ->select()
            ->where('is_active', 'true')
            ->limit(100000);

        $builder->get()->getResultArray();

        return $params;
    }

    public static function update(Params $params): Params  {
        /** @var BaseConnection $db */
        $db = $params->getParam('codeigniterBaseConnection');
        $userId = $params->getParam('userId');;

        $db->table('users')
            ->update(
                ['email' => uniqid() . '@codeigniter_update_example.com'],
                ['user_id' => $userId]
            );

        return $params;
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
