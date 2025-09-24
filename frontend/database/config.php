<?php
declare(strict_types=1);

// Minimal configuration: expose only a reusable PDO connection factory.

function get_pdo(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    // Use environment variables for production, fallback to local for development
    $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $db   = $_ENV['DB_NAME'] ?? 'my_test_db';
    $user = $_ENV['DB_USER'] ?? 'root';
    $pass = $_ENV['DB_PASS'] ?? '14122002';
    $port = $_ENV['DB_PORT'] ?? 3306;

    $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

    $pdo = new PDO($dsn, $user, $pass, $options);
    return $pdo;
}