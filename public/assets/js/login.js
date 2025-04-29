document.getElementById('loginForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();

    if (!username || !password) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Fields',
            text: 'Please enter both username and password.',
        });
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../../src/controllers/login.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        try {
            const response = JSON.parse(xhr.responseText);

            if (xhr.status === 200 && response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Welcome!',
                    html: `<b>Welcome, ${response.username}</b>`,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Role-based redirection
                    if (response.role === 'admin') {
                        window.location.href = '../../src/views/admin_page.php';
                    } else if (response.role === 'student') {
                        window.location.href = '../../src/views/student_page.php';
                    } else {
                        window.location.href = '../../src/views/teacher_page.php';
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: response.message
                });
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Unexpected Error',
                text: 'Failed to parse server response.',
                footer: `<code>${xhr.responseText}</code>`
            });
        }
    };

    xhr.onerror = function () {
        Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'Could not reach the server. Please try again later.'
        });
    };

    xhr.send(`username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`);
});
