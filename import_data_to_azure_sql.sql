-- Import data to Azure SQL Database
-- Run this after creating the tables

-- Import Students (removing duplicates and using IDENTITY)
INSERT INTO Students_Table_1 (first_name, last_name, email, enrollment_year)
SELECT DISTINCT first_name, last_name, email, enrollment_year
FROM (
    VALUES 
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
    ('Goce','Shopkoski','sopkoski@uniportal.com',2022),
    ('test','test','test@gmail.com',2022),
    ('Test','Test','test@uniplatfom.com',2022),
    ('test','test','test@uniportal.com',2024),
    ('Test','Test','testTEST@uniportal.com',2025),
    ('Test','Testing','testTEST@student.uniportal.com',2025),
    ('Testing','Test','studnet@uniportal.com',2023),
    ('Students','Test','studenttest@uniportal.com',2023),
    ('Testing','Student','mail@student.uniportal.com',2024),
    ('Goce','Testing','testingG@student.uniportal.com',2023),
    ('Goce','Raboti','raboti@uniportal.com',2023),
    ('Goce','TESTIRA','testiram@uniportal.com',2023),
    ('test','Goce','rab@mail.com',2025),
    ('stefan','uchi','uchi@mail.com',2024),
    ('aron ','student','aron@student.com',2022),
    ('pay','pal','peying@stydent.com',2025),
    ('goce','PLAY','shopko@gmail.com',2024)
) AS Students(first_name, last_name, email, enrollment_year);

-- Import Courses
INSERT INTO Courses_Table_1 (course_name, credits)
SELECT DISTINCT course_name, credits
FROM (
    VALUES 
    ('Databases',6),
    ('Web Development',6),
    ('Algorithms',6),
    ('Computer Networks',6),
    ('Calculus',6),
    ('Operating systems',6),
    ('Structural programming',6),
    ('Software engineering',6),
    ('Software quality and testing',6),
    ('Test Course',4)
) AS Courses(course_name, credits);

-- Import Professors
INSERT INTO Professors_Table_1 (first_name, last_name, email, department)
SELECT DISTINCT first_name, last_name, email, department
FROM (
    VALUES 
    ('Kristina','Stefanovska','k.stefanovska@univ.mk','Computer Science'),
    ('Darko','Poposki','d.poposki@univ.mk','Software Engineering'),
    ('Simona','Tasevska','s.tasevska@univ.mk','Mathematics'),
    ('Aleksandar','Ilievski','a.ilievski@univ.mk','Networks'),
    ('Jovana','Ristova','j.ristova@univ.mk','QA & Testing'),
    ('Prof','Professor','prof@uniportal.com','Software Engineering'),
    ('Prof','Professor','proff@uniportal.com','Software Engineering'),
    ('luca','stefano','luca@uniportal.com','Software Engineering'),
    ('Heath','Warming','heath@uniportal.com','Mathematics'),
    ('Stefano','Luca','stefano@uniportal.com','Mathematics'),
    ('stef','stefano','stef@uniportal.com','Networks'),
    ('test','prof','test@uniportal.com','Networks'),
    ('test2','prof2','test2@uniportal.com','Networks'),
    ('testingq','test','test@mail.com','Mathematics')
) AS Professors(first_name, last_name, email, department);

-- Import Enrollments (sample data - you may need to adjust based on your actual data)
INSERT INTO Enrollments_Table_1 (student_id, course_id, grade)
VALUES 
(1,1,8),(2,1,9),(3,1,10),(4,1,6),(1,2,9),(2,2,10),(3,2,6),(5,2,8),
(1,3,7),(8,3,9),(5,3,9),(2,4,8),(9,4,8),(6,4,8),(3,5,9),(10,5,9),
(7,5,9),(4,6,8),(1,6,8),(8,6,7),(5,7,9),(2,7,9),(9,7,8),(6,8,7),
(3,8,7),(10,8,9),(7,9,8),(4,9,8),(1,9,8);
