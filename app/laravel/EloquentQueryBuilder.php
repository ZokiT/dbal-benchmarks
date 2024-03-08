<?php

namespace App\laravel;

use App\Benchmark\Params;
use App\laravel\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class EloquentQueryBuilder
{
    public static function insert(Params $params): Params {
        $builder = $params->getParam('eloquentBuilder');
        $builder->from('users')->insert(User::fakeWithQuotes());

        return $params;
    }

    public static function select(Params $params): Params {
        $builder = $params->getParam('eloquentBuilder');
        $limit = $params->getParam('selectLimit');
        $builder->select()
            ->from('users')
            ->where('is_active', '=', 'true')
            ->limit($limit)
            ->get()
            ->all();

        return $params;
    }

    public static function update(Params $params): Params {
        /** @var Builder $builder */
        $builder = $params->getParam('eloquentBuilder');
        $userId = $params->getParam('userId');

        // Perform the update query
        $builder->from('users')
            ->where('user_id', $userId)
            ->update(['email' => "'" . uniqid() . '@eloquent_update_example.com' . "'"]);

        return $params;
    }

    public static function delete(Params $params): Params {
        /** @var Builder $builder */
        $builder = $params->getParam('eloquentBuilder');
        $builder->from('users')
            ->where('user_id', '=', $params->getParam('minUserId'))
            ->delete();
        $params->addParam('minUserId', $params->getParam('minUserId') + 1);

        return $params;
    }

    public static function complexQuerySelect(Params $params): Params {
        $builder = clone $params->getParam('eloquentBuilder');

        $limit = $params->getParam('selectLimit');
        $res = $builder->select([
            'users.username AS user_name',
            'orders.order_id AS order_id',
            'orders.order_date AS order_date',
            'orders.status AS order_status',
        ])
            ->selectRaw('SUM(order_details.quantity) AS total_ordered_quantity')
            ->selectRaw('AVG(products.price) as avg_product_price')
            ->selectRaw('MAX(products.price) as max_product_price')
            ->selectRaw('COUNT(DISTINCT products.product_id) as unique_products_ordered')
            ->from('users')
            ->join('orders', 'users.user_id', '=', 'orders.user_id')
            ->join('order_details', 'orders.order_id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.product_id')
            ->leftJoin('addresses', 'users.user_id', '=', 'addresses.user_id')
            ->where('users.is_active', true)
            ->whereIn('orders.status', ['completed', 'pending'])
            ->groupBy('users.username', 'orders.order_id', 'orders.order_date', 'orders.status')
            ->havingRaw('SUM(order_details.quantity) > ?', [5])
            ->orderBy('orders.order_date', 'DESC')
            ->limit($limit)->get();

        unset($builder);

        return $params;
    }
}