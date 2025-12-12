<!-- Header -->
<div class="container px-6 mx-auto">
  <div class="flex items-center justify-between my-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200"><span class="text-gray-400">Create Project::</span> Company Profile</h2>
        <p class="text-gray-500">Please provide basic information about your company before proceeding with the strategic analysis</p>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('projectForm');
    const nextBtn = document.getElementById('nextBtn');
    const nextText = document.getElementById('nextText');
    const loadingText = document.getElementById('loadingText');
    const alertContainer = document.getElementById('alert-container');
    const alertMessage = document.getElementById('alert-message');

    // Check if we have a key parameter for editing
    const urlParams = new URLSearchParams(window.location.search);
    const projectKey = urlParams.get('key');
    let isEditMode = false;
    
    // If we have a key, load the existing project data
    if (projectKey) {
        isEditMode = true;
        loadProjectData(projectKey);
    }
    
    // Function to load existing project data
    function loadProjectData(uuid) {
        // Show loading state
        document.getElementById('company_name').disabled = true;
        document.getElementById('industry').disabled = true;
        document.getElementById('description').disabled = true;
        document.getElementById('vision').disabled = true;
        document.getElementById('mission').disabled = true;
        nextBtn.disabled = true;
        nextText.style.display = 'none';
        loadingText.style.display = 'inline-flex';
        loadingText.textContent = 'Loading...';
        
        fetch('<?= base_url('api/project/profile_get') ?>?uuid=' + encodeURIComponent(uuid), {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Populate form fields with existing data
                document.getElementById('company_name').value = result.data.company_name || '';
                document.getElementById('industry').value = result.data.industry || '';
                document.getElementById('description').value = result.data.description || '';
                document.getElementById('vision').value = result.data.vision || '';
                document.getElementById('mission').value = result.data.mission || '';
                
                // Store UUID for update
                form.dataset.uuid = uuid;
            } else {
                showAlert(result.message || 'Failed to load project data', 'error');
            }
        })
        .catch(error => {
            console.error('Error loading project data:', error);
            showAlert('An error occurred while loading project data', 'error');
        })
        .finally(() => {
            // Re-enable form elements
            document.getElementById('company_name').disabled = false;
            document.getElementById('industry').disabled = false;
            document.getElementById('description').disabled = false;
            document.getElementById('vision').disabled = false;
            document.getElementById('mission').disabled = false;
            nextBtn.disabled = false;
            nextText.style.display = 'inline';
            loadingText.style.display = 'none';
        });
    }

    // Clear previous error messages
    function clearErrors() {
        const errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(element => {
            element.style.display = 'none';
            element.textContent = '';
        });
    }

    // Show error messages
    function showErrors(errors) {
        Object.keys(errors).forEach(field => {
            const errorElement = document.getElementById('error-' + field);
            if (errorElement) {
                errorElement.textContent = errors[field];
                errorElement.style.display = 'block';
            }
        });
    }

    // Show alert message
    function showAlert(message, type = 'success') {
        alertMessage.textContent = message;
        alertMessage.className = `px-4 py-3 rounded-md text-sm ${
            type === 'success' 
                ? 'bg-green-100 text-green-700 border border-green-300' 
                : 'bg-red-100 text-red-700 border border-red-300'
        }`;
        alertContainer.style.display = 'block';
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            alertContainer.style.display = 'none';
        }, 5000);
    }

    // Handle form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Clear previous errors and alerts
        clearErrors();
        alertContainer.style.display = 'none';
        
        // Show loading state
        nextText.style.display = 'none';
        loadingText.style.display = 'inline-flex';
        loadingText.textContent = 'Saving...';
        nextBtn.disabled = true;

        // Collect form data
        const formData = {
            company_name: document.getElementById('company_name').value.trim(),
            industry: document.getElementById('industry').value.trim(),
            description: document.getElementById('description').value.trim(),
            vision: document.getElementById('vision').value.trim(),
            mission: document.getElementById('mission').value.trim()
        };
        
        // Add UUID if in edit mode
        const uuid = form.dataset.uuid;
        if (uuid) {
            formData.uuid = uuid;
        }

        try {
            const response = await fetch('<?= base_url('api/project/profile_saves') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.success) {
                // Redirect immediately without showing success notification
                window.location.href = result.data.redirect_url;
            } else {
                if (result.errors) {
                    showErrors(result.errors);
                }
                showAlert(result.message || 'An error occurred while saving project data', 'error');
                
                // Reset button state only on error
                nextText.style.display = 'inline';
                loadingText.style.display = 'none';
                nextBtn.disabled = false;
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('An error occurred while saving project data', 'error');
            
            // Reset button state only on error
            nextText.style.display = 'inline';
            loadingText.style.display = 'none';
            nextBtn.disabled = false;
        }
    });
});
</script>

