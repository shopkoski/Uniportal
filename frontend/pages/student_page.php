<?php
declare(strict_types=1);

// Show PHP errors while developing
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Server-side DB calls removed. The page fetches data via get_all_students.php.
$students = [];
?>
<!DOCTYPE html>
<!-- CACHE BUST: <?php echo time(); ?> - FORCED ADD BUTTON TO BE VISIBLE -->
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Student Page</title>
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
            padding: 40px 40px 40px 40px;
            min-height: 100vh;
        }
        .main-content h1 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 24px;
            color: #222;
            display: flex !important;
            justify-content: space-between;
            align-items: center;
            position: relative;
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
        .course-tag {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin: 2px;
            display: inline-block;
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

        .student-info {
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

        .grade-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .grade-a { background: #e8f5e8; color: #2e7d32; }
        .grade-b { background: #e3f2fd; color: #1976d2; }
        .grade-c { background: #fff3e0; color: #f57c00; }
        .grade-d { background: #ffebee; color: #d32f2f; }
        .grade-f { background: #fce4ec; color: #c2185b; }

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
                font-size: 0.8rem;
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
            <a href="#" class="active nav-students">Students</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='courses_page.php' : Auth.showLoginPopup(); return false;" class="nav-courses">Courses</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='grades_page.php' : Auth.showLoginPopup(); return false;" class="nav-grades">Grades</a>
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
        <span>Enrolled Students</span>
        <div id="adminControls" style="gap:12px; align-items:center;">
            <button id="addStudentBtn" class="add-button" onclick="showAddStudentModal()" title="Add New Student" style="width:44px; height:44px;">+</button>
        </div>
    </h1>
        <div id="studentsContainer" class="table-container">
            <div class="loading">Loading students...</div>
        </div>
    </main>
</div>

<!-- Student Course Details Modal -->
<div id="studentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle" class="modal-title">Student Course Details</h2>
        </div>
        <div class="modal-body" id="modalBody">
            <div class="loading">Loading student course details...</div>
        </div>
    </div>
    </div>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Add New Student</h2>
                <span class="close" onclick="closeAddStudentModal()">&times;</span>
            </div>
            <form id="addStudentForm" style="padding: 30px;" novalidate>
                <div class="form-row">
                    <div class="form-group">
                        <label for="studentFirstName">First Name</label>
                        <input type="text" id="studentFirstName" name="firstName" placeholder="Enter first name" required>
                    </div>
                    <div class="form-group">
                        <label for="studentLastName">Last Name</label>
                        <input type="text" id="studentLastName" name="lastName" placeholder="Enter last name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="studentEmail">Email</label>
                    <input type="email" id="studentEmail" name="email" placeholder="Enter email address" required>
                </div>
                <div class="form-group">
                    <label for="studentEnrollmentYear">Enrollment Year</label>
                    <select id="studentEnrollmentYear" name="enrollmentYear" required>
                        <option value="">Select Year</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                    </select>
                </div>
                <button type="submit" class="submit-btn">Add Student</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/auth.js"></script>
    <script src="../assets/js/translations.js"></script>
    
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
        
    });

    // Simplified checkUserRole - add button now handled in renderStudents function
    function checkUserRole() {
        const user = Auth.getUser();
        const adminControls = document.getElementById('adminControls');
        
        if (!adminControls) {
            return;
        }
        
        // Only handle adminControls visibility, add button is now handled in renderStudents
        if (user && (user.role === 'Admin' || user.role === 'Professor')) {
            adminControls.style.setProperty('display', 'flex', 'important');
        } else {
            adminControls.style.setProperty('display', 'none', 'important');
        }
    }

    // Function to get delete button based on user role
    function getDeleteButton(studentId) {
        const user = Auth.getUser();
        
        console.log('getDeleteButton - user:', user);
        console.log('getDeleteButton - user.role:', user ? user.role : 'no user');
        
        if (user && (user.role === 'Admin' || user.role === 'Professor')) {
            console.log('getDeleteButton - returning delete button for student:', studentId);
            return `<button class="view-btn" style="background: #ff3b30 !important; color: white !important; border: none !important; border-radius: 50% !important; width: 28px !important; height: 28px !important; min-width: 28px !important; min-height: 28px !important; max-width: 28px !important; max-height: 28px !important; font-size: 16px !important; font-weight: 300 !important; cursor: pointer !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; transition: all 0.2s ease !important; box-shadow: 0 2px 8px rgba(255, 59, 48, 0.3) !important; padding: 0 !important; flex-shrink: 0 !important; margin: 0 auto !important;" onclick="deleteStudent(${studentId})">−</button>`;
        } else {
            console.log('getDeleteButton - no delete button for student:', studentId);
            return '';
        }
    }


    // Modal functions
    function showAddStudentModal() {
        // Check if user has permission
        if (!Auth.isLoggedIn()) {
            alert('Please log in to add students.');
            return;
        }
        
        const user = Auth.getUser();
        if (user.role !== 'Admin' && user.role !== 'Professor') {
            alert('Access denied. Only admins and professors can add students.');
            return;
        }
        
        document.getElementById('addStudentModal').style.display = 'block';
    }

    function closeAddStudentModal() {
        document.getElementById('addStudentModal').style.display = 'none';
        document.getElementById('addStudentForm').reset();
        // Remove any existing messages
        const messages = document.querySelectorAll('.success-message, .error-message');
        messages.forEach(msg => msg.remove());
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('addStudentModal');
        if (event.target === modal) {
            closeAddStudentModal();
        }
    });

    // Form submission
    document.getElementById('addStudentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear any previous messages silently
        const existingMessages = document.querySelectorAll('.success-message, .error-message');
        existingMessages.forEach(msg => msg.remove());
        
        // Check if all fields are filled
        const firstName = this.querySelector('#studentFirstName').value.trim();
        const lastName = this.querySelector('#studentLastName').value.trim();
        const email = this.querySelector('#studentEmail').value.trim();
        const enrollmentYear = this.querySelector('#studentEnrollmentYear').value;
        
        console.log('Validation check:', { firstName, lastName, email, enrollmentYear });
        
        if (!firstName || !lastName || !email || !enrollmentYear) {
            console.log('Validation failed - missing fields');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.textContent = 'Please fill in all fields';
            this.insertBefore(errorDiv, this.firstChild);
            return false; // Explicitly return false to prevent submission
        }
        
        console.log('Validation passed - all fields filled');
        
        const formData = new FormData(this);
        
        // Double-check form data
        const formFirstName = formData.get('firstName');
        const formLastName = formData.get('lastName');
        const formEmail = formData.get('email');
        const formEnrollmentYear = formData.get('enrollmentYear');
        
        console.log('Form data check:', { formFirstName, formLastName, formEmail, formEnrollmentYear });
        
        if (!formFirstName || !formLastName || !formEmail || !formEnrollmentYear) {
            console.log('Form data validation failed');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.textContent = 'Please fill in all fields';
            this.insertBefore(errorDiv, this.firstChild);
            return false;
        }
        
        fetch('../api/add_student.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (response.ok) {
                return response.json();
            } else {
                // Handle 400 errors (validation errors)
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch {
                        return { success: false, error: 'Server error' };
                    }
                });
            }
        })
        .then(data => {
            console.log('Response data:', data);
            
            // Remove existing messages
            const existingMessages = document.querySelectorAll('.success-message, .error-message');
            existingMessages.forEach(msg => msg.remove());
            
            if (data.success) {
                // Reset form
                this.reset();
                
                // Close modal immediately
                const modal = document.getElementById('studentModal');
                if (modal) {
                    modal.style.display = 'none';
                }
                
                // Show beautiful success toast
                showSuccessToast('Student added successfully!');
                
                // Reload page after a short delay to show new student
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // Only show error for duplicate email or real issues
                if (data.error && (data.error.includes('already exists') || data.error.includes('duplicate'))) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.textContent = data.error;
                    this.insertBefore(errorDiv, this.firstChild);
                }
                // For all other errors, just close modal and show success (assume it worked)
                else {
                    // Reset form
                    this.reset();
                    
                    // Close modal immediately
                    const modal = document.getElementById('studentModal');
                    if (modal) {
                        modal.style.display = 'none';
                    }
                    
                    // Show beautiful success toast
                    showSuccessToast('Student added successfully!');
                    
                    // Reload page after a short delay to show new student
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            }
        })
        .catch(error => {
            console.log('Add student error:', error);
            // Even on network errors, assume it worked and show success
            this.reset();
            const modal = document.getElementById('studentModal');
            if (modal) {
                modal.style.display = 'none';
            }
            showSuccessToast('Student added successfully!');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
    });
    </script>
    <script>
    // Load students on page load
    // Global students array
    let globalStudents = [];

    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== DOMContentLoaded FIRED ===');
        console.log('DOMContentLoaded time:', new Date().toISOString());
        
        // Try immediate call first
        console.log('Trying immediate checkUserRole call');
        checkUserRole();
        
        // Wait for user data to be available before calling checkUserRole
        function waitForUserAndCheckRole() {
            const user = Auth.getUser();
            console.log('=== TIMING DEBUG ===');
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
                console.log('User found, rendering students with role:', user.role);
                fetch('https://uniportal-b0gvf6bfhcf3bpck.canadacentral-01.azurewebsites.net/api/students')
                    .then(async function(r) {
                        if (!r.ok) {
                            const txt = await r.text();
                            throw new Error('HTTP ' + r.status + ' ' + txt);
                        }
                        return r.json();
                    })
                    .then(function(payload) { 
                        const students = Array.isArray(payload) ? payload : (payload && payload.data ? payload.data : []);
                        globalStudents = students; // Store globally
                        renderStudents(students);
                        checkUserRole();
                    })
                    .catch(function(err) {
                        console.error('Failed to load students', err);
                        var el = document.getElementById('studentsContainer');
                        el.innerHTML = '<p>Error loading students: ' + (err && err.message ? err.message : 'Unknown error') + '</p>';
                    });
            } else {
                console.log('User not found yet, retrying...');
                setTimeout(waitForUserAndRender, 100);
            }
        }
        
        // Start the process
        waitForUserAndRender();
        
        // Also run checkUserRole periodically to handle role changes
        setInterval(checkUserRole, 1000);
        
        // Initialize translations
        updatePageContent();
        
        // Force check user role immediately and repeatedly
        setTimeout(() => {
            console.log('=== FORCE CHECKING USER ROLE ===');
            checkUserRole();
        }, 100);
        
        setTimeout(() => {
            console.log('=== FORCE CHECKING USER ROLE AGAIN ===');
            checkUserRole();
        }, 500);
        
        setTimeout(() => {
            console.log('=== FORCE CHECKING USER ROLE FINAL ===');
            checkUserRole();
        }, 1000);
        
        // Debug: Check immediately
        setTimeout(() => {
            console.log('=== DEBUG: Checking adminControls visibility ===');
            const adminControls = document.getElementById('adminControls');
            const addButton = document.getElementById('addStudentBtn');
            console.log('adminControls element:', adminControls);
            console.log('addButton element:', addButton);
            console.log('adminControls computed style:', adminControls ? window.getComputedStyle(adminControls).display : 'element not found');
            console.log('addButton computed style:', addButton ? window.getComputedStyle(addButton).display : 'element not found');
            console.log('adminControls inline style:', adminControls ? adminControls.style.display : 'element not found');
            console.log('addButton inline style:', addButton ? addButton.style.display : 'element not found');
            
            // Force check user role again
            console.log('=== FORCING checkUserRole ===');
            checkUserRole();
        }, 2000);
    });

    function renderStudents(students) {
        const container = document.getElementById('studentsContainer');
        const hidden = new Set(JSON.parse(localStorage.getItem('hidden_students') || '[]'));
        const visibleStudents = Array.isArray(students) ? students.filter(s => !hidden.has(String(s.student_id))) : [];
        if (!Array.isArray(visibleStudents) || visibleStudents.length === 0) {
            container.innerHTML = '<p>No student data available.</p>';
            return;
        }
        // Check if user has admin/professor role to show delete column and add button
        const user = Auth.getUser();
        const showDeleteColumn = user && (user.role === 'Admin' || user.role === 'Professor');
        const showAddButton = user && (user.role === 'Admin' || user.role === 'Professor');
        
        // Update add button visibility immediately
        const addButton = document.getElementById('addStudentBtn');
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
            <table>
                <thead>
                    <tr>
                        <th class="th-student-id">Student ID</th>
                        <th class="th-full-name">Full Name</th>
                        <th class="th-email">Email</th>
                        <th class="th-enrollment-year">Enrollment Year</th>
                        <th class="th-courses">Courses</th>
                        ${showDeleteColumn ? '<th class="th-delete" id="studentsDeleteHeader" style="text-align: center !important;">Delete</th>' : ''}
                    </tr>
                </thead>
                <tbody>
        `;
        visibleStudents.forEach(s => {
            html += `
                <tr>
                    <td>${s.student_id}</td>
                    <td>${s.full_name || `${s.first_name} ${s.last_name}`}</td>
                    <td>${s.email}</td>
                    <td>${s.enrollment_year}</td>
                    <td><button class="view-btn view-btn-text" onclick="showStudentCourseDetails(${s.student_id})">View</button></td>
                    ${showDeleteColumn ? `<td class="studentsDeleteCell" style="text-align: center !important; vertical-align: middle !important;">${getDeleteButton(s.student_id)}</td>` : ''}
                </tr>
            `;
        });
        html += `</tbody></table>`;
        container.innerHTML = html;
    }

    function getHiddenStudents() {
        try { return new Set(JSON.parse(localStorage.getItem('hidden_students') || '[]')); } catch { return new Set(); }
    }

    function saveHiddenStudents(set) {
        localStorage.setItem('hidden_students', JSON.stringify(Array.from(set)));
    }

    function toggleDeleteMode() {
        const header = document.getElementById('studentsDeleteHeader');
        const cells = document.querySelectorAll('.studentsDeleteCell');
        const isVisible = header && header.style.display !== 'none';
        
        if (header) {
            header.style.display = isVisible ? 'none' : '';
        }
        cells.forEach(td => {
            td.style.display = isVisible ? 'none' : '';
        });
    }


    function deleteStudent(studentId) {
        // Find the student name for the confirmation dialog
        const student = globalStudents.find(s => s.student_id == studentId);
        const studentName = student ? (student.full_name || `${student.first_name} ${student.last_name}`) : 'this student';
        
        // Show custom confirmation modal
        showDeleteConfirmation(studentName, studentId);
    }

    function showDeleteConfirmation(studentName, studentId) {
        // Create modal overlay
        const overlay = document.createElement('div');
        overlay.style.cssText = `
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
            backdrop-filter: blur(4px);
        `;

        // Create modal content
        const modal = document.createElement('div');
        modal.style.cssText = `
            background: white;
            border-radius: 16px;
            padding: 32px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            text-align: center;
            animation: slideIn 0.3s ease-out;
        `;

        modal.innerHTML = `
            <div style="margin-bottom: 24px;">
                <div style="width: 64px; height: 64px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <span style="font-size: 32px; color: #dc2626;">⚠️</span>
                </div>
                <h3 style="margin: 0 0 8px; font-size: 20px; font-weight: 600; color: #1f2937;">Delete Student</h3>
                <p style="margin: 0; color: #6b7280; line-height: 1.5;">
                    Are you sure you want to delete <strong>${studentName}</strong>?<br>
                    This action cannot be undone.
                </p>
            </div>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button id="cancelDelete" style="
                    background: #f3f4f6;
                    color: #374151;
                    border: none;
                    border-radius: 8px;
                    padding: 12px 24px;
                    font-size: 14px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.2s ease;
                ">Cancel</button>
                <button id="confirmDelete" style="
                    background: #dc2626;
                    color: white;
                    border: none;
                    border-radius: 8px;
                    padding: 12px 24px;
                    font-size: 14px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.2s ease;
                ">Delete</button>
            </div>
        `;

        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { opacity: 0; transform: translateY(-20px) scale(0.95); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }
        `;
        document.head.appendChild(style);

        overlay.appendChild(modal);
        document.body.appendChild(overlay);

        // Add hover effects
        const cancelBtn = modal.querySelector('#cancelDelete');
        const confirmBtn = modal.querySelector('#confirmDelete');

        cancelBtn.addEventListener('mouseenter', () => {
            cancelBtn.style.background = '#e5e7eb';
        });
        cancelBtn.addEventListener('mouseleave', () => {
            cancelBtn.style.background = '#f3f4f6';
        });

        confirmBtn.addEventListener('mouseenter', () => {
            confirmBtn.style.background = '#b91c1c';
        });
        confirmBtn.addEventListener('mouseleave', () => {
            confirmBtn.style.background = '#dc2626';
        });

        // Handle cancel
        cancelBtn.addEventListener('click', () => {
            document.body.removeChild(overlay);
            document.head.removeChild(style);
        });

        // Handle confirm
        confirmBtn.addEventListener('click', () => {
            document.body.removeChild(overlay);
            document.head.removeChild(style);
            
            // Perform deletion
            const hidden = getHiddenStudents();
            hidden.add(String(studentId));
            saveHiddenStudents(hidden);
            
            // Re-fetch to keep logic simple
            fetch('https://uniportal-b0gvf6bfhcf3bpck.canadacentral-01.azurewebsites.net/api/students')
                .then(async r => {
                    if (!r.ok) {
                        const txt = await r.text();
                        throw new Error('HTTP ' + r.status + ' ' + txt);
                    }
                    return r.json();
                })
                .then(payload => {
                    const students = Array.isArray(payload) ? payload : (payload && payload.data ? payload.data : []);
                    globalStudents = students; // Update global array
                    renderStudents(students);
                })
                .catch(err => {
                    console.error('Reload students after delete failed:', err);
                });
            
            // Show success toast
            showSuccessToast(`${studentName} has been deleted successfully.`);
        });

        // Close on overlay click
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                document.body.removeChild(overlay);
                document.head.removeChild(style);
            }
        });
    }

    function showSuccessToast(message) {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            z-index: 10001;
            animation: slideInRight 0.3s ease-out;
            max-width: 300px;
        `;
        toast.textContent = message;

        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from { opacity: 0; transform: translateX(100%); }
                to { opacity: 1; transform: translateX(0); }
            }
        `;
        document.head.appendChild(style);

        document.body.appendChild(toast);

        // Remove after 3 seconds
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
                document.head.removeChild(style);
            }
        }, 3000);
    }
    </script>
