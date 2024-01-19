<?php

namespace App\laminas;

use App\User;
use Laminas\Db\TableGateway\TableGateway;

class LaminasQueryBuilderInsert
{
    use User;
    public static function insert(TableGateway $tableGateway): void {
        $tableGateway->insert(self::fake());
    }
}