<div class="container mx-auto px-6">
    <style>
    @media (max-width: 1024px) {
        .steps { gap:6px !important; }
        .step { padding:8px !important; }
        .step__desc { display:none !important; }
        .step__title { font-size:13px !important; line-height:1.2 !important; }
    }
    </style>

    <div>
        <div class="steps flex items-stretch justify-between gap-3">

            <!-- STEP 1 (Active) -->
            <div class="step step--active bg-blue-300 rounded-2xl px-4 py-2 items-center gap-2 flex flex-1 basis-0 border border-gray-200">
                <div class="step__num w-6 h-6 flex items-center justify-center rounded-full shadow-md font-bold text-white bg-blue-600 w-9 h-9 flex-shrink-0 flex-grow-0 basis-9 flex justify-center">1</div>
                <div class="flex flex-col gap-1 min-w-0">
                    <div class="step__title font-bold text-sm">Company Profile</div>
                    <div class="step__desc text-xs text-gray-500">Complete your company data</div>
                </div>
            </div>

            <!-- STEP 2 -->
            <div class="step step--inactive bg-white dark:bg-gray-800 rounded-2xl px-4 py-2 items-center border border-gray-200 dark:border-gray-600 gap-2 flex flex-1 basis-0">
                <div class="step__num w-6 h-6 flex items-center justify-center rounded-full shadow-md font-bold  bg-gray-300 dark:bg-gray-700 dark:text-white w-9 h-9 flex-shrink-0 flex-grow-0 basis-9 flex justify-center">2</div>
                <div class="flex flex-col gap-1 min-w-0">
                    <div class="step__title font-bold text-sm dark:text-white">SWOT Analysis</div>
                    <div class="step__desc text-xs text-gray-500">Assess Strengths, Weaknesses, Opportunities, and Threats</div>
                </div>
            </div>

            <!-- STEP 3 -->
            <div class="step step--inactive bg-white dark:bg-gray-800 rounded-2xl px-4 py-2 items-center border border-gray-200 dark:border-gray-600 gap-2 flex flex-1 basis-0">
                <div class="step__num w-6 h-6 flex items-center justify-center rounded-full shadow-md font-bold  bg-gray-300 dark:bg-gray-700 dark:text-white w-9 h-9 flex-shrink-0 flex-grow-0 basis-9 flex justify-center">3</div>
                <div class="flex flex-col gap-1 min-w-0">
                    <div class="step__title font-bold text-sm dark:text-white">Matrix Analysis</div>
                    <div class="step__desc text-xs text-gray-500">Evaluate IE Matrix and strategic recommendations</div>
                </div>
            </div>

        </div>
    </div>

    <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-md mx-auto mt-8 p-6">
        <!-- Alert Container -->
        <div id="alert-container" class="mb-4" style="display: none;">
            <div id="alert-message" class="px-4 py-3 rounded-md text-sm"></div>
        </div>

        <form id="projectForm">
            <!-- Row 1 -->
            <div class="mt-6 flex flex-wrap gap-6">
                <!-- Company Name -->
                <div class="flex flex-col gap-2" style="flex:1 1 300px;">
                    <label class="font-semibold gap-2 flex items-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                        </svg>
                        <span class="text-sm text-gray-800 dark:text-gray-400">Company Name</span>
                    </label>
                    <input type="text" name="company_name" id="company_name" placeholder="e.g. Acme Corporation" class="w-full px-4 py-3 border border-gray-300 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 focus:bg-white dark:focus:bg-gray-900">
                    <span id="error-company_name" class="error-message text-red-500 text-xs" style="display: none;"></span>
                </div>

                <!-- Industry Sector -->
                <div class="flex flex-col gap-2" style="flex:1 1 300px;">
                <label class="font-semibold gap-2 flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                    </svg>

                    <span class="text-sm text-gray-800 dark:text-gray-400">Industry Sector</span>
                </label>
                <input type="text" name="industry" id="industry" placeholder="e.g. Technology, Healthcare, Finance" 
                        class="w-full px-4 py-3 border border-gray-300 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 focus:bg-white dark:focus:bg-gray-900">
                <span id="error-industry" class="error-message text-red-500 text-xs" style="display: none;"></span>
                </div>
            </div>

            <!-- Description -->
            <div class="flex flex-col gap-2 mt-6">
                <label class="font-semibold gap-2 flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <span class="text-sm text-gray-800 dark:text-gray-400">Brief Description</span>
                </label>
                <textarea name="description" id="description" placeholder="Describe your company's products or services" rows="7" class="w-full px-4 py-3 border border-gray-300 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 focus:bg-white dark:focus:bg-gray-900"></textarea>
                <span id="error-description" class="error-message text-red-500 text-xs" style="display: none;"></span>
            </div>

            <!-- Vision -->
            <div class="flex flex-col gap-2 mt-6">
                <label class="font-semibold gap-2 flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>

                <span class="text-sm text-gray-800 dark:text-gray-400">Vision Statement</span>
                </label>
                <textarea name="vision" id="vision" placeholder="Your company's vision for the future" rows="5" class="w-full px-4 py-3 border border-gray-300 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 focus:bg-white dark:focus:bg-gray-900"></textarea>
                <span id="error-vision" class="error-message text-red-500 text-xs" style="display: none;"></span>
            </div>

            <!-- Mission -->
            <div class="flex flex-col gap-2 mt-6">
                <label class="font-semibold gap-2 flex items-center text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                </svg>

                <span class="text-sm text-gray-800 dark:text-gray-400">Mission Statement</span>
                </label>
                <textarea name="mission" id="mission" placeholder="Your company's purpose and goals" rows="5" class="w-full px-4 py-3 border border-gray-300 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 focus:bg-white dark:focus:bg-gray-900"></textarea>  
                <span id="error-mission" class="error-message text-red-500 text-xs" style="display: none;"></span>
            </div>

            <!-- Button Next -->
            <div class="flex justify-end mt-6">
                <button type="submit" id="nextBtn" class="btn gradient-primary text-white" style="padding-left: 24px;padding-right: 24px;">
                    <span id="nextText">Next</span>
                    <span id="loadingText" style="display: none;">
                        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </button>
            </div>
        </form>
    </div>


</div>
