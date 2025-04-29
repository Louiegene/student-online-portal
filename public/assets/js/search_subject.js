let subjects = [];

document.addEventListener('DOMContentLoaded', () => {
    fetchSubjects();

    // Debounce the search input to reduce unnecessary filtering
    const searchInput = document.getElementById('searchInput');
    let debounceTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const query = searchInput.value.trim().toLowerCase();
            const filtered = filterSubjects(query);
            renderTable(filtered);
        }, 200); // 200ms delay
    });
});

// Fetch subjects from the server
function fetchSubjects() {
    fetch('../../src/controllers/get_subject.php')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                subjects = data.subjects || [];
                renderTable(subjects);
            } else {
                console.error('Fetch error:', data.message);
                renderEmptyState('Failed to load subjects.');
            }
        })
        .catch(err => {
            console.error('Fetch exception:', err);
            renderEmptyState('An error occurred while fetching subjects.');
        });
}

// Filter subjects based on the search query
function filterSubjects(query) {
    if (!query) return subjects;

    return subjects.filter(({ subject_code = '', subject_name = '' }) =>
        subject_code.toLowerCase().includes(query) ||
        subject_name.toLowerCase().includes(query)
    );
}

// Function to render the subject data into the table
function renderTable(list) {
    const tbody = document.getElementById('subjectTableBody');
    tbody.innerHTML = '';

    if (!list.length) {
        renderEmptyState('No subjects found.');
        return;
    }

    // Render subjects (limit to max 10 rows for the scrollable area)
    list.slice(0, 10).forEach(subject => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td hidden>${subject.subject_id}</td>
            <td style="padding: 10px; border: 1px solid #dee2e6; text-align: center; vertical-align: middle;">
                ${subject.subject_code}
            </td>
            <td style="padding: 10px; border: 1px solid #dee2e6; text-align: center; vertical-align: middle;">
                ${subject.subject_name}
            </td>
            <td style="padding: 10px; border: 1px solid #dee2e6; text-align: center; vertical-align: middle;">
                ${subject.subject_type}
            </td>
            <td style="padding: 8px; border: 1px solid #dee2e6; text-align: center; vertical-align: middle;">
                <div class="d-flex justify-content-center align-items-center gap-1 flex-wrap">
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${subject.subject_id}">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${subject.subject_id}">Delete</button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });

}

// Function to render an empty state message
function renderEmptyState(message) {
    const tbody = document.getElementById('subjectTableBody');
    const row = document.createElement('tr');
    row.innerHTML = `<td colspan="4" style="padding: 10px; text-align: center;">${message}</td>`;
    tbody.appendChild(row);
}

// Call this function after successfully adding a subject
function updateSubjectList(newSubject) {
    subjects.push(newSubject); // Add the new subject to the list
    const query = document.getElementById('searchInput').value.trim().toLowerCase();
    const filtered = filterSubjects(query); // Reapply filter based on the current search input
    renderTable(filtered); // Re-render the table with the updated list
}
