document.addEventListener("DOMContentLoaded", function () {
    // Get all required elements
    const newPassword = document.getElementById("newPassword");
    const confirmPassword = document.getElementById("confirmPassword");
    const errorMessage = document.getElementById("errorMessage");
    
    // Check if critical elements exist
    const criticalElements = [];
    
    if (!newPassword) criticalElements.push("newPassword");
    if (!confirmPassword) criticalElements.push("confirmPassword");
    if (!errorMessage) criticalElements.push("errorMessage");
    
    // If any critical elements are missing, log error and exit
    if (criticalElements.length > 0) {
        console.error(`Password validation error: Missing critical elements: ${criticalElements.join(', ')}`);
        return; // Exit the function without setting up event listeners
    }
    
    // Get password guide elements
    const passwordGuide = {
        lengthCheck: document.getElementById("lengthCheck"),
        uppercaseCheck: document.getElementById("uppercaseCheck"),
        lowercaseCheck: document.getElementById("lowercaseCheck"),
        numberCheck: document.getElementById("numberCheck"),
        specialCheck: document.getElementById("specialCheck"),
    };
    
    // Check if all guide elements exist
    const missingGuideElements = Object.entries(passwordGuide)
        .filter(([key, element]) => !element)
        .map(([key]) => key);
        
    if (missingGuideElements.length > 0) {
        console.warn(`Password guide elements missing: ${missingGuideElements.join(', ')}`);
        // Continue execution, as these aren't critical
    }
    
    // Set up event listeners for password fields
    newPassword.addEventListener("input", function () {
        const password = newPassword.value;
        validatePassword(password);
        checkPasswordMatch();
    });
    
    confirmPassword.addEventListener("input", function () {
        checkPasswordMatch();
    });
    
    // Set up toggle password functionality using class selector
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    if (toggleButtons.length === 0) {
        console.warn("No toggle password buttons found with class 'toggle-password'.");
    } else {
        toggleButtons.forEach(button => {
            button.addEventListener("click", function() {
                const targetId = this.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);
                
                if (targetInput) {
                    // Toggle the input type
                    targetInput.type = targetInput.type === "password" ? "text" : "password";
                    
                    // Optionally update button text/icon if needed
                    // this.textContent = targetInput.type === "password" ? "Show" : "Hide";
                } else {
                    console.warn(`Toggle button target '${targetId}' not found.`);
                }
            });
        });
    }
    
    // Check if passwords match
    function checkPasswordMatch() {
        if (newPassword.value.trim() === "" || confirmPassword.value.trim() === "") {
            errorMessage.style.display = "none"; // Hide error when either field is empty
        } else if (confirmPassword.value !== newPassword.value) {
            errorMessage.textContent = "❌ Passwords do not match!";
            errorMessage.style.display = "block";
        } else {
            errorMessage.style.display = "none";
        }
    }
    
    // Validate password requirements
    function validatePassword(password) {
        // Only update guide elements that exist
        if (passwordGuide.lengthCheck) {
            passwordGuide.lengthCheck.textContent = password.length >= 8 ? 
                "✅ At least 8 characters" : "❌ At least 8 characters";
        }
        
        if (passwordGuide.uppercaseCheck) {
            passwordGuide.uppercaseCheck.textContent = /[A-Z]/.test(password) ? 
                "✅ At least 1 uppercase letter" : "❌ At least 1 uppercase letter";
        }
        
        if (passwordGuide.lowercaseCheck) {
            passwordGuide.lowercaseCheck.textContent = /[a-z]/.test(password) ? 
                "✅ At least 1 lowercase letter" : "❌ At least 1 lowercase letter";
        }
        
        if (passwordGuide.numberCheck) {
            passwordGuide.numberCheck.textContent = /\d/.test(password) ? 
                "✅ At least 1 number" : "❌ At least 1 number";
        }
        
        if (passwordGuide.specialCheck) {
            passwordGuide.specialCheck.textContent = /[@$!%*?&]/.test(password) ? 
                "✅ At least 1 special character (@$!%*?&)" : "❌ At least 1 special character (@$!%*?&)";
        }
    }
});