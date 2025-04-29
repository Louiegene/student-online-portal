// Function to update the student profile picture in the modal
function updateStudentProfilePic(studentId) {
    // Fetch the specific student data
    fetch('../../src/controllers/view_student_info.php?id=' + id)
      .then(response => response.json())
      .then(student => {
        // Get reference to the profile picture element
        const profilePicElement = document.getElementById('studentProfilePic');
        
        // Update the image source
        if (student.profilePicture && student.profilePicture !== '') {
          // Use the student's uploaded profile picture
          profilePicElement.src = student.profilePicture;
        } else {
          // Use the default profile picture
          profilePicElement.src = "../../public/assets/images/user_profile.png";
        }
      })
      .catch(error => {
        console.error('Error fetching student profile picture:', error);
        // In case of error, keep/reset to the default image
        document.getElementById('studentProfilePic').src = "../../public/assets/images/user_profile.png";
      });
  }
  
  // This function can be called when the modal is being opened
  // For example:
  document.getElementById('viewStudentButton').addEventListener('click', function(event) {
    const studentId = this.getAttribute('data-student-id');
    updateStudentProfilePic(studentId);
    // Other code to populate the rest of the student information...
  });