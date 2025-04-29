document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('addStudentModal');
    const modal = new bootstrap.Modal(modalElement);

    // Manually show modal when button is clicked
    document.getElementById('addStudentGradeBtn').addEventListener('click', function () {
        modal.show();
    });


    // Optionally reset the form when modal is hidden
    modalElement.addEventListener('hidden.bs.modal', function () {
        document.getElementById('addStudentGradeForm').reset();
    });

    // Handle form submission for adding a student
    document.getElementById('addStudentForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('../../src/controllers/add_student.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Student added successfully!',
                    text: data.message,
                });

                fetchStudents(); // Refresh table
                this.reset();     // Clear form
                modal.hide();     // Close modal
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an issue adding the student. Please try again later.',
            });
        });
    });

    // Fetch students
    function fetchStudents() {
        fetch('../../src/controllers/get_student.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    renderStudents(data.students);
                } else {
                    console.error('Failed to fetch students');
                }
            })
            .catch(err => {
                console.error('Error fetching students:', err);
            });
    }

    // Render students in table
    function renderStudents(students) {
        const studentTableBody = document.getElementById('studentTableBody');
        studentTableBody.innerHTML = '';

        if (students.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = '<td colspan="6" style="padding: 10px; text-align: center;">No students found</td>';
            studentTableBody.appendChild(row);
            return;
        }

        students.forEach(student => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td hidden>${student.student_id}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.lrn}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.first_name} ${student.middle_name} ${student.last_name}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.grade_level}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.section}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.trackname}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">
                    <button class="btn btn-sm btn-primary view-btn" data-id="${student.student_id}" title="View full profile">View</button>
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${student.student_id}">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${student.student_id}">Delete</button>
                </td>
            `;
            studentTableBody.appendChild(row);
        });
    }
});
