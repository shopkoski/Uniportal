-- Setup script for Azure SQL Database
-- This creates the tables and imports your data

-- Create Students_Table_1
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='Students_Table_1' AND xtype='U')
CREATE TABLE Students_Table_1 (
    student_id INT IDENTITY(1,1) PRIMARY KEY,
    first_name NVARCHAR(100) NOT NULL,
    last_name NVARCHAR(100) NOT NULL,
    email NVARCHAR(255) NOT NULL,
    enrollment_year INT
);

-- Create Courses_Table_1
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='Courses_Table_1' AND xtype='U')
CREATE TABLE Courses_Table_1 (
    course_id INT IDENTITY(1,1) PRIMARY KEY,
    course_name NVARCHAR(255) NOT NULL,
    credits INT,
    _2 NVARCHAR(255) NULL
);

-- Create Professors_Table_1
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='Professors_Table_1' AND xtype='U')
CREATE TABLE Professors_Table_1 (
    professor_id INT IDENTITY(1,1) PRIMARY KEY,
    first_name NVARCHAR(100) NOT NULL,
    last_name NVARCHAR(100) NOT NULL,
    email NVARCHAR(255) NOT NULL,
    department NVARCHAR(255)
);

-- Create Enrollments_Table_1
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='Enrollments_Table_1' AND xtype='U')
CREATE TABLE Enrollments_Table_1 (
    enrollment_id INT IDENTITY(1,1) PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    grade DECIMAL(5,2)
);

-- Note: Data will be imported from your exported SQL file
