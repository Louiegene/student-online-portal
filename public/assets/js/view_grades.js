/**
 * Grades page JavaScript functionality
 */
document.addEventListener("DOMContentLoaded", function() {
    // Get references to form elements
    const gradesFilterForm = document.getElementById('gradesFilterForm');
    const gradeLevelSelect = document.getElementById('gradeLevel');
    const semesterSelect = document.getElementById('semesterFilter');
    const quarterSelect = document.getElementById('quarterFilter');
    
    // Set up change event listeners for automatic form submission
    if (gradesFilterForm) {
        // Add change listeners to each filter dropdown
        gradeLevelSelect.addEventListener('change', handleFilterChange);
        semesterSelect.addEventListener('change', handleFilterChange);
        quarterSelect.addEventListener('change', handleFilterChange);
        
        // Initialize username in navigation if available
        initializeUserDisplay();
    }
    
    /**
     * Handles filter dropdown changes
     * Automatically submits the form when any filter is changed
     */
    function handleFilterChange() {
        // Submit the form automatically when a filter changes
        gradesFilterForm.submit();
    }
    
    /**
     * Initialize user display from session storage if available
     */
    function initializeUserDisplay() {
        let username = sessionStorage.getItem("username");
        const userDisplayElement = document.getElementById("loggedInUserDropdown");
        
        if (username && userDisplayElement) {
            // Only update if the element doesn't already have content (from PHP)
            if (!userDisplayElement.textContent.trim()) {
                userDisplayElement.textContent = username;
            }
        }
    }
    
    /**
     * Apply table row highlighting on hover
     */
    const tableRows = document.querySelectorAll('#gradesTable tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseover', function() {
            this.classList.add('table-hover-highlight');
        });
        
        row.addEventListener('mouseout', function() {
            this.classList.remove('table-hover-highlight');
        });
    });
});