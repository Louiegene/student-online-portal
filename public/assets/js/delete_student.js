document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('#studentTableBody');

    table.addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-btn')) {
            const button = e.target;
            const studentId = button.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteStudent(studentId, button);
                }
            });
        }
    });

    function deleteStudent(studentId, button) {
        console.log('Deleting student ID:', studentId);
        
        const params = new URLSearchParams();
        params.append('id', studentId);

        fetch('../../src/controllers/delete_student.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: params.toString() // Clean way to append data
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });

                // Remove the row from the table
                const row = button.closest('tr');
                if (row) {
                    row.remove();
                }

                // Call fetchStudents to reload and update the table after deletion
                fetchStudents(); // Fetch the updated list of students
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(err => {
            console.error('Error during delete operation:', err);
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: 'Unable to delete student. Check your connection.'
            });
        });
    }

    // Function to fetch all students and render the table
    function fetchStudents() {
        fetch('../../src/controllers/get_student.php') // Update URL if needed
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    renderStudents(data.students); // Pass updated student list
                } else {
                    console.error('Failed to fetch students');
                }
            })
            .catch(err => {
                console.error('Error fetching students:', err);
            });
    }

    // Function to render student data in a table
    function renderStudents(students) {
        const studentTableBody = document.querySelector('#studentTableBody'); // Ensure you're targeting the right table body
        studentTableBody.innerHTML = ''; // Clear the table content

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
});
