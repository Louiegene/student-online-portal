document.addEventListener('DOMContentLoaded', function () {
    let allSubjects = []; // Store all the subject data

    // Debounce utility to limit frequent function calls (for search)
    function debounce(fn, delay = 300) {
        let timeoutId;
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => fn(...args), delay);
        };
    }

    // Fetch subject data from server
    function fetchSubjectData() {
        fetch('../../src/controllers/get_subject.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    allSubjects = data.subjects || [];
                    renderSubjects(allSubjects);
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    // Render subjects in table
    function renderSubjects(subjects) {
        const subjectTableBody = document.getElementById('subjectTableBody');
        subjectTableBody.innerHTML = ''; // Clear table

        if (subjects.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td colspan="5" style="padding: 10px; text-align: center; color: #999;">
                    <em>No subjects found</em>
                </td>
            `;
            subjectTableBody.appendChild(row);
            return;
        }

        subjects.forEach(subject => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td hidden>${subject.subject_id}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${subject.subject_code || ''}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${subject.subject_name || ''}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${subject.subject_type || ''}</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${subject.subject_id}">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${subject.subject_id}">Delete</button>
                </td>
            `;
            subjectTableBody.appendChild(row);
        });
    }

    // Filter subjects based on search term
    function filterSubjects(searchTerm) {
        const searchLower = searchTerm.toLowerCase();

        const filtered = allSubjects.filter(subject => {
            const code = subject.subject_code?.toLowerCase() || '';
            const name = subject.subject_name?.toLowerCase() || '';
            return code.includes(searchLower) || name.includes(searchLower);
        });

        renderSubjects(filtered);
    }

    // Initial data load
    fetchSubjectData();

    // Add debounced search event
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', debounce(function () {
        filterSubjects(searchInput.value);
    }, 300));
});
