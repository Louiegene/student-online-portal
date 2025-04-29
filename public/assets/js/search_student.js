let students = []; // Store all students
let filteredStudents = []; // Store filtered students (for search functionality)

document.addEventListener('DOMContentLoaded', function () {
    fetchStudents(); // Fetch student data when the page loads

    // Event listener for the search box with debounce
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', debounce(function () {
        filterAndRender(); // Apply filter and render the updated list
    }, 300));  // 300ms debounce delay
});

// Fetch student data from the server
function fetchStudents() {
    fetch('../../src/controllers/get_student.php') // Use the correct URL here
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                students = data.students || [];
                filteredStudents = [...students]; // Initially, no filter applied
                renderTable(filteredStudents); // Render the students (no search applied)
            } else {
                console.error('Failed to fetch students', data.message);
            }
        })
        .catch(err => {
            console.error('Error fetching students:', err);
        });
}

// Debounce helper function to prevent too many re-renders
function debounce(func, delay) {
    let timeout;
    return function () {
        clearTimeout(timeout);
        timeout = setTimeout(func, delay);
    };
}

// Filter the students based on the search input
function filterAndRender() {
    const filter = document.getElementById('searchInput').value.toLowerCase();

    // Filter students based on LRN, Name, Track, or Strand
    filteredStudents = students.filter(student => {
        const lrn = student.lrn ? student.lrn.toLowerCase() : '';
        const name = `${student.first_name || ''} ${student.middle_name || ''} ${student.last_name || ''}`.toLowerCase();
        const track = student.trackname ? student.trackname.toLowerCase() : '';
        const strand = student.strand ? student.strand.toLowerCase() : '';

        return lrn.includes(filter) || name.includes(filter) || track.includes(filter) || strand.includes(filter);
    });

    renderTable(filteredStudents); // Render the filtered students
}

// Function to render the student data into the table
function renderTable(studentsToRender) {
    const studentTableBody = document.getElementById('studentTableBody');
    studentTableBody.innerHTML = ''; // Clear existing rows

    if (studentsToRender.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = '<td colspan="6" style="padding: 10px; text-align: center;">No students found</td>';
        studentTableBody.appendChild(row);
        return;
    }

    // Render filtered students (limit to max 10 rows for the scrollable area)
    studentsToRender.slice(0, 10).forEach(student => {
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

// Call this function after successfully adding a student
function updateStudentList(newStudent) {
    students.push(newStudent); // Add the new student to the list
    filteredStudents = [...students]; // Reset filter
    filterAndRender(); // Reapply filter and re-render the updated list
}
