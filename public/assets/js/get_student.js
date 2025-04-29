document.addEventListener('DOMContentLoaded', function () {
    let allStudents = []; // Store all student data

    const studentTableBody = document.getElementById('studentTableBody');
    const searchInput = document.getElementById('searchInput');
    const loadingMessage = document.getElementById('loadingMessage'); // Optional loading indicator

    // Ensure required elements are present
    if (!studentTableBody || !searchInput) {
        console.warn('Required DOM elements not found. Exiting script.');
        return;
    }

    // Fetch student data from backend
    function fetchStudentData() {
        if (loadingMessage) loadingMessage.style.display = 'block';

        fetch(`../../src/controllers/get_student.php?nocache=${Date.now()}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    allStudents = Array.isArray(data.students) ? data.students : [];
                    renderStudents(allStudents);
                } else {
                    displayError(data.message || 'Failed to load student data.');
                }
            })
            .catch(error => {
                displayError(`Error fetching student data: ${error.message}`);
            })
            .finally(() => {
                if (loadingMessage) loadingMessage.style.display = 'none';
            });
    }

    // Render students to the table
    function renderStudents(students) {
        studentTableBody.innerHTML = ''; // Clear table content

        if (!students.length) {
            const row = document.createElement('tr');
            row.innerHTML = `<td colspan="6" style="padding: 10px; text-align: center;">No students found</td>`;
            studentTableBody.appendChild(row);
            return;
        }

        students.forEach(student => {
            const row = document.createElement('tr');
            row.dataset.studentId = student.student_id;
            row.dataset.fullName = `${student.first_name} ${student.middle_name ?? ''} ${student.last_name}`;
            row.dataset.gradeLevel = student.grade_level;
        
            row.innerHTML = `
                <td style="padding: 8px; border: 1px solid #dee2e6;">${student.lrn}</td>
                <td style="padding: 8px; border: 1px solid #dee2e6;">
                    ${student.first_name} ${student.last_name}
                </td>
                <td style="padding: 8px; border: 1px solid #dee2e6;">${student.grade_level} ${student.section}</td>
                <td style="padding: 8px; border: 1px solid #dee2e6;">${student.trackname}</td>
                <td style="padding: 8px; border: 1px solid #dee2e6;">${student.strandname}</td>
                <td style="padding: 8px; border: 1px solid #dee2e6;">
                    <button class="btn btn-sm btn-primary view-btn" data-id="${student.student_id}" title="View full profile">View</button>
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${student.student_id}">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${student.student_id}">Delete</button>
                </td>
            `;
        
            studentTableBody.appendChild(row);
        });        
    }

    // Filter students by LRN or full name
    function filterStudents(searchTerm) {
        const searchTermLower = searchTerm.toLowerCase();
        const filtered = allStudents.filter(student =>
            student.lrn.toLowerCase().includes(searchTermLower) ||
            `${student.first_name} ${student.middle_name ?? ''} ${student.last_name}`.toLowerCase().includes(searchTermLower)
        );
        renderStudents(filtered);
    }

    // Display fallback message
    function displayError(message) {
        studentTableBody.innerHTML = `
            <tr>
                <td colspan="6" style="padding: 10px; text-align: center; color: red;">${message}</td>
            </tr>
        `;
    }

    // Search input handler
    searchInput.addEventListener('input', () => {
        filterStudents(searchInput.value.trim());
    });

    // Initial data load
    fetchStudentData();
});
