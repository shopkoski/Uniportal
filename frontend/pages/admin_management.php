<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management - Uni Portal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 0 32px 20px 32px;
            font-size: 1rem;
            font-weight: bold;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .portal-text {
            font-size: 0.9rem;
            margin-left: 10px;
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 120px);
        }

        .sidebar nav a {
            color: white;
            text-decoration: none;
            padding: 12px 32px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .sidebar nav a:hover {
            background: rgba(255,255,255,0.1);
        }

        .sidebar nav a.active {
            background: rgba(255,255,255,0.2);
            border-left: 4px solid white;
        }

        .language-flags {
            display: flex;
            justify-content: flex-start;
            gap: 15px;
            padding: 12px 32px;
        }

        .language-flags button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .language-flags img {
            width: 18px;
            height: 14px;
            border-radius: 2px;
        }

        .logout-section {
            margin-top: auto;
            padding: 12px 32px;
        }

        .logout-btn {
            width: 100%;
            background: rgba(255,255,255,0.1);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 6px;
            padding: 10px 16px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            white-space: normal;
            line-height: 1.2;
            min-height: 44px;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
        }

        .page-title {
            font-size: 2rem;
            margin-bottom: 30px;
            color: #333;
        }

        .management-tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
        }

        .tab-button {
            padding: 12px 24px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-button.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        .tab-button:hover {
            color: #667eea;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        @media (max-width: 900px) {
            .main-content {
                padding: 24px 8px;
            }
            .sidebar {
                width: 60px;
                padding: 16px 0 0 0;
            }
            .sidebar .logo {
                font-size: 0.9rem;
                padding-left: 20px;
            }
            .portal-text {
                font-size: 0.9rem;
            }
            .sidebar nav a {
                padding: 10px 10px;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <div class="logo">
            <span>🎓</span> <span class="portal-text">Uni Portal</span>
        </div>
        <nav>
            <a href="home.php" class="nav-home">Home</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='student_page.php' : Auth.showLoginPopup(); return false;" class="nav-students">Students</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='courses_page.php' : Auth.showLoginPopup(); return false;" class="nav-courses">Courses</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='grades_page.php' : Auth.showLoginPopup(); return false;" class="nav-grades">Grades</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='professor_page.php' : Auth.showLoginPopup(); return false;" class="nav-professor">Professor</a>
            <a href="contact_page.php" class="nav-contact">Contact</a>
            <div class="language-flags">
                <button onclick="changeLanguage('mk')" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); cursor: pointer; padding: 4px 8px; border-radius: 4px; color: white; font-size: 12px;">
                    MK
                </button>
                <button onclick="changeLanguage('en')" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); cursor: pointer; padding: 4px 8px; border-radius: 4px; color: white; font-size: 12px;">
                    EN
                </button>
                <button onclick="changeLanguage('al')" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); cursor: pointer; padding: 4px 8px; border-radius: 4px; color: white; font-size: 12px;">
                    AL
                </button>
            </div>
            <div class="logout-section">
                <button onclick="Auth.logout()" class="logout-btn">
                    <span class="logout-text">Logout</span>
                </button>
            </div>
        </nav>
    </aside>
    <main class="main-content">
        <h1 class="page-title">Admin Management</h1>

        <div class="management-tabs">
            <button class="tab-button active" onclick="showTab('students')">Add Student</button>
            <button class="tab-button" onclick="showTab('courses')">Add Course</button>
            <button class="tab-button" onclick="showTab('grades')">Add Grade</button>
            <button class="tab-button" onclick="showTab('professors')">Add Professor</button>
        </div>

        <!-- Add Student Tab -->
        <div id="students" class="tab-content active">
            <div class="form-container">
                <h2>Add New Student</h2>
                <form id="addStudentForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="studentFirstName">First Name</label>
                            <input type="text" id="studentFirstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="studentLastName">Last Name</label>
                            <input type="text" id="studentLastName" name="lastName" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="studentEmail">Email</label>
                        <input type="email" id="studentEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="studentEnrollmentYear">Enrollment Year</label>
                        <select id="studentEnrollmentYear" name="enrollmentYear" required>
                            <option value="">Select Year</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                        </select>
                    </div>
                    <button type="submit" class="submit-btn">Add Student</button>
                </form>
            </div>
        </div>

        <!-- Add Course Tab -->
        <div id="courses" class="tab-content">
            <div class="form-container">
                <h2>Add New Course</h2>
                <form id="addCourseForm">
                    <div class="form-group">
                        <label for="courseName">Course Name</label>
                        <input type="text" id="courseName" name="courseName" required>
                    </div>
                    <div class="form-group">
                        <label for="courseCredits">Credits</label>
                        <select id="courseCredits" name="credits" required>
                            <option value="">Select Credits</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="courseProfessor">Professor</label>
                        <select id="courseProfessor" name="professorId" required>
                            <option value="">Select Professor</option>
                        </select>
                    </div>
                    <button type="submit" class="submit-btn">Add Course</button>
                </form>
            </div>
        </div>

        <!-- Add Grade Tab -->
        <div id="grades" class="tab-content">
            <div class="form-container">
                <h2>Add New Grade</h2>
                <form id="addGradeForm">
                    <div class="form-group">
                        <label for="gradeStudent">Student</label>
                        <select id="gradeStudent" name="studentId" required>
                            <option value="">Select Student</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gradeCourse">Course</label>
                        <select id="gradeCourse" name="courseId" required>
                            <option value="">Select Course</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gradeValue">Grade</label>
                        <select id="gradeValue" name="grade" required>
                            <option value="">Select Grade</option>
                            <option value="10">10 (A)</option>
                            <option value="9">9 (A)</option>
                            <option value="8">8 (B)</option>
                            <option value="7">7 (C)</option>
                            <option value="6">6 (D)</option>
                            <option value="5">5 (F)</option>
                        </select>
                    </div>
                    <button type="submit" class="submit-btn">Add Grade</button>
                </form>
            </div>
        </div>

        <!-- Add Professor Tab -->
        <div id="professors" class="tab-content">
            <div class="form-container">
                <h2>Add New Professor</h2>
                <form id="addProfessorForm">
                    <div class="form-group">
                        <label for="profFirstName">First Name</label>
                        <input type="text" id="profFirstName" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="profLastName">Last Name</label>
                        <input type="text" id="profLastName" name="lastName" required>
                    </div>
                    <div class="form-group">
                        <label for="profEmail">Email</label>
                        <input type="email" id="profEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="profDepartment">Department</label>
                        <select id="profDepartment" name="department" required>
                            <option value="">Select Department</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Software Engineering">Software Engineering</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="Networks">Networks</option>
                            <option value="QA & Testing">QA & Testing</option>
                        </select>
                    </div>
                    <button type="submit" class="submit-btn">Add Professor</button>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="../assets/js/auth.js"></script>
