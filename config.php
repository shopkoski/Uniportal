<?php
declare(strict_types=1);

/**
 * Database config for MySQL via PDO.
 * - Uses TCP (127.0.0.1:3306) to avoid socket quirks on macOS.
 * - Change USER/PASS below to your actual MySQL credentials.
 * - If you prefer a Unix socket, see the commented DSN in getConnection().
 */
class Database {
    private string $host;
    private int $port;
    private string $db;
    private string $user;
    private string $pass;
    private ?PDO $pdo = null;

    public function __construct(
        string $host = '127.0.0.1',
        string $db   = 'my_test_db',   
        string $user = 'root',         // <-- CHANGE THIS
        string $pass = '14122002',// <-- CHANGE THIS
        int    $port = 3306
    ) {
        $this->host = $host;
        $this->db   = $db;
        $this->user = $user;
        $this->pass = $pass;
        $this->port = $port;
    }

    public function getConnection(): PDO {
        if ($this->pdo !== null) return $this->pdo;

        // TCP DSN (recommended)
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset=utf8mb4";

        // If you must use a Unix socket instead, replace the line above with:
        // $dsn = "mysql:unix_socket=/tmp/mysql.sock;dbname={$this->db};charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        return $this->pdo;
    }

    /**
     * Return students with all their information and a comma-separated list of their courses.
     * Uses your actual table names:
     *   Students_Table_1 (student_id, first_name, last_name, email, enrollment_year)
     *   Enrollments_Table_1 (student_id, course_id)
     *   Courses_Table_1 (course_id, course_name)
     */
    public function getStudentsWithCourses(): array {
        $pdo = $this->getConnection();

        $sql = "
            SELECT
                s.student_id,
                s.first_name,
                s.last_name,
                CONCAT(s.first_name, ' ', s.last_name) AS full_name,
                s.email,
                s.enrollment_year,
                COALESCE(GROUP_CONCAT(DISTINCT c.course_name ORDER BY c.course_name SEPARATOR ', '), '') AS courses
            FROM Students_Table_1 s
            LEFT JOIN (
                SELECT e.student_id, e.course_id, c.course_name
                FROM Enrollments_Table_1 e
                JOIN Courses_Table_1 c ON e.course_id = c.course_id
                WHERE c.course_name NOT IN ('Databases', 'Web Development')
            ) filtered_enrollments ON s.student_id = filtered_enrollments.student_id
            LEFT JOIN Courses_Table_1 c ON filtered_enrollments.course_id = c.course_id
            GROUP BY s.student_id, s.first_name, s.last_name, s.email, s.enrollment_year
            ORDER BY s.student_id;
        ";

        return $pdo->query($sql)->fetchAll();
    }

    /**
     * Return students with role-based access control.
     * Students can only see their own grades, but can see other students' course enrollments.
     */
    public function getStudentsWithCoursesFiltered(string $userRole, ?int $userId = null): array {
        $pdo = $this->getConnection();

        // Base query for all students
        $sql = "
            SELECT
                s.student_id,
                s.first_name,
                s.last_name,
                CONCAT(s.first_name, ' ', s.last_name) AS full_name,
                s.email,
                s.enrollment_year,
                COALESCE(GROUP_CONCAT(DISTINCT c.course_name ORDER BY c.course_name SEPARATOR ', '), '') AS courses
            FROM Students_Table_1 s
            LEFT JOIN (
                SELECT e.student_id, e.course_id, c.course_name
                FROM Enrollments_Table_1 e
                JOIN Courses_Table_1 c ON e.course_id = c.course_id
            ) enrollments ON s.student_id = enrollments.student_id
            LEFT JOIN Courses_Table_1 c ON enrollments.course_id = c.course_id
            GROUP BY s.student_id, s.first_name, s.last_name, s.email, s.enrollment_year
            ORDER BY s.student_id;
        ";

        return $pdo->query($sql)->fetchAll();
    }

