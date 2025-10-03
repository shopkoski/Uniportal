-- Create Users table
CREATE TABLE IF NOT EXISTS Users (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(255) NOT NULL UNIQUE,
    PasswordHash TEXT NOT NULL,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    Role VARCHAR(50),
    CreatedAt DATETIME NOT NULL,
    IsActive BOOLEAN NOT NULL DEFAULT TRUE
);

-- Insert default users
INSERT IGNORE INTO Users (Id, Email, PasswordHash, FirstName, LastName, Role, CreatedAt, IsActive) VALUES
(1, 'admin@uniportal.com', '$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu', 'Admin', 'User', 'Admin', '2024-01-01 00:00:00', TRUE),
(2, 'john@student.uniportal.com', '$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu', 'John', 'Doe', 'Student', '2024-01-01 00:00:00', TRUE),
(3, 'jane@student.uniportal.com', '$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu', 'Jane', 'Smith', 'Student', '2024-01-01 00:00:00', TRUE),
(4, 'mike@student.uniportal.com', '$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu', 'Mike', 'Johnson', 'Student', '2024-01-01 00:00:00', TRUE),
(100, 'k.stefanovska@univ.mk', '$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu', 'Kristina', 'Stefanovska', 'Professor', '2024-01-01 00:00:00', TRUE);

-- Create other tables that your frontend expects
CREATE TABLE IF NOT EXISTS Students (
    StudentID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    DateOfBirth DATE,
    Major VARCHAR(100),
    YearOfStudy INT,
    GPA DECIMAL(3,2),
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Professors (
    ProfessorID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    Department VARCHAR(100),
    OfficeLocation VARCHAR(100),
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Courses (
    CourseID INT AUTO_INCREMENT PRIMARY KEY,
    CourseName VARCHAR(200) NOT NULL,
    CourseCode VARCHAR(20) NOT NULL,
    Credits INT,
    Department VARCHAR(100),
    ProfessorID INT,
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ProfessorID) REFERENCES Professors(ProfessorID)
);

CREATE TABLE IF NOT EXISTS Grades (
    GradeID INT AUTO_INCREMENT PRIMARY KEY,
    StudentID INT NOT NULL,
    CourseID INT NOT NULL,
    Grade DECIMAL(5,2),
    Semester VARCHAR(20),
    AcademicYear VARCHAR(10),
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (StudentID) REFERENCES Students(StudentID),
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID)
);

-- Insert sample data
INSERT IGNORE INTO Students (StudentID, FirstName, LastName, Email, DateOfBirth, Major, YearOfStudy, GPA) VALUES
(1, 'John', 'Doe', 'john@student.uniportal.com', '2000-01-15', 'Computer Science', 3, 3.75),
(2, 'Jane', 'Smith', 'jane@student.uniportal.com', '1999-05-22', 'Mathematics', 4, 3.90),
(3, 'Mike', 'Johnson', 'mike@student.uniportal.com', '2001-03-10', 'Physics', 2, 3.60);

INSERT IGNORE INTO Professors (ProfessorID, FirstName, LastName, Email, Department, OfficeLocation) VALUES
(1, 'Kristina', 'Stefanovska', 'k.stefanovska@univ.mk', 'Computer Science', 'CS-201'),
(2, 'Dr. Smith', 'Mathematics', 'smith@univ.mk', 'Mathematics', 'MATH-105'),
(3, 'Dr. Brown', 'Physics', 'brown@univ.mk', 'Physics', 'PHYS-301');

INSERT IGNORE INTO Courses (CourseID, CourseName, CourseCode, Credits, Department, ProfessorID) VALUES
(1, 'Introduction to Programming', 'CS101', 3, 'Computer Science', 1),
(2, 'Data Structures', 'CS201', 4, 'Computer Science', 1),
(3, 'Calculus I', 'MATH101', 4, 'Mathematics', 2),
(4, 'Physics I', 'PHYS101', 4, 'Physics', 3);

INSERT IGNORE INTO Grades (GradeID, StudentID, CourseID, Grade, Semester, AcademicYear) VALUES
(1, 1, 1, 85.5, 'Fall', '2024'),
(2, 1, 2, 92.0, 'Spring', '2024'),
(3, 2, 3, 88.5, 'Fall', '2024'),
(4, 3, 4, 79.0, 'Fall', '2024');
