<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../database/services/professors.php';
require_once __DIR__ . '/../database/services/export_csv.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $input = $_POST;
    }
    
    $firstName = trim($input['firstName'] ?? '');
    $lastName = trim($input['lastName'] ?? '');
    $email = trim($input['email'] ?? '');
    $department = trim($input['department'] ?? '');
    
    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || empty($department)) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required']);
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email format']);
        exit;
    }
    
    // Validate department
    $validDepartments = ['Computer Science', 'Software Engineering', 'Mathematics', 'Networks', 'QA & Testing'];
    if (!in_array($department, $validDepartments)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid department']);
        exit;
    }

    $success = professors_add($firstName, $lastName, $email, $department);

    if ($success) {
        export_all_csvs();
        echo json_encode(['success' => true, 'message' => 'Professor added successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Professor with this email already exists']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
