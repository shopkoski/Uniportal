<?php
declare(strict_types=1);

// Show PHP errors while developing
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/config.php';

// CHANGE these if you didn't already in config.php.
// (You can also just use: $db = new Database(); if you set creds there.)
$dbUser = 'root';
$dbPass = '14122002';

try {
    // If you're running PHP inside Docker and your MySQL service is named "db",
    // change host to 'db'. Otherwise 127.0.0.1 is correct for local MySQL.
    $db = new Database('127.0.0.1', 'my_test_db', $dbUser, $dbPass, 3306);
    
    // Get user information from session or token (this will be handled by JavaScript)
    $grades = $db->getAllGrades(); // Default to all grades, will be filtered by JavaScript
} catch (Throwable $e) {
    http_response_code(500);
    echo "<pre>Database error: " . htmlspecialchars($e->getMessage()) . "</pre>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Grades - Uni Portal</title>
    <style>
        :root {
            --primary: #1a4a5e;
            --primary-dark: #153a4a;
            --background: #fff;
            --sidebar-bg: #1a4a5e;
            --sidebar-text: #fff;
            --table-header: #f5f5f5;
            --table-border: #e0e0e0;
        }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: var(--primary);
            min-height: 100vh;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 240px;
            background: var(--primary);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            padding: 32px 0 0 0;
        }
        .sidebar .logo {
            font-size: 1rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 8px;
            padding-left: 30px;
        }
        
        .portal-text {
            font-size: 0.9rem;
        }
        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
            height: calc(100vh - 120px);
        }
        .sidebar nav a {
            color: var(--sidebar-text);
            text-decoration: none;
            padding: 12px 32px;
            font-size: 1rem;
            border-left: 4px solid transparent;
            transition: background 0.2s, border-color 0.2s;
            display: block;
        }
        .sidebar nav a.active, .sidebar nav a:hover {
            background: rgba(255,255,255,0.08);
            border-left: 4px solid #fff;
        }
        
        .language-link {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .language-link:hover {
            opacity: 1;
        }
        .main-content {
            flex: 1;
            background: var(--background);
            border-radius: 0 8px 8px 0;
            padding: 40px 40px 100px 40px;
            min-height: 100vh;
        }
        .main-content h1 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 24px;
            color: #222;
        }
        .table-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            padding: 24px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            padding: 12px 16px;
            text-align: left;
        }
        th {
            background: var(--table-header);
            font-size: 0.98rem;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid var(--table-border);
        }
        td {
            border-bottom: 1px solid var(--table-border);
            font-size: 0.97rem;
            color: #444;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .view-btn {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 6px 16px;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .view-btn:hover {
            background: var(--primary-dark);
        }
        .grade-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
            min-width: 40px;
        }
        .grade-a {
            background: #e8f5e8;
            color: #2e7d32;
        }
        .grade-b {
            background: #e3f2fd;
            color: #1976d2;
        }
        .grade-c {
            background: #fff3e0;
            color: #f57c00;
        }
        .grade-d {
            background: #ffebee;
            color: #d32f2f;
        }
        .grade-f {
            background: #fce4ec;
            color: #c2185b;
        }
        .course-tag {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
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
            <a href="#" class="active nav-grades">Grades</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='professor_page.php' : Auth.showLoginPopup(); return false;" class="nav-professor">Professor</a>
            <a href="contact_page.php" class="nav-contact">Contact</a>
            <div class="language-flags" style="display: flex; justify-content: flex-start; gap: 15px; padding: 12px 32px;">
                <button onclick="changeLanguage('mk')" style="background: none; border: none; cursor: pointer; padding: 0;">
                    <img src="flags/mk.png" alt="Македонски" width="18" height="14" style="border-radius: 2px;">
                </button>
                <button onclick="changeLanguage('en')" style="background: none; border: none; cursor: pointer; padding: 0;">
                    <img src="flags/en.png" alt="English" width="18" height="14" style="border-radius: 2px;">
                </button>
                <button onclick="changeLanguage('al')" style="background: none; border: none; cursor: pointer; padding: 0;">
                    <img src="flags/al.png" alt="Shqip" width="18" height="14" style="border-radius: 2px;">
                </button>
            </div>
            <div class="logout-section" style="margin-top: auto; padding: 12px 32px;">
                <button onclick="Auth.logout()" class="logout-btn" style="width: 100%; background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.3); border-radius: 6px; padding: 10px 16px; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; white-space: normal; line-height: 1.2; min-height: 44px;">
                    <span class="logout-text">Logout</span>
                </button>
            </div>
        </nav>
    </aside>
    <main class="main-content">
        <h1 class="page-title">Student Grades</h1>

        <div id="gradesContainer">
            <div class="loading">Loading grades...</div>
        </div>
    </main>
</div>

    <script src="auth.js"></script>
    <script src="translations.js"></script>
    
    <script>
    // Update logout button with user info when page loads
    document.addEventListener('DOMContentLoaded', function() {
        if (Auth.isLoggedIn()) {
            const logoutText = document.querySelector('.logout-text');
            if (logoutText) {
                const user = Auth.getUser();
                const userName = user?.firstName || user?.email || 'User';
                const userRole = user?.role || 'User';
                
                // Translate role
                let translatedRole;
                if (userRole.toLowerCase() === 'admin') {
                    translatedRole = t('role_admin');
                } else if (userRole.toLowerCase() === 'student') {
                    translatedRole = t('role_student');
                } else {
                    translatedRole = userRole;
                }
                
                // Use the template with user info
                const logoutTemplate = t('logout_with_user');
                logoutText.textContent = logoutTemplate
                    .replace('{name}', userName)
                    .replace('{role}', translatedRole);
            }
        }
    });
    </script>

<script>
// Check authentication and load appropriate grades
document.addEventListener('DOMContentLoaded', function() {
    loadGrades();
    updatePageContent(); // Update translations
});

function loadGrades() {
    if (!Auth.isLoggedIn()) {
        document.getElementById('gradesContainer').innerHTML = '<p>Please log in to view grades.</p>';
        return;
    }
    
    const user = Auth.getUser();
    const userRole = user?.role || 'User';
    const userEmail = user?.email || null;
    
    if (userRole === 'Admin') {
        // Admin sees all grades
        loadAllGrades();
    } else if (userRole === 'Student' && userEmail) {
        // Student sees only their own grades
        loadStudentGrades(userEmail);
    } else {
        document.getElementById('gradesContainer').innerHTML = '<p>Access denied. Please log in as a student or admin.</p>';
    }
}

function loadAllGrades() {
    // For admin, we'll use the existing getAllGrades data
    fetch('get_all_grades.php')
        .then(response => {
            if (!response || !response.ok) {
                throw new Error(`HTTP error! status: ${response ? response.status : 'No response'}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                document.getElementById('gradesContainer').innerHTML = `<p>Error: ${data.error}</p>`;
                return;
            }
            displayGrades(data, true);
        })
        .catch(error => {
            console.error('Error loading all grades:', error);
            document.getElementById('gradesContainer').innerHTML = `<p>Error loading grades: ${error.message}</p>`;
        });
}

function loadStudentGrades(userEmail) {
    // First get the student ID for this user
    fetch(`get_student_id.php?email=${encodeURIComponent(userEmail)}`)
        .then(response => {
            if (!response || !response.ok) {
                throw new Error(`HTTP error! status: ${response ? response.status : 'No response'}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                document.getElementById('gradesContainer').innerHTML = `<p>Error: ${data.error}</p>`;
                return;
            }
            
            if (!data.student_id) {
                document.getElementById('gradesContainer').innerHTML = '<p>Student not found.</p>';
                return;
            }
            
            // Now get the student's grades
            return fetch(`get_student_grades.php?student_id=${data.student_id}`);
        })
        .then(response => {
            if (!response || !response.ok) {
                throw new Error(`HTTP error! status: ${response ? response.status : 'No response'}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                document.getElementById('gradesContainer').innerHTML = `<p>Error: ${data.error}</p>`;
                return;
            }
            displayGrades(data, false);
        })
        .catch(error => {
            console.error('Error loading student grades:', error);
            document.getElementById('gradesContainer').innerHTML = `<p>Error loading grades: ${error.message}</p>`;
        });
}

function displayGrades(grades, isAdmin) {
    const container = document.getElementById('gradesContainer');
    
    if (!grades || grades.length === 0) {
        container.innerHTML = '<p>No grades available.</p>';
        return;
    }
    
    let html = `
        <div class="table-container">
            <table>
                <thead>
                <tr>
                    ${isAdmin ? '<th>Student ID</th><th>Student Name</th>' : ''}
                    <th>Course</th>
                    <th>Grade</th>
                    <th>Letter Grade</th>
                    <th>Professor</th>
                </tr>
                </thead>
                <tbody>
    `;
    
    grades.forEach(grade => {
        const gradeClass = getGradeClass(grade.letter_grade);
        html += `
            <tr>
                ${isAdmin ? `<td>${grade.student_id || ''}</td><td>${grade.student_name || ''}</td>` : ''}
                <td><span class="course-tag">${grade.course_name}</span></td>
                <td><span class="grade-badge grade-${gradeClass}">${grade.grade}</span></td>
                <td><span class="grade-badge grade-${gradeClass}">${grade.letter_grade}</span></td>
                <td>${grade.professor_name || 'Not assigned'}</td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    container.innerHTML = html;
}

function getGradeClass(letterGrade) {
    switch(letterGrade) {
        case 'A': return 'a';
        case 'B': return 'b';
        case 'C': return 'c';
        case 'D': return 'd';
        case 'F': return 'f';
        default: return 'a';
    }
}
</script>
</body>
</html>
