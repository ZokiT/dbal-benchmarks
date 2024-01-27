<?php

namespace App\codeIgniter;

use App\codeIgniter\Models\User;
use App\DatabaseConfig;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Config;
use Config\Paths;

class CodeIgniterConnection
{
    public static function connect(DatabaseConfig $config): ?BaseConnection
    {
        defined('CI_DEBUG') || define('CI_DEBUG', false);
        defined('ENVIRONMENT') || define('ENVIRONMENT', 'production');
        defined('MYSQLI_STORE_RESULT') || define('MYSQLI_STORE_RESULT', 0);

        $paths = new Paths();
        require_once __DIR__ . '/../vendor/codeigniter4/framework/system/bootstrap.php';

        try {
            return Config::connect($config->getCodeIgniterDatabaseConfig());
        } catch (\Throwable $throwable) {
            // if unable to connect catch the exception and count everything as miss
        }

        return null;
    }

    public static function connectForUpdate(DatabaseConfig $config): array {
        $baseConnection = self::connect($config);

        $builder = $baseConnection->table('users')
            ->select('user_id')->limit(1);
        $userId = $builder->get()->getFirstRow()->user_id;

        return [$baseConnection, (int)$userId];
    }

    public static function connectForORMUpdate(DatabaseConfig $config): array {
        $baseConnection = self::connect($config);

        $user = new User($baseConnection);
        $res = $user->first();

        return [$baseConnection, $res];
    }
}