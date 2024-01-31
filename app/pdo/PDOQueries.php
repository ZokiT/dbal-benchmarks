<?php

namespace App\pdo;

use App\Benchmark\Params;
use App\User;
use PDO;

class PDOQueries
{
    use User;
    public static function insert(Params $params): Params {

        $pdo = $params->getParam('pdo');
        // Prepare the SQL statement with placeholders
        $sql = 'INSERT INTO users (username, email, birth_date, registration_date, is_active, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);

        // Execute the statement
        $stmt->execute(self::fakeArray());

        return $params;
    }

    public static function select(Params $params): Params {

        $pdo = $params->getParam('pdo');
        $limit = $params->getParam('selectLimit');
        $sql = "SELECT * FROM users WHERE is_active = ? LIMIT {$limit}";
        $stmt = $pdo->prepare($sql);

        // Execute the statement
        $stmt->execute([true]);
        $stmt->fetch(PDO::FETCH_ASSOC);

        return $params;
    }

    public static function update(Params $params): Params {
        /** @var PDO $pdo */
        $pdo = $params->getParam('pdo');
        $userId = $params->getParam('userId');

        // Prepare the SQL statement with placeholders
        $sql = 'UPDATE users SET email = ? WHERE user_id = ?';
        $stmt = $pdo->prepare($sql);

        // Execute the statement
        $stmt->execute([uniqid() . '@pdo_update_example.com', $userId]);

        return $params;
    }

    public static function delete(Params $params): Params {
        /** @var PDO $pdo */
        $pdo = $params->getParam('pdo');

        // Prepare the SQL statement with placeholders
        $sql = 'DELETE FROM users WHERE user_id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$params->getParam('minUserId')]);
        $params->addParam('minUserId', $params->getParam('minUserId') + 1);

        return $params;
    }
}