document.addEventListener("DOMContentLoaded", function () {
    const newPassword = document.getElementById("newPassword");
    const confirmPassword = document.getElementById("confirmPassword");
    const errorMessage = document.getElementById("errorMessage");
    const passwordGuide = {
        lengthCheck: document.getElementById("lengthCheck"),
        uppercaseCheck: document.getElementById("uppercaseCheck"),
        lowercaseCheck: document.getElementById("lowercaseCheck"),
        numberCheck: document.getElementById("numberCheck"),
        specialCheck: document.getElementById("specialCheck"),
    };

    newPassword.addEventListener("input", function () {
        const password = newPassword.value;
        validatePassword(password);
        checkPasswordMatch();
    });

    confirmPassword.addEventListener("input", function () {
        checkPasswordMatch();
    });

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

    function validatePassword(password) {
        passwordGuide.lengthCheck.textContent = password.length >= 8 ? "✅ At least 8 characters" : "❌ At least 8 characters";
        passwordGuide.uppercaseCheck.textContent = /[A-Z]/.test(password) ? "✅ At least 1 uppercase letter" : "❌ At least 1 uppercase letter";
        passwordGuide.lowercaseCheck.textContent = /[a-z]/.test(password) ? "✅ At least 1 lowercase letter" : "❌ At least 1 lowercase letter";
        passwordGuide.numberCheck.textContent = /\d/.test(password) ? "✅ At least 1 number" : "❌ At least 1 number";
        passwordGuide.specialCheck.textContent = /[@$!%*?&]/.test(password) ? "✅ At least 1 special character" : "❌ At least 1 special character";
    }

    document.getElementById("togglePassword").addEventListener("click", function () {
        togglePasswordVisibility([newPassword, confirmPassword]);
    });

    function togglePasswordVisibility(inputs) {
        inputs.forEach(input => {
            input.type = input.type === "password" ? "text" : "password";
        });
    }
});
