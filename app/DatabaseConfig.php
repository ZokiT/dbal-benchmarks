<?php

namespace App;

use JetBrains\PhpStorm\ArrayShape;

class DatabaseConfig {

    const HOST                = '192.168.99.106';
    const USER                = 'user';
    const PASSWORD            = 'password';
    const DATABASE            = 'dbal_benchmarks';
    const DRIVER              = 'pgsql';
    const PORT                = 5432;

    /** CodeIgniter requires different driver name for the database connection, refer to the documentation for changes */
    const CODE_IGNITER_DRIVER = 'Postgre';

    #[ArrayShape([
        'driver'    => "string",
        'host'      => "string",
        'database'  => "string",
        'username'  => "string",
        'password'  => "string",
        'port'      => "int",
        'charset'   => "string",
        'collation' => "string",
        'prefix'    => "string"
    ])]
    public static function getLaravelDatabaseConfig(): array
    {
        return [
            'driver'    => self::DRIVER,
            'host'      => self::HOST,
            'database'  => self::DATABASE,
            'username'  => self::USER,
            'password'  => self::PASSWORD,
            'port'      => self::PORT,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ];
    }

    public static function getCodeIgniterDatabaseConfig(): array
    {
        return [
            'DSN'      => '',
            'hostname' => self::HOST,
            'username' => self::USER,
            'password' => self::PASSWORD,
            'database' => self::DATABASE,
            'DBDriver' => self::CODE_IGNITER_DRIVER,
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug'  => false,
            'charset'  => 'utf8',
            'DBCollat' => 'utf8_general_ci',
            'swapPre'  => '',
            'encrypt'  => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port'     => (string)self::PORT,
            'logDatabaseErrors' => false,
        ];
    }

    #[ArrayShape([
        'hostname' => "string",
        'username' => "string",
        'password' => "string",
        'database' => "string",
        'driver'   => "string",
        'port'     => "int"
    ])]
    public static function getLaminasDatabaseConfig(): array
    {
        return [
            'hostname' => self::HOST,
            'username' => self::USER,
            'password' => self::PASSWORD,
            'database' => self::DATABASE,
            'driver'   => self::DRIVER,
            'port'     => self::PORT
        ];
    }

    #[ArrayShape([
        'dbname'   => "string",
        'user'     => "string",
        'password' => "string",
        'host'     => "string",
        'driver'   => "string",
        'port'     => "int"
    ])]
    public static function getSymphonyDatabaseConfig(): array
    {
        return [
            'dbname'   => self::DATABASE,
            'user'     => self::USER,
            'password' => self::PASSWORD,
            'host'     => self::HOST,
            'driver'   => self::DRIVER,
            'port'     => self::PORT
        ];
    }

    #[ArrayShape([
        'dsn'      => "string",
        'username' => "string",
        'password' => "string"
    ])]
    public static function getPDODatabaseConfig(): array
    {
        return [
            'dsn' => self::DRIVER . ':host=' . self::HOST . ';port=' . self::PORT . ';dbname=' . self::DATABASE,
            'username' => self::USER,
            'password' => self::PASSWORD
        ];
    }

}
