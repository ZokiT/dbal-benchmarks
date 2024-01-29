<?php

namespace App\pdo;

use App\User;
use PDO;

class PDOQueries
{
    use User;
    public static function insert(PDO $pdo): void {

        // Prepare the SQL statement with placeholders
        $sql = 'INSERT INTO users (username, email, birth_date, registration_date, is_active, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);

        // Execute the statement
        $stmt->execute(self::fakeArray());
    }

    public static function select(PDO $pdo): void {

        // Prepare the SQL statement with placeholders
        $sql = 'SELECT * FROM users WHERE is_active = ? LIMIT 100000';
        $stmt = $pdo->prepare($sql);

        // Execute the statement
        $stmt->execute([true]);
        $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update(array $params): void {
        /** @var PDO $pdo */
        $pdo = $params[0];
        $userId = $params[1];

        // Prepare the SQL statement with placeholders
        $sql = 'UPDATE users SET email = ? WHERE user_id = ?';
        $stmt = $pdo->prepare($sql);

        // Execute the statement
        $stmt->execute([uniqid() . '@pdo_update_example.com', $userId]);
    }
}