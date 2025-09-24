<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function export_table_to_csv(string $tableKey, string $csvPath, array $columnsInOrder): bool {
    $pdo = db();
    $s = schema();

    $tableName = $s['tables'][$tableKey] ?? null;
    if ($tableName === null) return false;

    $quotedCols = array_map(fn($c) => "`$c`", $columnsInOrder);
    $sql = 'SELECT ' . implode(', ', $quotedCols) . " FROM $tableName";
    $stmt = $pdo->query($sql);

    $tmpPath = $csvPath . '.tmp';
    $fh = fopen($tmpPath, 'w');
    if ($fh === false) return false;

    // Write header
    fputcsv($fh, $columnsInOrder, ',', '"', '\\');

    while ($row = $stmt->fetch()) {
        $line = [];
        foreach ($columnsInOrder as $col) {
            $line[] = $row[$col] ?? '';
        }
        fputcsv($fh, $line, ',', '"', '\\');
    }

    fclose($fh);
    // Atomic replace
    rename($tmpPath, $csvPath);
    return true;
}

function export_all_csvs(): void {
    $s = schema();
    $base = dirname(__DIR__);

    // Students
    export_table_to_csv(
        'students',
        $base . '/Students-Table 1.csv',
        [
            $s['columns']['students']['id'],
            $s['columns']['students']['first_name'],
            $s['columns']['students']['last_name'],
            $s['columns']['students']['email'],
            $s['columns']['students']['enrollment_year'],
        ]
    );

    // Courses
    export_table_to_csv(
        'courses',
        $base . '/Courses-Table 1.csv',
        [
            $s['columns']['courses']['id'],
            $s['columns']['courses']['name'],
            $s['columns']['courses']['credits'],
        ]
    );

    // Professors
    export_table_to_csv(
        'professors',
        $base . '/Professors-Table 1.csv',
        [
            $s['columns']['professors']['id'],
            $s['columns']['professors']['first_name'],
            $s['columns']['professors']['last_name'],
            $s['columns']['professors']['email'],
            $s['columns']['professors']['department'],
        ]
    );

    // Enrollments
    export_table_to_csv(
        'enrollments',
        $base . '/Enrollments-Table 1.csv',
        [
            $s['columns']['enrollments']['id'],
            $s['columns']['enrollments']['student_id'],
            $s['columns']['enrollments']['course_id'],
            $s['columns']['enrollments']['grade'],
        ]
    );
}





