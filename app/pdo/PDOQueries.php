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

    public static function complexQuerySelect(Params $params): Params {

        $pdo = $params->getParam('pdo');
        $limit = $params->getParam('selectLimit');
        $sql = "SELECT users.username as user_name,
                    orders.order_id as order_id,
                    orders.order_date as order_date,
                    orders.status as order_status,
                    SUM(order_details.quantity) AS total_ordered_quantity,
                    AVG(products.price) as avg_product_price,
                    MAX(products.price) as max_product_price,
                    COUNT(DISTINCT products.product_id) as unique_products_ordered
                from users inner join orders on users.user_id = orders.user_id
                    inner join order_details on orders.order_id = order_details.order_id
                    inner join products on order_details.product_id = products.product_id
                    left join addresses on users.user_id = addresses.user_id
                where users.is_active = ? and orders.status in ('completed', 'pending')
                group by users.username, orders.order_id, orders.order_date, orders.status
                having SUM(order_details.quantity) > 5
                order by orders.order_date desc LIMIT {$limit}";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([true]);
        $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $params;
    }
}