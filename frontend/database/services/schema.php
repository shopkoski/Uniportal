<?php
declare(strict_types=1);

// Centralized schema mapping for table and column names.
// Adjust these to match your actual MySQL schema.

return [
    'tables' => [
        'students'    => 'Students_Table_1',
        'courses'     => 'Courses_Table_1',
        'enrollments' => 'Enrollments_Table_1',
        'professors'  => 'Professors_Table_1',
    ],
    'columns' => [
        'students' => [
            'id' => 'student_id',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'email' => 'email',
            'enrollment_year' => 'enrollment_year',
        ],
        'courses' => [
            'id' => 'course_id',
            'name' => 'course_name',
            'credits' => 'credits',
            'professor_id' => '_2', // This column exists but may not contain professor_id data
        ],
        'enrollments' => [
            'id' => 'enrollment_id',
            'student_id' => 'student_id',
            'course_id' => 'course_id',
            'grade' => 'grade',
        ],
        'professors' => [
            'id' => 'professor_id',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'email' => 'email',
            'department' => 'department',
        ],
    ],
];


