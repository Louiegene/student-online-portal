document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('#studentTableBody');
    const modalElement = document.getElementById('viewStudentGradeModal');
    const gradesTableBody = document.getElementById('gradesTableBody');

    const gradeModal = new bootstrap.Modal(modalElement);
    let removedGradeIds = []; // Array to store removed grade IDs

    table.addEventListener('click', function (e) {
        if (e.target.classList.contains('view-btn')) {
            const button = e.target;
            const studentId = button.getAttribute('data-id');
            const userId = button.getAttribute('data-user-id');
            fetchStudentDetails(studentId, userId);
        }
    });

    // Add event listeners for dropdown changes
    document.getElementById('quarterSelect').addEventListener('change', handleDropdownChange);
    document.getElementById('gradeLevelSelect').addEventListener('change', handleDropdownChange);

    function handleDropdownChange(e) {
        e.preventDefault();
        const studentId = modalElement.dataset.studentId;
        const userId = modalElement.dataset.userId;
        if (studentId && userId) {
            fetchStudentDetails(studentId, userId); // Fetch details again when dropdown is changed
        }
    }

    function fetchStudentDetails(studentId, userId) {
        const quarter = document.getElementById('quarterSelect').value || '1st';
        const gradeLevel = document.getElementById('gradeLevelSelect').value || '11';

        // Set modal dataset
        modalElement.dataset.studentId = studentId;
        modalElement.dataset.userId = userId;

        // Fetch student info and grades in one request
        fetch(`../../src/controllers/get_student_grades.php?student_id=${studentId}&user_id=${userId}&quarter=${quarter}&grade_level=${gradeLevel}`)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
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

                    // Filter grades by selected grade level (if PHP didn't do it)
                    let filteredGrades = data.grades;
                    if (data.grades && data.grades.length > 0) {
                        // Filter grades that match the selected grade level
                        filteredGrades = data.grades.filter(grade => 
                            grade.grade_level === gradeLevel || 
                            grade.grade_level === parseInt(gradeLevel, 10));
                        
                        renderEditableGrades(filteredGrades);
                    } else {
                        gradesTableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center text-muted">No grades found for this quarter and grade level</td>
                            </tr>
                        `;
                    }

                    gradeModal.show();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Data',
                        text: data.message || 'No student data available'
                    });
                }
            })
            .catch(err => {
                console.error('Error fetching student data:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: `Failed to load student data. (${err.message})`
                });
            });
    }

    // Function to render editable grades in the table
    function renderEditableGrades(grades) {
        gradesTableBody.innerHTML = '';
    
        if (grades.length === 0) {
            gradesTableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-muted">No grades found for this quarter and grade level</td>
                </tr>
            `;
            return;
        }
    
        grades.forEach(grade => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" class="form-control-plaintext text-center" value="${grade.subject_name}" readonly></td>
                <td><input type="text" class="form-control-plaintext text-center" value="${grade.quarter}" readonly></td>
                <td>
                    <input type="number" min="0" max="100" step="0.01"
                           class="form-control grade-input text-center"
                           name="grade[]" value="${grade.grade}"
                           data-grade-id="${grade.grade_id}" required />
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-grade-btn" data-grade-id="${grade.grade_id}">Remove</button>
                </td>
            `;
            gradesTableBody.appendChild(row);
    
            // Add event listener for removing the row
            row.querySelector('.remove-grade-btn').addEventListener('click', function () {
                const gradeId = this.getAttribute('data-grade-id');
                removeGrade(gradeId);  // Track the grade removal
                row.remove();  // Remove the row from the table
            });
        });
    }

    // Function to track the removed grade
    function removeGrade(gradeId) {
        if (!removedGradeIds.includes(gradeId)) {
            removedGradeIds.push(gradeId);  // Store the grade ID for deletion
        }
    }

    // Reset modal content when it is hidden
    modalElement.addEventListener('hidden.bs.modal', function () {
        // Reset dropdowns
        document.getElementById('quarterSelect').value = '1st';
        document.getElementById('gradeLevelSelect').value = '11';

        // Reset student info
        document.getElementById('info-lrn').textContent = '';
        document.getElementById('info-name').textContent = '';
        document.getElementById('info-track').textContent = 'N/A';
        document.getElementById('info-strand').textContent = 'N/A';
        document.getElementById('info-grade-level').textContent = 'N/A';
        document.getElementById('info-section').textContent = '';

        // Reset grades table
        gradesTableBody.innerHTML = '';

        // Remove backdrop (if necessary)
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
            document.body.classList.remove('modal-open');
        }
    });

    // Example of how you might handle form submission
    document.getElementById('saveGradesBtn').addEventListener('click', function () {
        const grades = [];
        let isValid = true;

        // Validate grade inputs before saving
        document.querySelectorAll('.grade-input').forEach(input => {
            const gradeValue = input.value;
            const gradeId = input.getAttribute('data-grade-id');
            
            // If grade is out of valid range (0-100)
            if (gradeValue < 0 || gradeValue > 100) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
                grades.push({ gradeId, grade: gradeValue });
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Grade',
                text: 'Please ensure all grades are between 0 and 100.'
            });
            return; // Prevent form submission if validation fails
        }

        // Proceed with saving grades if validation passes
        const studentId = modalElement.dataset.studentId;
        const userId = modalElement.dataset.userId;

        fetch('../../src/controllers/update_student_grades.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                studentId,
                userId,
                grades,
                removedGradeIds // Send the removed grades
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Grades Saved',
                    text: 'The grades have been saved successfully.'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Saving Grades',
                    text: data.message || 'Something went wrong.'
                });
            }
        })
        .catch(error => {
            console.error('Error saving grades:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while saving the grades.'
            });
        });
    });
});
