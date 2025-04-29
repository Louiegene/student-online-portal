document.addEventListener("DOMContentLoaded", function () {
    fetch("../../src/controllers/getUser.php")
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const userDropdown = document.getElementById("loggedInUserDropdown");
            if (userDropdown) {
                if (data.status === "success" && data.full_name) {
                    userDropdown.textContent = data.full_name;
                } else {
                    userDropdown.textContent = "Unknown Name";
                }
            } else {
                console.error("Element with ID 'loggedInUserDropdown' not found.");
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
            const userDropdown = document.getElementById("loggedInUserDropdown");
            if (userDropdown) {
                userDropdown.textContent = "Error";
            }
        });
});