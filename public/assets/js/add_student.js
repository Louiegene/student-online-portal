document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('addStudentModal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('addStudentForm');
    const submitBtn = form.querySelector('button[type="submit"]');

    // Show modal
    document.getElementById('addStudentBtn').addEventListener('click', function () {
        modal.show();
    });

    // Reset form and feedback when modal is closed
    modalElement.addEventListener('hidden.bs.modal', function () {
        form.reset();
        clearFeedbackMessages();  // Clear previous feedback messages
    });

    // Form submission handler
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.textContent = "Saving...";

        const formData = new FormData(this);
        const schoolYear = document.getElementById('school_year').value;
        formData.append('school_year', schoolYear);

        fetch('../../src/controllers/add_student.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.textContent = "Add Student";  // Reset button text

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Student added successfully!',
                    text: data.message,
                });

                
                form.reset();     // Clear form
                modal.hide();     // Close modal
                fetchStudents();  // Refresh the student list
                
            } else {
                showValidationErrors(data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an issue adding the student. Please try again later.',
            });
            submitBtn.disabled = false;
            submitBtn.textContent = "Add Student";
        });
    });

    // Fetch and render all students
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

    // Render the list of students in the table
    function renderStudents(students) {
        const studentTableBody = document.getElementById('studentTableBody');
        studentTableBody.innerHTML = '';

        if (students.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = '<td colspan="7" style="padding: 10px; text-align: center;">No students found</td>';
            studentTableBody.appendChild(row);
            return;
        }

        students.forEach(student => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td hidden>${student.student_id}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.lrn}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.first_name} ${student.last_name}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.grade_level} ${student.section}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.trackname}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.strandname}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">
                    <button class="btn btn-sm btn-primary view-btn" data-id="${student.student_id}" title="View full profile">View</button>
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${student.student_id}">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${student.student_id}">Delete</button>
                </td>
            `;
            studentTableBody.appendChild(row);
        });
    }

    // Show validation feedback for any form errors
    function showValidationErrors(data) {
        if (data.missingFields) {
            data.missingFields.forEach(field => {
                const inputElement = document.getElementById(field);
                if (inputElement) {
                    const feedback = document.createElement('div');
                    feedback.classList.add('text-danger', 'feedback-message');
                    feedback.textContent = `${field.charAt(0).toUpperCase() + field.slice(1)} is required.`;
                    inputElement.classList.add('is-invalid');
                    inputElement.parentElement.appendChild(feedback);
                }
            });
        }

        // if (data.message) {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Error',
        //         text: data.message,
        //     });
        // }
    }

    // Clear feedback messages from invalid inputs
    function clearFeedbackMessages() {
        const feedbackElements = document.querySelectorAll('.feedback-message');
        feedbackElements.forEach(el => el.remove());

        const invalidInputs = document.querySelectorAll('.is-invalid');
        invalidInputs.forEach(input => input.classList.remove('is-invalid'));
    }
});
