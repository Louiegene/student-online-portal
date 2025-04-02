document.addEventListener("DOMContentLoaded", function () {
    fetch("../../src/controllers/getUser.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok " + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log(data); // Debugging: Check the data returned from PHP

            const loggedInUserElement = document.getElementById("loggedInUser");
            const loggedInUserDropdownElement = document.getElementById("loggedInUserDropdown");

            if (data.status === "success") {
                if (loggedInUserElement) {
                    loggedInUserElement.textContent = data.username;
                }
                if (loggedInUserDropdownElement) {
                    loggedInUserDropdownElement.textContent = data.full_name;
                }
            } else {
                if (loggedInUserElement) {
                    loggedInUserElement.textContent = "Guest";
                }
                if (loggedInUserDropdownElement) {
                    loggedInUserDropdownElement.textContent = "Unknown Name";
                }
            }
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
            const loggedInUserElement = document.getElementById("loggedInUser");
            const loggedInUserDropdownElement = document.getElementById("loggedInUserDropdown");

            if (loggedInUserElement) {
                loggedInUserElement.textContent = "Guest";
            }
            if (loggedInUserDropdownElement) {
                loggedInUserDropdownElement.textContent = "Unknown Name";
            }
        });
});
