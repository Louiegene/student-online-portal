let cropper;
let originalFile; // Store the original file for use in cropping

const cropperModal = document.getElementById('cropperModal');
const imageToCrop = document.getElementById('imageToCrop');
const previewImage = document.getElementById('previewImage');

// Open the cropping modal
function openCropperModal() {
  const fileInput = document.createElement('input');
  fileInput.type = 'file';
  fileInput.accept = 'image/*';

  fileInput.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
      if (file.size > 1024 * 1024) {
        Swal.fire('Error', 'File size must be 1MB or less.', 'error');
        return;
      }

      originalFile = file; // Save the file globally

      const reader = new FileReader();
      reader.onload = function (e) {
        imageToCrop.src = e.target.result;
        cropperModal.style.display = 'block'; // Show the modal

        // Initialize the cropper
        if (cropper) {
          cropper.destroy(); // Destroy the old cropper instance if any
        }

        cropper = new Cropper(imageToCrop, {
          aspectRatio: 1, // You can adjust the aspect ratio here
          viewMode: 2, // Keep the cropped area in view
          minContainerWidth: 200,
          minContainerHeight: 200,
        });
      };
      reader.readAsDataURL(file);
    }
  });

  fileInput.click(); // Trigger the file input dialog
}

// Crop the image and upload it
function cropImage() {
  if (!originalFile) {
    Swal.fire('Error', 'No image selected', 'error');
    return;
  }

  // Show loading alert
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
    const croppedFile = new File([blob], fileName, { type: mimeType });

    const formData = new FormData();
    formData.append('profile_picture', croppedFile);

    fetch('../../src/controllers/update_profile_picture.php', {
      method: 'POST',
      body: formData,
    })
      .then(res => res.json())
      .then(data => {
        Swal.close(); // Close the loading dialog

        if (data.success) {
          previewImage.src = data.newImagePath + '?t=' + Date.now();
          Swal.fire('Success', 'Profile picture updated!', 'success');
          cropperModal.style.display = 'none'; // Close the modal only on success
        } else {
          Swal.fire('Error', data.message, 'error');
        }
      })
      .catch(error => {
        Swal.close(); // Close the loading dialog
        Swal.fire('Error', 'Failed to upload: ' + error.message, 'error');
      });
  }, mimeType, 0.9);
}
