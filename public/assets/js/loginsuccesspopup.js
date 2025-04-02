document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");
    const successPopup = document.getElementById("successPopup");

    if (!loginForm) {
        console.error("Login form not found!");
        return;
    }

    loginForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission

        console.log("Form submitted! Showing popup...");

        // Show the popup
        successPopup.style.display = "block";
        document.body.classList.add("no-scroll"); // Disable background scrolling

        // Hide popup after 2 seconds and redirect
        setTimeout(() => {
            successPopup.style.display = "none";
            document.body.classList.remove("no-scroll"); // Enable scrolling again
            window.location.href = "../../src/views/student_page.html"; // Redirect
        }, 2000);
    });
});
