<?php

namespace App\codeIgniter;

use App\Benchmark\Params;
use CodeIgniter\Database\BaseConnection;
use App\codeIgniter\Models\User;
use Exception;
use ReflectionException;

class CodeIgniterModel
{
    public static function insert(Params $params): void {
        $db = $params->getParam('codeigniterBaseConnection');
        $user = new User($db);
        $user->insert(User::fake());
    }

    public static function select(Params $params): void {
        $limit = $params->getParam('selectLimit');
        $db = $params->getParam('codeigniterBaseConnection');
        $user = new User($db);
        $user->where('is_active', true)->findAll($limit);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public static function update(Params $params): void {
        /** @var BaseConnection $db */
        $db = $params->getParam('codeigniterBaseConnection');
        $userModel = new User($db);

        $userArray = $params->getParam('user');
        $userArray['email'] = uniqid() . '@codeigniter_orm_example2.com';

        $updated = $userModel->update($userArray['user_id'], $userArray);

        if (!$updated) {
            throw new Exception('entity was not updated');
        }
    }
}
