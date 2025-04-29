document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('#studentTableBody');
    const modalElement = document.getElementById('viewStudentGradeModal');
    const gradesTableBody = document.getElementById('gradesTableBody');
    const form = document.getElementById('editGradesForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const gradeModal = new bootstrap.Modal(modalElement);

    if (!form) {
        console.error('editGradesForm not found.');
        return;
    }

    // Event listener for viewing grades
    table.addEventListener('click', function (e) {
        if (e.target.classList.contains('view-btn')) {
            const button = e.target;
            const studentId = button.getAttribute('data-id');
            const userId = button.getAttribute('data-user-id');
            fetchStudentDetails(studentId, userId);
        }
    });

    // Dropdown change event listener
    document.getElementById('quarterSelect').addEventListener('change', handleDropdownChange);
    document.getElementById('gradeLevelSelect').addEventListener('change', handleDropdownChange);

    function handleDropdownChange(e) {
        e.preventDefault();
        const studentId = modalElement.dataset.studentId;
        const userId = modalElement.dataset.userId;
        if (studentId && userId) {
            fetchStudentDetails(studentId, userId); // Re-fetch details when dropdown changes
        }
    }

    // Fetch student details and grades
    function fetchStudentDetails(studentId, userId) {
        const quarter = document.getElementById('quarterSelect').value || '1st';
        const gradeLevel = document.getElementById('gradeLevelSelect').value || '11';

        modalElement.dataset.studentId = studentId;
        modalElement.dataset.userId = userId;

        fetch(`../../src/controllers/get_student_grades.php?student_id=${studentId}&user_id=${userId}&quarter=${quarter}&grade_level=${gradeLevel}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Populate student info
                    if (data.student) {
                        const s = data.student;
                        document.getElementById('info-lrn').textContent = s.lrn || 'N/A';
                        document.getElementById('info-name').textContent = s.full_name || 'N/A';
                        document.getElementById('info-track').textContent = s.trackname !== 'N/A' ? s.trackname : 'Not Assigned';
                        document.getElementById('info-strand').textContent = s.strand !== 'N/A' ? s.strand : 'Not Assigned';
                        
                        // For combined grade level and section display
                        const gradeLevel = s.grade_level || 'N/A';
                        const section = s.section || 'N/A';
                        document.getElementById('info-grade-level').textContent = gradeLevel;
                        document.getElementById('info-section').textContent = section;
                    }

                    renderEditableGrades(data.grades); // Render grades after populating student info
                    gradeModal.show();
                } else {
                    Swal.fire({ icon: 'warning', title: 'No Data', text: data.message || 'No student data available' });
                }
            })
            .catch(err => Swal.fire({ icon: 'error', title: 'Error', text: `Failed to load student data. (${err.message})` }));
    }

    // Function to render editable grades in the table
    function renderEditableGrades(grades) {
        gradesTableBody.innerHTML = '';
        if (grades.length === 0) {
            gradesTableBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No grades found for this quarter and grade level</td></tr>';
            return;
        }
        grades.forEach(grade => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" class="form-control-plaintext text-center" value="${grade.subject_name}" readonly></td>
                <td><input type="text" class="form-control-plaintext text-center" value="${grade.quarter}" readonly></td>
                <td>
                    <input type="number" min="0" max="100" step="0.01" class="form-control grade-input text-center"
                           name="grade[]" value="${grade.grade}" data-grade-id="${grade.grade_id}" required />
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-grade-btn" data-grade-id="${grade.grade_id}">Remove</button>
                </td>
            `;
            gradesTableBody.appendChild(row);

            // Add event listener for grade removal
            row.querySelector('.remove-grade-btn').addEventListener('click', function () {
                const gradeId = this.getAttribute('data-grade-id');
                const currentRow = this.closest('tr');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Process deletion immediately
                        processGradeDeletion(gradeId, currentRow);
                    }
                });
            });
        });
    }

    // Process grade deletion immediately
    function processGradeDeletion(gradeId, row) {
        // Show loading state
        const removeBtn = row.querySelector('.remove-grade-btn');
        const originalText = removeBtn.textContent;
        removeBtn.disabled = true;
        removeBtn.textContent = 'Deleting...';

        // Delete the grade immediately
        deleteGrade(gradeId)
            .then(response => {
                if (response.status === 'success') {
                    // Remove row from table on successful deletion
                    row.remove();
                    Swal.fire({ 
                        icon: 'success', 
                        title: 'Deleted!', 
                        text: 'The grade has been deleted successfully.' 
                    });
                } else {
                    throw new Error(response.message || 'Failed to delete the grade');
                }
            })
            .catch(error => {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Error', 
                    text: `Failed to delete the grade: ${error.message}` 
                });
                // Reset button state
                removeBtn.disabled = false;
                removeBtn.textContent = originalText;
            });
    }

    // Delete a single grade
    function deleteGrade(gradeId) {
        return fetch('../../src/controllers/delete_student_grades.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                deletedGrades: [{ grade_id: gradeId }] 
            })
        })
        .then(response => response.json());
    }

    // Reset modal content when it is hidden
    modalElement.addEventListener('hidden.bs.modal', function () {
        gradesTableBody.innerHTML = '';
        document.getElementById('quarterSelect').value = '1st';
        document.getElementById('gradeLevelSelect').value = '11';
    });

    // Handle grade form submission - now only for grade value updates
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const updatedGrades = [];

        // Collect updated grades
        document.querySelectorAll('.grade-input').forEach(input => {
            const gradeId = input.dataset.gradeId;
            const newGrade = input.value;

            if (gradeId && newGrade !== '' && !isNaN(newGrade)) {
                const gradeNumber = parseFloat(newGrade);
                if (gradeNumber >= 0 && gradeNumber <= 100) {
                    updatedGrades.push({ grade_id: gradeId, grade: gradeNumber });
                }
            }
        });

        if (updatedGrades.length === 0) {
            Swal.fire({ icon: 'info', title: 'No Changes', text: 'No grade values were modified.' });
            return;
        }

        Swal.fire({
            title: 'Confirm Update',
            text: 'Are you sure you want to update these grade values?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Saving...';
                }
                
                // Process only grade value updates
                updateGrades(updatedGrades)
                    .then(response => {
                        if (response.status === 'success') {
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Success', 
                                text: 'Grade values updated successfully.' 
                            });
                            // Refresh the grades display
                            fetchGradesForStudent();
                        } else {
                            throw new Error(response.message || 'Failed to update grades');
                        }
                    })
                    .catch(err => {
                        Swal.fire({ 
                            icon: 'error', 
                            title: 'Error', 
                            text: `Failed to update grades: ${err.message}` 
                        });
                    })
                    .finally(() => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Save Changes';
                        }
                    });
            }
        });
    });

    // Update grades API call
    function updateGrades(updatedGrades) {
        return fetch('../../src/controllers/update_student_grades.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ updatedGrades })
        })
        .then(response => response.json());
    }

    // Fetch grades for the student
    function fetchGradesForStudent() {
        const studentId = modalElement.dataset.studentId;
        const userId = modalElement.dataset.userId;
        const quarter = document.getElementById('quarterSelect').value || '1st';
        const gradeLevel = document.getElementById('gradeLevelSelect').value || '11';
        
        fetch(`../../src/controllers/get_student_grades.php?student_id=${studentId}&user_id=${userId}&quarter=${quarter}&grade_level=${gradeLevel}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    renderEditableGrades(data.grades);
                }
            })
            .catch(err => {
                console.error('Error refreshing grades:', err);
            });
    }
});