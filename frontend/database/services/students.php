<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function students_get_all(): array {
    $pdo = db();
    $s = schema();
    $st = $s['columns']['students'];
    $sql = "SELECT {$st['id']} AS student_id, {$st['first_name']} AS first_name, {$st['last_name']} AS last_name, {$st['email']} AS email, {$st['enrollment_year']} AS enrollment_year FROM {$s['tables']['students']} ORDER BY {$st['last_name']}, {$st['first_name']}";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function students_add(string $firstName, string $lastName, string $email, int $enrollmentYear): bool {
    $pdo = db();
    $s = schema();
    $st = $s['columns']['students'];

    $checkSql = "SELECT {$st['id']} FROM {$s['tables']['students']} WHERE {$st['email']} = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$email]);
    if ($checkStmt->fetch()) {
        return false;
    }

    $maxIdSql = "SELECT MAX({$st['id']}) as max_id FROM {$s['tables']['students']}";
    $maxIdStmt = $pdo->prepare($maxIdSql);
    $maxIdStmt->execute();
    $maxId = $maxIdStmt->fetch();
    $newId = ($maxId['max_id'] ?? 0) + 1;

    $insertSql = "INSERT INTO {$s['tables']['students']} ({$st['id']}, {$st['first_name']}, {$st['last_name']}, {$st['email']}, {$st['enrollment_year']}) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $pdo->prepare($insertSql);
    return $insertStmt->execute([$newId, $firstName, $lastName, $email, $enrollmentYear]);
}

function students_delete(int $studentId): bool {
    $pdo = db();
    $s = schema();
    $st = $s['columns']['students'];

    // Delete enrollments first to satisfy FK
    $e = $s['columns']['enrollments'];
    $delEnroll = $pdo->prepare("DELETE FROM {$s['tables']['enrollments']} WHERE {$e['student_id']} = ?");
    $delEnroll->execute([$studentId]);

    $stmt = $pdo->prepare("DELETE FROM {$s['tables']['students']} WHERE {$st['id']} = ?");
    return $stmt->execute([$studentId]);
}

function students_get_grades(int $studentId): array {
    $pdo = db();
    $s = schema();
    $st = $s['columns']['students'];
    $c = $s['columns']['courses'];
    $e = $s['columns']['enrollments'];
    $p = $s['columns']['professors'];
    $sql = "
        SELECT
            s.{$st['id']} AS student_id,
            CONCAT(s.{$st['first_name']}, ' ', s.{$st['last_name']}) AS student_name,
            c.{$c['name']} AS course_name,
            e.{$e['grade']} AS grade,
            CASE 
                WHEN e.{$e['grade']} >= 9 THEN 'A'
                WHEN e.{$e['grade']} >= 8 THEN 'B'
                WHEN e.{$e['grade']} >= 7 THEN 'C'
                WHEN e.{$e['grade']} >= 6 THEN 'D'
                ELSE 'F'
            END AS letter_grade,
            CASE 
                WHEN c.course_name = 'Databases' THEN 'Kristina Stefanovska'
                WHEN c.course_name = 'Algorithms' THEN 'Kristina Stefanovska'
                WHEN c.course_name = 'Operating systems' THEN 'Kristina Stefanovska'
                WHEN c.course_name = 'Web Development' THEN 'Darko Poposki'
                WHEN c.course_name = 'Structural programming' THEN 'Darko Poposki'
                WHEN c.course_name = 'Software engineering' THEN 'Darko Poposki'
                WHEN c.course_name = 'Calculus' THEN 'Simona Tasevska'
                WHEN c.course_name = 'Computer Networks' THEN 'Aleksandar Ilievski'
                WHEN c.course_name = 'Software quality and testing' THEN 'Jovana Ristova'
                ELSE 'Not assigned'
            END AS professor_name
        FROM {$s['tables']['enrollments']} e
        JOIN {$s['tables']['students']} s ON e.{$e['student_id']} = s.{$st['id']}
        JOIN {$s['tables']['courses']} c ON e.{$e['course_id']} = c.{$c['id']}
        LEFT JOIN {$s['tables']['professors']} p ON 1=0
        WHERE e.{$e['student_id']} = ?
        ORDER BY c.{$c['name']}
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$studentId]);
    return $stmt->fetchAll();
}

