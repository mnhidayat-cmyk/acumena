<!-- Header -->
<div class="container px-6 mx-auto">
  <div class="flex items-center justify-between my-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200">Setting</h2>
    </div>
  </div>
</div>

<div class="container mx-auto px-6">
    <!-- Alert Messages -->
    <div id="alert-container" class="max-w-4xl mx-auto mb-4" style="display: none;">
        <div id="alert-message" class="px-4 py-3 rounded-md text-sm"></div>
    </div>

    <form id="settingsForm" class="max-w-4xl rounded-2xl bg-white dark:bg-gray-800 shadow-md mx-auto mt-8 p-6">
        <!-- Row 1 -->
        <div class="mt-6 flex flex-wrap gap-6">
            <!-- Full Name -->
            <div class="flex flex-col gap-2" style="flex:1 1 300px;">
                <label class="font-semibold gap-2 flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span class="text-sm text-gray-800 dark:text-gray-400">Full Name</span>
                </label>
                <input type="text" name="full_name" id="full_name" placeholder="e.g. John Doe" class="w-full px-4 py-3 border border-gray-300 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 focus:bg-white dark:focus:bg-gray-900" required>
                <span class="error-message text-red-500 text-xs" id="error-full_name"></span>
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-2" style="flex:1 1 300px;">
            <label class="font-semibold gap-2 flex items-center text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                </svg>
                <span class="text-sm text-gray-800 dark:text-gray-400">Email</span>
            </label>
            <input type="email" name="email" id="email" placeholder="e.g. example@domain.com" 
                    class="w-full px-4 py-3 border border-gray-300 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 focus:bg-white dark:focus:bg-gray-900" required>
            <span class="error-message text-red-500 text-xs" id="error-email"></span>
            </div>

            <div class="w-full bg-soft-primary px-4 py-2 text-sm text-primary rounded-md">Leave the password field blank if you don't want to change it</div>

            <!-- Password -->
            <div class="flex flex-col gap-2" style="flex:1 1 300px;">
                <label class="font-semibold gap-2 flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                    </svg>
                    <span class="text-sm text-gray-800 dark:text-gray-400">Password</span>
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password" placeholder="**********" class="w-full px-4 py-3 pr-12 border border-gray-300 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 focus:bg-white dark:focus:bg-gray-900">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg id="eyeSlashIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 1-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                <span class="error-message text-red-500 text-xs" id="error-password"></span>
            </div>

            <!-- Confirm Password -->
            <div class="flex flex-col gap-2" style="flex:1 1 300px;">
                <label class="font-semibold gap-2 flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                    </svg>
                    <span class="text-sm text-gray-800 dark:text-gray-400">Re-enter Password</span>
                </label>
                <div class="relative">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="**********" class="w-full px-4 py-3 pr-12 border border-gray-300 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 focus:bg-white dark:focus:bg-gray-900">
                    <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                        <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg id="eyeSlashIconConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 1-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                <span class="error-message text-red-500 text-xs" id="error-confirm_password"></span>
            </div>
        </div>

        <!-- Button Save -->
        <div class="flex justify-end mt-6">
            <button type="submit" id="saveBtn" class="btn gradient-primary text-white" style="padding-left: 24px;padding-right: 24px;">
                <span id="saveText">Save</span>
                <span id="loadingText" style="display: none;">Saving...</span>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('settingsForm');
    const saveBtn = document.getElementById('saveBtn');
    const saveText = document.getElementById('saveText');
    const loadingText = document.getElementById('loadingText');
    const alertContainer = document.getElementById('alert-container');
    const alertMessage = document.getElementById('alert-message');

    // Load current user data
    loadUserData();

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        updateProfile();
    });

    function loadUserData() {
        fetch('<?= base_url('api/setting') ?>', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('full_name').value = data.data.full_name || '';
                document.getElementById('email').value = data.data.email || '';
            }
        })
        .catch(error => {
            console.error('Error loading user data:', error);
        });
    }

    function updateProfile() {
        // Clear previous errors
        clearErrors();
        
        // Show loading state
        saveBtn.disabled = true;
        saveText.style.display = 'none';
        loadingText.style.display = 'inline';

        const formData = new FormData(form);

        fetch('<?= base_url('api/setting/update') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                // Clear password fields
                document.getElementById('password').value = '';
                document.getElementById('confirm_password').value = '';
            } else {
                if (data.errors) {
                    displayErrors(data.errors);
                }
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while saving data');
        })
        .finally(() => {
            // Reset button state
            saveBtn.disabled = false;
            saveText.style.display = 'inline';
            loadingText.style.display = 'none';
        });
    }

    function showAlert(type, message) {
        alertContainer.style.display = 'block';
        alertMessage.className = 'px-4 py-3 rounded-md text-sm';
        
        if (type === 'success') {
            alertMessage.className += ' bg-green-100 border border-green-400 text-green-700';
        } else {
            alertMessage.className += ' bg-red-100 border border-red-400 text-red-700';
        }
        
        alertMessage.textContent = message;
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            alertContainer.style.display = 'none';
        }, 5000);
    }

    function displayErrors(errors) {
        for (const field in errors) {
            const errorElement = document.getElementById('error-' + field);
            if (errorElement) {
                errorElement.textContent = errors[field];
            }
        }
    }

    function clearErrors() {
        const errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(element => {
            element.textContent = '';
        });
    }

    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeSlashIcon = document.getElementById('eyeSlashIcon');

    togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        
        if (type === 'text') {
            eyeIcon.style.display = 'none';
            eyeSlashIcon.style.display = 'block';
        } else {
            eyeIcon.style.display = 'block';
            eyeSlashIcon.style.display = 'none';
        }
    });

    // Confirm Password toggle functionality
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPasswordField = document.getElementById('confirm_password');
    const eyeIconConfirm = document.getElementById('eyeIconConfirm');
    const eyeSlashIconConfirm = document.getElementById('eyeSlashIconConfirm');

    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordField.setAttribute('type', type);
        
        if (type === 'text') {
            eyeIconConfirm.style.display = 'none';
            eyeSlashIconConfirm.style.display = 'block';
        } else {
            eyeIconConfirm.style.display = 'block';
            eyeSlashIconConfirm.style.display = 'none';
        }
    });
});
</script>
