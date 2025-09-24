// Authentication utilities
class Auth {
    static isLoggedIn() {
        const token = localStorage.getItem('token');
        return token !== null && token !== undefined;
    }

    static getToken() {
        return localStorage.getItem('token');
    }

    static getUser() {
        const userStr = localStorage.getItem('user');
        return userStr ? JSON.parse(userStr) : null;
    }

    static logout() {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = 'login.html';
    }

    static updateLoginButton() {
        const loginButton = document.getElementById('loginButton');
        if (loginButton) {
            if (this.isLoggedIn()) {
                const user = this.getUser();
                const userName = user?.firstName || user?.email || 'User';
                const userRole = user?.role || 'User';
                
                // Translate role if translation function is available
                let translatedRole = userRole;
                if (typeof t === 'function') {
                    if (userRole.toLowerCase() === 'admin') {
                        translatedRole = t('role_admin');
                    } else if (userRole.toLowerCase() === 'student') {
                        translatedRole = t('role_student');
                    }
                }
                
                // Use translation template if available
                if (typeof t === 'function') {
                    const logoutTemplate = t('logout_with_user');
                    loginButton.textContent = logoutTemplate
                        .replace('{name}', userName)
                        .replace('{role}', translatedRole);
                } else {
                    loginButton.textContent = `Logout (${userName} - ${translatedRole})`;
                }
                loginButton.onclick = () => this.logout();
            } else {
                if (typeof t === 'function') {
                    loginButton.textContent = t('login');
                } else {
                    loginButton.textContent = 'Log in';
                }
                loginButton.onclick = () => window.location.href = 'login.html';
            }
        }
    }

    static checkAuth() {
        if (!this.isLoggedIn()) {
            // Allow access to home page, contact page and login page without authentication
            const currentPage = window.location.pathname.split('/').pop();
            if (currentPage !== 'home.php' && currentPage !== 'contact_page.php' && currentPage !== 'login.html') {
                this.showLoginPopup();
                return false;
            }
        }
        return true;
    }

    static showLoginPopup() {
        // Create modal overlay
        const overlay = document.createElement('div');
        overlay.id = 'loginModalOverlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        `;

        // Create modal content
        const modal = document.createElement('div');
        modal.id = 'loginModal';
        modal.style.cssText = `
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 400px;
            width: 90%;
        `;

        modal.innerHTML = `
            <h2 style="margin: 0 0 20px 0; color: #333;">Login Required</h2>
            <p style="margin: 0 0 25px 0; color: #666; line-height: 1.5;">
                You need to be logged in to access this page. Please log in to continue.
            </p>
            <div style="display: flex; gap: 15px; justify-content: center;">
                <button id="loginModalBtn" style="
                    background: #007bff;
                    color: white;
                    border: none;
                    padding: 12px 24px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                    transition: background 0.3s;
                ">Log In</button>
                <button id="cancelModalBtn" style="
                    background: #6c757d;
                    color: white;
                    border: none;
                    padding: 12px 24px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                    transition: background 0.3s;
                ">Cancel</button>
            </div>
        `;

        overlay.appendChild(modal);
        document.body.appendChild(overlay);

        // Add event listeners
        document.getElementById('loginModalBtn').addEventListener('click', () => {
            window.location.href = 'login.html';
        });

        document.getElementById('cancelModalBtn').addEventListener('click', () => {
            this.hideLoginPopup();
        });

        // Close modal when clicking outside
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                this.hideLoginPopup();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.hideLoginPopup();
            }
        });
    }

    static hideLoginPopup() {
        const overlay = document.getElementById('loginModalOverlay');
        if (overlay) {
            overlay.remove();
        }
    }

    static async makeAuthenticatedRequest(url, options = {}) {
        const token = this.getToken();
        if (!token) {
            throw new Error('No authentication token');
        }

        const defaultOptions = {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                ...options.headers
            }
        };

        const response = await fetch(url, { ...defaultOptions, ...options });
        
        if (response.status === 401) {
            // Token expired or invalid
            this.logout();
            return null;
        }

        return response;
    }
}

// Initialize authentication on page load
document.addEventListener('DOMContentLoaded', function() {
    Auth.checkAuth();
    Auth.updateLoginButton();
});