function students_get_course_details(int $studentId, string $userRole = 'Admin', ?int $userId = null): array {
    $pdo = db();
    $s = schema();
    $st = $s['columns']['students'];
    $c = $s['columns']['courses'];
    $e = $s['columns']['enrollments'];
    $p = $s['columns']['professors'];

    $studentSql = "
        SELECT
            s.{$st['id']} AS student_id,
            CONCAT(s.{$st['first_name']}, ' ', s.{$st['last_name']}) AS student_name,
            s.{$st['email']} AS student_email,
            s.{$st['enrollment_year']} AS enrollment_year
        FROM {$s['tables']['students']} s
        WHERE s.{$st['id']} = ?
    ";
    $studentStmt = $pdo->prepare($studentSql);
    $studentStmt->execute([$studentId]);
    $student = $studentStmt->fetch();

    $canSeeGrades = ($userRole === 'Admin') || ($userRole === 'User' && $userId === $studentId);

    $coursesSql = "
        SELECT
            c.{$c['id']} AS course_id,
            c.{$c['name']} AS course_name,
            c.{$c['credits']} AS credits,
            " . ($canSeeGrades ? "e.{$e['grade']} as grade," : "NULL as grade,") . "
            " . ($canSeeGrades ? "
            CASE 
                WHEN e.{$e['grade']} >= 9 THEN 'A'
                WHEN e.{$e['grade']} >= 8 THEN 'B'
                WHEN e.{$e['grade']} >= 7 THEN 'C'
                WHEN e.{$e['grade']} >= 6 THEN 'D'
                ELSE 'F'
            END AS letter_grade," : "NULL as letter_grade,") . "
            CASE 
                WHEN c.course_name = 'Databases' THEN 'Kristina Stefanovska'
                WHEN c.course_name = 'Algorithms' THEN 'Kristina Stefanovska'
                WHEN c.course_name = 'Operating systems' THEN 'Kristina Stefanovska'
                WHEN c.course_name = 'Web Development' THEN 'Darko Poposki'
                WHEN c.course_name = 'Structural programming' THEN 'Darko Poposki'
                WHEN c.course_name = 'Software engineering' THEN 'Darko Poposki'
                WHEN c.course_name = 'Calculus' THEN 'Simona Tasevska'
                WHEN c.course_name = 'Computer Networks' THEN 'Aleksandar Ilievski'
                WHEN c.course_name = 'Software quality and testing' THEN 'Jovana Ristova'
                ELSE 'Not assigned'
            END AS professor_name,
            CASE 
                WHEN c.course_name = 'Databases' THEN 'k.stefanovska@univ.mk'
                WHEN c.course_name = 'Algorithms' THEN 'k.stefanovska@univ.mk'
                WHEN c.course_name = 'Operating systems' THEN 'k.stefanovska@univ.mk'
                WHEN c.course_name = 'Web Development' THEN 'd.poposki@univ.mk'
                WHEN c.course_name = 'Structural programming' THEN 'd.poposki@univ.mk'
                WHEN c.course_name = 'Software engineering' THEN 'd.poposki@univ.mk'
                WHEN c.course_name = 'Calculus' THEN 's.tasevska@univ.mk'
                WHEN c.course_name = 'Computer Networks' THEN 'a.ilievski@univ.mk'
                WHEN c.course_name = 'Software quality and testing' THEN 'j.ristova@univ.mk'
                ELSE 'Not assigned'
            END AS professor_email,
            CASE 
                WHEN c.course_name = 'Databases' THEN 'Computer Science'
                WHEN c.course_name = 'Algorithms' THEN 'Computer Science'
                WHEN c.course_name = 'Operating systems' THEN 'Computer Science'
                WHEN c.course_name = 'Web Development' THEN 'Software Engineering'
                WHEN c.course_name = 'Structural programming' THEN 'Software Engineering'
                WHEN c.course_name = 'Software engineering' THEN 'Software Engineering'
                WHEN c.course_name = 'Calculus' THEN 'Mathematics'
                WHEN c.course_name = 'Computer Networks' THEN 'Networks'
                WHEN c.course_name = 'Software quality and testing' THEN 'QA & Testing'
                ELSE 'Not assigned'
            END AS department
        FROM {$s['tables']['enrollments']} e
        JOIN {$s['tables']['courses']} c ON e.{$e['course_id']} = c.{$c['id']}
        LEFT JOIN {$s['tables']['professors']} p ON 1=0
        WHERE e.{$e['student_id']} = ?
        ORDER BY c.{$c['name']}
    ";

    $coursesStmt = $pdo->prepare($coursesSql);
    $coursesStmt->execute([$studentId]);
    $courses = $coursesStmt->fetchAll();

    return [
        'student' => $student,
        'courses' => $courses
    ];
}


