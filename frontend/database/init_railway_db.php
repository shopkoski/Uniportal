<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function init_railway_database(): void {
    $pdo = get_pdo();
    
    echo "🚀 Initializing Railway MySQL Database...\n";
    
    // Create tables
    create_tables($pdo);
    
    // Import CSV data
    import_csv_data($pdo);
    
    echo "✅ Database initialization complete!\n";
}

function create_tables(PDO $pdo): void {
    echo "📊 Creating database tables...\n";
    
    // Create Students_Table_1
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS Students_Table_1 (
            student_id INT PRIMARY KEY AUTO_INCREMENT,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            enrollment_year INT NOT NULL,
            INDEX idx_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    // Create Professors_Table_1
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS Professors_Table_1 (
            professor_id INT PRIMARY KEY AUTO_INCREMENT,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            department VARCHAR(100) NOT NULL,
            INDEX idx_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    // Create Courses_Table_1
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS Courses_Table_1 (
            course_id INT PRIMARY KEY AUTO_INCREMENT,
            course_name VARCHAR(200) NOT NULL,
            credits INT NOT NULL,
            _2 INT DEFAULT NULL,
            INDEX idx_course_name (course_name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    // Create Enrollments_Table_1
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS Enrollments_Table_1 (
            enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
            student_id INT NOT NULL,
            course_id INT NOT NULL,
            grade DECIMAL(3,2) DEFAULT NULL,
            FOREIGN KEY (student_id) REFERENCES Students_Table_1(student_id) ON DELETE CASCADE,
            FOREIGN KEY (course_id) REFERENCES Courses_Table_1(course_id) ON DELETE CASCADE,
            UNIQUE KEY unique_enrollment (student_id, course_id),
            INDEX idx_student (student_id),
            INDEX idx_course (course_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    // Drop and recreate Users table (for authentication)
    $pdo->exec("DROP TABLE IF EXISTS Users");
    $pdo->exec("
        CREATE TABLE Users (
            user_id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'student', 'professor') NOT NULL,
            student_id INT DEFAULT NULL,
            professor_id INT DEFAULT NULL,
            INDEX idx_username (username),
            INDEX idx_role (role)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    echo "✅ Tables created successfully!\n";
}

function import_csv_data(PDO $pdo): void {
    echo "📥 Importing CSV data...\n";
    
    $csvDir = __DIR__ . '/';
    
    // Import Students
    import_csv_file($pdo, $csvDir . 'Students-Table 1.csv', 'Students_Table_1', [
        'student_id', 'first_name', 'last_name', 'email', 'enrollment_year'
    ]);
    
    // Import Professors
    import_csv_file($pdo, $csvDir . 'Professors-Table 1.csv', 'Professors_Table_1', [
        'professor_id', 'first_name', 'last_name', 'email', 'department'
    ]);
    
    // Import Courses (only 3 columns in CSV)
    import_csv_file($pdo, $csvDir . 'Courses-Table 1.csv', 'Courses_Table_1', [
        'course_id', 'course_name', 'credits'
    ]);
    
    // Import Enrollments
    import_csv_file($pdo, $csvDir . 'Enrollments-Table 1.csv', 'Enrollments_Table_1', [
        'enrollment_id', 'student_id', 'course_id', 'grade'
    ]);
    
    // Import Users
    import_csv_file($pdo, $csvDir . 'Users-Table 1.csv', 'Users', [
        'user_id', 'username', 'password', 'role', 'student_id', 'professor_id'
    ]);
    
    echo "✅ CSV data imported successfully!\n";
}

function import_csv_file(PDO $pdo, string $filePath, string $tableName, array $columns): void {
    if (!file_exists($filePath)) {
        echo "⚠️  File not found: $filePath\n";
        return;
    }
    
    echo "📄 Importing $filePath...\n";
    
    $handle = fopen($filePath, 'r');
    if (!$handle) {
        echo "❌ Could not open file: $filePath\n";
        return;
    }
    
    // Skip header row
    fgetcsv($handle, 0, ',', '"', '\\');
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO $tableName (" . implode(', ', $columns) . ") VALUES (" . str_repeat('?,', count($columns) - 1) . "?)");
    
    $rowCount = 0;
    while (($data = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
        if (count($data) >= count($columns)) {
            $stmt->execute($data);
            $rowCount++;
        }
    }
    
    fclose($handle);
    echo "✅ Imported $rowCount rows into $tableName\n";
}

// Run the initialization
if (php_sapi_name() === 'cli') {
    init_railway_database();
}
