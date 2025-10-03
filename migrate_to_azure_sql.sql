-- Migration script to create tables and migrate data from Railway MySQL to Azure SQL
-- Run this script in Azure SQL Database

-- Create Students_Table_1
CREATE TABLE Students_Table_1 (
    student_id INT IDENTITY(1,1) PRIMARY KEY,
    first_name NVARCHAR(100) NOT NULL,
    last_name NVARCHAR(100) NOT NULL,
    email NVARCHAR(255) NOT NULL,
    enrollment_year INT
);

-- Create Courses_Table_1
CREATE TABLE Courses_Table_1 (
    course_id INT IDENTITY(1,1) PRIMARY KEY,
    course_name NVARCHAR(255) NOT NULL,
    credits INT,
    _2 NVARCHAR(255) NULL
);

-- Create Professors_Table_1
CREATE TABLE Professors_Table_1 (
    professor_id INT IDENTITY(1,1) PRIMARY KEY,
    first_name NVARCHAR(100) NOT NULL,
    last_name NVARCHAR(100) NOT NULL,
    email NVARCHAR(255) NOT NULL,
    department NVARCHAR(255)
);

-- Create Enrollments_Table_1
CREATE TABLE Enrollments_Table_1 (
    enrollment_id INT IDENTITY(1,1) PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    grade DECIMAL(5,2),
    FOREIGN KEY (student_id) REFERENCES Students_Table_1(student_id),
    FOREIGN KEY (course_id) REFERENCES Courses_Table_1(course_id)
);

-- Note: You'll need to insert your data using the exported SQL file
-- The INSERT statements from your Railway export will work with minor modifications
