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

    $courseId = (int)($_POST['courseId'] ?? 0);
    if ($courseId === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'courseId is required']);
        exit;
    }

    if (courses_delete($courseId)) {
        export_all_csvs();
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to delete course']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}
?>





