-- Enroll John Doe (student_id: 12) in some courses so he has grades to display

INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(12, 1, 8.5),  -- Databases
(12, 2, 7.0),  -- Web Development  
(12, 3, 9.0),  -- Algorithms
(12, 5, 8.0),  -- Calculus
(12, 6, 7.5);  -- Operating Systems

-- Verify John's enrollments
SELECT 'John Doe Enrollments:' as Info;
SELECT 
    s.first_name + ' ' + s.last_name as student_name,
    c.course_name,
    e.grade
FROM Enrollments_Table_1 e
JOIN Students_Table_1 s ON e.student_id = s.student_id
JOIN Courses_Table_1 c ON e.course_id = c.course_id
WHERE s.student_id = 12;
