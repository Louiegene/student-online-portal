let originalFile;
const cropperModal = document.getElementById('cropperModal');
const imageToCrop = document.getElementById('imageToCrop');
const previewImage = document.getElementById('previewImage');
let cropper;

function openCropperModal() {
  const fileInput = document.createElement('input');
  fileInput.type = 'file';
  fileInput.accept = 'image/*';

  fileInput.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
      if (file.size > 5 * 1024 * 1024) {
        Swal.fire('Error', 'File size must be less than 5MB.', 'error');
        return;
      }

      const allowedExtensions = ['jpg', 'jpeg', 'png'];
      const fileExtension = file.name.split('.').pop().toLowerCase();
      if (!allowedExtensions.includes(fileExtension)) {
        Swal.fire('Error', 'Only JPG, JPEG, and PNG files are allowed.', 'error');
        return;
      }

      originalFile = file;

      const reader = new FileReader();
      reader.onload = function (e) {
        imageToCrop.src = e.target.result;
        cropperModal.style.display = 'block';

        if (cropper) {
          cropper.destroy();
        }

        cropper = new Cropper(imageToCrop, {
          aspectRatio: 1,
          viewMode: 2,
          minContainerWidth: 200,
          minContainerHeight: 200,
        });
      };
      reader.readAsDataURL(file);
    }
  });

  fileInput.click();
}

function cropImage() {
  if (!originalFile) {
    Swal.fire('Error', 'No image selected', 'error');
    return;
  }

  Swal.fire({
    title: 'Uploading...',
    text: 'Please wait while your picture is being updated.',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading()
  });

  const mimeType = originalFile.type;
  const fileExtension = originalFile.name.split('.').pop().toLowerCase();
  const randomNumbers = Math.floor(Math.random() * 10000000000);
  const fileName = 'profile_' + randomNumbers + '.' + fileExtension;

  const canvas = cropper.getCroppedCanvas({
    width: 500,
    height: 500,
  });

  canvas.toBlob(function (blob) {
    if (!blob) {
      Swal.close();
      Swal.fire('Error', 'Failed to create cropped image.', 'error');
      return;
    }

    const croppedFile = new File([blob], fileName, { type: mimeType });

    const formData = new FormData();
    formData.append('profile_picture', croppedFile);

    fetch('../../src/controllers/update_profile_picture.php', {
      method: 'POST',
      body: formData,
    })
      .then(res => res.json())
      .then(data => {
        Swal.close();

        if (data.success) {
          // Cache-busting and persistent display
          const newPath = data.newImagePath + '?t=' + new Date().getTime();
          previewImage.src = newPath;
          localStorage.setItem('profileImagePath', newPath);

          Swal.fire('Success', 'Profile picture updated!', 'success');
          fetchUpdatedProfilePicture();  // Fetch updated profile picture
          cropperModal.style.display = 'none';
        } else {
          Swal.fire('Error', data.message, 'error');
        }
      })
      .catch(error => {
        Swal.close();
        Swal.fire('Error', 'Failed to upload: ' + error.message, 'error');
      });
  }, mimeType, 0.9);
}

function cropperClose() {
  if (cropper) {
    cropper.destroy();
    cropper = null;
  }

  cropperModal.style.display = 'none';
  imageToCrop.src = '';
  originalFile = null;
}

function fetchUpdatedProfilePicture() {
    fetch('../../src/controllers/get_profile_picture.php')
        .then(response => response.json())
        .then(data => {
            const profileImagePath = data.profile_picture ? data.profile_picture : 'user_profile.png';
            const imageUrl = '/student-online-portal/public/assets/images/uploads/profile_pictures/' + profileImagePath + '?t=' + Date.now();

            const previewImage = document.getElementById('previewImage');
            const profileImg = document.getElementById('profileImage');
            const profileSvg = document.querySelector('.profile-svg');

            // Update preview modal image
            if (previewImage) previewImage.src = imageUrl;

            // Update navbar image
            if (profileImg) {
                profileImg.src = imageUrl;
                profileImg.style.display = profileImagePath === '/student-online-portal/public/assets/images/uploads/profile_pictures/user_profile.png' ? 'none' : 'inline';
            }

            // Toggle SVG visibility
            if (profileSvg) {
                profileSvg.style.display = profileImagePath === '/student-online-portal/public/assets/images/uploads/profile_pictures/user_profile.png' ? 'inline' : 'none';
            }

            // Store updated image path for persistence
            localStorage.setItem('profileImagePath', imageUrl);
        })
        .catch(error => {
            console.error('Error fetching updated profile picture:', error);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    const savedPath = localStorage.getItem('profileImagePath');
    const profileImg = document.getElementById('profileImage');
    const profileSvg = document.querySelector('.profile-svg');
    const previewImage = document.getElementById('previewImage');

    if (savedPath && profileImg) {
        profileImg.src = savedPath;
        profileImg.style.display = 'inline';
        if (previewImage) previewImage.src = savedPath;
        if (profileSvg) profileSvg.style.display = 'none';
    } else {
        fetchUpdatedProfilePicture();
    }
});


