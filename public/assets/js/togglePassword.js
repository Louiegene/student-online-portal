document.addEventListener('DOMContentLoaded', () => {
    // Select all toggle buttons
    const toggleButtons = document.querySelectorAll('.toggle-password');
  
    toggleButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');
            const passwordField = document.getElementById(targetId);
  
            if (passwordField) {
                const isPasswordVisible = passwordField.type === 'password';
                passwordField.type = isPasswordVisible ? 'text' : 'password';
  
                // Update button's aria-label for accessibility
                button.setAttribute('aria-label', isPasswordVisible ? 'Hide password' : 'Show password');
  
                // Change the SVG icon based on password visibility
                button.innerHTML = isPasswordVisible ? `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                ` : `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                `;
            } else {
                console.warn(`Password field with id "${targetId}" not found.`);
            }
        });
    });
  });