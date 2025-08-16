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
    $professors = $db->getAllProfessors();
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
    <title>Professors - Uni Portal</title>
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
        .department-tag {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .courses-count {
            background: #f3e5f5;
            color: #7b1fa2;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            border-radius: 8px;
            width: 80%;
            max-width: 900px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .modal-header {
            background: var(--primary);
            color: white;
            padding: 20px 24px;
            border-radius: 8px 8px 0 0;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .modal-body {
            padding: 24px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover {
            color: white;
        }

        .professor-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        .info-value {
            color: #333;
            font-size: 1rem;
        }

        .courses-section h3 {
            margin-bottom: 16px;
            color: #333;
            font-size: 1.2rem;
        }

        .courses-table {
            width: 100%;
            border-collapse: collapse;
        }

        .courses-table th,
        .courses-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .courses-table th {
            background: #f5f5f5;
            font-weight: 600;
            color: #333;
        }

        .enrollment-count {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
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
            <a href="#" class="active nav-professor">Professor</a>
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
        <h1 class="page-title">Faculty Professors</h1>

        <?php if (!empty($professors)): ?>
            <div class="table-container">
                <table>
                    <thead>
                    <tr>
                        <th class="th-professor-id">Professor ID</th>
                        <th class="th-full-name">Full Name</th>
                        <th class="th-email">Email</th>
                        <th class="th-department">Department</th>
                        <th class="th-courses-taught">Courses Taught</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($professors as $professor): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)$professor['professor_id']) ?></td>
                            <td><?= htmlspecialchars((string)$professor['full_name']) ?></td>
                            <td><?= htmlspecialchars((string)$professor['email']) ?></td>
                            <td>
                                <span class="department-tag"><?= htmlspecialchars((string)$professor['department']) ?></span>
                            </td>
                            <td>
                                <span class="courses-count"><?= htmlspecialchars((string)$professor['courses_taught']) ?> courses</span>
                            </td>
                            <td>
                                <button class="view-btn view-btn-text" onclick="showProfessorCourseDetails(<?= htmlspecialchars((string)$professor['professor_id']) ?>)">View</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No professor data available.</p>
        <?php endif; ?>
    </main>
</div>

<!-- Professor Course Details Modal -->
<div id="professorModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle" class="modal-title">Professor Course Details</h2>
        </div>
        <div class="modal-body" id="modalBody">
            <div class="loading">Loading professor course details...</div>
        </div>
    </div>
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
// Modal functionality
const modal = document.getElementById('professorModal');

function showProfessorCourseDetails(professorId) {
    modal.style.display = 'block';
    document.getElementById('modalBody').innerHTML = '<div class="loading">Loading professor course details...</div>';
    
    fetch(`get_professor_course_details.php?professor_id=${professorId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('modalBody').innerHTML = `<div class="loading">Error: ${data.error}</div>`;
                return;
            }
            
            displayProfessorCourseDetails(data);
        })
        .catch(error => {
            document.getElementById('modalBody').innerHTML = `<div class="loading">Error loading professor course details: ${error.message}</div>`;
        });
}

function displayProfessorCourseDetails(data) {
    const professor = data.professor;
    const courses = data.courses;
    
    document.getElementById('modalTitle').textContent = `${professor.professor_name} - ${t('course_details')}`;
    
    const modalBody = document.getElementById('modalBody');
    
    let html = `
        <div class="professor-info">
            <div class="info-item">
                <span class="info-label">${t('professor_id')}</span>
                <span class="info-value">${professor.professor_id}</span>
            </div>
            <div class="info-item">
                <span class="info-label">${t('full_name')}</span>
                <span class="info-value">${professor.professor_name}</span>
            </div>
            <div class="info-item">
                <span class="info-label">${t('email')}</span>
                <span class="info-value">${professor.professor_email}</span>
            </div>
            <div class="info-item">
                <span class="info-label">${t('department')}</span>
                <span class="info-value">${professor.department}</span>
            </div>
        </div>
        
        <div class="courses-section">
            <h3>${t('courses_taught_count')} (${courses.length})</h3>
            <table class="courses-table">
                <thead>
                    <tr>
                        <th>${t('course')}</th>
                        <th>${t('credits')}</th>
                        <th>${t('enrolled_students')}</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    courses.forEach(course => {
        html += `
            <tr>
                <td>${course.course_name}</td>
                <td>${course.credits}</td>
                <td><span class="enrollment-count">${course.enrolled_students} ${t('students')}</span></td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    modalBody.innerHTML = html;
}

function closeModal() {
    modal.style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    if (event.target === modal) {
        closeModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && modal.style.display === 'block') {
        closeModal();
    }
});
</script>
</body>
</html>
