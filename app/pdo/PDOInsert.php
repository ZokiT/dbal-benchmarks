<?php

namespace App\pdo;

use App\User;
use PDO;

class PDOInsert
{
    use User;
    public static function insert(PDO $pdo): void {

        $data = self::fake();

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
    }
}