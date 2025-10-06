-- QUICK DATABASE FIX - Run this in Azure SQL Database Query Editor
-- This will fix all the email and department data

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

-- Verify the data
SELECT 'Students with emails:' as Info;
SELECT student_id, first_name, last_name, email, enrollment_year FROM Students_Table_1 ORDER BY student_id;

SELECT 'Professors with emails:' as Info;
SELECT professor_id, first_name, last_name, email, department FROM Professors_Table_1 ORDER BY professor_id;
