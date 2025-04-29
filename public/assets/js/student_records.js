// Define all functions first

// Add getUserId function to get the user ID from session/localStorage, URL params, or cookie
function loadGrades(userId, gradeLevel, semester, quarter) {
    const tableId = `grade${gradeLevel}-sem${semester}-q${quarter}-grades`;
    const tableBody = document.getElementById(tableId);

    if (!tableBody) {
        console.warn(`Table body with ID #${tableId} not found.`);
        return;
    }

    tableBody.innerHTML = `
        <tr class="loading-row">
            <td colspan="3" class="text-center py-2">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Loading grades...
            </td>
        </tr>
    `;

    const quarterString = convertQuarterToString(quarter);
    const gradeLevelString = String(gradeLevel);

    const url = `../../src/controllers/get_grades.php?user_id=${userId}&grade_level=${gradeLevelString}&semester=${semester}&quarter=${quarterString}`;
    console.log(`Fetching grades from: ${url}`);

    fetch(url)
        .then(response => {
            console.log(`Response status for G${gradeLevel}-S${semester}-Q${quarter}: ${response.status} ${response.statusText}`);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log(`Grades received for G${gradeLevel}-S${semester}-Q${quarter}:`, data);
            tableBody.innerHTML = '';

            // Will inject the school year into the page
            if (data.school_year) {
                const schoolYearSpan = document.getElementById('school-year');;
                if (schoolYearSpan) {
                    schoolYearSpan.textContent = data.school_year || 'N/A';
                }
            }

            if ((data.status === 'success' || data.success === true) && Array.isArray(data.grades) && data.grades.length) {
                data.grades.forEach(grade => {
                    const subjectName = grade.subject_name || grade.subjectName || grade.subject || 'Unknown Subject';
                    const gradeValue = grade.grade || grade.value || grade.score || 'N/A';

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${subjectName}</td>
                        <td>${gradeValue}</td>
                        <td>${getRemarks(gradeValue)}</td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                const noDataRow = `
                    <tr class="no-data-row">
                        <td colspan="3" class="text-center py-2">
                            ${data.message || 'No Grades Found.'}
                        </td>
                    </tr>
                `;
                tableBody.innerHTML = noDataRow;
            }
        })
        .catch(error => {
            console.error(`Error fetching grades for G${gradeLevel}-S${semester}-Q${quarter}:`, error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-danger py-2">
                        Failed to load grades: ${error.message}
                    </td>
                </tr>
            `;
        });
}



// Function to load student info - FIXED to include userId
function loadStudentInfo(userId) {
    console.log('Loading student info for user ID:', userId);

    // Send both student_id and user_id to be safe
    // Also convert userId to string to ensure type compatibility
    fetch(`../../src/controllers/get_student_info_records.php?user_id=${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data);
            if (data.success || data.status === 'success') {
                // Try to extract fields from both data formats
                const student = data.student || data;

                const fields = {
                    'student-first-name': student.first_name || (student.full_name ? student.full_name.split(' ')[0] : 'N/A'),
                    'student-last-name': student.last_name || (student.full_name ? student.full_name.split(' ').pop() : 'N/A'),
                    'student-lrn': student.lrn || 'N/A',
                    'student-track': student.trackname || student.track || 'N/A',
                    'student-strand': student.strandname || student.strand || 'N/A'
                };

                for (const [id, value] of Object.entries(fields)) {
                    const el = document.getElementById(id);
                    if (el) el.textContent = value || 'N/A';
                }
            } else {
                showError(data.message || 'Failed to load student information');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showError(`Error loading student information: ${error.message}`);
        });
}

// Function to load all grades (called inside the student info load)
// FIXED: Updated to match the actual HTML structure and the backend expectations
function loadAllGrades(userId) {
    // Map semester to appropriate quarters based on HTML structure
    // Semester 1 has quarters 1 and 2
    // Semester 2 has quarters 3 and 4
    const gradeQuarterMap = [
        { gradeLevel: 11, semester: 1, quarters: [1, 2] },
        { gradeLevel: 11, semester: 2, quarters: [3, 4] },
        { gradeLevel: 12, semester: 1, quarters: [1, 2] },
        { gradeLevel: 12, semester: 2, quarters: [3, 4] }
    ];

    // Loop through the mapping
    gradeQuarterMap.forEach(({ gradeLevel, semester, quarters }) => {
        quarters.forEach(quarter => {
            loadGrades(userId, gradeLevel, semester, quarter);
        });
    });
}

// Helper function to convert numeric quarter to string format expected by backend
function convertQuarterToString(quarter) {
    switch(parseInt(quarter)) {
        case 1: return '1st';
        case 2: return '2nd';
        case 3: return '3rd';
        case 4: return '4th';
        default: return '1st'; // Default to 1st quarter if invalid
    }
}

// Error display utility
function showError(message) {
    let errorDiv = document.getElementById('error-message');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.id = 'error-message';
        errorDiv.className = 'alert alert-danger mt-3';
        const container = document.querySelector('.content-area') || document.body;
        container.prepend(errorDiv);
    }
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}

// Remarks for grade
function getRemarks(grade) {
    const numGrade = parseFloat(grade);
    if (isNaN(numGrade)) {
        return 'Invalid Grade';
    }
    return numGrade >= 75 ? 'Passed' : 'Failed';
}

// Now call the functions once the DOM is fully loaded
window.addEventListener('DOMContentLoaded', () => {
    // Fetch user ID first
    getUserId().then(userId => {
        if (!userId) {
            showError('Authentication error: No user ID found. Please log in again.');
            return;
        }

        // Log the user ID for debugging
        console.log('User ID retrieved successfully:', userId);

        // Load student info and grades once user is authenticated
        loadStudentInfo(userId);
        loadAllGrades(userId);
    }).catch(error => {
        showError(`Error loading user information: ${error.message}`);
    });
});
