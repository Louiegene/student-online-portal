document.addEventListener("DOMContentLoaded", function () {
        // Get current page URL
        const currentPage = window.location.pathname.split("/").pop();

        // Get all navbar links
        const navLinks = document.querySelectorAll(".nav-link");

        navLinks.forEach(link => {
            const page = link.getAttribute("data-page");

            // Remove 'active' from all links first
            link.classList.remove("active");

            // Add 'active' class to the matching page
            if (currentPage.includes(page)) {
                link.classList.add("active");
            }
        });
    });
