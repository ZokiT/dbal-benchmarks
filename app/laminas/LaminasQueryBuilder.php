<?php

namespace App\laminas;

use App\User;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGateway;

class LaminasQueryBuilder
{
    use User;
    public static function insert(TableGateway $tableGateway): void {
        $tableGateway->insert(self::fakeWithQuotes());
    }

    public static function select(TableGateway $tableGateway): void {
        $rowSet = $tableGateway->select(function (Select $select) {
            $select->where->equalTo('is_active', 'true');
            $select->limit(1);
        });

        // the result is Traversable, Countable object, so we need to actually get them
        $rowSet->current();
    }

    public static function update(array $params): void {

        /** @var TableGateway $tableGateway */
        $tableGateway = $params[0];
        $userId = $params[1];

        // Perform the update operation
        $tableGateway->update(
            ['email' =>  uniqid() . "@laminas_update_example.com"],
            ['user_id' => $userId]
        );
    }
}