<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function grades_get_all(): array {
    $pdo = db();
    $s = schema();
    $e = $s['columns']['enrollments'];
    $st = $s['columns']['students'];
    $c = $s['columns']['courses'];
    $sql = "
        SELECT
            e.{$e['id']} AS enrollment_id,
            s.{$st['id']} AS student_id,
            CONCAT(s.{$st['first_name']}, ' ', s.{$st['last_name']}) AS student_name,
            c.{$c['id']} AS course_id,
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
            END AS professor_email
        FROM {$s['tables']['enrollments']} e
        JOIN {$s['tables']['students']} s ON e.{$e['student_id']} = s.{$st['id']}
        JOIN {$s['tables']['courses']} c ON e.{$e['course_id']} = c.{$c['id']}
        ORDER BY s.{$st['id']}, c.{$c['name']};
    ";
    return $pdo->query($sql)->fetchAll();
}

function grades_add(int $studentId, int $courseId, int $grade): bool {
    $pdo = db();
    $s = schema();
    $e = $s['columns']['enrollments'];
    $checkSql = "SELECT {$e['id']} FROM {$s['tables']['enrollments']} WHERE {$e['student_id']} = ? AND {$e['course_id']} = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$studentId, $courseId]);
    if ($checkStmt->fetch()) {
        return false;
    }

    $maxIdSql = "SELECT MAX({$e['id']}) as max_id FROM {$s['tables']['enrollments']}";
    $maxIdStmt = $pdo->prepare($maxIdSql);
    $maxIdStmt->execute();
    $maxId = $maxIdStmt->fetch();
    $newId = ($maxId['max_id'] ?? 0) + 1;

    $insertSql = "INSERT INTO {$s['tables']['enrollments']} ({$e['id']}, {$e['student_id']}, {$e['course_id']}, {$e['grade']}) VALUES (?, ?, ?, ?)";
    $insertStmt = $pdo->prepare($insertSql);
    return $insertStmt->execute([$newId, $studentId, $courseId, $grade]);
}


