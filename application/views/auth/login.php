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
                        
                        <form id="loginForm" method="POST">
                        <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Email</span>
                        <input id="email" name="email" type="email" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input bg-gray-100 focus:bg-white"
                           placeholder="your@email.com" required
                           />
                        </label>
                        <label class="block mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Password</span>
                        <div class="relative">
                           <input id="password" name="password"
                              class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input bg-gray-100 focus:bg-white pr-10"
                              placeholder="***************"
                              type="password" required
                              />
                           <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                              <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                              </svg>
                              <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                              </svg>
                           </button>
                        </div>
                        </label>
                        <div class="flex items-center justify-between mt-4">
                           <label class="flex items-center">
                           <input id="remember" name="remember" class="form-checkbox" type="checkbox" value="1" />
                           <span class="ml-2 text-sm text-gray-600 dark:text-gray-400"
                              >Remember me</span
                              >
                           </label>
                        </div>
                        <!-- Login Button -->
                        <button type="submit" id="loginBtn"
                           class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 rounded-lg focus:outline-none gradient-primary disabled:opacity-60 disabled:cursor-not-allowed"
                           >
                        <span id="loginBtnText">Log in</span>
                        <span id="loginBtnLoading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Logging in...
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
                              href="<?= base_url('auth/forgot_password') ?>"
                              >
                           Forgot your password?
                           </a>
                        </p>
                        <p class="mt-1">
                           <a
                              class="text-sm font-medium text-blue-500 dark:text-blue-500 hover:underline"
                              href="<?= base_url('auth/register') ?>"
                              >
                           Create account
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

        // Login form handling
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
          e.preventDefault();
          
          const loginBtn = document.getElementById('loginBtn');
          const loginBtnText = document.getElementById('loginBtnText');
          const loginBtnLoading = document.getElementById('loginBtnLoading');
          const flashDiv = document.getElementById('flash-message');
          
          // Get form data
          const formData = new FormData(this);
          const email = formData.get('email');
          const password = formData.get('password');
          const remember = formData.get('remember') ? 1 : 0;
          
          // Show loading state
          loginBtn.disabled = true;
          loginBtnText.classList.add('hidden');
          loginBtnLoading.classList.remove('hidden');
          
          try {
            const response = await fetch('<?= base_url('api/auth/login') ?>', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                email: email,
                password: password,
                remember: remember
              })
            });
            
            const result = await response.json();
            
            if (result.success) {
              // Show success message with green color
              flashDiv.textContent = result.message || 'Login successful! Redirecting to dashboard...';
              flashDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
              flashDiv.style.display = 'block';
              
              // Redirect to dashboard after short delay
              setTimeout(() => {
                window.location.href = result.data?.redirect_url || '<?= base_url('dashboard') ?>';
              }, 1500);
            } else {
              // Check if user requires verification
              if (result.requires_verification) {
                // Show verification message with orange/yellow color
                flashDiv.textContent = result.message || 'Account not verified. Redirecting to verification page...';
                flashDiv.className = 'bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4';
                flashDiv.style.display = 'block';
                
                // Redirect to verification page after short delay
                setTimeout(() => {
                  window.location.href = result.redirect_url;
                }, 1500);
              } else {
                // Show error message
                flashDiv.textContent = result.message || 'Login failed. Please try again.';
                flashDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
                flashDiv.style.display = 'block';
                
                // Reset button state
                loginBtn.disabled = false;
                loginBtnText.classList.remove('hidden');
                loginBtnLoading.classList.add('hidden');
              }
            }
          } catch (error) {
            console.error('Login error:', error);
            flashDiv.textContent = 'An error occurred. Please try again.';
            flashDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
            flashDiv.style.display = 'block';
            
            // Reset button state
            loginBtn.disabled = false;
            loginBtnText.classList.remove('hidden');
            loginBtnLoading.classList.add('hidden');
          }
        });

        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        togglePassword.addEventListener('click', function() {
          const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordField.setAttribute('type', type);
          
          // Toggle eye icons
          if (type === 'password') {
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
          } else {
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
          }
        });
      </script>
   </body>
</html>