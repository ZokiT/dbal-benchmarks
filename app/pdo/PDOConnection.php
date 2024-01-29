<?php

namespace App\pdo;

use App\Benchmark\Benchmark;
use App\DatabaseConfig;
use PDO;

class PDOConnection
{
    public static function connect(Benchmark $benchmark): ?PDO
    {
        // Create a PDO instance
        try {
            return new PDO(...DatabaseConfig::getPDODatabaseConfig());
        } catch (\Throwable $throwable) {
        }

        return null;
    }

    public static function connectForUpdate(Benchmark $benchmark): array
    {
        // Create a PDO instance
        $pdo = self::connect($benchmark);

        // Prepare the SQL statement with placeholders
        $sql = 'SELECT user_id FROM users LIMIT 1';
        $stmt = $pdo->prepare($sql);

        // Execute the statement
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return [$pdo, $user['user_id']];
    }

}