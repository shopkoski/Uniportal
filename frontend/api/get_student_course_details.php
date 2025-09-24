<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../database/services/students.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }

    $studentId = $_GET['student_id'] ?? null;
    
    if (!$studentId || !is_numeric($studentId)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid student ID']);
        exit;
    }

    $studentDetails = students_get_course_details((int)$studentId);

    if (!$studentDetails['student']) {
        http_response_code(404);
        echo json_encode(['error' => 'Student not found']);
        exit;
    }

    echo json_encode($studentDetails);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}
?>
