document.addEventListener('DOMContentLoaded', function () {
    let allStudents = []; // Store all student data

    const studentTableBody = document.getElementById('studentTableBody');
    const searchInput = document.getElementById('searchInput');
    const loadingMessage = document.getElementById('loadingMessage');

    // Fetch student data
    function fetchStudentData() {
        if (loadingMessage) loadingMessage.style.display = 'block'; // Show loading safely

        return fetch('../../src/controllers/get_student.php')
            .then(response => response.json())
            .then(data => {
                console.log('Fetched Data:', data); // Debug output

                if (data.status === 'success') {
                    allStudents = Array.isArray(data.students) ? data.students : [];
                    renderStudents(allStudents);
                } else {
                    console.error('Fetch Error:', data.message);
                    if (studentTableBody) {
                        studentTableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Error loading students.</td></tr>';
                    }
                }
            })
            .catch(error => {
                console.error('Network Error:', error);
                if (studentTableBody) {
                    studentTableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Failed to fetch student data.</td></tr>';
                }
            })
            .finally(() => {
                if (loadingMessage) loadingMessage.style.display = 'none'; // Hide loading safely
            });
    }

    // Render student rows
    function renderStudents(students) {
        if (!studentTableBody) return;

        studentTableBody.innerHTML = ''; // Clear table first

        if (students.length === 0) {
            studentTableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No students found.</td></tr>';
            return;
        }

        students.forEach(student => {
            const row = document.createElement('tr');
            row.dataset.studentId = student.student_id;
            row.dataset.userId = student.user_id;

            row.innerHTML = `
                <td style="padding: 8px; border: 1px solid #dee2e6;">${student.lrn}</td>
                <td style="padding: 8px; border: 1px solid #dee2e6;">${student.first_name} ${student.last_name}</td>
                <td style="padding: 8px; border: 1px solid #dee2e6;">${student.grade_level} ${student.section}</td>
                <td style="padding: 8px; border: 1px solid #dee2e6;">${student.trackname}</td>
                <td style="padding: 8px; border: 1px solid #dee2e6;">${student.strandname}</td>
                <td style="padding: 8px; border: 1px solid #dee2e6;">
                    <button 
                        class="btn btn-sm btn-primary view-btn" 
                        data-id="${student.student_id}" 
                        data-user-id="${student.user_id}" 
                        title="View Grade">View Grades</button>
                </td>
            `;
            studentTableBody.appendChild(row);
        });
    }

    // Filter function
    function filterStudents(searchTerm) {
        const lowerTerm = searchTerm.toLowerCase();
        const filtered = allStudents.filter(student =>
            student.lrn.toLowerCase().includes(lowerTerm) ||
            `${student.first_name} ${student.middle_name} ${student.last_name}`.toLowerCase().includes(lowerTerm)
        );
        renderStudents(filtered);
    }

    // Initial load
    fetchStudentData();

    // Search event
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            filterStudents(searchInput.value);
        });
    }
});
