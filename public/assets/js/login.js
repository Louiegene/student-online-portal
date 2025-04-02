document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById("loginForm");
    const errorMessage = document.getElementById("errorMessage");
    const successPopup = document.getElementById("successPopup");
    const failedPopup = document.getElementById("failedPopup");

    if (loginForm) {
        loginForm.addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            let formData = new FormData(this);

            fetch(this.action, {
                method: "POST",
                body: formData,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok " + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === "error") {
                    if (errorMessage) {
                        errorMessage.textContent = data.message; // Show error message
                        errorMessage.style.display = "block";
                    }
                    if (failedPopup) {
                        failedPopup.style.display = "flex";
                        setTimeout(() => {
                            failedPopup.style.display = "none";
                        }, 3000); // Hide after 3 seconds
                    }
                } else {
                    if (successPopup) {
                        successPopup.style.display = "flex";
                        setTimeout(() => {
                            successPopup.style.display = "none";
                            window.location.href = "../../src/views/student_page.html"; // Redirect if login is successful
                        }, 3000); // Hide after 3 seconds
                    }
                }
            })
            .catch(error => {
                console.error("Error:", error);
                if (errorMessage) {
                    errorMessage.textContent = "An error occurred. Please try again.";
                    errorMessage.style.display = "block";
                }
                if (failedPopup) {
                    failedPopup.style.display = "flex";
                    setTimeout(() => {
                        failedPopup.style.display = "none";
                    }, 3000); // Hide after 3 seconds
                }
            });
        });
    } else {
        console.error("Login form not found.");
    }
});
