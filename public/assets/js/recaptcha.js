// Show the reCAPTCHA when the user clicks the Reset button
document.getElementById("showCaptchaBtn").addEventListener("click", function() {
    document.getElementById("captchaContainer").style.display = "block";
    this.style.display = "none"; // Hide the Reset button
    document.getElementById("submitResetBtn").style.display = "block"; // Show the Confirm Reset button
});

// Handle form submission
document.getElementById("resetForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the default form submission
    const formData = new FormData(this);
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const messageDiv = document.querySelector('.reset-message');
        messageDiv.style.display = 'block'; // Show the message div
        if (data.status === "success") {
            messageDiv.innerHTML = `<p style="color: green;">${data.message}</p>`;
        } else {
            messageDiv.innerHTML = `<p style="color: red;">${data.message}</p>`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.querySelector('.reset-message').innerHTML = `<p style="color: red;">An error occurred. Please try again later.</p>`;
    });
});