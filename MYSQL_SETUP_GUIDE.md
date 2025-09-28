# MySQL Database Setup Guide

## Step 1: Access Railway MySQL Database

1. Go to your Railway dashboard
2. Click on your MySQL database service
3. Click on "Data" tab
4. You should see an empty database

## Step 2: Create Tables

Copy and paste this SQL into the MySQL editor:

```sql
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
```

## Step 3: Insert Sample Data

After creating tables, run this to insert sample data:

```sql
-- Insert sample users
INSERT INTO Users (user_id, username, password, role, student_id, professor_id) VALUES
(1, 'admin@uniportal.com', 'admin123', 'admin', NULL, NULL),
(2, 'john@student.uniportal.com', 'admin123', 'student', 1, NULL),
(3, 'jane@student.uniportal.com', 'admin123', 'student', 2, NULL),
(4, 'k.stefanovska@univ.mk', 'admin123', 'professor', NULL, 1);

-- Insert sample students
INSERT INTO Students (student_id, first_name, last_name, email, enrollment_year) VALUES
(1, 'John', 'Doe', 'john@student.uniportal.com', 2022),
(2, 'Jane', 'Smith', 'jane@student.uniportal.com', 2022);

-- Insert sample professors
INSERT INTO Professors (professor_id, first_name, last_name, email, department) VALUES
(1, 'Kristina', 'Stefanovska', 'k.stefanovska@univ.mk', 'Computer Science');

-- Insert sample courses
INSERT INTO Courses (course_id, course_name, credits) VALUES
(1, 'Databases', 6),
(2, 'Web Development', 6);

-- Insert sample enrollments
INSERT INTO Enrollments (enrollment_id, student_id, course_id, grade) VALUES
(1, 1, 1, 8.0),
(2, 2, 1, 9.0);
```

## Step 4: Verify Data

Run this to check if data was inserted:

```sql
SELECT 'Users' as table_name, COUNT(*) as record_count FROM Users
UNION ALL
SELECT 'Students', COUNT(*) FROM Students
UNION ALL
SELECT 'Professors', COUNT(*) FROM Professors
UNION ALL
SELECT 'Courses', COUNT(*) FROM Courses
UNION ALL
SELECT 'Enrollments', COUNT(*) FROM Enrollments;
```

## Step 5: Test Backend

After setting up the database, test the backend:

```bash
curl https://uniportal-backend-production.up.railway.app/health
curl -X POST https://uniportal-backend-production.up.railway.app/api/auth/login \
     -H "Content-Type: application/json" \
     -d '{"email":"admin@uniportal.com","password":"admin123"}'
```
