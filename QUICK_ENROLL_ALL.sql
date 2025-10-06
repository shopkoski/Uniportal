-- QUICK ENROLL ALL STUDENTS - Run this in Azure Portal Query Editor
-- This will give EVERY student grades to display

-- Enroll students 1-10 in multiple courses
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
-- Databases (Course 1)
(1, 1, 8.0), (2, 1, 9.0), (3, 1, 10.0), (4, 1, 6.0), (5, 1, 8.5),
(6, 1, 7.5), (7, 1, 9.5), (8, 1, 8.0), (9, 1, 7.0), (10, 1, 8.5),
-- Web Development (Course 2)
(1, 2, 9.0), (2, 2, 10.0), (3, 2, 6.0), (4, 2, 8.0), (5, 2, 7.5),
(6, 2, 9.0), (7, 2, 8.5), (8, 2, 7.0), (9, 2, 8.5), (10, 2, 9.0),
-- Algorithms (Course 3)
(1, 3, 7.0), (2, 3, 8.5), (3, 3, 9.0), (4, 3, 6.5), (5, 3, 8.0),
(6, 3, 7.5), (7, 3, 9.5), (8, 3, 8.0), (9, 3, 7.0), (10, 3, 8.5),
-- Computer Networks (Course 4)
(1, 4, 8.5), (2, 4, 7.0), (3, 4, 9.0), (4, 4, 6.0), (5, 4, 8.0),
(6, 4, 7.5), (7, 4, 9.0), (8, 4, 8.5), (9, 4, 7.0), (10, 4, 8.0),
-- Calculus (Course 5)
(1, 5, 7.5), (2, 5, 8.0), (3, 5, 9.5), (4, 5, 6.5), (5, 5, 8.5),
(6, 5, 7.0), (7, 5, 9.0), (8, 5, 8.0), (9, 5, 7.5), (10, 5, 8.5);

-- Enroll students 11-20 in multiple courses
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
-- Databases (Course 1)
(11, 1, 8.5), (12, 1, 7.0), (13, 1, 9.0), (14, 1, 6.5), (15, 1, 8.0),
(16, 1, 7.5), (17, 1, 9.5), (18, 1, 8.0), (19, 1, 7.0), (20, 1, 8.5),
-- Web Development (Course 2)
(11, 2, 7.5), (12, 2, 8.0), (13, 2, 9.5), (14, 2, 6.0), (15, 2, 8.5),
(16, 2, 7.0), (17, 2, 9.0), (18, 2, 8.5), (19, 2, 7.5), (20, 2, 8.0),
-- Algorithms (Course 3)
(11, 3, 8.0), (12, 3, 7.5), (13, 3, 9.0), (14, 3, 6.5), (15, 3, 8.5),
(16, 3, 7.0), (17, 3, 9.5), (18, 3, 8.0), (19, 3, 7.5), (20, 3, 8.5),
-- Operating Systems (Course 6)
(11, 6, 8.0), (12, 6, 7.5), (13, 6, 9.0), (14, 6, 6.0), (15, 6, 8.5),
(16, 6, 7.0), (17, 6, 9.5), (18, 6, 8.0), (19, 6, 7.5), (20, 6, 8.5);

-- Enroll students 21-30 in multiple courses
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
-- Databases (Course 1)
(21, 1, 8.0), (22, 1, 7.5), (23, 1, 9.0), (24, 1, 6.0), (25, 1, 8.5),
(26, 1, 7.0), (27, 1, 9.5), (28, 1, 8.0), (29, 1, 7.5), (30, 1, 8.5),
-- Web Development (Course 2)
(21, 2, 7.5), (22, 2, 8.5), (23, 2, 9.0), (24, 2, 6.5), (25, 2, 8.0),
(26, 2, 7.0), (27, 2, 9.5), (28, 2, 8.5), (29, 2, 7.0), (30, 2, 8.0),
-- Algorithms (Course 3)
(21, 3, 8.5), (22, 3, 7.0), (23, 3, 9.5), (24, 3, 6.0), (25, 3, 8.0),
(26, 3, 7.5), (27, 3, 9.0), (28, 3, 8.5), (29, 3, 7.0), (30, 3, 8.0),
-- Software Engineering (Course 8)
(21, 8, 8.0), (22, 8, 7.5), (23, 8, 9.0), (24, 8, 6.0), (25, 8, 8.5),
(26, 8, 7.0), (27, 8, 9.5), (28, 8, 8.0), (29, 8, 7.5), (30, 8, 8.5);

-- Show summary
SELECT 'Total Enrollments:' as Metric, COUNT(*) as Count FROM Enrollments_Table_1
UNION ALL
SELECT 'Unique Students Enrolled:', COUNT(DISTINCT student_id) FROM Enrollments_Table_1
UNION ALL
SELECT 'Courses with Enrollments:', COUNT(DISTINCT course_id) FROM Enrollments_Table_1
UNION ALL
SELECT 'Average Grade:', CAST(AVG(CAST(grade AS FLOAT)) AS DECIMAL(4,2)) FROM Enrollments_Table_1;
