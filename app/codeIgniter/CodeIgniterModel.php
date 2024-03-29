<?php

namespace App\codeIgniter;

use App\Benchmark\Params;
use CodeIgniter\Database\BaseConnection;
use App\codeIgniter\Models\User;
use Exception;
use Laminas\Db\Sql\Select;
use ReflectionException;

class CodeIgniterModel
{
    public static function insert(Params $params): Params {
        $db = $params->getParam('codeigniterBaseConnection');
        $user = new User($db);
        $user->insert(User::fake());

        return $params;
    }

    public static function select(Params $params): Params {
        $limit = $params->getParam('selectLimit');
        $db = $params->getParam('codeigniterBaseConnection');
        $user = new User($db);
        $user->where('is_active', true)->findAll($limit);

        return $params;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public static function update(Params $params): Params {
        /** @var BaseConnection $db */
        $db = $params->getParam('codeigniterBaseConnection');
        $userModel = new User($db);

        $userArray = $params->getParam('user');
        $userArray['email'] = uniqid() . '@codeigniter_orm_example2.com';

        $updated = $userModel->update($userArray['user_id'], $userArray);

        if (!$updated) {
            throw new Exception('entity was not updated');
        }

        return $params;
    }

    /**
     * @throws Exception
     */
    public static function delete(Params $params): Params {
        /** @var BaseConnection $baseConnection */
        $baseConnection = $params->getParam('codeigniterBaseConnection');
        $userModel = new User($baseConnection);
        $userModel->delete($params->getParam('minUserId'));
        $params->addParam('minUserId', $params->getParam('minUserId') + 1);

        return $params;
    }

    public static function complexQuerySelect(Params $params): Params {
        $limit = $params->getParam('selectLimit');
        $db = $params->getParam('codeigniterBaseConnection');
        $user = new User($db);
        $user->select('users.*')
             ->join('orders', 'users.user_id = orders.user_id')
             ->join('order_details', 'orders.order_id = order_details.order_id')
             ->where('is_active', true)
             ->whereIn('orders.status',['completed', 'pending'])
            ->groupBy([
                'users.user_id',
                'orders.order_id',
                'orders.order_date',
                'orders.status'
            ])
            ->having('SUM(order_details.quantity) >', 5)
            ->orderBy('users.user_id', 'ASC')
            ->limit($limit);


        // Get results as User objects
        $users = $user->get()->getCustomResultObject(User::class);

        return $params;
    }

}
