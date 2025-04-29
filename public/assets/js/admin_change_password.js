document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('adminChangePasswordForm');
    const currentPasswordInput = document.getElementById('currentPassword');
    const newPasswordInput = document.getElementById('newPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const errorDisplay = document.getElementById('passwordMismatchError');

    function validatePasswordsMatch() {
        const newPassword = newPasswordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();

        if (!confirmPassword) {
            errorDisplay.style.display = 'none';
            return;
        }

        if (newPassword !== confirmPassword) {
            errorDisplay.textContent = 'Passwords do not match.';
            errorDisplay.style.display = 'block';
        } else {
            errorDisplay.style.display = 'none';
        }
    }

    confirmPasswordInput.addEventListener('input', validatePasswordsMatch);
    newPasswordInput.addEventListener('input', validatePasswordsMatch);

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const currentPassword = currentPasswordInput.value.trim();
        const newPassword = newPasswordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();

        errorDisplay.style.display = 'none';

        if (!currentPassword || !newPassword || !confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: '<strong class="text-danger">ERROR</strong>',
                html: '<b>All fields are required.</b>',
                width: '400px',
                allowOutsideClick: false
            });
            return;
        }

        if (newPassword !== confirmPassword) {
            errorDisplay.textContent = 'Passwords do not match.';
            errorDisplay.style.display = 'block';
            return;
        }

        if (newPassword.length < 8) {
            errorDisplay.textContent = 'Password must be at least 8 characters.';
            errorDisplay.style.display = 'block';
            return;
        }

        const formData = new FormData(form);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);

        xhr.onload = function () {
            try {
                const response = JSON.parse(xhr.responseText);
                if (xhr.status === 200 && response.status === 'success') {
                    const modalEl = document.getElementById('adminChangePasswordModal');
                    if (modalEl) {
                        const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modalInstance.hide();
                    }

                    Swal.fire({
                        icon: 'success',
                        title: '<strong class="text-success">SUCCESS</strong>',
                        html: '<b>Password changed successfully! Logging you out...</b>',
                        width: '400px',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "../../src/views/student_portal.php";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '<strong class="text-danger">ERROR</strong>',
                        html: `<b>${response.message}</b>`,
                        width: '400px',
                        allowOutsideClick: false
                    });
                }
            } catch (e) {
                console.error("JSON parse error:", e);
                Swal.fire({
                    icon: 'error',
                    title: '<strong class="text-danger">ERROR</strong>',
                    html: '<b>Unexpected server response.</b>',
                    width: '400px',
                    allowOutsideClick: false
                });
            }
        };

        xhr.onerror = function () {
            Swal.fire({
                icon: 'error',
                title: '<strong class="text-danger">ERROR</strong>',
                html: '<b>Failed to connect to the server.</b>',
                width: '400px',
                allowOutsideClick: false
            });
        };

        xhr.send(formData);
    });
});
