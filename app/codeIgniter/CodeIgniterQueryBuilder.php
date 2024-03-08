<?php

namespace App\codeIgniter;

use App\Benchmark\Params;
use App\User;
use CodeIgniter\Database\BaseBuilder;
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
        $limit = $params->getParam('selectLimit');
        $builder = $db->table('users')
            ->select()
            ->where('is_active', 'true')
            ->limit($limit);

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

    public static function complexQuerySelect(Params $params): Params {
        /** @var BaseConnection $baseConnection */
        $db = $params->getParam('codeigniterBaseConnection');

        $limit = $params->getParam('selectLimit');

        /** @var BaseBuilder $builder */
        $builder = $db->table('users')
                      ->select([
                              'user_name' => 'users.username',
                              'order_id' => 'orders.order_id',
                              'order_date' => 'orders.order_date',
                              'order_status' => 'orders.status',
                              'total_ordered_quantity' => 'SUM(order_details.quantity) as total_ordered_quantity',
                              'avg_product_price' => 'AVG(products.price) as avg_product_price',
                              'max_product_price' => 'MAX(products.price) as max_product_price',
                              'unique_products_ordered' => 'COUNT(DISTINCT products.product_id) as unique_products_ordered',
                      ], false)
                        ->join('orders', 'users.user_id = orders.user_id')
                        ->join('order_details', 'orders.order_id = order_details.order_id')
                        ->join('products', 'order_details.product_id = products.product_id')
                        ->join('addresses', 'users.user_id = addresses.user_id', 'left')

                        ->where(['users.is_active' => 'true'])
                        ->whereIn('orders.status',['completed', 'pending'])
                      ->groupBy([
                          'users.username',
                          'orders.order_id',
                          'orders.order_date',
                          'orders.status'
                      ])
                      ->having('SUM(order_details.quantity) >', 5)
                      ->orderBy('orders.order_date', 'DESC')
                      ->limit($limit);

        $res = $builder->get()->getResultArray();

        return $params;
    }

}
