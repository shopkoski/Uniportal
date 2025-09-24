<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function courses_get_all(): array {
    $pdo = db();
    $s = schema();
    $c = $s['columns']['courses'];
    $e = $s['columns']['enrollments'];
    $sql = "
        SELECT
            c.{$c['id']}   AS course_id,
            c.{$c['name']} AS course_name,
            c.{$c['credits']} AS credits,
            COUNT(e.{$e['student_id']}) AS enrolled_students
        FROM {$s['tables']['courses']} c
        LEFT JOIN {$s['tables']['enrollments']} e ON c.{$c['id']} = e.{$e['course_id']}
        GROUP BY c.{$c['id']}, c.{$c['name']}, c.{$c['credits']}
        ORDER BY c.{$c['id']};
    ";
    return $pdo->query($sql)->fetchAll();
}

function courses_add(string $courseName, int $credits, int $professorId): bool {
    $pdo = db();
    $s = schema();
    $c = $s['columns']['courses'];
    $checkSql = "SELECT {$c['id']} FROM {$s['tables']['courses']} WHERE {$c['name']} = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$courseName]);
    if ($checkStmt->fetch()) {
        return false;
    }

    $maxIdSql = "SELECT MAX({$c['id']}) as max_id FROM {$s['tables']['courses']}";
    $maxIdStmt = $pdo->prepare($maxIdSql);
    $maxIdStmt->execute();
    $maxId = $maxIdStmt->fetch();
    $newId = ($maxId['max_id'] ?? 0) + 1;

    $insertSql = "INSERT INTO {$s['tables']['courses']} ({$c['id']}, {$c['name']}, {$c['credits']}, {$c['professor_id']}) VALUES (?, ?, ?, ?)";
    $insertStmt = $pdo->prepare($insertSql);
    return $insertStmt->execute([$newId, $courseName, $credits, $professorId]);
}

function courses_delete(int $courseId): bool {
    $pdo = db();
    $s = schema();
    $c = $s['columns']['courses'];
    $e = $s['columns']['enrollments'];
    // Remove enrollments for this course first
    $pdo->prepare("DELETE FROM {$s['tables']['enrollments']} WHERE {$e['course_id']} = ?")->execute([$courseId]);
    $stmt = $pdo->prepare("DELETE FROM {$s['tables']['courses']} WHERE {$c['id']} = ?");
    return $stmt->execute([$courseId]);
}

function courses_get_details(int $courseId): array {
    $pdo = db();
    $s = schema();
    $c = $s['columns']['courses'];
    $p = $s['columns']['professors'];
    $e = $s['columns']['enrollments'];
    $st = $s['columns']['students'];

    $courseSql = "
        SELECT
            c.course_id,
            c.course_name,
            c.credits
        FROM {$s['tables']['courses']} c
        WHERE c.{$c['id']} = ?
    ";
    $courseStmt = $pdo->prepare($courseSql);
    $courseStmt->execute([$courseId]);
    $course = $courseStmt->fetch();

    // Assign professor based on course type
    $professorInfo = getProfessorForCourse($course['course_name']);
    $course['professor_id'] = $professorInfo['professor_id'];
    $course['professor_name'] = $professorInfo['professor_name'];
    $course['professor_email'] = $professorInfo['professor_email'];
    $course['department'] = $professorInfo['department'];

    $studentsSql = "
        SELECT
            s.student_id,
            CONCAT(s.first_name, ' ', s.last_name) AS student_name,
            s.email AS student_email,
            e.grade,
            CASE 
                WHEN e.grade >= 9 THEN 'A'
                WHEN e.grade >= 8 THEN 'B'
                WHEN e.grade >= 7 THEN 'C'
                WHEN e.grade >= 6 THEN 'D'
                ELSE 'F'
            END AS letter_grade
        FROM {$s['tables']['enrollments']} e
        JOIN {$s['tables']['students']} s ON e.{$e['student_id']} = s.{$st['id']}
        WHERE e.{$e['course_id']} = ?
        ORDER BY s.last_name, s.first_name
    ";
    $studentsStmt = $pdo->prepare($studentsSql);
    $studentsStmt->execute([$courseId]);
    $students = $studentsStmt->fetchAll();

    return [
        'course' => $course,
        'students' => $students
    ];
}

function getProfessorForCourse(string $courseName): array {
    $pdo = db();
    $s = schema();
    $p = $s['columns']['professors'];
    
    // Define course-to-professor mapping based on departments
    $courseMapping = [
        'Databases' => 'Computer Science',
        'Algorithms' => 'Computer Science', 
        'Operating systems' => 'Computer Science',
        'Web Development' => 'Software Engineering',
        'Structural programming' => 'Software Engineering',
        'Software engineering' => 'Software Engineering',
        'Calculus' => 'Mathematics',
        'Computer Networks' => 'Networks',
        'Software quality and testing' => 'QA & Testing'
    ];
    
    $department = $courseMapping[$courseName] ?? null;
    
    if (!$department) {
        return [
            'professor_id' => null,
            'professor_name' => 'Not assigned',
            'professor_email' => null,
            'department' => 'Not assigned'
        ];
    }
    
    // Get the first professor from the matching department
    $professorSql = "
        SELECT 
            {$p['id']} AS professor_id,
            CONCAT({$p['first_name']}, ' ', {$p['last_name']}) AS professor_name,
            {$p['email']} AS professor_email,
            {$p['department']} AS department
        FROM {$s['tables']['professors']} 
        WHERE {$p['department']} = ?
        LIMIT 1
    ";
    
    $stmt = $pdo->prepare($professorSql);
    $stmt->execute([$department]);
    $professor = $stmt->fetch();
    
    if ($professor) {
        return $professor;
    }
    
    return [
        'professor_id' => null,
        'professor_name' => 'Not assigned',
        'professor_email' => null,
        'department' => $department
    ];
}


