# PowerShell script to add more student enrollments
# Run this in Azure Cloud Shell or Windows PowerShell with sqlcmd

Write-Host "🎓 Adding more student enrollments to courses..." -ForegroundColor Green

# Connection details
$server = "uniportal-sql-server.database.windows.net"
$database = "uniportal-db"
$username = "sqladmin"
$password = "Admin123"

# SQL commands to add enrollments
$sqlCommands = @"
-- Add enrollments for students 11-30 in various courses
-- Web Development (Course ID: 2)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(11, 2, 8.5),
(12, 2, 7.0),
(13, 2, 9.0),
(14, 2, 8.0),
(15, 2, 7.5);

-- Algorithms (Course ID: 3)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(11, 3, 8.0),
(12, 3, 6.5),
(13, 3, 9.5),
(14, 3, 7.0),
(15, 3, 8.5),
(16, 3, 7.5),
(17, 3, 9.0);

-- Computer Networks (Course ID: 4)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(11, 4, 7.5),
(12, 4, 8.0),
(13, 4, 8.5),
(14, 4, 6.5),
(15, 4, 9.0),
(16, 4, 7.0),
(17, 4, 8.5),
(18, 4, 7.5);

-- Calculus (Course ID: 5)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(11, 5, 8.0),
(12, 5, 7.5),
(13, 5, 9.0),
(14, 5, 6.0),
(15, 5, 8.5),
(16, 5, 7.0),
(17, 5, 8.0),
(18, 5, 7.5),
(19, 5, 9.5);

-- Operating Systems (Course ID: 6)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(11, 6, 7.0),
(12, 6, 8.5),
(13, 6, 8.0),
(14, 6, 7.5),
(15, 6, 9.0),
(16, 6, 6.5),
(17, 6, 8.5),
(18, 6, 7.0),
(19, 6, 8.0),
(20, 6, 7.5);

-- Structural Programming (Course ID: 7)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(11, 7, 8.5),
(12, 7, 7.0),
(13, 7, 9.0),
(14, 7, 8.0),
(15, 7, 7.5),
(16, 7, 8.5),
(17, 7, 6.5),
(18, 7, 9.0),
(19, 7, 7.0),
(20, 7, 8.5),
(21, 7, 7.5);

-- Software Engineering (Course ID: 8)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(11, 8, 8.0),
(12, 8, 7.5),
(13, 8, 9.5),
(14, 8, 8.5),
(15, 8, 7.0),
(16, 8, 8.0),
(17, 8, 7.5),
(18, 8, 9.0),
(19, 8, 6.5),
(20, 8, 8.5),
(21, 8, 7.0),
(22, 8, 8.0);

-- Software Quality and Testing (Course ID: 9)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(11, 9, 7.5),
(12, 9, 8.0),
(13, 9, 8.5),
(14, 9, 7.0),
(15, 9, 9.0),
(16, 9, 7.5),
(17, 9, 8.0),
(18, 9, 6.5),
(19, 9, 8.5),
(20, 9, 7.0),
(21, 9, 9.5),
(22, 9, 7.5),
(23, 9, 8.0);

-- Add some students to Databases (Course ID: 1) as well
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(11, 1, 8.0),
(12, 1, 7.5),
(13, 1, 9.0),
(14, 1, 8.5),
(15, 1, 7.0),
(16, 1, 8.5),
(17, 1, 6.5),
(18, 1, 9.0),
(19, 1, 7.5),
(20, 1, 8.0);
"@

Write-Host "📊 Adding enrollments for students 11-30..." -ForegroundColor Yellow

# Execute the SQL commands
try {
    sqlcmd -S $server -d $database -U $username -P $password -Q $sqlCommands
    Write-Host "✅ Enrollments added successfully!" -ForegroundColor Green
    
    # Show summary
    $summaryQuery = @"
SELECT 'Total Enrollments:' as Info, COUNT(*) as Count FROM Enrollments_Table_1
UNION ALL
SELECT 'Unique Students Enrolled:', COUNT(DISTINCT student_id) FROM Enrollments_Table_1
UNION ALL
SELECT 'Courses with Enrollments:', COUNT(DISTINCT course_id) FROM Enrollments_Table_1;
"@
    
    Write-Host "📈 Summary:" -ForegroundColor Cyan
    sqlcmd -S $server -d $database -U $username -P $password -Q $summaryQuery
    
} catch {
    Write-Host "❌ Error adding enrollments: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "💡 Try running this script in Azure Cloud Shell or install sqlcmd locally" -ForegroundColor Yellow
}
