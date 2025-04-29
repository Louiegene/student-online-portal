document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('#studentTableBody');
    const BASE_PROFILE_PATH = '/student-online-portal/public/assets/images/uploads/profile_pictures/';
    const fallbackImagePath = `${BASE_PROFILE_PATH}user_profile.png`;

    if (!table) {
        console.error('Table element not found.');
        return;
    }

    table.addEventListener('click', function (e) {
        if (e.target.classList.contains('view-btn')) {
            const studentId = e.target.getAttribute('data-id');
            console.log('View button clicked for student ID:', studentId);
            fetchStudentDetails(studentId);
        }
    });

    function fetchStudentProfilePicture(studentId, targetElementId) {
        console.log('Fetching profile picture for student ID:', studentId);
        const targetElement = document.getElementById(targetElementId);

        if (!targetElement) {
            console.error('Target element not found:', targetElementId);
            return;
        }

        targetElement.setAttribute('data-loading', 'true');

        // Log the fetch URL for debugging
        const fetchUrl = `../../src/controllers/get_student_profile_picture.php?student_id=${studentId}`;
        console.log('Fetching from URL:', fetchUrl);

        fetch(fetchUrl)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`Network response was not ok: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Log the raw response data
                console.log('API Response data:', data);
                
                targetElement.removeAttribute('data-loading');
                
                if (data.status === 'success') {
                    const imageUrl = data.profile_picture
                        ? `${BASE_PROFILE_PATH}${data.profile_picture}`
                        : fallbackImagePath;
                    
                    console.log('Setting profile picture to:', imageUrl);
                    targetElement.src = imageUrl;
                    
                    // Log when the image loads successfully
                    targetElement.onload = function() {
                        console.log('Profile image loaded successfully');
                    };
                    
                    targetElement.onerror = function() {
                        console.error('Failed to load profile picture from:', imageUrl);
                        console.log('Falling back to default image');
                        this.src = fallbackImagePath;
                    };
                } else {
                    console.error('API returned error:', data.message || 'Unknown error');
                    targetElement.src = fallbackImagePath;
                }
            })
            .catch(error => {
                console.error('Error fetching student profile picture:', error);
                targetElement.src = fallbackImagePath;
                targetElement.removeAttribute('data-loading');
            });
    }

    function fetchStudentDetails(id) {
        fetch(`../../src/controllers/view_student_info.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const s = data.student;

                    fetchStudentProfilePicture(id, 'studentProfilePic');

                    document.getElementById('info-lrn').textContent = s.lrn;
                    document.getElementById('info-name').textContent = `${s.first_name} ${(s.middle_name || '').trim() ? s.middle_name + ' ' : ''}${s.last_name}`;
                    document.getElementById('info-gender').textContent = s.gender;
                    document.getElementById('info-birthdate').textContent = s.birthdate;
                    document.getElementById('info-enrollment').textContent = s.enrollment_date;
                    const statusElement = document.getElementById('info-status');
                    const statusText = s.enrollment_status || 'N/A';
                    let badgeClass = '';

                    switch (statusText) {
                        case 'Enrolled':
                            badgeClass = 'enrolled';
                            break;
                        case 'Dropped':
                            badgeClass = 'dropped';
                            break;
                        case 'No Longer Participating':
                            badgeClass = 'nlp';
                            break;
                        default:
                            badgeClass = 'unknown';
                    }

                    statusElement.innerHTML = `<span class="status-badge ${badgeClass}">${statusText}</span>`;
                    document.getElementById('info-grade-section').textContent = `${s.grade_level} - ${s.section}`;
                    document.getElementById('info-track').textContent = s.trackname || 'N/A';
                    document.getElementById('info-strand').textContent = s.strandname || 'N/A';
                    document.getElementById('info-specific-strand').textContent = s.specific_strand || 'N/A';

                    const modalElement = document.getElementById('viewStudentModal');
                    if (modalElement) {
                        if (typeof bootstrap !== 'undefined') {
                            const modal = new bootstrap.Modal(modalElement);
                            modal.show();
                        } else {
                            console.error('Bootstrap library not loaded.');
                        }
                    } else {
                        console.error('Modal element not found.');
                    }
                } else {
                    alert('Student not found.');
                }
            })
            .catch(err => {
                console.error('Error fetching student data:', err);
                alert('Error fetching student data.');
            });
    }
});