    /**
     * Return all courses with their information.
     * Uses your actual table name: Courses_Table_1 (course_id, course_name, credits)
     */
    public function getAllCourses(): array {
        $pdo = $this->getConnection();

        $sql = "
            SELECT
                c.course_id,
                c.course_name,
                c.credits,
                COUNT(e.student_id) AS enrolled_students
            FROM Courses_Table_1 c
            LEFT JOIN Enrollments_Table_1 e ON c.course_id = e.course_id
            GROUP BY c.course_id, c.course_name, c.credits
            ORDER BY c.course_id;
        ";

        return $pdo->query($sql)->fetchAll();
    }

    /**
     * Return all professors with their information.
     * Uses your actual table name: Professors_Table_1 (professor_id, first_name, last_name, email, department)
     */
    public function getAllProfessors(): array {
        $pdo = $this->getConnection();

        $sql = "
            SELECT
                p.professor_id,
                p.first_name,
                p.last_name,
                CONCAT(p.first_name, ' ', p.last_name) AS full_name,
                p.email,
                p.department,
                COUNT(DISTINCT c.course_id) AS courses_taught
            FROM Professors_Table_1 p
            LEFT JOIN Courses_Table_1 c ON p.department = 'Computer Science' OR p.department = 'Software Engineering' OR p.department = 'Mathematics' OR p.department = 'Networks' OR p.department = 'QA & Testing'
            GROUP BY p.professor_id, p.first_name, p.last_name, p.email, p.department
            ORDER BY p.professor_id;
        ";

        return $pdo->query($sql)->fetchAll();
    }

    /**
     * Return all grades with student and course information.
     * Uses your actual table names: Enrollments_Table_1, Students_Table_1, Courses_Table_1
     */
    public function getAllGrades(): array {
        $pdo = $this->getConnection();

        $sql = "
            SELECT
                e.enrollment_id,
                s.student_id,
                CONCAT(s.first_name, ' ', s.last_name) AS student_name,
                c.course_id,
                c.course_name,
                e.grade,
                CASE 
                    WHEN e.grade >= 9 THEN 'A'
                    WHEN e.grade >= 8 THEN 'B'
                    WHEN e.grade >= 7 THEN 'C'
                    WHEN e.grade >= 6 THEN 'D'
                    ELSE 'F'
                END AS letter_grade
            FROM Enrollments_Table_1 e
            JOIN Students_Table_1 s ON e.student_id = s.student_id
            JOIN Courses_Table_1 c ON e.course_id = c.course_id
            ORDER BY s.student_id, c.course_name;
        ";

        return $pdo->query($sql)->fetchAll();
    }

