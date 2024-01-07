<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Benchmark\Benchmark;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Capsule\Manager as Capsule;
use Doctrine\DBAL\DriverManager;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\TableGateway;

function eloquentConnection() {
    $capsule = new Capsule;

    $capsule->addConnection([
        'driver' => 'pgsql',
        'host' => '192.168.99.106',
        'database' => 'dbal_benchmarks',
        'username' => 'user',
        'password' => 'password',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ]);

// Make this Capsule instance available globally via static methods... (optional)
    $capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
    $capsule->bootEloquent();
}

function eloquentInsert() {
    $data = [
        'username' => uniqid() . 'UserName',
        'email' => uniqid() .  '@example.com',
        'birth_date' => '1995-12-30',
        'registration_date' => '2023-10-08',
        'is_active' => true,
        'created_at' => '2023-10-10',
        'updated_at' => '2023-12-10',
    ];

    \App\laravel\Models\User::insert($data);
}

function doctrineConnection(): \Doctrine\DBAL\Query\QueryBuilder
{
    $conn = DriverManager::getConnection([
        'dbname' => 'dbal_benchmarks',
        'user' => 'user',
        'password' => 'password',
        'host' => '192.168.99.106',
        'driver' => 'pgsql',
    ]);

    // Return the query builder
    return $conn->createQueryBuilder();
}

$doctrineInsert = function (QueryBuilder $queryBuilder) {
    $data = [
        'username' => "'" . uniqid() . "UserName'",
        'email' => "'" . uniqid() . '@example.com' . "'",
        'birth_date' => "'1995-12-30'",
        'registration_date' => "'2023-10-08'",
        'is_active' => 'true',
        'created_at' => "'2023-10-10'",
        'updated_at' => "'2023-12-30'",
    ];

    $queryBuilder->insert('users')
        ->values($data)
        ->executeQuery(); // Execute the query
};

function eloquentQueryBuilderConnection(): Builder
{
    $capsule = new Capsule;

    $capsule->addConnection([
        'driver' => 'pgsql',
        'host' => '192.168.99.106',
        'database' => 'dbal_benchmarks',
        'username' => 'user',
        'password' => 'password',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ]);

    // Make this Capsule instance available globally via static methods
    $capsule->setAsGlobal();

    // Do not boot Eloquent in this case

    // Create a new query builder instance
    return new Builder($capsule->getConnection());
}

$eloquentQueryBuilderInsert = function (Builder $queryBuilder) {
    $data = [
        'username' => "'" . uniqid() . "UserName'",
        'email' => "'" . uniqid() . '@example.com' . "'",
        'birth_date' => "'1995-12-30'",
        'registration_date' => "'2023-10-08'",
        'is_active' => 'true',
        'created_at' => "'2023-10-10'",
        'updated_at' => "'2023-12-30'",
    ];

    $queryBuilder->from('users')->insert($data);
};

function pdoConnection(): PDO
{
    $dsn = 'pgsql:host=192.168.99.106;dbname=dbal_benchmarks';
    $username = 'user';
    $password = 'password';

    // Create a PDO instance
    return new PDO($dsn, $username, $password);
}

$pdoInsert = function (PDO $pdo) {

    $data = [
        'username' => uniqid() . 'UserName',
        'email' => uniqid() . '@example.com',
        'birth_date' => '1995-12-30',
        'registration_date' => '2023-10-08',
        'is_active' => true,
        'created_at' => '2023-10-10',
        'updated_at' => '2023-12-30',
    ];

    // Prepare the SQL statement with placeholders
    $sql = 'INSERT INTO users (username, email, birth_date, registration_date, is_active, created_at, updated_at) 
            VALUES (:username, :email, :birth_date, :registration_date, :is_active, :created_at, :updated_at)';
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
    $stmt->bindParam(':birth_date', $data['birth_date'], PDO::PARAM_STR);
    $stmt->bindParam(':registration_date', $data['registration_date'], PDO::PARAM_STR);
    $stmt->bindParam(':is_active', $data['is_active'], PDO::PARAM_BOOL);
    $stmt->bindParam(':created_at', $data['created_at'], PDO::PARAM_STR);
    $stmt->bindParam(':updated_at', $data['updated_at'], PDO::PARAM_STR);

    // Execute the statement
    $stmt->execute();
};

function laminasConnection(): TableGateway
{
    $adapter = new Adapter(
        [
            'hostname' => '192.168.99.106',
            'username' => 'user',
            'password' => 'password',
            'database' => 'dbal_benchmarks',
            'driver'   => 'pgsql',
        ]
    );

    return new TableGateway('users', $adapter);
}

$laminasInsert = function (TableGateway $tableGateway) {

    $data = [
        'username' => uniqid() . 'UserName',
        'email' => uniqid() . '@example.com',
        'birth_date' => '1995-12-30',
        'registration_date' => '2023-10-08',
        'is_active' => true,
        'created_at' => '2023-10-10',
        'updated_at' => '2023-12-30',
    ];

    $tableGateway->insert($data);
};

function codeIgniterConnection() : \CodeIgniter\Database\BaseConnection
{
    require_once __DIR__ . '/../codeigniter/app/Config/Paths.php';
    $paths = new App\codeIgniter\app\Config\Paths();
    require_once __DIR__ . '/../vendor/codeigniter4/framework/system/bootstrap.php';

    return CodeIgniter\Database\Config::connect([
        'DSN'      => '',
        'hostname' => '192.168.99.106',
        'username' => 'user',
        'password' => 'password',
        'database' => 'dbal_benchmarks',
        'DBDriver' => 'Postgre',
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
        'port'     => 5432,
    ]);
}

$codeIgniterInser = function (\CodeIgniter\Database\BaseConnection $db) {

    $data = [
        'username' => uniqid() . 'UserName',
        'email' => uniqid() . '@example.com',
        'birth_date' => '1995-12-30',
        'registration_date' => '2023-10-08',
        'is_active' => true,
        'created_at' => '2023-10-10',
        'updated_at' => '2023-12-30',
    ];

    $db->table('users')->insert($data);
};

$benchmark = new Benchmark();
//$benchmark->addMethod('eloquent insert','eloquentInsert', 'eloquentConnection');
$benchmark->addMethod('doctrine query builder insert', $doctrineInsert, 'doctrineConnection');
$benchmark->addMethod('eloquent query builder insert', $eloquentQueryBuilderInsert, 'eloquentQueryBuilderConnection');
$benchmark->addMethod('laminas insert', $laminasInsert, 'laminasConnection');
$benchmark->addMethod('codeIgniter insert', $codeIgniterInser, 'codeIgniterConnection');
$benchmark->addMethod('pdo insert', $pdoInsert  , 'pdoConnection');

$benchmark->run();