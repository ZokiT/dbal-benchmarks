<?php

namespace App\laminas;

use App\Benchmark\Params;
use App\User;
use Laminas\Db\Adapter\Driver\Pgsql\Statement;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\TableGateway\TableGateway;

class LaminasQueryBuilder
{
    use User;
    public static function insert(Params $params): Params {
        $sql = $params->getParam('laminasSql');
//        $sql->table('users')->insert(self::fake());

        $insert = $sql->insert('users');
        $insert->values(self::fake());

        // Execute the insert statement
        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();

        return $params;
    }

    public static function select(Params $params): Params {
        $limit = $params->getParam('selectLimit');
        $sql = $params->getParam('laminasSql');

        $select    = $sql->select('users');
        $select->where(['is_active' => 'true']);
        $select->limit($limit);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        // This is the returned User
        $result->current();

        return $params;
    }

    public static function update(Params $params): Params {

        /** @var Sql $sql */
        $sql = $params->getParam('laminasSql');

        $update = $sql->update('users');
        $update->set([
            'email' => uniqid() . '@laminas_orm@example.com',
        ]);
        $update->where(['user_id' => $params->getParam('userId')]);

        $updateStatement = $sql->prepareStatementForSqlObject($update);
        $updateStatement->execute();

        return $params;
    }

    public static function delete(Params $params): Params {

        /** @var Sql $sql */
        $sql = $params->getParam('laminasSql');
        /** @var User $user */
        $minUserId = $params->getParam('minUserId');

        $delete = $sql->delete('users')->where(['user_id = ?' => $minUserId]);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();

        $params->addParam('minUserId', $minUserId + 1);
        return $params;
    }

    public static function complexQuerySelect(Params $params): Params {
        $limit = $params->getParam('selectLimit');
        /** @var Sql $sql */
        $sql = $params->getParam('laminasSql');

        $select    = $sql->select();

        $select
            ->columns([
                'user_name' => 'users.username',
                'order_id' => 'orders.order_id',
                'order_date' => 'orders.order_date',
                'order_status' => 'orders.status',
                'total_ordered_quantity' => new \Laminas\Db\Sql\Expression('SUM(order_details.quantity)'),
                'avg_product_price' => new \Laminas\Db\Sql\Expression('AVG(products.price)'),
                'max_product_price' => new \Laminas\Db\Sql\Expression('MAX(products.price)'),
                'unique_products_ordered' => new \Laminas\Db\Sql\Expression('COUNT(DISTINCT products.product_id)'),
            ], false)
               ->from('users')
               ->join('orders', 'users.user_id = orders.user_id', [])
               ->join('order_details', 'orders.order_id = order_details.order_id', [])
               ->join('products', 'order_details.product_id = products.product_id', [])
               ->join('addresses', 'users.user_id = addresses.user_id', [],\Laminas\Db\Sql\Join::JOIN_LEFT)
            ->where([
                'users.is_active' => true,
                'orders.status' => ['completed', 'pending']
            ])
            ->group(['users.username', 'orders.order_id', 'orders.order_date', 'orders.status'])
            ->having(['SUM(order_details.quantity) > ?' => 5])
            ->order('orders.order_date DESC')
            ->limit($limit);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        return $params;
    }

}