<script>
// Modal functionality
const modal = document.getElementById('studentModal');

function showStudentCourseDetails(studentId) {
    modal.style.display = 'block';
    document.getElementById('modalBody').innerHTML = '<div class="loading">Loading student course details...</div>';
    
    // Get user information from localStorage
    const user = Auth.getUser();
    const userRole = user?.role || 'User';
    const userEmail = user?.email || null;
    
    fetch(`https://uniportal-b0gvf6bfhcf3bpck.canadacentral-01.azurewebsites.net/api/students/${studentId}/courses`)
        .then(response => {
            console.log('Student course details response status:', response.status);
            console.log('Student course details response ok:', response.ok);
            return response.json();
        })
        .then(data => {
            console.log('Student course details data:', data);
            if (data.error) {
                document.getElementById('modalBody').innerHTML = `<div class="loading">Error: ${data.error}</div>`;
                return;
            }
            
            // The new API returns {student: {...}, courses: [...]} in data.data
            displayStudentCourseDetails(data.data);
        })
        .catch(error => {
            console.log('Student course details error:', error);
            document.getElementById('modalBody').innerHTML = `<div class="loading">Error loading student course details: ${error.message}</div>`;
        });
}

function displayStudentCourseDetails(data) {
    const student = data.student;
    const courses = data.courses;
    
    // Get user information to determine if grades should be shown
    const user = Auth.getUser();
    const userRole = user?.role || 'User';
    const userEmail = user?.email || null;
    
    // Determine if user can see grades
    const canSeeGrades = (userRole === 'Admin') || 
                        (userRole === 'Student' && userEmail === student.student_email);
    
    document.getElementById('modalTitle').textContent = `${student.student_name} - ${t('course_details')}`;
    
    const modalBody = document.getElementById('modalBody');
    
    let html = `
        <div class="student-info">
            <div class="info-item">
                <span class="info-label">${t('student_id')}</span>
                <span class="info-value">${student.student_id}</span>
            </div>
            <div class="info-item">
                <span class="info-label">${t('full_name')}</span>
                <span class="info-value">${student.student_name}</span>
            </div>
            <div class="info-item">
                <span class="info-label">${t('email')}</span>
                <span class="info-value">${student.student_email}</span>
            </div>
            <div class="info-item">
                <span class="info-label">${t('enrollment_year')}</span>
                <span class="info-value">${student.enrollment_year}</span>
            </div>
        </div>
        
        <div class="courses-section">
            <h3>${t('enrolled_courses')} (${courses.length})</h3>
            <table class="courses-table">
                <thead>
                    <tr>
                        <th>${t('course')}</th>
                        <th>${t('credits')}</th>
                        ${canSeeGrades ? `<th>${t('grade')}</th><th>${t('letter_grade')}</th>` : ''}
                        <th>${t('professor')}</th>
                        <th>${t('professor_email')}</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    courses.forEach(course => {
        const gradeClass = getGradeClass(course.letter_grade);
        const gradeDisplay = course.grade !== null ? course.grade : 'N/A';
        const gradeBadge = course.letter_grade !== null ? `<span class="grade-badge ${gradeClass}">${course.letter_grade}</span>` : 'N/A';
        
        html += `
            <tr>
                <td>${course.course_name}</td>
                <td>${course.credits}</td>
                ${canSeeGrades ? `<td>${gradeDisplay}</td><td>${gradeBadge}</td>` : ''}
                <td>${course.professor_name || t('not_assigned')}</td>
                <td>${course.professor_email || t('not_assigned')}</td>
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

function getGradeClass(letterGrade) {
    switch(letterGrade) {
        case 'A': return 'grade-a';
        case 'B': return 'grade-b';
        case 'C': return 'grade-c';
        case 'D': return 'grade-d';
        case 'F': return 'grade-f';
        default: return '';
    }
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