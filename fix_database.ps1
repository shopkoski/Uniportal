# PowerShell script to fix Azure SQL Database data
# Run this in Azure Cloud Shell or Windows PowerShell with sqlcmd

Write-Host "🔧 Fixing Database Data..." -ForegroundColor Green

# Connection details
$server = "uniportal-sql-server.database.windows.net"
$database = "uniportal-db"
$username = "sqladmin"
$password = "Admin123"

# SQL commands to update the data
$sqlCommands = @"
-- Update Students with proper emails
UPDATE Students_Table_1 SET email = 'ana@student.uniportal.com' WHERE student_id = 1;
UPDATE Students_Table_1 SET email = 'ivan@student.uniportal.com' WHERE student_id = 2;
UPDATE Students_Table_1 SET email = 'elena@student.uniportal.com' WHERE student_id = 3;
UPDATE Students_Table_1 SET email = 'marko@student.uniportal.com' WHERE student_id = 4;
UPDATE Students_Table_1 SET email = 'sara@student.uniportal.com' WHERE student_id = 5;
UPDATE Students_Table_1 SET email = 'nikola@student.uniportal.com' WHERE student_id = 6;
UPDATE Students_Table_1 SET email = 'marija@student.uniportal.com' WHERE student_id = 7;
UPDATE Students_Table_1 SET email = 'jovan@student.uniportal.com' WHERE student_id = 8;
UPDATE Students_Table_1 SET email = 'teodora@student.uniportal.com' WHERE student_id = 9;
UPDATE Students_Table_1 SET email = 'petar@student.uniportal.com' WHERE student_id = 10;
UPDATE Students_Table_1 SET email = 'mike@student.uniportal.com' WHERE student_id = 11;
UPDATE Students_Table_1 SET email = 'john@student.uniportal.com' WHERE student_id = 12;
UPDATE Students_Table_1 SET email = 'jane@student.uniportal.com' WHERE student_id = 13;
UPDATE Students_Table_1 SET email = 'test@student.uniportal.com' WHERE student_id = 14;
UPDATE Students_Table_1 SET email = 'sopkoski@uniportal.com' WHERE student_id = 15;

-- Update Professors with proper emails and departments
UPDATE Professors_Table_1 SET email = 'k.stefanovska@univ.mk', department = 'Computer Science' WHERE professor_id = 1;
UPDATE Professors_Table_1 SET email = 'd.poposki@univ.mk', department = 'Software Engineering' WHERE professor_id = 2;
UPDATE Professors_Table_1 SET email = 's.tasevska@univ.mk', department = 'Mathematics' WHERE professor_id = 3;
UPDATE Professors_Table_1 SET email = 'a.ilievski@univ.mk', department = 'Networks' WHERE professor_id = 4;
UPDATE Professors_Table_1 SET email = 'j.ristova@univ.mk', department = 'QA & Testing' WHERE professor_id = 5;
"@

Write-Host "📊 Updating student and professor emails..." -ForegroundColor Yellow

# Execute the SQL commands
try {
    sqlcmd -S $server -d $database -U $username -P $password -Q $sqlCommands
    Write-Host "✅ Database fix completed!" -ForegroundColor Green
    Write-Host "🎯 All emails should now be properly connected in the database" -ForegroundColor Cyan
} catch {
    Write-Host "❌ Error updating database: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "💡 Try running this script in Azure Cloud Shell or install sqlcmd locally" -ForegroundColor Yellow
}
