-- Create tables and upload CSV data to Railway MySQL
-- Run this script in Railway MySQL database

-- Create Users table
CREATE TABLE IF NOT EXISTS Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    student_id INT,
    professor_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Students table
CREATE TABLE IF NOT EXISTS Students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    enrollment_year INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Professors table
CREATE TABLE IF NOT EXISTS Professors (
    professor_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    department VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Courses table
CREATE TABLE IF NOT EXISTS Courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(200) NOT NULL,
    credits INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Enrollments table
CREATE TABLE IF NOT EXISTS Enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    grade DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES Students(student_id),
    FOREIGN KEY (course_id) REFERENCES Courses(course_id)
);

-- Insert Users data
INSERT INTO Users (user_id, username, password, role, student_id, professor_id) VALUES
(1, 'admin@uniportal.com', 'admin123', 'admin', NULL, NULL),
(2, 'ana@student.uniportal.com', 'admin123', 'student', 1, NULL),
(3, 'ivan@student.uniportal.com', 'admin123', 'student', 2, NULL),
(4, 'elena@student.uniportal.com', 'admin123', 'student', 3, NULL),
(5, 'marko@student.uniportal.com', 'admin123', 'student', 4, NULL),
(6, 'sara@student.uniportal.com', 'admin123', 'student', 5, NULL),
(7, 'nikola@student.uniportal.com', 'admin123', 'student', 6, NULL),
(8, 'marija@student.uniportal.com', 'admin123', 'student', 7, NULL),
(9, 'jovan@student.uniportal.com', 'admin123', 'student', 8, NULL),
(10, 'teodora@student.uniportal.com', 'admin123', 'student', 9, NULL),
(11, 'petar@student.uniportal.com', 'admin123', 'student', 10, NULL),
(12, 'mike@student.uniportal.com', 'admin123', 'student', 11, NULL),
(13, 'john@student.uniportal.com', 'admin123', 'student', 12, NULL),
(14, 'jane@student.uniportal.com', 'admin123', 'student', 13, NULL),
(15, 'test@student.uniportal.com', 'admin123', 'student', 14, NULL),
(16, 'sopkoski@uniportal.com', 'admin123', 'student', 15, NULL),
(17, 'test@gmail.com', 'admin123', 'student', 16, NULL),
(18, 'test@uniplatfom.com', 'admin123', 'student', 17, NULL),
(19, 'test@uniportal.com', 'admin123', 'student', 18, NULL),
(20, 'testTEST@uniportal.com', 'admin123', 'student', 19, NULL),
(21, 'testTEST@student.uniportal.com', 'admin123', 'student', 20, NULL),
(22, 'studnet@uniportal.com', 'admin123', 'student', 21, NULL),
(23, 'studenttest@uniportal.com', 'admin123', 'student', 22, NULL),
(24, 'mail@student.uniportal.com', 'admin123', 'student', 23, NULL),
(25, 'testingG@student.uniportal.com', 'admin123', 'student', 24, NULL),
(26, 'raboti@uniportal.com', 'admin123', 'student', 25, NULL),
(27, 'testiram@uniportal.com', 'admin123', 'student', 26, NULL),
(28, 'rab@mail.com', 'admin123', 'student', 27, NULL),
(29, 'uchi@mail.com', 'admin123', 'student', 28, NULL),
(30, 'aron@student.com', 'admin123', 'student', 29, NULL),
(31, 'peying@stydent.com', 'admin123', 'student', 30, NULL),
(32, 'shopko@gmail.com', 'admin123', 'student', 31, NULL),
(33, 'k.stefanovska@univ.mk', 'admin123', 'professor', NULL, 1),
(34, 'd.poposki@univ.mk', 'admin123', 'professor', NULL, 2),
(35, 's.tasevska@univ.mk', 'admin123', 'professor', NULL, 3),
(36, 'a.ilievski@univ.mk', 'admin123', 'professor', NULL, 4),
(37, 'j.ristova@univ.mk', 'admin123', 'professor', NULL, 5),
(38, 't.professor@univ.mk', 'admin123', 'professor', NULL, 6),
(39, 't.professor@univ.mk', 'admin123', 'professor', NULL, 7),
(40, 't.professor2@univ.mk', 'admin123', 'professor', NULL, 8),
(41, 't.professor3@univ.mk', 'admin123', 'professor', NULL, 9),
(42, 'p.professor@univ.mk', 'admin123', 'professor', NULL, 10);

