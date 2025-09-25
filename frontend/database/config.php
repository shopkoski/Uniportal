<?php
declare(strict_types=1);

// Minimal configuration: expose only a reusable PDO connection factory.

function get_pdo(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    // Use Railway MySQL environment variables for production, fallback to local for development
    $host = $_ENV['MYSQLHOST'] ?? $_ENV['MYSQL_HOST'] ?? $_ENV['DB_HOST'] ?? '127.0.0.1';
    $db   = $_ENV['MYSQLDATABASE'] ?? $_ENV['MYSQL_DATABASE'] ?? $_ENV['DB_NAME'] ?? 'my_test_db';
    $user = $_ENV['MYSQLUSER'] ?? $_ENV['MYSQL_USER'] ?? $_ENV['DB_USER'] ?? 'root';
    $pass = $_ENV['MYSQLPASSWORD'] ?? $_ENV['MYSQL_PASSWORD'] ?? $_ENV['DB_PASS'] ?? '14122002';
    $port = $_ENV['MYSQLPORT'] ?? $_ENV['MYSQL_PORT'] ?? $_ENV['DB_PORT'] ?? 3306;

    $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

    $pdo = new PDO($dsn, $user, $pass, $options);
    return $pdo;
}