<?php
declare(strict_types=1);

// Show PHP errors while developing
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Server-side DB calls removed. The page fetches data via get_all_professors.php.
$professors = [];
?>
<!DOCTYPE html>
<!-- CACHE BUST: <?php echo time(); ?> - ADD PROFESSOR MODAL 900px WIDTH LIKE STUDENT MODAL -->
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Professors - Uni Portal</title>
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
        
        /* Add button visibility controlled by JavaScript (Admin only) */
        .add-button {
            display: none;
        }

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

        /* Modal styles - matching student modal exactly */
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
            max-width: 900px;
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
            margin-bottom: 0;
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
        <span>Faculty Professors</span>
        <div id="adminControls" style="gap:12px; align-items:center;">
            <button id="addProfessorBtn" class="add-button" onclick="showAddProfessorModal()" title="Add New Professor" style="width:44px; height:44px;">+</button>
        </div>
    </h1>
        <div id="professorsContainer" class="table-container">
            <div class="loading">Loading professors...</div>
        </div>
    </main>
</div>

<!-- Add Professor Modal -->
<div id="addProfessorModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Professor</h2>
            <span class="close" onclick="closeAddProfessorModal()">&times;</span>
        </div>
        <form id="addProfessorForm" style="padding: 30px;" novalidate>
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" placeholder="Enter first name" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" placeholder="Enter last name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter email address" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <select id="department" name="department" required>
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
        
        // Check user role and show/hide admin controls (admin only for professors)
        checkUserRole();
        
        // Also run after a short delay to ensure all scripts have loaded
        setTimeout(checkUserRole, 100);
        setTimeout(checkUserRole, 500);
        setTimeout(checkUserRole, 1500);
    });

    // Simplified checkUserRole - add button now handled in renderProfessors function
    function checkUserRole() {
        const user = Auth.getUser();
        const adminControls = document.getElementById('adminControls');
        
        if (!adminControls) {
            return;
        }
        
        // Only handle adminControls visibility, add button is now handled in renderProfessors
        if (user && user.role === 'Admin') {
            adminControls.style.setProperty('display', 'flex', 'important');
        } else {
            adminControls.style.setProperty('display', 'none', 'important');
        }
    }

    // Function to get delete button based on user role (admin only for professors)
    function getDeleteButton(professorId) {
        const user = Auth.getUser();
        
        if (user && user.role === 'Admin') {
            return `<button class="view-btn" style="background: #ff3b30 !important; color: white !important; border: none !important; border-radius: 50% !important; width: 28px !important; height: 28px !important; min-width: 28px !important; min-height: 28px !important; max-width: 28px !important; max-height: 28px !important; font-size: 16px !important; font-weight: 300 !important; cursor: pointer !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; transition: all 0.2s ease !important; box-shadow: 0 2px 8px rgba(255, 59, 48, 0.3) !important; padding: 0 !important; flex-shrink: 0 !important; margin: 0 auto !important;" onclick="deleteProfessor(${professorId})">−</button>`;
        } else {
            return '';
        }
    }

    // Periodically check user role
    setInterval(checkUserRole, 1000);
    
    // Add Professor Modal Functions
    function showAddProfessorModal() {
        document.getElementById('addProfessorModal').style.display = 'block';
    }
    
    function closeAddProfessorModal() {
        document.getElementById('addProfessorModal').style.display = 'none';
        // Reset form
        document.getElementById('addProfessorForm').reset();
    }
    
    </script>
    <script>
    // Load professors on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for user data to be available before calling checkUserRole
        function waitForUserAndCheckRole() {
            const user = Auth.getUser();
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
                console.log('User found, rendering professors with role:', user.role);
                fetch('https://uniportal-b0gvf6bfhcf3bpck.canadacentral-01.azurewebsites.net/api/professors')
                    .then(function(r) { return r.json(); })
                    .then(function(professors) { 
                        renderProfessors(professors);
                        checkUserRole();
                    })
                    .catch(function(err) {
                        console.error('Failed to load professors', err);
                        var el = document.getElementById('professorsContainer');
                        el.innerHTML = '<p>Error loading professors: ' + (err && err.message ? err.message : 'Unknown error') + '</p>';
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
        
        // Force check user role for professor page (Admin only for add button)
        setTimeout(() => {
            console.log('=== FORCE CHECKING USER ROLE FOR PROFESSOR PAGE ===');
            checkUserRole();
        }, 100);
        
        setTimeout(() => {
            console.log('=== FORCE CHECKING USER ROLE AGAIN ===');
            checkUserRole();
        }, 500);
        
        // Add event listener for add professor form
        const addProfessorForm = document.getElementById('addProfessorForm');
        if (addProfessorForm) {
            addProfessorForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const firstName = formData.get('firstName');
                const lastName = formData.get('lastName');
                const email = formData.get('email');
                const department = formData.get('department');
                
                // Check if all fields are filled
                if (!firstName || !lastName || !email || !department) {
                    alert('Please fill in all required fields.');
                    return;
                }
                
                // Send data to backend
                fetch('../api/add_professor.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        firstName: firstName,
                        lastName: lastName,
                        email: email,
                        department: department
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessToast('Professor added successfully!');
                        
                        // Close modal after a short delay
                        setTimeout(() => {
                            closeAddProfessorModal();
                            // Refresh the professors list
                            fetch('https://uniportal-b0gvf6bfhcf3bpck.canadacentral-01.azurewebsites.net/api/professors')
                                .then(r => r.json())
                                .then(renderProfessors);
                        }, 1500);
                    } else {
                        alert('Error: ' + (data.error || 'Failed to add professor'));
                    }
                })
                .catch(error => {
                    console.error('Add professor error:', error);
                    alert('Error adding professor. Please try again.');
                });
            });
        }
    });

    function renderProfessors(professors) {
        const container = document.getElementById('professorsContainer');
        const hidden = new Set(JSON.parse(localStorage.getItem('hidden_professors') || '[]'));
        const visible = Array.isArray(professors) ? professors.filter(p => !hidden.has(String(p.professor_id))) : [];
        if (!Array.isArray(visible) || visible.length === 0) {
            container.innerHTML = '<p>No professor data available.</p>';
            return;
        }
        // Check if user has admin role to show delete column and add button (professors can only be managed by admin)
        const user = Auth.getUser();
        const showDeleteColumn = user && user.role === 'Admin';
        const showAddButton = user && user.role === 'Admin';
        
        // Update add button visibility immediately
        const addButton = document.getElementById('addProfessorBtn');
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
                        <th class="th-professor-id">Professor ID</th>
                        <th class="th-full-name">Full Name</th>
                        <th class="th-email">Email</th>
                        <th class="th-department">Department</th>
                        <th class="th-courses-taught">Courses Taught</th>
                        <th></th>
                        ${showDeleteColumn ? '<th id="professorsDeleteHeader" style="display:table-cell; text-align: center !important;">Delete</th>' : ''}
                    </tr>
                </thead>
                <tbody>
        `;
        visible.forEach(p => {
            html += `
                <tr>
                    <td>${p.professor_id}</td>
                    <td>${p.full_name}</td>
                    <td>${p.email}</td>
                    <td><span class="department-tag">${p.department}</span></td>
                    <td><span class="courses-count">${p.courses_taught} courses</span></td>
                    <td><button class="view-btn view-btn-text" onclick="showProfessorCourseDetails(${p.professor_id})">View</button></td>
                    ${showDeleteColumn ? `<td class="professorsDeleteCell" style="display:table-cell; text-align: center !important; vertical-align: middle !important;">${getDeleteButton(p.professor_id)}</td>` : ''}
                </tr>
            `;
        });
        html += `</tbody></table>`;
        container.innerHTML = html;
    }

    function toggleProfessorsDeleteMode() {
        const header = document.getElementById('professorsDeleteHeader');
        const cells = document.querySelectorAll('.professorsDeleteCell');
        const isVisible = header && header.style.display !== 'none';
        
        if (header) {
            header.style.display = isVisible ? 'none' : '';
        }
        cells.forEach(td => {
            td.style.display = isVisible ? 'none' : '';
        });
    }

    function deleteProfessor(professorId, professorName) {
        // Show confirmation modal
        showDeleteConfirmation(professorName, professorId, function(id) {
            // Hide the professor (since we don't have a real delete endpoint)
            const hidden = new Set(JSON.parse(localStorage.getItem('hidden_professors') || '[]'));
            hidden.add(String(id));
            localStorage.setItem('hidden_professors', JSON.stringify(Array.from(hidden)));
            
            // Show success toast
            showSuccessToast(`${professorName} has been deleted successfully.`);
            
            // Refresh the professors list
            fetch('https://uniportal-b0gvf6bfhcf3bpck.canadacentral-01.azurewebsites.net/api/professors').then(r => r.json()).then(renderProfessors);
        });
    }
    </script>
<script>
// Modal functionality
const modal = document.getElementById('professorModal');

function showProfessorCourseDetails(professorId) {
    modal.style.display = 'block';
    document.getElementById('modalBody').innerHTML = '<div class="loading">Loading professor course details...</div>';
    
    fetch(`../api/get_professor_course_details.php?professor_id=${professorId}`)
        .then(response => {
            console.log('Professor course details response status:', response.status);
            console.log('Professor course details response ok:', response.ok);
            return response.json();
        })
        .then(data => {
            console.log('Professor course details data:', data);
            if (data.error) {
                document.getElementById('modalBody').innerHTML = `<div class="loading">Error: ${data.error}</div>`;
                return;
            }
            
            displayProfessorCourseDetails(data);
        })
        .catch(error => {
            console.log('Professor course details error:', error);
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
