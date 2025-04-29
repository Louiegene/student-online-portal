document.addEventListener("DOMContentLoaded", () => {
    const emailInput = document.getElementById("email");
    const emailFeedback = document.getElementById("email-feedback");

    const usernameInput = document.getElementById("username");
    const usernameFeedback = document.getElementById("username-feedback");

    let isEmailValid = false;
    let isUsernameValid = false;

    // Helper function for debounce
    function debounce(func, delay) {
        let timeout;
        return function () {
            clearTimeout(timeout);
            timeout = setTimeout(func, delay);
        };
    }

    // Email validation with debounce
    const validateEmail = debounce(() => {
        const email = emailInput.value.trim();
        if (email.length === 0) {
            emailFeedback.textContent = '';
            isEmailValid = false;
            return;
        }

        // Show loading indicator (optional)
        emailFeedback.textContent = "Checking availability...";

        fetch('../../src/controllers/check_email.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `email=${encodeURIComponent(email)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                emailFeedback.textContent = "This email is already in use.";
                emailFeedback.style.color = "red";
                isEmailValid = false;
            } else {
                emailFeedback.textContent = "Email is available.";
                emailFeedback.style.color = "green";
                isEmailValid = true;
            }
        })
        .catch(() => {
            emailFeedback.textContent = "Error checking email. Please try again later.";
            emailFeedback.style.color = "red";
            isEmailValid = false;
        });
    }, 500);  // 500ms debounce delay for email

    emailInput.addEventListener("input", validateEmail);

    // Username validation with debounce
    const validateUsername = debounce(() => {
        const username = usernameInput.value.trim();
        if (username.length === 0) {
            usernameFeedback.textContent = '';
            isUsernameValid = false;
            return;
        }

        // Show loading indicator (optional)
        usernameFeedback.textContent = "Checking availability...";

        fetch('../../src/controllers/check_username.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `username=${encodeURIComponent(username)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                usernameFeedback.textContent = "This username is already taken.";
                usernameFeedback.style.color = "red";
                isUsernameValid = false;
            } else {
                usernameFeedback.textContent = "Username is available.";
                usernameFeedback.style.color = "green";
                isUsernameValid = true;
            }
        })
        .catch(() => {
            usernameFeedback.textContent = "Error checking username. Please try again later.";
            usernameFeedback.style.color = "red";
            isUsernameValid = false;
        });
    }, 500);  // 500ms debounce delay for username

    usernameInput.addEventListener("input", validateUsername);

    const form = document.querySelector("form");
    if (!form) {
        console.error("Form not found.");
        return;
    }

    const submitBtn = form.querySelector("button[type='submit']");
    if (!submitBtn) {
        console.error('Submit button not found!');
        return;
    }

    // Disable submit button initially until both email and username are valid
    submitBtn.disabled = true;

    // Check validity before form submission
    form.addEventListener("submit", (e) => {
        if (!isEmailValid || !isUsernameValid) {
            e.preventDefault();
            alert("Please fix the errors before submitting.");
        }
    });

    // Enable submit button only when both fields are valid
    function checkSubmitButtonState() {
        if (isEmailValid && isUsernameValid) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    // Watch for changes and check if form can be submitted
    emailInput.addEventListener("input", checkSubmitButtonState);
    usernameInput.addEventListener("input", checkSubmitButtonState);
});
