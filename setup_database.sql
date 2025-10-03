-- Create tables for UniPortal system
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    student_id INT,
    professor_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    enrollment_year INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Professors (
    professor_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    department VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(200) NOT NULL,
    credits INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    grade DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES Students(student_id),
    FOREIGN KEY (course_id) REFERENCES Courses(course_id)
);

-- Insert sample data
INSERT INTO Users (user_id, username, password, role, student_id, professor_id) VALUES
(1, 'admin@uniportal.com', 'admin123', 'admin', NULL, NULL),
(2, 'john@student.uniportal.com', 'admin123', 'student', 1, NULL),
(3, 'jane@student.uniportal.com', 'admin123', 'student', 2, NULL),
(4, 'k.stefanovska@univ.mk', 'admin123', 'professor', NULL, 1);

INSERT INTO Students (student_id, first_name, last_name, email, enrollment_year) VALUES
(1, 'John', 'Doe', 'john@student.uniportal.com', 2022),
(2, 'Jane', 'Smith', 'jane@student.uniportal.com', 2022);

INSERT INTO Professors (professor_id, first_name, last_name, email, department) VALUES
(1, 'Kristina', 'Stefanovska', 'k.stefanovska@univ.mk', 'Computer Science');

INSERT INTO Courses (course_id, course_name, credits) VALUES
(1, 'Databases', 6),
(2, 'Web Development', 6);

INSERT INTO Enrollments (enrollment_id, student_id, course_id, grade) VALUES
(1, 1, 1, 8.0),
(2, 2, 1, 9.0);

-- Show tables to verify
SHOW TABLES;
SELECT * FROM Users;
SELECT * FROM Students;
SELECT * FROM Professors;
SELECT * FROM Courses;
SELECT * FROM Enrollments;