-- Insert Students data
INSERT INTO Students (student_id, first_name, last_name, email, enrollment_year) VALUES
(1, 'Ana', 'Petrova', 'ana@student.uniportal.com', 2022),
(2, 'Ivan', 'Stojanov', 'ivan@student.uniportal.com', 2023),
(3, 'Elena', 'Markovska', 'elena@student.uniportal.com', 2022),
(4, 'Marko', 'Milosevski', 'marko@student.uniportal.com', 2023),
(5, 'Sara', 'Spasovska', 'sara@student.uniportal.com', 2022),
(6, 'Nikola', 'Nikoloski', 'nikola@student.uniportal.com', 2021),
(7, 'Marija', 'Stanojkovska', 'marija@student.uniportal.com', 2021),
(8, 'Jovan', 'Ivanov', 'jovan@student.uniportal.com', 2023),
(9, 'Teodora', 'Kostadinova', 'teodora@student.uniportal.com', 2022),
(10, 'Petar', 'Ristov', 'petar@student.uniportal.com', 2023),
(11, 'Mike', 'Johnson', 'mike@student.uniportal.com', 2023),
(12, 'John', 'Doe', 'john@student.uniportal.com', 2022),
(13, 'Jane', 'Smith', 'jane@student.uniportal.com', 2022),
(14, 'Test', 'Student', 'test@student.uniportal.com', 2024),
(15, 'Goce', 'Shopkoski', 'sopkoski@uniportal.com', 2022),
(16, 'test', 'test', 'test@gmail.com', 2022),
(17, 'Test', 'Test', 'test@uniplatfom.com', 2022),
(18, 'test', 'test', 'test@uniportal.com', 2024),
(19, 'Test', 'Test', 'testTEST@uniportal.com', 2025),
(20, 'Test', 'Testing', 'testTEST@student.uniportal.com', 2025),
(21, 'Testing', 'Test', 'studnet@uniportal.com', 2023),
(22, 'Students', 'Test', 'studenttest@uniportal.com', 2023),
(23, 'Testing', 'Student', 'mail@student.uniportal.com', 2024),
(24, 'Goce', 'Testing', 'testingG@student.uniportal.com', 2023),
(25, 'Goce', 'Raboti', 'raboti@uniportal.com', 2023),
(26, 'Goce', 'TESTIRA', 'testiram@uniportal.com', 2023),
(27, 'test', 'Goce', 'rab@mail.com', 2025),
(28, 'stefan', 'uchi', 'uchi@mail.com', 2024),
(29, 'aron', 'student', 'aron@student.com', 2022),
(30, 'pay', 'pal', 'peying@stydent.com', 2025),
(31, 'goce', 'PLAY', 'shopko@gmail.com', 2024);

-- Insert Professors data
INSERT INTO Professors (professor_id, first_name, last_name, email, department) VALUES
(1, 'Kristina', 'Stefanovska', 'k.stefanovska@univ.mk', 'Computer Science'),
(2, 'Darko', 'Poposki', 'd.poposki@univ.mk', 'Software Engineering'),
(3, 'Simona', 'Tasevska', 's.tasevska@univ.mk', 'Mathematics'),
(4, 'Aleksandar', 'Ilievski', 'a.ilievski@univ.mk', 'Networks'),
(5, 'Jovana', 'Ristova', 'j.ristova@univ.mk', 'QA & Testing'),
(6, 'luca', 'stefano', 'luca@uniportal.com', 'Software Engineering'),
(7, 'Heath', 'Warming', 'heath@uniportal.com', 'Mathematics'),
(8, 'Stefano', 'Luca', 'stefano@uniportal.com', 'Mathematics'),
(9, 'stef', 'stefano', 'stef@uniportal.com', 'Networks'),
(10, 'test', 'prof', 'test@uniportal.com', 'Networks');

-- Insert Courses data
INSERT INTO Courses (course_id, course_name, credits) VALUES
(1, 'Databases', 6),
(2, 'Web Development', 6),
(3, 'Algorithms', 6),
(4, 'Computer Networks', 6),
(5, 'Calculus', 6),
(6, 'Operating systems', 6),
(7, 'Structural programming', 6),
(8, 'Software engineering', 6),
(9, 'Software quality and testing', 6),
(10, 'Test Course', 4);

-- Insert Enrollments data
INSERT INTO Enrollments (enrollment_id, student_id, course_id, grade) VALUES
(1, 1, 1, 8.0),
(2, 2, 1, 9.0),
(3, 3, 1, 10.0),
(4, 4, 1, 6.0),
(5, 1, 2, 9.0),
(6, 2, 2, 10.0),
(7, 3, 2, 6.0),
(8, 5, 2, 8.0),
(9, 1, 3, 7.0),
(10, 8, 3, 9.0),
(11, 5, 3, 9.0),
(12, 2, 4, 8.0),
(13, 9, 4, 8.0),
(14, 6, 4, 8.0),
(15, 3, 5, 9.0),
(16, 10, 5, 9.0),
(17, 7, 5, 9.0),
(18, 4, 6, 8.0),
(19, 1, 6, 8.0),
(20, 8, 6, 7.0),
(21, 5, 7, 9.0),
(22, 2, 7, 9.0),
(23, 9, 7, 8.0),
(24, 6, 8, 7.0),
(25, 3, 8, 7.0),
(26, 10, 8, 9.0),
(27, 7, 9, 8.0),
(28, 4, 9, 8.0),
(29, 1, 9, 8.0),
(30, 11, 1, 9.0),
(31, 11, 2, 6.0),
(32, 11, 4, 6.0),
(33, 11, 6, 6.0),
(34, 11, 9, 7.0),
(35, 12, 1, 8.0),
(36, 12, 2, 7.0),
(37, 12, 4, 8.0),
(38, 12, 5, 6.0),
(39, 12, 7, 7.0),
(40, 13, 4, 8.0),
(41, 13, 5, 9.0),
(42, 13, 6, 10.0),
(43, 14, 1, 7.0),
(44, 8, 9, 5.0);

-- Show summary
SELECT 'Users' as table_name, COUNT(*) as record_count FROM Users
UNION ALL
SELECT 'Students', COUNT(*) FROM Students
UNION ALL
SELECT 'Professors', COUNT(*) FROM Professors
UNION ALL
SELECT 'Courses', COUNT(*) FROM Courses
UNION ALL
SELECT 'Enrollments', COUNT(*) FROM Enrollments;
