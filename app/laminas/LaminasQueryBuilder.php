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

        // the result is Traversable, Countable object so we need to actualy get them
        $rowSet->current();
    }
}