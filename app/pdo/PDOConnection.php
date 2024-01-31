<?php

namespace App\pdo;

use App\Benchmark\Benchmark;
use App\Benchmark\Params;
use App\DatabaseConfig;
use App\User;
use PDO;

class PDOConnection
{
    use User;

    public static function connect(Params $params): Params
    {
        $pdo = new PDO(...DatabaseConfig::getPDODatabaseConfig());
        $params->addParam('pdo', $pdo);

        return $params;
    }

    public static function connectForUpdate(Params $params): Params
    {
        $params = self::connect($params);
        $pdo = $params->getParam('pdo');

        // Prepare the SQL statement with placeholders
        $sql = 'SELECT user_id FROM users LIMIT 1';
        $stmt = $pdo->prepare($sql);

        // Execute the statement
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $params->addParam('userId', $user['user_id']);
        return $params;
    }

    public static function prepareForDelete(Params $params): Params
    {
        $params = self::connect($params);
        $pdo = $params->getParam('pdo');
        $iterations = $params->getParam('iterations');

        // Prepare the SQL statement with placeholders
        $sql = 'SELECT last_value FROM users_user_id_seq LIMIT 1';
        $stmt = $pdo->prepare($sql);

        // Execute the statement
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        for ($i = 0; $i < $iterations; $i++) {
            $sql = 'INSERT INTO users (username, email, birth_date, registration_date, is_active, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)';
            $stmt = $pdo->prepare($sql);

            // Execute the statement
            $stmt->execute(self::fakeArray());
        }
        $params->addParam('minUserId', $result["last_value"] + 1);

        return $params;
    }

}