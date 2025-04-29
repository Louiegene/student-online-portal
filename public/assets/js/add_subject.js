document.addEventListener('DOMContentLoaded', function () {
    var modal = new bootstrap.Modal(document.getElementById('addSubjectModal'));

    // Modal will now not automatically show up on page load, so remove this line
    // modal.show();

    // Show the modal when the "Add Subject" button is clicked
    document.getElementById('addSubjectBtn').addEventListener('click', function () {
        modal.show();  // This triggers the modal when the button is clicked
    });

    // When the modal is hidden, any related clean-up logic can go here
    document.getElementById('addSubjectModal').addEventListener('hidden.bs.modal', function () {
        // Optional: Reset the form if necessary after closing the modal
        document.getElementById('addSubjectForm').reset();
    });

    // Handle form submission for adding a subject
    document.getElementById('addSubjectForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the form from submitting the traditional way

        const formData = new FormData(this);

        // Basic client-side validation
        if (
            !formData.get('subject_code') ||
            !formData.get('subject_name') ||
            !formData.get('subject_type')
        ) {
            Swal.fire({
                icon: 'warning',
                title: 'Incomplete Form',
                text: 'Please fill out all required fields.',
            });
            return;
        }
        

        // Send AJAX request to add the subject
        fetch('../../src/controllers/add_subject.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Show success message with SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Subject added successfully!',
                    text: data.message,
                });

                // Fetch and reload subject data
                fetchSubjects();

                // Reset the form after submission
                document.getElementById('addSubjectForm').reset();

                // Close the modal
                modal.hide();
            } else {
                // Show error message if failed
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
                text: 'There was an issue adding the subject. Please try again later.',
            });
        });
    });

    // Fetch all subjects and update the table
    function fetchSubjects() {
        fetch('../../src/controllers/get_subject.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    renderSubjects(data.subjects);
                } else {
                    console.error('Failed to fetch subjects');
                }
            })
            .catch(err => {
                console.error('Error fetching subjects:', err);
            });
    }

    // Render subject data in a table
    function renderSubjects(subjects) {
        const subjectTableBody = document.getElementById('subjectTableBody');
        subjectTableBody.innerHTML = ''; // Clear table

        if (subjects.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = '<td colspan="6" style="padding: 10px; text-align: center;">No subject found</td>';
            subjectTableBody.appendChild(row);
            return;
        }

        subjects.forEach(subject => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td hidden>${subject.subject_id}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${subject.subject_code}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${subject.subject_name}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${subject.subject_type}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${subject.subject_id}">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${subject.subject_id}">Delete</button>
                </td>
            `;
            subjectTableBody.appendChild(row);
        });
    }
});
