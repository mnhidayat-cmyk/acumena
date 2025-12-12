<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
   <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title><?= $title ?> | Acumena</title>
      <link rel="icon" href="<?= base_url('assets/img/acumena-favicon.png') ?>" type="image/png" />
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
      <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.output.css') ?>" />
      <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
      <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer ></script>
      <script src="<?= base_url('assets/js/init-alpine.js') ?>"></script>
   </head>
   <body>
      <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
         <div class="mx-auto overflow-hidden" style="width: 400px">
            <img src="<?= base_url('assets/img/acumena-logo.png') ?>" alt="" class="mb-4 mx-auto" style="width: 70%"/>
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
               <div class="overflow-y-auto">
                  <div class="flex items-center justify-center p-6">
                     <div class="w-full">
                        <!-- Flash Message dari sessionStorage -->
                        <div id="flash-message" class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded" style="display: none;">
                        </div>
                        
                        <form id="forgotForm" method="POST">
                        <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Email</span>
                        <input id="email" name="email" type="email" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input bg-gray-100 focus:bg-white"
                           placeholder="your@email.com" required
                           />
                        </label>
                        <!-- Forgot Password Button -->
                        <button type="submit" id="forgotBtn"
                           class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 rounded-lg focus:outline-none gradient-primary disabled:opacity-60 disabled:cursor-not-allowed"
                           >
                        <span id="forgotBtnText">Send Reset Link</span>
                        <span id="forgotBtnLoading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Sending OTP...
                        </span>
                        </button>
                        </form>
                        <hr class="my-8" />
                        <!-- <button
                           class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray"
                           >
                           <img
                             src="assets/svg/google-icon.svg"
                             alt=""
                             class="w-4 h-4 mr-2"
                           />
                           Google
                           </button> -->
                        <p class="mt-4">
                           <a
                              class="text-sm font-medium text-blue-500 dark:text-blue-500 hover:underline"
                              href="<?= base_url('auth/login') ?>"
                              >
                           Back to Login
                           </a>
                        </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <script>
        // Tampilkan flash message dari sessionStorage jika ada
        document.addEventListener('DOMContentLoaded', function() {
          const flashMessage = sessionStorage.getItem('verification_success');
          if (flashMessage) {
            const flashDiv = document.getElementById('flash-message');
            flashDiv.textContent = flashMessage;
            flashDiv.style.display = 'block';
            
            // Hapus message dari sessionStorage setelah ditampilkan
            sessionStorage.removeItem('verification_success');
            
            // Auto hide setelah 5 detik
            setTimeout(() => {
              flashDiv.style.display = 'none';
            }, 5000);
          }
        });

        // Forgot password handling
        document.getElementById('forgotForm').addEventListener('submit', async function(e) {
          e.preventDefault();
          
          const forgotBtn = document.getElementById('forgotBtn');
          const forgotBtnText = document.getElementById('forgotBtnText');
          const forgotBtnLoading = document.getElementById('forgotBtnLoading');
          const flashDiv = document.getElementById('flash-message');
          
          // Get form data
          const formData = new FormData(this);
          const email = formData.get('email');
          
          // Show loading state
          forgotBtn.disabled = true;
          forgotBtnText.classList.add('hidden');
          forgotBtnLoading.classList.remove('hidden');
          
          try {
            const response = await fetch('<?= base_url('api/auth/forgot_password') ?>', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                email: email
              })
            });
            
            const result = await response.json();
            
            // Always show generic message
            if (result.success) {
              flashDiv.textContent = result.message;
              flashDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
              flashDiv.style.display = 'block';
            } else {
              flashDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
              flashDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
              flashDiv.style.display = 'block';
              // Reset button state
              forgotBtn.disabled = false;
              forgotBtnText.classList.remove('hidden');
              forgotBtnLoading.classList.add('hidden');
            }
          } catch (error) {
            console.error('Login error:', error);
            flashDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
            flashDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
            flashDiv.style.display = 'block';
            
            // Reset button state
            forgotBtn.disabled = false;
            forgotBtnText.classList.remove('hidden');
            forgotBtnLoading.classList.add('hidden');
          }
        });
        // Tidak ada field password di halaman ini
      </script>
   </body>
</html>