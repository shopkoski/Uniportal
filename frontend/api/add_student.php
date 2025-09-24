<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../database/services/students.php';
require_once __DIR__ . '/../database/services/export_csv.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }

    // Get form data
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $email = $_POST['email'] ?? '';
    $enrollmentYear = (int)($_POST['enrollmentYear'] ?? 0);

    // Validate input
    if (empty($firstName) || empty($lastName) || empty($email) || $enrollmentYear === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email format']);
        exit;
    }

    if ($enrollmentYear < 2020 || $enrollmentYear > 2025) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid enrollment year']);
        exit;
    }

    $success = students_add($firstName, $lastName, $email, $enrollmentYear);

    if ($success) {
        export_all_csvs();
        echo json_encode(['success' => true, 'message' => 'Student added successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Student with this email already exists']);
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}
?>
