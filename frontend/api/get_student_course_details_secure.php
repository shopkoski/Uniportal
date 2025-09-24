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

    $studentId = $_GET['student_id'] ?? null;
    $userRole = $_GET['user_role'] ?? 'Admin';
    $userEmail = $_GET['user_email'] ?? null;
    
    if (!$studentId || !is_numeric($studentId)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid student ID']);
        exit;
    }

    $currentUserStudentId = null;
    if ($userEmail) {
        $pdo = db();
        $s = schema();
        $st = $s['columns']['students'];
        $stmt = $pdo->prepare("SELECT {$st['id']} AS student_id FROM {$s['tables']['students']} WHERE {$st['email']} = ?");
        $stmt->execute([$userEmail]);
        $row = $stmt->fetch();
        $currentUserStudentId = $row ? (int)$row['student_id'] : null;
    }
    $studentDetails = students_get_course_details((int)$studentId, $userRole, $currentUserStudentId);

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
