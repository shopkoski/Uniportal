# PowerShell script to enroll ALL students in courses with grades
# Run this in Azure Cloud Shell or Windows PowerShell with sqlcmd

Write-Host "🎓 Enrolling ALL students in courses with grades..." -ForegroundColor Green

# Connection details
$server = "uniportal-sql-server.database.windows.net"
$database = "uniportal-db"
$username = "sqladmin"
$password = "Admin123"

# SQL commands to enroll all students
$sqlCommands = @"
-- Enroll students 1-10 in various courses
-- Databases (Course ID: 1)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(1, 1, 8.0), (2, 1, 9.0), (3, 1, 10.0), (4, 1, 6.0), (5, 1, 8.5),
(6, 1, 7.5), (7, 1, 9.5), (8, 1, 8.0), (9, 1, 7.0), (10, 1, 8.5);

-- Web Development (Course ID: 2)  
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(1, 2, 9.0), (2, 2, 10.0), (3, 2, 6.0), (4, 2, 8.0), (5, 2, 7.5),
(6, 2, 9.0), (7, 2, 8.5), (8, 2, 7.0), (9, 2, 8.5), (10, 2, 9.0);

-- Algorithms (Course ID: 3)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(1, 3, 7.0), (2, 3, 8.5), (3, 3, 9.0), (4, 3, 6.5), (5, 3, 8.0),
(6, 3, 7.5), (7, 3, 9.5), (8, 3, 8.0), (9, 3, 7.0), (10, 3, 8.5);

-- Computer Networks (Course ID: 4)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(1, 4, 8.5), (2, 4, 7.0), (3, 4, 9.0), (4, 4, 6.0), (5, 4, 8.0),
(6, 4, 7.5), (7, 4, 9.0), (8, 4, 8.5), (9, 4, 7.0), (10, 4, 8.0);

-- Calculus (Course ID: 5)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(1, 5, 7.5), (2, 5, 8.0), (3, 5, 9.5), (4, 5, 6.5), (5, 5, 8.5),
(6, 5, 7.0), (7, 5, 9.0), (8, 5, 8.0), (9, 5, 7.5), (10, 5, 8.5);

-- Operating Systems (Course ID: 6)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(1, 6, 8.0), (2, 6, 7.5), (3, 6, 9.0), (4, 6, 6.0), (5, 6, 8.5),
(6, 6, 7.0), (7, 6, 9.5), (8, 6, 8.0), (9, 6, 7.5), (10, 6, 8.5);

-- Structural Programming (Course ID: 7)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(1, 7, 8.5), (2, 7, 7.0), (3, 7, 9.0), (4, 7, 6.5), (5, 7, 8.0),
(6, 7, 7.5), (7, 7, 9.5), (8, 7, 8.5), (9, 7, 7.0), (10, 7, 8.0);

-- Software Engineering (Course ID: 8)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(1, 8, 7.0), (2, 8, 8.5), (3, 8, 9.0), (4, 8, 6.0), (5, 8, 8.0),
(6, 8, 7.5), (7, 8, 9.5), (8, 8, 8.0), (9, 8, 7.5), (10, 8, 8.5);

-- Software Quality and Testing (Course ID: 9)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(1, 9, 8.0), (2, 9, 7.5), (3, 9, 9.0), (4, 9, 6.5), (5, 9, 8.5),
(6, 9, 7.0), (7, 9, 9.5), (8, 9, 8.0), (9, 9, 7.5), (10, 9, 8.5);

-- Enroll students 11-20 in various courses
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(11, 1, 8.5), (12, 1, 7.0), (13, 1, 9.0), (14, 1, 6.5), (15, 1, 8.0),
(16, 1, 7.5), (17, 1, 9.5), (18, 1, 8.0), (19, 1, 7.0), (20, 1, 8.5),
(11, 2, 7.5), (12, 2, 8.0), (13, 2, 9.5), (14, 2, 6.0), (15, 2, 8.5),
(16, 2, 7.0), (17, 2, 9.0), (18, 2, 8.5), (19, 2, 7.5), (20, 2, 8.0),
(11, 3, 8.0), (12, 3, 7.5), (13, 3, 9.0), (14, 3, 6.5), (15, 3, 8.5),
(16, 3, 7.0), (17, 3, 9.5), (18, 3, 8.0), (19, 3, 7.5), (20, 3, 8.5);

-- Enroll students 21-30 in various courses
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(21, 1, 8.0), (22, 1, 7.5), (23, 1, 9.0), (24, 1, 6.0), (25, 1, 8.5),
(26, 1, 7.0), (27, 1, 9.5), (28, 1, 8.0), (29, 1, 7.5), (30, 1, 8.5),
(21, 2, 7.5), (22, 2, 8.5), (23, 2, 9.0), (24, 2, 6.5), (25, 2, 8.0),
(26, 2, 7.0), (27, 2, 9.5), (28, 2, 8.5), (29, 2, 7.0), (30, 2, 8.0),
(21, 3, 8.5), (22, 3, 7.0), (23, 3, 9.5), (24, 3, 6.0), (25, 3, 8.0),
(26, 3, 7.5), (27, 3, 9.0), (28, 3, 8.5), (29, 3, 7.0), (30, 3, 8.0);
"@

Write-Host "📊 Enrolling all 30 students in courses..." -ForegroundColor Yellow

# Execute the SQL commands
try {
    sqlcmd -S $server -d $database -U $username -P $password -Q $sqlCommands
    Write-Host "✅ All students enrolled successfully!" -ForegroundColor Green
    
    # Show summary
    $summaryQuery = @"
SELECT 'Total Enrollments:' as Metric, COUNT(*) as Count FROM Enrollments_Table_1
UNION ALL
SELECT 'Unique Students Enrolled:', COUNT(DISTINCT student_id) FROM Enrollments_Table_1
UNION ALL
SELECT 'Courses with Enrollments:', COUNT(DISTINCT course_id) FROM Enrollments_Table_1
UNION ALL
SELECT 'Average Grade:', CAST(AVG(CAST(grade AS FLOAT)) AS DECIMAL(4,2)) FROM Enrollments_Table_1;
"@
    
    Write-Host "📈 Summary:" -ForegroundColor Cyan
    sqlcmd -S $server -d $database -U $username -P $password -Q $summaryQuery
    
} catch {
    Write-Host "❌ Error enrolling students: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "💡 Try running this script in Azure Cloud Shell or install sqlcmd locally" -ForegroundColor Yellow
}
