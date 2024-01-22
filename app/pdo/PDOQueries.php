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
        $sql = 'SELECT * FROM users WHERE is_active = ? LIMIT 1';
        $stmt = $pdo->prepare($sql);

        // Execute the statement
        $stmt->execute([true]);
        $stmt->fetch(PDO::FETCH_ASSOC);
    }
}