document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('#studentTableBody');

    table.addEventListener('click', function (e) {
        if (e.target.classList.contains('edit-btn')) {
            const studentId = e.target.getAttribute('data-id');
            fetchStudentForEdit(studentId);
        }
    });

    function fetchStudentForEdit(id) {
        fetch('../../src/controllers/get_student_info_for_edit.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.student) {
                    const s = data.student;

                    // Populate form fields
                    document.getElementById('edit-student-id').value = s.student_id;
                    document.getElementById('edit-lrn').value = s.lrn || '';
                    document.getElementById('edit-first-name').value = s.first_name || '';
                    document.getElementById('edit-middle-name').value = s.middle_name || '';
                    document.getElementById('edit-last-name').value = s.last_name || '';
                    document.getElementById('edit-enrollment-status').value = s.enrollment_status || 'N/A';
                    document.getElementById('edit-specific-strand').value = s.specific_strand || '';

                    // Populate dropdowns
                    populateTrackDropdown(s.track_id);
                    populateStrandDropdown(s.strand_id);

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('editStudentModal'));
                    modal.show();
                } else {
                    alert('Student not found or data is missing.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error fetching student data.');
            });
    }

    function populateTrackDropdown(selectedId) {
        const trackSelect = document.getElementById('edit-track');
        const tracks = {
            1: "Academic",
            2: "Technical-Vocational-Livelihood (TVL)"
        };

        trackSelect.innerHTML = ''; // Clear previous options
        Object.entries(tracks).forEach(([id, name]) => {
            const opt = document.createElement('option');
            opt.value = id;
            opt.textContent = name;
            if (id == selectedId) opt.selected = true;
            trackSelect.appendChild(opt);
        });
    }

    function populateStrandDropdown(selectedId) {
        const strandSelect = document.getElementById('edit-strand');
        const strands = {
            1: "Accountancy, Business, and Management (ABM)",
            2: "Science, Technology, Engineering, and Mathematics",
            3: "Humanities and Social Sciences (HUMSS)",
            4: "General Academic Strand (GAS)",
            5: "Agri-Fishery Arts",
            6: "Home Economics",
            7: "Industrial Arts",
            8: "Information and Communication Technology (ICT)"
        };

        strandSelect.innerHTML = ''; // Clear previous options
        Object.entries(strands).forEach(([id, name]) => {
            const opt = document.createElement('option');
            opt.value = id;
            opt.textContent = name;
            if (id == selectedId) opt.selected = true;
            strandSelect.appendChild(opt);
        });
    }

    // Handle form submission for editing student
    document.getElementById('editStudentForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('../../src/controllers/update_student_info.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire('Success', data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('editStudentModal')).hide();
                fetchStudents(); // Refresh the student list
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Update failed.', 'error');
        });
    });

    // Function to fetch all students and render them
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
            .catch(err => console.error('Error fetching students:', err));
    }

    function renderStudents(students) {
        const studentTableBody = document.getElementById('studentTableBody');
        studentTableBody.innerHTML = '';

        if (students.length === 0) {
            studentTableBody.innerHTML = '<tr><td colspan="6" style="padding: 10px; text-align: center;">No students found</td></tr>';
            return;
        }

        students.forEach(student => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td hidden>${student.student_id}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.lrn || 'N/A'}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.first_name || ''} ${student.middle_name || ''} ${student.last_name || ''}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.grade_level || 'N/A'}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.section || 'N/A'}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${student.trackname || 'N/A'}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">
                    <button class="btn btn-sm btn-primary view-btn" data-id="${student.student_id}" title="View full profile">View</button>
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${student.student_id}" title="Edit Student Info">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${student.student_id}" title="Delete Student">Delete</button>
                </td>
            `;
            studentTableBody.appendChild(row);
        });
    }
});
