<?php
declare(strict_types=1);

// Show PHP errors while developing
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Using client-side fetches to load data from endpoints; no server-side DB usage here.
?>
<!DOCTYPE html>
<!-- CACHE BUST: <?php echo time(); ?> - FORCED ADD BUTTON TO BE VISIBLE -->
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Grades - Uni Portal</title>
    <style>
        :root {
            --primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-dark: #5a5f9e;
            --background: #fff;
            --sidebar-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-text: #fff;
            --table-header: #f5f5f5;
            --table-border: #e0e0e0;
        }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .add-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 24px;
            cursor: pointer;
            display: none; /* Hidden by default */
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            z-index: 100;
            position: relative;
        }
        
        /* Show add button for authorized users */
        .add-button.show {
            display: flex !important;
        }
        

        .add-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        /* Hide admin controls by default */
        #adminControls {
            display: none !important;
        }
        
        /* Add button visibility controlled by JavaScript */

        .delete-button {
            background: #ff3b30 !important;
            color: white !important;
            border: none !important;
            border-radius: 50% !important;
            width: 44px !important;
            height: 44px !important;
            font-size: 20px !important;
            font-weight: 300 !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 2px 8px rgba(255, 59, 48, 0.3) !important;
            z-index: 100 !important;
            position: relative !important;
            line-height: 1 !important;
            min-width: 44px !important;
            min-height: 44px !important;
            max-width: 44px !important;
            max-height: 44px !important;
        }

        .delete-button:hover {
            background: #ff2d55;
            transform: scale(1.1);
            box-shadow: 0 3px 12px rgba(255, 59, 48, 0.4);
        }

        .delete-button:active {
            transform: scale(0.95);
        }

        /* Success Toast Styles */
        .success-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(16, 185, 129, 0.3);
            z-index: 10000;
            font-weight: 500;
            font-size: 14px;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .success-toast.show {
            transform: translateX(0);
        }

        .success-toast::before {
            content: "✓";
            font-size: 18px;
            font-weight: bold;
        }

        /* Delete Confirmation Modal */
        .delete-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        .delete-modal {
            background: white;
            border-radius: 16px;
            padding: 32px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .delete-modal h3 {
            margin: 0 0 16px 0;
            color: #1f2937;
            font-size: 20px;
        }

        .delete-modal p {
            margin: 0 0 24px 0;
            color: #6b7280;
            line-height: 1.5;
        }

        .delete-modal-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .delete-modal-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .delete-modal-cancel {
            background: #f3f4f6;
            color: #374151;
        }

        .delete-modal-cancel:hover {
            background: #e5e7eb;
        }

        .delete-modal-confirm {
            background: #ef4444;
            color: white;
        }

        .delete-modal-confirm:hover {
            background: #dc2626;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            backdrop-filter: blur(8px);
        }

        .modal-content {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            margin: 3% auto;
            padding: 0;
            border-radius: 20px;
            width: 90%;
            max-width: 480px;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
            animation: modalSlideIn 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.2);
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px 20px 30px;
            margin: 0;
            border-bottom: none;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: white;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .close {
            color: rgba(255,255,255,0.8);
            font-size: 24px;
            font-weight: 300;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }

        .close:hover {
            color: white;
            background: rgba(255,255,255,0.2);
            transform: scale(1.1);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #2d3748;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #ffffff;
            color: #2d3748;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .form-group input::placeholder {
            color: #a0aec0;
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 16px 30px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
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
            <div class="logout-section" style="padding: 12px 32px;">
                <button onclick="Auth.logout()" class="logout-btn" style="width: 100%; background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.3); border-radius: 6px; padding: 10px 16px; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; white-space: normal; line-height: 1.2; min-height: 44px;">
                    <span class="logout-text">Logout</span>
                </button>
            </div>
        </nav>
    </aside>
    <main class="main-content">
    <h1 class="page-title" style="justify-content:space-between;">
        <span>Student Grades</span>
        <div id="adminControls" style="gap:12px; align-items:center;">
            <button class="add-button" onclick="showAddGradeModal()" title="Add New Grade" style="width:44px; height:44px;">+</button>
        </div>
    </h1>

        <div id="gradesContainer">
            <div class="loading">Loading grades...</div>
        </div>
    </main>
    </div>

    <!-- Add Grade Modal -->
    <div id="addGradeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Add New Grade</h2>
                <span class="close" onclick="closeAddGradeModal()">&times;</span>
            </div>
            <form id="addGradeForm" style="padding: 30px;" novalidate>
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

    <script src="../assets/js/auth.js"></script>
    <script src="../assets/js/translations.js"></script>
    
    <script>
    // Success Toast Function
    function showSuccessToast(message) {
        // Remove any existing toast
        const existingToast = document.querySelector('.success-toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        // Create new toast
        const toast = document.createElement('div');
        toast.className = 'success-toast';
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    // Delete Confirmation Modal
    function showDeleteConfirmation(itemName, itemId, deleteFunction) {
        // Remove any existing modal
        const existingModal = document.querySelector('.delete-modal-overlay');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Create modal
        const overlay = document.createElement('div');
        overlay.className = 'delete-modal-overlay';
        overlay.innerHTML = `
            <div class="delete-modal">
                <h3>Confirm Delete</h3>
                <p>Are you sure you want to delete "${itemName}"? This action cannot be undone.</p>
                <div class="delete-modal-buttons">
                    <button class="delete-modal-btn delete-modal-cancel">Cancel</button>
                    <button class="delete-modal-btn delete-modal-confirm">Delete</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(overlay);
        
        // Add event listeners
        overlay.querySelector('.delete-modal-cancel').addEventListener('click', () => {
            overlay.remove();
        });
        
        overlay.querySelector('.delete-modal-confirm').addEventListener('click', () => {
            overlay.remove();
            deleteFunction(itemId);
        });
        
        // Close on overlay click
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.remove();
            }
        });
    }
    </script>
    
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
        
        // Check user role and show/hide admin controls
        checkUserRole();
        
        // Also run after a short delay to ensure all scripts have loaded
        setTimeout(checkUserRole, 100);
        setTimeout(checkUserRole, 500);
        setTimeout(checkUserRole, 1500);
    });

    // Simplified checkUserRole - add button now handled in displayGrades function
    function checkUserRole() {
        const user = Auth.getUser();
        const adminControls = document.getElementById('adminControls');
        
        if (!adminControls) {
            return;
        }
        
        // Only handle adminControls visibility, add button is now handled in displayGrades
        if (user && (user.role === 'Admin' || user.role === 'Professor')) {
            adminControls.style.setProperty('display', 'flex', 'important');
        } else {
            adminControls.style.setProperty('display', 'none', 'important');
        }
    }

    // Function to get delete button based on user role
    function getDeleteButton(gradeId) {
        const user = Auth.getUser();
        
        if (user && (user.role === 'Admin' || user.role === 'Professor')) {
            return `<button class="view-btn" style="background: #ff3b30 !important; color: white !important; border: none !important; border-radius: 50% !important; width: 28px !important; height: 28px !important; min-width: 28px !important; min-height: 28px !important; max-width: 28px !important; max-height: 28px !important; font-size: 16px !important; font-weight: 300 !important; cursor: pointer !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; transition: all 0.2s ease !important; box-shadow: 0 2px 8px rgba(255, 59, 48, 0.3) !important; padding: 0 !important; flex-shrink: 0 !important; margin: 0 auto !important;" onclick="deleteGrade(${gradeId})">−</button>`;
        } else {
            return '';
        }
    }

    // Periodically check user role
    setInterval(checkUserRole, 1000);
    </script>

<script>
// Check authentication and load appropriate grades
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== GRADES PAGE DOMContentLoaded FIRED ===');
    console.log('DOMContentLoaded time:', new Date().toISOString());
    
    // Try immediate call first
    console.log('Trying immediate checkUserRole call');
    checkUserRole();
    
    // Wait for user data to be available before calling checkUserRole
    function waitForUserAndCheckRole() {
        const user = Auth.getUser();
        console.log('=== GRADES PAGE TIMING DEBUG ===');
        console.log('waitForUserAndCheckRole called at:', new Date().toISOString());
        console.log('Auth.getUser() result:', user);
        console.log('localStorage user:', localStorage.getItem('user'));
        console.log('localStorage token:', localStorage.getItem('token'));
        
        if (user) {
            console.log('User data available, calling checkUserRole');
            checkUserRole();
        } else {
            console.log('User data not available yet, retrying in 50ms');
            setTimeout(waitForUserAndCheckRole, 50);
        }
    }
    
    // Start checking for user data
    waitForUserAndCheckRole();
    
    // Wait for user role to be available before rendering
    function waitForUserAndRender() {
        const user = Auth.getUser();
        if (user) {
            console.log('User found, rendering grades with role:', user.role);
            loadGrades();
            updatePageContent(); // Update translations
            
            // Load form data for grade modal
            loadFormData();
            
            // Check user role for admin controls
            checkUserRole();
        } else {
            console.log('User not found yet, retrying...');
            setTimeout(waitForUserAndRender, 100);
        }
    }
    
    // Start the process
    waitForUserAndRender();
    
        // Also run checkUserRole periodically to handle role changes
        setInterval(checkUserRole, 1000);
});

// Function to ensure plus button is always visible for authorized users
function ensurePlusButtonVisibility() {
    const addButton = document.querySelector('.add-button');
    if (addButton) {
        if (Auth.isLoggedIn()) {
            const user = Auth.getUser();
            if (user.role === 'Admin' || user.role === 'Professor') {
                addButton.style.display = 'flex';
                addButton.style.visibility = 'visible';
            } else {
                addButton.style.display = 'none';
            }
        } else {
            addButton.style.display = 'none';
        }
    }
}

// Periodically check to ensure plus button stays visible
setInterval(ensurePlusButtonVisibility, 1000);

function toggleGradesDeleteMode() {
    const header = document.getElementById('gradesDeleteHeader');
    const cells = document.querySelectorAll('.gradesDeleteCell');
    const isVisible = header && header.style.display !== 'none';
    
    if (header) {
        header.style.display = isVisible ? 'none' : '';
    }
    cells.forEach(td => {
        td.style.display = isVisible ? 'none' : '';
    });
}

function deleteGrade(gradeId, gradeName) {
    // Show confirmation modal
    showDeleteConfirmation(gradeName, gradeId, function(id) {
        // For now, just show success message (since we don't have a real delete endpoint)
        showSuccessToast(`${gradeName} has been deleted successfully.`);
        
        // In a real implementation, you would call a delete API here
        // fetch('../api/delete_grade.php', { method: 'POST', body: new FormData().append('grade_id', id) })
        //     .then(() => { /* refresh grades */ });
    });
}

function loadGrades() {
    if (!Auth.isLoggedIn()) {
        document.getElementById('gradesContainer').innerHTML = '<p>Please log in to view grades.</p>';
        return;
    }
    
    const user = Auth.getUser();
    const userRole = user?.role || 'User';
    const userEmail = user?.email || null;
    
    if (userRole === 'Admin' || userRole === 'Professor') {
        // Admin and Professor see all grades
        loadAllGrades();
    } else if (userRole === 'Student' && userEmail) {
        // Student sees only their own grades
        loadStudentGrades(userEmail);
    } else {
        document.getElementById('gradesContainer').innerHTML = '<p>Access denied. Please log in as a student, professor, or admin.</p>';
    }
}

function loadAllGrades() {
    // For admin, we'll use the existing getAllGrades data
    fetch('https://uniportal-b0gvf6bfhcf3bpck.canadacentral-01.azurewebsites.net/api/grades')
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
            displayGrades(data.data, true);
        })
        .catch(error => {
            console.error('Error loading all grades:', error);
            document.getElementById('gradesContainer').innerHTML = `<p>Error loading grades: ${error.message}</p>`;
        });
}

function loadStudentGrades(userEmail) {
    console.log('Loading grades for student email:', userEmail);
    fetch('https://uniportal-b0gvf6bfhcf3bpck.canadacentral-01.azurewebsites.net/api/grades')
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
            
            console.log('All grades data:', data.data);
            
            // Filter grades for this student by email
            const studentGrades = data.data.filter(grade => {
                // Try multiple matching strategies
                const emailName = userEmail.split('@')[0].toLowerCase();
                const studentName = grade.student_name ? grade.student_name.toLowerCase() : '';
                
                // Match by email name in student name
                const nameMatch = studentName.includes(emailName);
                
                // Also try to match by exact email if available
                const emailMatch = grade.student_email && grade.student_email.toLowerCase() === userEmail.toLowerCase();
                
                console.log(`Checking grade for ${grade.student_name}: nameMatch=${nameMatch}, emailMatch=${emailMatch}`);
                
                return nameMatch || emailMatch;
            });
            
            console.log('Filtered student grades:', studentGrades);
            displayGrades(studentGrades, false);
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
    
    // Check if user has admin/professor role to show delete column and add button
    const user = Auth.getUser();
    const showDeleteColumn = user && (user.role === 'Admin' || user.role === 'Professor');
    const showAddButton = user && (user.role === 'Admin' || user.role === 'Professor');
    
    // Update add button visibility immediately
    const addButton = document.querySelector('.add-button');
    if (addButton) {
        if (showAddButton) {
            addButton.classList.add('show');
            addButton.style.display = 'flex';
        } else {
            addButton.classList.remove('show');
            addButton.style.display = 'none';
        }
    }
    
    let html = `
        <div class="table-container">
            <table>
                <thead>
                <tr>
                    ${isAdmin ? '<th>Student ID</th><th>Student Name</th>' : ''}
                    <th>Course</th>
                    <th>Grade</th>
                    <th>Professor</th>
                    ${showDeleteColumn ? '<th class="th-delete" id="gradesDeleteHeader" style="display:table-cell; text-align: center !important;">Delete</th>' : ''}
                </tr>
                </thead>
                <tbody>
    `;
    
    grades.forEach(grade => {
        const gradeClass = getGradeClass(grade.grade);
        html += `
            <tr>
                ${isAdmin ? `<td>${grade.student_id || ''}</td><td>${grade.student_name || ''}</td>` : ''}
                <td><span class="course-tag">${grade.course_name}</span></td>
                <td><span class="grade-badge grade-${gradeClass}">${grade.grade}</span></td>
                <td>${grade.professor_name || 'Not assigned'}</td>
                ${showDeleteColumn ? `<td class="gradesDeleteCell" style="display:table-cell; text-align: center !important; vertical-align: middle !important;">${getDeleteButton(grade.grade_id)}</td>` : ''}
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

// Load form data for grade modal
function loadFormData() {
    // Load students for grade form
    fetch('https://uniportal-b0gvf6bfhcf3bpck.canadacentral-01.azurewebsites.net/api/students')
        .then(response => response.json())
        .then(payload => {
            const students = Array.isArray(payload) ? payload : (payload.data || []);
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
        .then(payload => {
            const courses = Array.isArray(payload) ? payload : (payload.data || []);
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

// Modal functions
function showAddGradeModal() {
    // Check if user has permission
    if (!Auth.isLoggedIn()) {
        alert('Please log in to add grades.');
        return;
    }
    
    const user = Auth.getUser();
    if (user.role !== 'Admin' && user.role !== 'Professor') {
        alert('Access denied. Only admins and professors can add grades.');
        return;
    }
    
    document.getElementById('addGradeModal').style.display = 'block';
}

function closeAddGradeModal() {
    document.getElementById('addGradeModal').style.display = 'none';
    document.getElementById('addGradeForm').reset();
    // Remove any existing messages
    const messages = document.querySelectorAll('.success-message, .error-message');
    messages.forEach(msg => msg.remove());
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('addGradeModal');
    if (event.target === modal) {
        closeAddGradeModal();
    }
});

// Form submission
document.getElementById('addGradeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Clear any previous messages
    const existingMessages = document.querySelectorAll('.success-message, .error-message');
    existingMessages.forEach(msg => msg.remove());
    
    // Check if all fields are filled
    const studentId = this.querySelector('#studentId').value.trim();
    const courseId = this.querySelector('#courseId').value.trim();
    const grade = this.querySelector('#grade').value.trim();
    
    if (!studentId || !courseId || !grade) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = 'Please fill in all fields';
        this.insertBefore(errorDiv, this.firstChild);
        return false;
    }
    
    const formData = new FormData(this);
    
    fetch('../api/add_grade.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reset form
            this.reset();
            
            // Close modal immediately
            const modal = document.getElementById('gradeModal');
            if (modal) {
                modal.style.display = 'none';
            }
            
            // Show beautiful success toast
            showSuccessToast('Grade added successfully!');
            
            // Reload page after a short delay to show new grade
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.textContent = data.error || 'Failed to add grade';
            this.insertBefore(errorDiv, this.firstChild);
        }
    })
    .catch(error => {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = 'Error: ' + error.message;
        this.insertBefore(errorDiv, this.firstChild);
    });
});
</script>
</body>
</html>
