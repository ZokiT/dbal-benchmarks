<?php

namespace App\codeIgniter;

use App\Benchmark\Benchmark;
use App\Benchmark\Params;
use App\codeIgniter\Models\User;
use App\DatabaseConfig;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Config;
use Config\Paths;

class CodeIgniterConnection
{
    use \App\User;

    public static function connect(Params $params): Params
    {
        defined('CI_DEBUG') || define('CI_DEBUG', false);
        defined('ENVIRONMENT') || define('ENVIRONMENT', 'production');
        defined('MYSQLI_STORE_RESULT') || define('MYSQLI_STORE_RESULT', 0);

        $paths = new Paths();
        require_once __DIR__ . '/../vendor/codeigniter4/framework/system/bootstrap.php';

        try {
            $params->addParam('codeigniterBaseConnection', Config::connect(DatabaseConfig::getCodeIgniterDatabaseConfig()));
        } catch (\Throwable $throwable) {
            // if unable to connect catch the exception and count everything as miss
        }

        return $params;
    }

    public static function connectForUpdate(Params $params): Params
    {
        $params = self::connect($params);
        $baseConnection = $params->getParam('codeigniterBaseConnection');

        $builder = $baseConnection->table('users')
            ->select('user_id')->limit(1);
        $userId = $builder->get()->getFirstRow()->user_id;

        $params->addParam('userId', (int)$userId);

        return $params;
    }

    public static function connectForORMUpdate(Params $params): Params
    {
        $params = self::connect($params);
        $baseConnection = $params->getParam('codeigniterBaseConnection');

        $user = new User($baseConnection);
        $res = $user->first();
        $params->addParam('user', $res);

        return $params;
    }

    public static function prepareForDelete(Params $params): Params
    {
        $params = self::connect($params);
        $baseConnection = $params->getParam('codeigniterBaseConnection');
        $iterations = $params->getParam('iterations');

        $builder = $baseConnection->table('users_user_id_seq')->select('last_value');
        $minUserId = $builder->get()->getFirstRow()->last_value;

        for ($i = 0; $i < $iterations; $i++) {
            $baseConnection->table('users')->insert(self::fake());
        }
        $params->addParam('minUserId', $minUserId + 1);

        return $params;
    }

    public static function prepareForORMDelete(Params $params): Params
    {
        $params = self::connect($params);
        $iterations = $params->getParam('iterations');
        $baseConnection = $params->getParam('codeigniterBaseConnection');
        $user = new User($baseConnection);
        $userId = $user->insert(User::fake());
        $params->addParam('minUserId', $userId);

        for ($i = 1; $i < $iterations; $i++) {
            $user->insert(User::fake());
        }

        return $params;
    }
}