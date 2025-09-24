<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function professors_get_all(): array {
    $pdo = db();
    $s = schema();
    $p = $s['columns']['professors'];
    $c = $s['columns']['courses'];

    // Use the correct course counting logic based on department assignments
    $sql = "
        SELECT
            p.{$p['id']} AS professor_id,
            p.{$p['first_name']} AS first_name,
            p.{$p['last_name']} AS last_name,
            CONCAT(p.{$p['first_name']}, ' ', p.{$p['last_name']}) AS full_name,
            p.{$p['email']} AS email,
            p.{$p['department']} AS department,
            COUNT(DISTINCT c.{$c['id']}) AS courses_taught
        FROM {$s['tables']['professors']} p
        LEFT JOIN {$s['tables']['courses']} c ON 
            (p.{$p['department']} = 'Computer Science' AND c.{$c['name']} IN ('Databases','Algorithms','Operating systems')) OR
            (p.{$p['department']} = 'Software Engineering' AND c.{$c['name']} IN ('Web Development','Structural programming','Software engineering')) OR
            (p.{$p['department']} = 'Mathematics' AND c.{$c['name']} = 'Calculus') OR
            (p.{$p['department']} = 'Networks' AND c.{$c['name']} = 'Computer Networks') OR
            (p.{$p['department']} = 'QA & Testing' AND c.{$c['name']} = 'Software quality and testing')
        GROUP BY p.{$p['id']}, p.{$p['first_name']}, p.{$p['last_name']}, p.{$p['email']}, p.{$p['department']}
        ORDER BY p.{$p['id']};
    ";
    return $pdo->query($sql)->fetchAll();
}

function professors_get_course_details(int $professorId): array {
    $pdo = db();
    $s = schema();
    $p = $s['columns']['professors'];
    $c = $s['columns']['courses'];
    $e = $s['columns']['enrollments'];

    $professorSql = "
        SELECT
            p.professor_id,
            CONCAT(p.first_name, ' ', p.last_name) AS professor_name,
            p.email AS professor_email,
            p.department
        FROM {$s['tables']['professors']} p
        WHERE p.{$p['id']} = ?
    ";
    $professorStmt = $pdo->prepare($professorSql);
    $professorStmt->execute([$professorId]);
    $professor = $professorStmt->fetch();

    $deptSql = "SELECT {$p['department']} as department FROM {$s['tables']['professors']} WHERE {$p['id']} = ?";
    $deptStmt = $pdo->prepare($deptSql);
    $deptStmt->execute([$professorId]);
    $deptResult = $deptStmt->fetch();
    $department = $deptResult['department'] ?? '';

    $coursesQuery = "
        SELECT
            c.{$c['id']} AS course_id,
            c.{$c['name']} AS course_name,
            c.{$c['credits']} AS credits,
            COUNT(e.{$e['student_id']}) AS enrolled_students
        FROM {$s['tables']['courses']} c
        LEFT JOIN {$s['tables']['enrollments']} e ON c.{$c['id']} = e.{$e['course_id']}
        WHERE ";

    switch($department) {
        case 'Computer Science':
            $coursesQuery .= "c.course_name IN ('Databases', 'Algorithms', 'Operating systems')";
            break;
        case 'Software Engineering':
            $coursesQuery .= "c.course_name IN ('Web Development', 'Structural programming', 'Software engineering')";
            break;
        case 'Mathematics':
            $coursesQuery .= "c.course_name = 'Calculus'";
            break;
        case 'Networks':
            $coursesQuery .= "c.course_name = 'Computer Networks'";
            break;
        case 'QA & Testing':
            $coursesQuery .= "c.course_name = 'Software quality and testing'";
            break;
        default:
            $coursesQuery .= "1=0";
    }

    $coursesQuery .= " GROUP BY c.{$c['id']}, c.{$c['name']}, c.{$c['credits']} ORDER BY c.{$c['name']}";

    $coursesStmt = $pdo->prepare($coursesQuery);
    $coursesStmt->execute();
    $courses = $coursesStmt->fetchAll();

    return [
        'professor' => $professor,
        'courses' => $courses
    ];
}

function professors_add(string $firstName, string $lastName, string $email, string $department): bool {
    $pdo = db();
    $s = schema();
    $p = $s['columns']['professors'];

    // Check if professor with this email already exists (case-insensitive)
    $checkSql = "SELECT {$p['id']} FROM {$s['tables']['professors']} WHERE LOWER({$p['email']}) = LOWER(?)";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$email]);
    if ($checkStmt->fetch()) {
        return false;
    }

    // Get next available ID
    $maxIdSql = "SELECT MAX({$p['id']}) as max_id FROM {$s['tables']['professors']}";
    $maxIdStmt = $pdo->prepare($maxIdSql);
    $maxIdStmt->execute();
    $maxId = $maxIdStmt->fetch();
    $newId = ($maxId['max_id'] ?? 0) + 1;

    // Insert professor
    $insertSql = "INSERT INTO {$s['tables']['professors']} ({$p['id']}, {$p['first_name']}, {$p['last_name']}, {$p['email']}, {$p['department']}) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $pdo->prepare($insertSql);
    $result = $insertStmt->execute([$newId, $firstName, $lastName, $email, $department]);

    if ($result) {
        // Also add to Users table in database (optional - don't fail if this doesn't work)
        try {
            $username = strtolower(substr($firstName, 0, 1) . '.' . $lastName . '@univ.mk');
            $userStmt = $pdo->prepare("INSERT INTO Users (Email, PasswordHash, Role, FirstName, LastName, IsActive, CreatedAt) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $userStmt->execute([$username, password_hash('admin123', PASSWORD_DEFAULT), 'Professor', $firstName, $lastName, 1]);
        } catch (Exception $e) {
            // Log error but don't fail the professor addition
            error_log("Failed to add professor to Users table: " . $e->getMessage());
        }
    }

    return $result;
}

function professors_delete(int $professorId): bool {
    $pdo = db();
    $s = schema();
    $p = $s['columns']['professors'];
    $c = $s['columns']['courses'];
    // No professor_id column exists in courses table, so no need to update
    $stmt = $pdo->prepare("DELETE FROM {$s['tables']['professors']} WHERE {$p['id']} = ?");
    return $stmt->execute([$professorId]);
}


