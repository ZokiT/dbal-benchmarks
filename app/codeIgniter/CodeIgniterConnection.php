<?php

namespace App\codeIgniter;

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
}