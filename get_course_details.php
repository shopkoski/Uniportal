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

    $courseId = $_GET['course_id'] ?? null;
    
    if (!$courseId || !is_numeric($courseId)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid course ID']);
        exit;
    }

    $db = new Database('127.0.0.1', 'my_test_db', 'root', '14122002', 3306);
    $courseDetails = $db->getCourseDetails((int)$courseId);

    if (!$courseDetails['course']) {
        http_response_code(404);
        echo json_encode(['error' => 'Course not found']);
        exit;
    }

    echo json_encode($courseDetails);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}
?>
