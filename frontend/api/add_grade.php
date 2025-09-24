<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../database/services/grades.php';
require_once __DIR__ . '/../database/services/export_csv.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }

    // Get form data
    $studentId = (int)($_POST['studentId'] ?? 0);
    $courseId = (int)($_POST['courseId'] ?? 0);
    $grade = (int)($_POST['grade'] ?? 0);

    // Validate input
    if ($studentId === 0 || $courseId === 0 || $grade === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required']);
        exit;
    }

    if ($grade < 5 || $grade > 10) {
        http_response_code(400);
        echo json_encode(['error' => 'Grade must be between 5 and 10']);
        exit;
    }

    $success = grades_add($studentId, $courseId, $grade);

    if ($success) {
        export_all_csvs();
        echo json_encode(['success' => true, 'message' => 'Grade added successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Grade for this student and course already exists']);
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}
?>
