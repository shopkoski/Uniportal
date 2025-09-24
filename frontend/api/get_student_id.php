<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../database/services/students.php';
require_once __DIR__ . '/../database/services/db.php';

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

    // Map email to student_id using schema mapping
    $pdo = db();
    $s = schema();
    $st = $s['columns']['students'];
    $stmt = $pdo->prepare("SELECT {$st['id']} AS student_id FROM {$s['tables']['students']} WHERE {$st['email']} = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    $studentId = $row ? (int)$row['student_id'] : null;

    echo json_encode(['student_id' => $studentId]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}
?>
