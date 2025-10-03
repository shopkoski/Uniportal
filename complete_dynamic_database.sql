-- Complete Dynamic Database Schema for UniPortal
-- This creates a fully functional, dynamic database with all relationships

-- Drop existing tables if they exist (in correct order due to foreign keys)
IF EXISTS (SELECT * FROM sysobjects WHERE name='Enrollments_Table_1' AND xtype='U')
    DROP TABLE Enrollments_Table_1;

IF EXISTS (SELECT * FROM sysobjects WHERE name='Students_Table_1' AND xtype='U')
    DROP TABLE Students_Table_1;

IF EXISTS (SELECT * FROM sysobjects WHERE name='Courses_Table_1' AND xtype='U')
    DROP TABLE Courses_Table_1;

IF EXISTS (SELECT * FROM sysobjects WHERE name='Professors_Table_1' AND xtype='U')
    DROP TABLE Professors_Table_1;

-- Create Students table with proper constraints
CREATE TABLE Students_Table_1 (
    student_id INT IDENTITY(1,1) PRIMARY KEY,
    first_name NVARCHAR(100) NOT NULL,
    last_name NVARCHAR(100) NOT NULL,
    email NVARCHAR(255) NOT NULL UNIQUE,
    enrollment_year INT NOT NULL,
    created_at DATETIME2 DEFAULT GETDATE(),
    updated_at DATETIME2 DEFAULT GETDATE()
);

-- Create Professors table with proper constraints
CREATE TABLE Professors_Table_1 (
    professor_id INT IDENTITY(1,1) PRIMARY KEY,
    first_name NVARCHAR(100) NOT NULL,
    last_name NVARCHAR(100) NOT NULL,
    email NVARCHAR(255) NOT NULL UNIQUE,
    department NVARCHAR(255) NOT NULL,
    created_at DATETIME2 DEFAULT GETDATE(),
    updated_at DATETIME2 DEFAULT GETDATE()
);

-- Create Courses table with proper constraints
CREATE TABLE Courses_Table_1 (
    course_id INT IDENTITY(1,1) PRIMARY KEY,
    course_name NVARCHAR(255) NOT NULL,
    credits INT NOT NULL CHECK (credits > 0),
    professor_id INT NULL, -- Optional professor assignment
    created_at DATETIME2 DEFAULT GETDATE(),
    updated_at DATETIME2 DEFAULT GETDATE(),
    FOREIGN KEY (professor_id) REFERENCES Professors_Table_1(professor_id) ON DELETE SET NULL
);