<script src="../assets/js/translations.js"></script>

<script>
// Check authentication on page load
document.addEventListener('DOMContentLoaded', function() {
    if (!Auth.isLoggedIn()) {
        window.location.href = 'login.html';
        return;
    }
    
    const user = Auth.getUser();
    if (user.role !== 'Admin' && user.role !== 'Professor') {
        alert('Access denied. Only admins and professors can access this page.');
        window.location.href = 'home.php';
        return;
    }
    
    loadFormData();
    updatePageContent();
});

function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => content.classList.remove('active'));
    
    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => button.classList.remove('active'));
    
    // Show selected tab content
    document.getElementById(tabName).classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}

function loadFormData() {
    // Load professors for course form
    fetch('https://uniportal-b0gvf6bfhcf3bpck.canadacentral-01.azurewebsites.net/api/professors')
        .then(response => response.json())
        .then(professors => {
            const professorSelect = document.getElementById('courseProfessor');
            professors.forEach(professor => {
                const option = document.createElement('option');
                option.value = professor.professor_id;
                option.textContent = `${professor.first_name} ${professor.last_name} (${professor.department})`;
                professorSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading professors:', error));

    // Load students for grade form
    fetch('https://uniportal-b0gvf6bfhcf3bpck.canadacentral-01.azurewebsites.net/api/students')
        .then(response => response.json())
        .then(students => {
            const studentSelect = document.getElementById('gradeStudent');
            students.forEach(student => {
                const option = document.createElement('option');
                option.value = student.student_id;
                option.textContent = `${student.first_name} ${student.last_name} (${student.email})`;
                studentSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading students:', error));

    // Load courses for grade form
    fetch('https://uniportal-b0gvf6bfhcf3bpck.canadacentral-01.azurewebsites.net/api/courses')
        .then(response => response.json())
        .then(courses => {
            const courseSelect = document.getElementById('gradeCourse');
            courses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.course_id;
                option.textContent = `${course.course_name} (${course.credits} credits)`;
                courseSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading courses:', error));
}

// Form submission handlers
document.getElementById('addStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('../api/add_student.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Student added successfully!', 'success');
            this.reset();
        } else {
            showMessage(data.error || 'Failed to add student', 'error');
        }
    })
    .catch(error => {
        showMessage('Error: ' + error.message, 'error');
    });
});

document.getElementById('addCourseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('../api/add_course.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Course added successfully!', 'success');
            this.reset();
        } else {
            showMessage(data.error || 'Failed to add course', 'error');
        }
    })
    .catch(error => {
        showMessage('Error: ' + error.message, 'error');
    });
});

document.getElementById('addGradeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('../api/add_grade.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Grade added successfully!', 'success');
            this.reset();
        } else {
            showMessage(data.error || 'Failed to add grade', 'error');
        }
    })
    .catch(error => {
        showMessage('Error: ' + error.message, 'error');
    });
});

// Add Professor Form Handler
document.getElementById('addProfessorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('../api/add_professor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Professor added successfully!', 'success');
            this.reset();
        } else {
            showMessage(data.error || 'Failed to add professor', 'error');
        }
    })
    .catch(error => {
        showMessage('Error: ' + error.message, 'error');
    });
});

function showMessage(message, type) {
    // Remove existing messages
    const existingMessages = document.querySelectorAll('.success-message, .error-message');
    existingMessages.forEach(msg => msg.remove());
    
    // Create new message
    const messageDiv = document.createElement('div');
    messageDiv.className = type === 'success' ? 'success-message' : 'error-message';
    messageDiv.textContent = message;
    
    // Insert at the top of the form container
    const formContainer = document.querySelector('.tab-content.active .form-container');
    formContainer.insertBefore(messageDiv, formContainer.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        messageDiv.remove();
    }, 5000);
}
</script>
</body>
</html>