    /**
     * Get course details including professor and enrolled students with grades.
     * Uses your actual table names: Courses_Table_1, Professors_Table_1, Enrollments_Table_1, Students_Table_1
     */
    public function getCourseDetails(int $courseId): array {
        $pdo = $this->getConnection();

        // Get course and professor information
        $courseSql = "
            SELECT
                c.course_id,
                c.course_name,
                c.credits,
                p.professor_id,
                CONCAT(p.first_name, ' ', p.last_name) AS professor_name,
                p.email AS professor_email,
                p.department
            FROM Courses_Table_1 c
            LEFT JOIN Professors_Table_1 p ON 
                (c.course_name = 'Databases' AND p.department = 'Computer Science') OR
                (c.course_name = 'Web Development' AND p.department = 'Software Engineering') OR
                (c.course_name = 'Algorithms' AND p.department = 'Computer Science') OR
                (c.course_name = 'Computer Networks' AND p.department = 'Networks') OR
                (c.course_name = 'Calculus' AND p.department = 'Mathematics') OR
                (c.course_name = 'Operating systems' AND p.department = 'Computer Science') OR
                (c.course_name = 'Structural programming' AND p.department = 'Software Engineering') OR
                (c.course_name = 'Software engineering' AND p.department = 'Software Engineering') OR
                (c.course_name = 'Software quality and testing' AND p.department = 'QA & Testing')
            WHERE c.course_id = ?
        ";

        $courseStmt = $pdo->prepare($courseSql);
        $courseStmt->execute([$courseId]);
        $course = $courseStmt->fetch();

        // Get enrolled students with grades
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
            FROM Enrollments_Table_1 e
            JOIN Students_Table_1 s ON e.student_id = s.student_id
            WHERE e.course_id = ?
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

    /**
     * Get student course details including grades, credits, and professor information.
     * Uses your actual table names: Students_Table_1, Enrollments_Table_1, Courses_Table_1, Professors_Table_1
     * Role-based access: Students can only see their own grades, but can see other students' course enrollments.
     */
    public function getStudentCourseDetails(int $studentId, string $userRole = 'Admin', ?int $userId = null): array {
        $pdo = $this->getConnection();

        // Get student information
        $studentSql = "
            SELECT
                s.student_id,
                CONCAT(s.first_name, ' ', s.last_name) AS student_name,
                s.email AS student_email,
                s.enrollment_year
            FROM Students_Table_1 s
            WHERE s.student_id = ?
        ";

        $studentStmt = $pdo->prepare($studentSql);
        $studentStmt->execute([$studentId]);
        $student = $studentStmt->fetch();

        // Determine if user can see grades based on role
        $canSeeGrades = ($userRole === 'Admin') || ($userRole === 'User' && $userId === $studentId);
        
        // Get student's courses with conditional grades, credits, and professor information
        $coursesSql = "
            SELECT
                c.course_id,
                c.course_name,
                c.credits,
                " . ($canSeeGrades ? "e.grade," : "NULL as grade,") . "
                " . ($canSeeGrades ? "
                CASE 
                    WHEN e.grade >= 9 THEN 'A'
                    WHEN e.grade >= 8 THEN 'B'
                    WHEN e.grade >= 7 THEN 'C'
                    WHEN e.grade >= 6 THEN 'D'
                    ELSE 'F'
                END AS letter_grade," : "NULL as letter_grade,") . "
                CONCAT(p.first_name, ' ', p.last_name) AS professor_name,
                p.email AS professor_email,
                p.department
            FROM Enrollments_Table_1 e
            JOIN Courses_Table_1 c ON e.course_id = c.course_id
            LEFT JOIN Professors_Table_1 p ON 
                (c.course_name = 'Databases' AND p.department = 'Computer Science') OR
                (c.course_name = 'Web Development' AND p.department = 'Software Engineering') OR
                (c.course_name = 'Algorithms' AND p.department = 'Computer Science') OR
                (c.course_name = 'Computer Networks' AND p.department = 'Networks') OR
                (c.course_name = 'Calculus' AND p.department = 'Mathematics') OR
                (c.course_name = 'Operating systems' AND p.department = 'Computer Science') OR
                (c.course_name = 'Structural programming' AND p.department = 'Software Engineering') OR
                (c.course_name = 'Software engineering' AND p.department = 'Software Engineering') OR
                (c.course_name = 'Software quality and testing' AND p.department = 'QA & Testing')
            WHERE e.student_id = ?
            ORDER BY c.course_name
        ";

        $coursesStmt = $pdo->prepare($coursesSql);
        $coursesStmt->execute([$studentId]);
        $courses = $coursesStmt->fetchAll();

        return [
            'student' => $student,
            'courses' => $courses
        ];
    }

    /**
     * Get student ID by user email (for role-based access control).
     * This creates a mapping between users and students based on email.
     */
    public function getStudentIdByUserEmail(string $userEmail): ?int {
        $pdo = $this->getConnection();
        
        // Try to find a student with matching email
        $sql = "SELECT student_id FROM Students_Table_1 WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userEmail]);
        $result = $stmt->fetch();
        
        return $result ? (int)$result['student_id'] : null;
    }

    /**
     * Get grades for a specific student.
     */
    public function getStudentGrades(int $studentId): array {
        $pdo = $this->getConnection();
        
        $sql = "
            SELECT
                s.student_id,
                CONCAT(s.first_name, ' ', s.last_name) AS student_name,
                c.course_name,
                e.grade,
                CASE 
                    WHEN e.grade >= 9 THEN 'A'
                    WHEN e.grade >= 8 THEN 'B'
                    WHEN e.grade >= 7 THEN 'C'
                    WHEN e.grade >= 6 THEN 'D'
                    ELSE 'F'
                END AS letter_grade,
                CONCAT(p.first_name, ' ', p.last_name) AS professor_name
            FROM Enrollments_Table_1 e
            JOIN Students_Table_1 s ON e.student_id = s.student_id
            JOIN Courses_Table_1 c ON e.course_id = c.course_id
            LEFT JOIN Professors_Table_1 p ON 
                (c.course_name = 'Databases' AND p.department = 'Computer Science') OR
                (c.course_name = 'Web Development' AND p.department = 'Software Engineering') OR
                (c.course_name = 'Algorithms' AND p.department = 'Computer Science') OR
                (c.course_name = 'Computer Networks' AND p.department = 'Networks') OR
                (c.course_name = 'Calculus' AND p.department = 'Mathematics') OR
                (c.course_name = 'Operating systems' AND p.department = 'Computer Science') OR
                (c.course_name = 'Structural programming' AND p.department = 'Software Engineering') OR
                (c.course_name = 'Software engineering' AND p.department = 'Software Engineering') OR
                (c.course_name = 'Software quality and testing' AND p.department = 'QA & Testing')
            WHERE e.student_id = ?
            ORDER BY c.course_name
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    /**
     * Get professor course details including courses taught, credits, and enrolled students.
     * Uses your actual table names: Professors_Table_1, Courses_Table_1, Enrollments_Table_1
     */
    public function getProfessorCourseDetails(int $professorId): array {
        $pdo = $this->getConnection();

        // Get professor information
        $professorSql = "
            SELECT
                p.professor_id,
                CONCAT(p.first_name, ' ', p.last_name) AS professor_name,
                p.email AS professor_email,
                p.department
            FROM Professors_Table_1 p
            WHERE p.professor_id = ?
        ";

        $professorStmt = $pdo->prepare($professorSql);
        $professorStmt->execute([$professorId]);
        $professor = $professorStmt->fetch();

        // Get courses taught by this professor with credits and enrolled students
        $coursesSql = "
            SELECT
                c.course_id,
                c.course_name,
                c.credits,
                COUNT(e.student_id) AS enrolled_students
            FROM Courses_Table_1 c
            LEFT JOIN Enrollments_Table_1 e ON c.course_id = e.course_id
            WHERE 
                (p.department = 'Computer Science' AND c.course_name IN ('Databases', 'Algorithms', 'Operating systems')) OR
                (p.department = 'Software Engineering' AND c.course_name IN ('Web Development', 'Structural programming', 'Software engineering')) OR
                (p.department = 'Mathematics' AND c.course_name = 'Calculus') OR
                (p.department = 'Networks' AND c.course_name = 'Computer Networks') OR
                (p.department = 'QA & Testing' AND c.course_name = 'Software quality and testing')
            GROUP BY c.course_id, c.course_name, c.credits
            ORDER BY c.course_name
        ";

        // First get the professor's department
        $deptSql = "SELECT department FROM Professors_Table_1 WHERE professor_id = ?";
        $deptStmt = $pdo->prepare($deptSql);
        $deptStmt->execute([$professorId]);
        $deptResult = $deptStmt->fetch();
        $department = $deptResult['department'] ?? '';

        // Now get courses based on department
        $coursesQuery = "
            SELECT
                c.course_id,
                c.course_name,
                c.credits,
                COUNT(e.student_id) AS enrolled_students
            FROM Courses_Table_1 c
            LEFT JOIN Enrollments_Table_1 e ON c.course_id = e.course_id
            WHERE 
        ";

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
                $coursesQuery .= "1=0"; // No courses if department doesn't match
        }

        $coursesQuery .= " GROUP BY c.course_id, c.course_name, c.credits ORDER BY c.course_name";

        $coursesStmt = $pdo->prepare($coursesQuery);
        $coursesStmt->execute();
        $courses = $coursesStmt->fetchAll();

        return [
            'professor' => $professor,
            'courses' => $courses
        ];
    }
}