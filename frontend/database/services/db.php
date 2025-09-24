<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';

function db(): PDO {
    return get_pdo();
}

function schema(): array {
    static $schema = null;
    if ($schema !== null) return $schema;
    $schema = require __DIR__ . '/schema.php';
    return $schema;
}


