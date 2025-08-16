<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }

    $email = $_GET['email'] ?? null;
    
    if (!$email) {
        http_response_code(400);
        echo json_encode(['error' => 'Email parameter is required']);
        exit;
    }

    $db = new Database('127.0.0.1', 'my_test_db', 'root', '14122002', 3306);
    $studentId = $db->getStudentIdByUserEmail($email);

    echo json_encode(['student_id' => $studentId]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}
?>