-- Create Enrollments table with proper relationships
CREATE TABLE Enrollments_Table_1 (
    enrollment_id INT IDENTITY(1,1) PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    grade DECIMAL(5,2) NULL CHECK (grade >= 0 AND grade <= 100),
    enrolled_at DATETIME2 DEFAULT GETDATE(),
    updated_at DATETIME2 DEFAULT GETDATE(),
    FOREIGN KEY (student_id) REFERENCES Students_Table_1(student_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES Courses_Table_1(course_id) ON DELETE CASCADE,
    UNIQUE(student_id, course_id) -- Prevent duplicate enrollments
);

-- Create indexes for better performance
CREATE INDEX IX_Students_Email ON Students_Table_1(email);
CREATE INDEX IX_Professors_Email ON Professors_Table_1(email);
CREATE INDEX IX_Enrollments_Student ON Enrollments_Table_1(student_id);
CREATE INDEX IX_Enrollments_Course ON Enrollments_Table_1(course_id);

-- Insert your actual data
INSERT INTO Students_Table_1 (first_name, last_name, email, enrollment_year) VALUES
('Ana','Petrova','ana@student.uniportal.com',2022),
('Ivan','Stojanov','ivan@student.uniportal.com',2023),
('Elena','Markovska','elena@student.uniportal.com',2022),
('Marko','Milosevski','marko@student.uniportal.com',2023),
('Sara','Spasovska','sara@student.uniportal.com',2022),
('Nikola','Nikoloski','nikola@student.uniportal.com',2021),
('Marija','Stanojkovska','marija@student.uniportal.com',2021),
('Jovan','Ivanov','jovan@student.uniportal.com',2023),
('Teodora','Kostadinova','teodora@student.uniportal.com',2022),
('Petar','Ristov','petar@student.uniportal.com',2023),
('Mike','Johnson','mike@student.uniportal.com',2023),
('John','Doe','john@student.uniportal.com',2022),
('Jane','Smith','jane@student.uniportal.com',2022),
('Test','Student','test@student.uniportal.com',2024),
('Goce','Shopkoski','sopkoski@uniportal.com',2022);

INSERT INTO Professors_Table_1 (first_name, last_name, email, department) VALUES
('Kristina','Stefanovska','k.stefanovska@univ.mk','Computer Science'),
('Darko','Poposki','d.poposki@univ.mk','Software Engineering'),
('Simona','Tasevska','s.tasevska@univ.mk','Mathematics'),
('Aleksandar','Ilievski','a.ilievski@univ.mk','Networks'),
('Jovana','Ristova','j.ristova@univ.mk','QA & Testing');

INSERT INTO Courses_Table_1 (course_name, credits, professor_id) VALUES
('Databases',6,1),
('Web Development',6,2),
('Algorithms',6,1),
('Computer Networks',6,4),
('Calculus',6,3),
('Operating systems',6,1),
('Structural programming',6,2),
('Software engineering',6,2),
('Software quality and testing',6,5),
('Test Course',4,1);

INSERT INTO Enrollments_Table_1 (student_id, course_id, grade) VALUES
(1,1,8),(2,1,9),(3,1,10),(4,1,6),(1,2,9),(2,2,10),(3,2,6),(5,2,8),
(1,3,7),(8,3,9),(5,3,9),(2,4,8),(9,4,8),(6,4,8),(3,5,9),(10,5,9),
(7,5,9),(4,6,8),(1,6,8),(8,6,7),(5,7,9),(2,7,9),(9,7,8),(6,8,7),
(3,8,7),(10,8,9),(7,9,8),(4,9,8),(1,9,8);

-- Create views for easy data access
CREATE VIEW StudentCourseView AS
SELECT 
    s.student_id,
    s.first_name + ' ' + s.last_name as student_name,
    s.email as student_email,
    c.course_id,
    c.course_name,
    c.credits,
    p.first_name + ' ' + p.last_name as professor_name,
    e.grade,
    e.enrolled_at
FROM Students_Table_1 s
JOIN Enrollments_Table_1 e ON s.student_id = e.student_id
JOIN Courses_Table_1 c ON e.course_id = c.course_id
LEFT JOIN Professors_Table_1 p ON c.professor_id = p.professor_id;

-- Create stored procedures for dynamic operations
CREATE PROCEDURE AddStudent
    @first_name NVARCHAR(100),
    @last_name NVARCHAR(100),
    @email NVARCHAR(255),
    @enrollment_year INT
AS
BEGIN
    INSERT INTO Students_Table_1 (first_name, last_name, email, enrollment_year)
    VALUES (@first_name, @last_name, @email, @enrollment_year);
    SELECT SCOPE_IDENTITY() as student_id;
END;

CREATE PROCEDURE AddCourse
    @course_name NVARCHAR(255),
    @credits INT,
    @professor_id INT = NULL
AS
BEGIN
    INSERT INTO Courses_Table_1 (course_name, credits, professor_id)
    VALUES (@course_name, @credits, @professor_id);
    SELECT SCOPE_IDENTITY() as course_id;
END;

CREATE PROCEDURE EnrollStudent
    @student_id INT,
    @course_id INT
AS
BEGIN
    IF NOT EXISTS (SELECT 1 FROM Enrollments_Table_1 WHERE student_id = @student_id AND course_id = @course_id)
    BEGIN
        INSERT INTO Enrollments_Table_1 (student_id, course_id)
        VALUES (@student_id, @course_id);
        SELECT 'Enrollment successful' as message;
    END
    ELSE
    BEGIN
        SELECT 'Student already enrolled in this course' as message;
    END
END;
