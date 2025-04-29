document.addEventListener('DOMContentLoaded', () => {
    const addFieldBtn = document.getElementById('addFieldBtn');
    const gradeFields = document.getElementById('gradeFields');
    const manualForm = document.getElementById('manualGradeForm');
    const bulkForm = document.getElementById('bulkUploadForm');
    const uploadGradesBtn = document.getElementById('uploadGradesBtn');
    const uploadResult = document.getElementById('uploadResult');
    const uploadResultMessage = document.getElementById('uploadResultMessage');
    const uploadErrors = document.getElementById('uploadErrors');

    // Load subjects when modal opens
    const modal = document.getElementById('addStudentGradeModal');
    if (modal) {
        modal.addEventListener('shown.bs.modal', loadSubjects);
    }

    function loadSubjects() {
        // Use your existing get_subjects.php endpoint
        fetch('../../src/controllers/get_subject.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const subjectDropdowns = document.querySelectorAll('.subject-dropdown');
                    
                    subjectDropdowns.forEach(dropdown => {
                        // Clear existing options
                        dropdown.innerHTML = '';
                        
                        // Add default option
                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = 'Select Subject';
                        defaultOption.disabled = true;
                        defaultOption.selected = true;
                        dropdown.appendChild(defaultOption);
                        
                        // Add subject options
                        data.subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.subject_id;
                            option.textContent = `${subject.subject_code} - ${subject.subject_name}`;
                            dropdown.appendChild(option);
                        });
                    });
                } else {
                    console.error('Failed to load subjects:', data.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load subjects. Please try again.'
                    });
                }
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error loading subjects. Please try again.'
                });
            });
    }

    // Add new subject-grade row by cloning
    if (addFieldBtn) {
        addFieldBtn.addEventListener('click', () => {
            const originalRow = gradeFields.querySelector('.grade-entry');
            if (!originalRow) return;
            const clone = originalRow.cloneNode(true);
            
            // Reset values in cloned inputs
            clone.querySelectorAll('input, select').forEach(input => {
                if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                } else {
                    input.value = '';
                }
            });
            
            gradeFields.appendChild(clone);
        });
    }

    // Remove row
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-field')) {
            const entry = e.target.closest('.grade-entry');
            if (gradeFields.querySelectorAll('.grade-entry').length > 1) {
                entry.remove();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'You need at least one subject entry.'
                });
            }
        }
    });

    // Toggle 'required' fields based on tab
    const gradeTabs = document.getElementById('gradeTabs');
    if (gradeTabs) {
        gradeTabs.addEventListener('shown.bs.tab', function (e) {
            const isManual = e.target.id === 'manual-tab';
            document.querySelectorAll('#manual [name], #csv [name]').forEach(el => {
                el.required = false;
            });
            document.querySelectorAll(isManual ? '#manual [name]' : '#csv [name]').forEach(el => {
                el.required = true;
            });

            // Update which form submit button is active
            const manualSubmitBtn = document.querySelector('button[form="manualGradeForm"]');
            if (manualSubmitBtn) {
                manualSubmitBtn.style.display = isManual ? 'block' : 'none';
            }
        });
    }

    // Manual form submission
    if (manualForm) {
        manualForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            // Show loading state
            // Find the submit button that's associated with the form using the form attribute
            const submitBtn = document.querySelector('button[type="submit"][form="manualGradeForm"]');
            
            // Check if submitBtn exists before accessing its properties
            if (!submitBtn) {
                console.error('Submit button not found - looking for button[type="submit"][form="manualGradeForm"]');
                return;
            }
            
            const originalText = submitBtn.textContent || 'Submit'; // Provide default text if not available
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';
            
            // Get the student LRN from the form
            // Note: Update the selector if your input field uses a different name
            const studentLRN = manualForm.querySelector('input[name="student_lrn"], input[name="lrn"]')?.value;
            
            if (!studentLRN) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing LRN',
                    text: 'Please enter a valid LRN for the student.'
                });
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                return;
            }
            
            // First, get the user_id that corresponds to this LRN
            fetch('../../src/controllers/get_studentid_by_lrn.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `lrn=${encodeURIComponent(studentLRN)}`
            })
            .then(response => response.json())
            .then(studentData => {
                if (studentData.status === 'success' && studentData.user_id) {
                    // Create FormData object
                    const formData = new FormData(manualForm);
                    
                    // Add the correct user_id to the form data
                    formData.set('user_id', studentData.user_id);
                    
                    // Send data to server
            fetch('../../src/controllers/save_manual_grades.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Student Grades Added!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Reset form
                    manualForm.reset();
                    
                    // Reset grade fields to single row
                    const firstEntry = gradeFields.querySelector('.grade-entry');
                    if (firstEntry) {
                        gradeFields.innerHTML = '';
                        const newRow = firstEntry.cloneNode(true);
                        newRow.querySelectorAll('input, select').forEach(input => {
                            if (input.tagName === 'SELECT') {
                                input.selectedIndex = 0;
                            } else {
                                input.value = '';
                            }
                        });
                        gradeFields.appendChild(newRow);
                    } else {
                        // Make sure addFieldBtn exists before clicking it
                        if (addFieldBtn) {
                            addFieldBtn.click(); // Add one fresh row
                        }
                    }
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addStudentGradeModal'));
                    if (modal) {
                        modal.hide();
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                    
                    // Log errors if any
                    if (data.errors && data.errors.length) {
                        console.error('Grade errors:', data.errors);
                    }
                }
            })
            .catch(error => {
                console.error('Error saving grades:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong while saving grades.'
                });
                // Restore submit button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
                } else {
                    console.error('Failed to get student user_id from LRN:', studentData?.message || 'Unknown error');
                    Swal.fire({
                        icon: 'error',
                        title: 'Student Not Found',
                        text: studentData?.message || 'Could not find a student with the provided LRN.'
                    });
                    // Restore submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error looking up student by LRN:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Could not look up student information with the provided LRN.'
                });
                // Restore submit button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    }

    // CSV Upload handler
    if (uploadGradesBtn) {
        uploadGradesBtn.addEventListener('click', function() {
            const fileInput = document.getElementById('csv_file');
            if (!fileInput || !fileInput.files.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing File',
                    text: 'Please select a CSV file to upload.'
                });
                return;
            }
            
            // Show loading state
            uploadGradesBtn.disabled = true;
            uploadGradesBtn.textContent = 'Uploading...';
            
            // Check if uploadResult exists before manipulating it
            if (uploadResult) {
                uploadResult.style.display = 'none';
            }
            
            const formData = new FormData(bulkForm);
            
            fetch('../../src/controllers/bulk_upload_grades.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Show result area if it exists
                if (uploadResult) {
                    uploadResult.style.display = 'block';
                
                    if (data.status === 'success') {
                        if (uploadResultMessage) {
                            uploadResultMessage.className = 'alert alert-success';
                            uploadResultMessage.textContent = data.message;
                        }
                        
                        // Display any errors if they exist
                        if (uploadErrors) {
                            if (data.errors && data.errors.length > 0) {
                                uploadErrors.innerHTML = '<div class="alert alert-warning mt-2">Warnings:</div>';
                                const errorList = document.createElement('ul');
                                data.errors.forEach(error => {
                                    const item = document.createElement('li');
                                    item.textContent = error;
                                    errorList.appendChild(item);
                                });
                                uploadErrors.appendChild(errorList);
                            } else {
                                uploadErrors.innerHTML = '';
                            }
                        }
                        
                        // Optionally reset the form if all went well
                        if (data.errors && data.errors.length === 0 && bulkForm) {
                            bulkForm.reset();
                        }
                    } else {
                        if (uploadResultMessage) {
                            uploadResultMessage.className = 'alert alert-danger';
                            uploadResultMessage.textContent = data.message;
                        }
                        
                        // Display any specific errors
                        if (uploadErrors) {
                            if (data.errors && data.errors.length > 0) {
                                uploadErrors.innerHTML = '<div class="alert alert-danger mt-2">Errors:</div>';
                                const errorList = document.createElement('ul');
                                data.errors.forEach(error => {
                                    const item = document.createElement('li');
                                    item.textContent = error;
                                    errorList.appendChild(item);
                                });
                                uploadErrors.appendChild(errorList);
                            } else {
                                uploadErrors.innerHTML = '';
                            }
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                if (uploadResult && uploadResultMessage) {
                    uploadResult.style.display = 'block';
                    uploadResultMessage.className = 'alert alert-danger';
                    uploadResultMessage.textContent = 'An error occurred during upload. Please try again.';
                }
                if (uploadErrors) {
                    uploadErrors.innerHTML = '';
                }
            })
            .finally(() => {
                // Restore button state
                uploadGradesBtn.disabled = false;
                uploadGradesBtn.textContent = 'Upload Grades';
            });
        });
    }
});