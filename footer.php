<!-- Footer Section -->
<footer>
    <div class="footer-content">
        <p>&copy; 2024 Company. All Rights Reserved.</p>
        <div class="footer-icons">
            <a href="https://www.linkedin.com/in/goce-shopkoski-315998176/" target="_blank" rel="noopener" aria-label="LinkedIn">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="24" height="24" rx="4" fill="#0A66C2"/>
                    <path d="M7.5 8.5C8.32843 8.5 9 7.82843 9 7C9 6.17157 8.32843 5.5 7.5 5.5C6.67157 5.5 6 6.17157 6 7C6 7.82843 6.67157 8.5 7.5 8.5Z" fill="white"/>
                    <rect x="6" y="10" width="3" height="8" rx="1.5" fill="white"/>
                    <path d="M12 10H14.5C16.1569 10 17.5 11.3431 17.5 13V18H15V13.5C15 13.2239 14.7761 13 14.5 13C14.2239 13 14 13.2239 14 13.5V18H12V10Z" fill="white"/>
                </svg>
            </a>
            <a href="https://www.instagram.com/_shopkoskig/" target="_blank" rel="noopener" aria-label="Instagram">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="24" height="24" rx="4" fill="#E1306C"/>
                    <path d="M12 8.5C10.067 8.5 8.5 10.067 8.5 12C8.5 13.933 10.067 15.5 12 15.5C13.933 15.5 15.5 13.933 15.5 12C15.5 10.067 13.933 8.5 12 8.5ZM12 14C10.8954 14 10 13.1046 10 12C10 10.8954 10.8954 10 12 10C13.1046 10 14 10.8954 14 12C14 13.1046 13.1046 14 12 14Z" fill="white"/>
                    <circle cx="16.5" cy="7.5" r="1.5" fill="white"/>
                </svg>
            </a>
        </div>
    </div>
</footer>

<style>
footer {
    background: var(--primary-blue);
    color: var(--white);
    padding: 20px;
    text-align: center;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

.footer-content {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.footer-content p {
    margin: 0;
    font-size: 14px;
}

.footer-icons {
    display: flex;
    gap: 15px;
    align-items: center;
}

.footer-icons a {
    transition: transform 0.3s ease;
}

.footer-icons a:hover {
    transform: scale(1.1);
}

@media (max-width: 768px) {
    .footer-content {
        flex-direction: column;
        gap: 15px;
    }
}
</style>



