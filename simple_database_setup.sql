-- Simple Database Setup for UniPortal
-- This creates a working database with your data

-- Drop existing tables if they exist
IF EXISTS (SELECT * FROM sysobjects WHERE name='Enrollments_Table_1' AND xtype='U')
    DROP TABLE Enrollments_Table_1;

IF EXISTS (SELECT * FROM sysobjects WHERE name='Students_Table_1' AND xtype='U')
    DROP TABLE Students_Table_1;

IF EXISTS (SELECT * FROM sysobjects WHERE name='Courses_Table_1' AND xtype='U')
    DROP TABLE Courses_Table_1;

IF EXISTS (SELECT * FROM sysobjects WHERE name='Professors_Table_1' AND xtype='U')
    DROP TABLE Professors_Table_1;

-- Create Students table
CREATE TABLE Students_Table_1 (
    student_id INT IDENTITY(1,1) PRIMARY KEY,
    first_name NVARCHAR(100) NOT NULL,
    last_name NVARCHAR(100) NOT NULL,
    email NVARCHAR(255) NOT NULL,
    enrollment_year INT
);

-- Create Professors table
CREATE TABLE Professors_Table_1 (
    professor_id INT IDENTITY(1,1) PRIMARY KEY,
    first_name NVARCHAR(100) NOT NULL,
    last_name NVARCHAR(100) NOT NULL,
    email NVARCHAR(255) NOT NULL,
    department NVARCHAR(255)
);

-- Create Courses table
CREATE TABLE Courses_Table_1 (
    course_id INT IDENTITY(1,1) PRIMARY KEY,
    course_name NVARCHAR(255) NOT NULL,
    credits INT,
    professor_id INT NULL
);

-- Create Enrollments table
CREATE TABLE Enrollments_Table_1 (
    enrollment_id INT IDENTITY(1,1) PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    grade DECIMAL(5,2) NULL
);

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

-- Test the data
SELECT 'Students' as TableName, COUNT(*) as RecordCount FROM Students_Table_1
UNION ALL
SELECT 'Professors', COUNT(*) FROM Professors_Table_1
UNION ALL
SELECT 'Courses', COUNT(*) FROM Courses_Table_1
UNION ALL
SELECT 'Enrollments', COUNT(*) FROM Enrollments_Table_1;
