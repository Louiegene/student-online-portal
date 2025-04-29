document.addEventListener('DOMContentLoaded', function () {
    // Fetch and render subjects on page load
    fetchSubjects(); 

    const table = document.querySelector('#subjectTableBody');

    // Event listener for edit button clicks
    table.addEventListener('click', function (e) {
        if (e.target.classList.contains('edit-btn')) {
            const subjectId = e.target.getAttribute('data-id');
            fetchSubjectForEdit(subjectId);
        }
    });

    // Fetch subject details for editing
    function fetchSubjectForEdit(id) {
        fetch('../../src/controllers/get_subject_info_for_edit.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const subject = data.subject;

                    // Populate the modal with subject data
                    document.getElementById('edit-subject-id').value = subject.subject_id;
                    document.getElementById('edit-subject-code').value = subject.subject_code;
                    document.getElementById('edit-subject-name').value = subject.subject_name;
                    populateSubjectTypeDropdown(subject.subject_type);
                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('editSubjectModal'));
                    modal.show();
                } else {
                    Swal.fire('Error', 'Subject not found.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Error fetching subject data.', 'error');
            });
    }

    // Handle form submission for editing subject
    document.getElementById('editSubjectForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        // Log the form data to ensure correct values are being submitted
        for (let [key, value] of formData.entries()) {
            console.log(key + ": " + value);  // Check what data is being sent
        }

        fetch('../../src/controllers/update_subject_info.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire('Success', data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('editSubjectModal')).hide();
                fetchSubjects(); // Refresh subjects after update
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Update failed.', 'error');
        });
    });

    // Fetch all subjects from the server
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
            .catch(err => console.error('Error fetching subjects:', err));
    }

    // Render the subjects in the table
    function renderSubjects(subjects) {
        const subjectTableBody = document.getElementById('subjectTableBody');
        subjectTableBody.innerHTML = '';

        if (subjects.length === 0) {
            subjectTableBody.innerHTML = '<tr><td colspan="4" style="padding: 10px; text-align: center;">No subjects found</td></tr>';
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
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${subject.subject_id}" title="Edit Subject Info">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${subject.subject_id}" title="Delete Subject">Delete</button>
                </td>
            `;
            subjectTableBody.appendChild(row);
        });
    }

    function populateSubjectTypeDropdown(selectedType) {
        const dropdown = document.getElementById('edit-subject-type');
        const types = ['Core Subject', 'Applied Subject', 'Specialized Subject'];
    
        dropdown.innerHTML = '<option value="">Select Subject Type</option>'; // Clear previous options
    
        types.forEach(type => {
            const option = document.createElement('option');
            option.value = type;
            option.textContent = type;
            if (type === selectedType) option.selected = true;
            dropdown.appendChild(option);
        });
    }
    
});
