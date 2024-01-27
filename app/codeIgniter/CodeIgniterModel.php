<?php

namespace App\codeIgniter;

use CodeIgniter\Database\BaseConnection;
use App\codeIgniter\Models\User;
use Exception;
use ReflectionException;

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

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public static function update(array $params): void {
        /** @var BaseConnection $db */
        $db = $params[0];
        $userModel = new User($db);

        $userArray = $params[1];
        $userArray['email'] = uniqid() . '@codeigniter_orm_example2.com';

        $updated = $userModel->update($userArray['user_id'], $userArray);

        if (!$updated) {
            throw new Exception('entity was not updated');
        }
    }
}
