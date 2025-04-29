document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('#subjectTableBody');

    table.addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-btn')) {
            const button = e.target;
            const subjectId = button.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteSubject(subjectId, button);
                }
            });
        }
    });

    function deleteSubject(subjectId, button) {
        console.log('Deleting subject ID:', subjectId);
        
        const params = new URLSearchParams();
        params.append('id', subjectId);

        fetch('../../src/controllers/delete_subject.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: params.toString() // Send the subject ID in the request body
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

                // Optionally, refresh the subject list after deletion (if needed)
                fetchSubjects(); // Fetch the updated list of subjects
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
                text: 'Unable to delete subject. Check your connection.'
            });
        });
    }

    // Function to fetch all subjects and render the table
    function fetchSubjects() {
        fetch('../../src/controllers/get_subject.php') // Update URL if needed
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    renderSubjects(data.subjects); // Pass updated subject list
                } else {
                    console.error('Failed to fetch subjects');
                }
            })
            .catch(err => {
                console.error('Error fetching subjects:', err);
            });
    }

    // Function to render subject data in a table
    function renderSubjects(subjects) {
        const subjectTableBody = document.getElementById('subjectTableBody');
        subjectTableBody.innerHTML = ''; // Clear existing rows

        if (subjects.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = '<td colspan="4" style="padding: 10px; text-align: center;">No subjects found</td>';
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
