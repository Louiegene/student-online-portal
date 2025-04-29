document.addEventListener('DOMContentLoaded', function () {
    // Fetch the user's profile picture from the server
    fetch('../../src/controllers/get_profile_picture.php') // Modify the URL based on your setup
        .then(response => response.json())
        .then(data => {
            // Check if the response has a profile picture path
            const profileImagePath = data.profile_picture ? data.profile_picture : '/student-online-portal/public/assets/images/uploads/profile_pictures/user_profile.png';
            const previewImage = document.getElementById('previewImage');
            const profileImg = document.getElementById('profileImage');  // Assuming you have a profile image element
            const profileSvg = document.querySelector('.profile-svg'); // The SVG element that acts as the default profile picture
            
            if (previewImage) {
                // Set the image source to the profile picture or default image
                const imageUrl = '/student-online-portal/public/assets/images/uploads/profile_pictures/' + profileImagePath;
                previewImage.src = imageUrl;
            }

            if (profileImg) {
                const imageUrl = '/student-online-portal/public/assets/images/uploads/profile_pictures/' + profileImagePath;

                // Show the profile picture if available, else show the default SVG
                if (profileImagePath === 'user_profile.png') {
                    // If it's the default image, show the SVG and hide the profile image
                    profileImg.style.display = 'none';
                    if (profileSvg) profileSvg.style.display = 'inline';
                } else {
                    // Otherwise, show the profile image and hide the SVG
                    profileImg.src = imageUrl;
                    profileImg.style.display = 'inline';
                    if (profileSvg) profileSvg.style.display = 'none';
                }

                // Store the path in localStorage for persistence
                localStorage.setItem('profileImagePath', imageUrl);
            }
        })
        .catch(error => {
            console.error('Error fetching profile picture:', error);
        });

    // Restore image from localStorage if available
    const savedPath = localStorage.getItem('profileImagePath');
    const profileImg = document.getElementById('profileImage');
    const profileSvg = document.querySelector('.profile-svg');
    
    if (profileImg) {
        if (savedPath) {
            // If a profile image is stored in localStorage, display it
            profileImg.src = savedPath;
            profileImg.style.display = 'inline';  // Show image
            if (profileSvg) profileSvg.style.display = 'none';    // Hide SVG
        } else {
            // If no image is stored, show the SVG
            profileImg.style.display = 'none';   // Hide image
            if (profileSvg) profileSvg.style.display = 'inline'; // Show SVG
        }
    }
});
