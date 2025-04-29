document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('#studentTableBody');  // Assuming your table has an ID of 'studentTableBody'

    table.addEventListener('click', function (e) {
        // Check if the clicked element is a view button
        if (e.target.classList.contains('view-btn')) {
            const button = e.target;
            const studentId = button.getAttribute('data-id');
            console.log('View button clicked for student ID:', studentId);
            fetchStudentDetails(studentId);  // This calls your fetchStudentDetails function
        }
    });

    function fetchStudentDetails(id) {
        console.log('Fetching student details for ID:', id);
        fetch('../../src/controllers/view_student_info.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const s = data.student;
                    document.getElementById('studentProfilePic').src = s.profile_picture || '../../public/assets/images/user_profile.png';
                    document.getElementById('info-lrn').textContent = s.lrn;
                    document.getElementById('info-name').textContent = `${s.first_name} ${s.middle_name || ''} ${s.last_name}`;
                    document.getElementById('info-gender').textContent = s.gender;
                    document.getElementById('info-birthdate').textContent = s.birthdate;
                    document.getElementById('info-enrollment').textContent = s.enrollment_date;
                    document.getElementById('info-status').textContent = s.enrollment_status || 'N/A';
                    document.getElementById('info-grade-section').textContent = `${s.grade_level} - ${s.section}`;
                    document.getElementById('info-track').textContent = s.trackname || 'N/A';
                    document.getElementById('info-strand').textContent = s.strandname || 'N/A';
                    document.getElementById('info-specific-strand').textContent = s.specific_strand || 'N/A';
        
                    const modal = new bootstrap.Modal(document.getElementById('viewStudentModal'));
                    modal.show();
                } else {
                    alert('Student not found.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error fetching student data.');
            });
    }
});
