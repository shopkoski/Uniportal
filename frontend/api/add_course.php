<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../database/services/courses.php';
require_once __DIR__ . '/../database/services/export_csv.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }

    // Get form data
    $courseName = $_POST['courseName'] ?? '';
    $credits = (int)($_POST['credits'] ?? 0);
    $professorId = (int)($_POST['professorId'] ?? 0);

    // Validate input
    if (empty($courseName) || $credits === 0 || $professorId === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required']);
        exit;
    }

    if ($credits < 1 || $credits > 10) {
        http_response_code(400);
        echo json_encode(['error' => 'Credits must be between 1 and 10']);
        exit;
    }

    $success = courses_add($courseName, $credits, $professorId);

    if ($success) {
        // Refresh CSV snapshots
        export_all_csvs();
        echo json_encode(['success' => true, 'message' => 'Course added successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Course with this name already exists']);
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}
?>
