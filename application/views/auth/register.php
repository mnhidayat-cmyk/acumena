<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?> | Acumena</title>
    <link rel="icon" href="<?= base_url('assets/img/acumena-favicon.png') ?>" type="image/png" />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.output.css?version=' . date('YmdHis')) ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css?version=' . date('YmdHis')) ?>" />
    <style>
      /* Spinner Animation */
      @keyframes spin {
        from {
          transform: rotate(0deg);
        }
        to {
          transform: rotate(360deg);
        }
      }
      .animate-spin {
        animation: spin 1s linear infinite;
      }
      /* Alpine.js x-cloak untuk mencegah flash content */
      [x-cloak] {
        display: none !important;
      }
    </style>
    <script
      src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
      defer
    ></script>
    <script src="<?= base_url('assets/js/init-alpine.js') ?>"></script>
    <script>
      // Debug Alpine.js loading
      document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, Alpine available:', typeof Alpine !== 'undefined');
      });
      
      // Debug Alpine.js initialization
      document.addEventListener('alpine:init', function() {
        console.log('Alpine.js initialized');
      });
    </script>
  </head>
  <body>
    <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
      <div class="mx-auto overflow-hidden" style="width: 400px">
        <img
          src="<?= base_url('assets/img/acumena-logo.png') ?>"
          alt=""
          class="mb-4 mx-auto"
          style="width: 70%"
        />
        <div class="bg-white rounded-lg shadow-xl dark:bg-gray-800 border border-gray-200">
          <div class="overflow-y-auto">
            <div class="flex items-center justify-center p-6">
              <div class="w-full" x-data="registerForm()">
                <label class="block text-sm">
                  <span class="text-gray-700 dark:text-gray-400">Full Name</span>
                  <input x-model="full_name" :class="{ 'border-red-500 focus:border-red-500': errors.full_name }" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input bg-gray-100 dark:bg-gray-600 focus:bg-white" placeholder="Enter your full name"/>
                  <small x-show="errors.full_name" x-text="errors.full_name" class="mt-1 block text-red-600"></small>
                </label>
                <label class="block mt-4 text-sm">
                  <span class="text-gray-700 dark:text-gray-400">Email</span>
                  <input x-model="email" :class="{ 'border-red-500 focus:border-red-500': errors.email }" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input bg-gray-100 dark:bg-gray-600 focus:bg-white"
                    placeholder="Enter your email"
                  />
                  <small x-show="errors.email" x-text="errors.email" class="mt-1 block text-red-600"></small>
                </label>
                <label class="block mt-4 text-sm">
                  <span class="text-gray-700 dark:text-gray-400">Password</span>
                  <div class="relative">
                    <input
                      x-model="password"
                      @input="hasSpecialChar = checkSpecialChar($event.target.value); console.log('Input event:', $event.target.value, 'Has special:', hasSpecialChar)"
                      :class="{ 'border-red-500 focus:border-red-500': errors.password }"
                      :type="showPassword ? 'text' : 'password'"
                      class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input bg-gray-100 dark:bg-gray-600 focus:bg-white pr-10"
                      placeholder="***************"
                    />
                    <button
                      type="button"
                      @click="showPassword = !showPassword"
                      class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                    >
                      <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                      <svg x-show="showPassword" class="w-4 h-4"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                      </svg>

                    </button>
                  </div>
                  <!-- Password Requirements Indicator -->
                  <div x-show="password.length > 0" class="mt-2 text-xs">
                    <div class="text-gray-600 dark:text-gray-400 mb-1">Password harus memenuhi:</div>
                    <div class="space-y-1">
                      <div :class="password.length >= 8 ? 'text-green-600' : 'text-red-600'" class="flex items-center">
                        <span x-text="password.length >= 8 ? '✓' : '✗'" class="mr-1"></span>
                        Minimal 8 karakter
                      </div>
                      <div :class="/[A-Z]/.test(password) ? 'text-green-600' : 'text-red-600'" class="flex items-center">
                        <span x-text="/[A-Z]/.test(password) ? '✓' : '✗'" class="mr-1"></span>
                        Mengandung huruf kapital
                      </div>
                      <div :class="/[0-9]/.test(password) ? 'text-green-600' : 'text-red-600'" class="flex items-center">
                        <span x-text="/[0-9]/.test(password) ? '✓' : '✗'" class="mr-1"></span>
                        Mengandung angka
                      </div>
                      <div :class="hasSpecialChar ? 'text-green-600' : 'text-red-600'" class="flex items-center">
                        <span x-text="hasSpecialChar ? '✓' : '✗'" class="mr-1"></span>
                        Mengandung karakter khusus
                      </div>

                    </div>
                  </div>
                  <small x-show="errors.password" x-text="errors.password" class="mt-1 block text-red-600"></small>
                </label>
                <label class="block mt-4 text-sm">
                  <span class="text-gray-700 dark:text-gray-400"
                    >Confirm Password</span
                  >
                  <div class="relative">
                    <input
                      x-model="confirm_password"
                      :class="{ 'border-red-500 focus:border-red-500': errors.confirm_password }"
                      :type="showPassword ? 'text' : 'password'"
                      class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input bg-gray-100 dark:bg-gray-600 focus:bg-white pr-10"
                      placeholder="***************"
                    />
                    <button
                      type="button"
                      @click="showPassword = !showPassword"
                      class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                    >
                      <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                      <svg x-show="showPassword" class="w-4 h-4"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                      </svg>

                    </button>
                  </div>
                  <small x-show="errors.confirm_password" x-text="errors.confirm_password" class="mt-1 block text-red-600"></small>
                </label>

                <div class="flex items-center justify-between mt-4">
                  <label class="flex items-center">
                    <input class="form-checkbox" type="checkbox" x-model="agree_policy" />
                    <span class="ml-2 text-sm"
                      >I agree to the
                      <a href="#" class="text-blue-500">privacy policy</a></span
                    >
                  </label>
                </div>

                <button
                  type="button"
                  @click="submitRegister()"
                  :disabled="loading || !agree_policy"
                  :class="{ 'opacity-60 cursor-not-allowed': loading || !agree_policy }"
                  class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 rounded-lg focus:outline-none gradient-primary"
                >
                  <span x-show="!loading" class="flex items-center justify-center">
                    Register
                  </span>
                  <span x-show="loading" x-cloak class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                  </span>
                </button>

                <hr class="my-6" />
                <p>
                  <a class="text-sm font-medium text-blue-500 dark:text-blue-500 hover:underline" href="<?= base_url('auth/login') ?>">
                    Already have an account? Login
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      function registerForm() {
        console.log('registerForm function called');
        return {
          full_name: '',
          email: '',
          password: '',
          confirm_password: '',
          agree_policy: false,
          loading: false,
          errors: {},
          message: '',
          hasSpecialChar: false,
          showPassword: false,
          
          init() {
            console.log('Alpine.js registerForm initialized');
            this.$watch('password', (value) => {
              console.log('Password watcher triggered with value:', value);
              this.hasSpecialChar = this.checkSpecialChar(value);
              console.log('Password changed:', value, 'Has special char:', this.hasSpecialChar);
            });
          },
          
          checkSpecialChar(password) {
            if (!password) return false;
            // Cek karakter khusus satu per satu
            const specialChars = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '-', '=', '[', ']', '{', '}', ';', "'", ':', '"', '|', ',', '.', '<', '>', '?', '/', '\\'];
            const result = specialChars.some(char => password.includes(char));
            console.log('Checking special chars for:', password, 'Result:', result);
            return result;
          },
          
          get hasErrors() { return Object.keys(this.errors).length > 0; },
          translate(msg) {
            const map = {
              'Nama lengkap wajib diisi': 'Full name is required',
              'Email wajib diisi': 'Email is required',
              'Format email tidak valid': 'Invalid email format',
              'Password wajib diisi': 'Password is required',
              'Konfirmasi password wajib diisi': 'Confirm password is required',
              'Konfirmasi password tidak cocok': 'Password confirmation does not match',
              'Email sudah terdaftar': 'Email is already registered',
              'Validasi input gagal': 'Input validation failed',
              'Terjadi kesalahan': 'An error occurred',
              'Registrasi berhasil': 'Registration successful',
              'Gagal menghubungi server': 'Failed to connect to server',
            };
            return map[msg] || msg;
          },
          translateErrors(errs) {
            const out = {};
            for (const k in (errs || {})) {
              out[k] = this.translate(errs[k]);
            }
            return out;
          },
          validatePassword(password) {
            const errors = [];
            if (password.length < 8) {
              errors.push('Minimal 8 karakter');
            }
            if (!/[A-Z]/.test(password)) {
              errors.push('Harus mengandung huruf kapital');
            }
            if (!/[0-9]/.test(password)) {
              errors.push('Harus mengandung angka');
            }
            if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
              errors.push('Harus mengandung karakter khusus');
            }
            return errors;
          },
          validateEmailUniqueness(email) {
            // Hilangkan titik dari email untuk validasi keunikan
            const normalizedEmail = email.replace(/\./g, '');
            return normalizedEmail;
          },
          async submitRegister() {
            this.errors = {}; this.message = '';
            // Client-side checks
            if (this.full_name.trim() === '') {
              this.errors.full_name = 'Full name is required';
            }
            
            // Validasi email
            if (this.email.trim() === '') {
              this.errors.email = 'Email is required';
            } else {
              const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
              if (!emailPattern.test(this.email.trim())) {
                this.errors.email = 'Invalid email format';
              }
            }
            
            // Validasi password dengan requirements baru
            if (this.password === '') {
              this.errors.password = 'Password is required';
            } else {
              const passwordErrors = this.validatePassword(this.password);
              if (passwordErrors.length > 0) {
                this.errors.password = passwordErrors.join(', ');
              }
            }
            
            if (this.confirm_password === '') {
              this.errors.confirm_password = 'Confirm password is required';
            } else if (this.password !== this.confirm_password) {
              this.errors.confirm_password = 'Password confirmation does not match';
            }
            if (Object.keys(this.errors).length > 0) { return; }

            console.log('Setting loading to true');
            this.loading = true;
            console.log('Loading state:', this.loading);
            try {
              const formData = new FormData();
              formData.append('full_name', this.full_name.trim());
              // Kirim email asli (dengan titik) ke backend
              formData.append('email', this.email.trim());
              formData.append('password', this.password);

              console.log('Sending request to:', '<?= base_url('api/auth/register') ?>');
              
              const res = await fetch('<?= base_url('api/auth/register') ?>', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData,
              });
              
              console.log('Response status:', res.status);
              console.log('Response headers:', res.headers);
              
              const json = await res.json();
              console.log('Response JSON:', json);

              if (!res.ok || json.error) {
                this.errors = this.translateErrors(json.errors || {});
                this.message = this.translate(json.message || 'An error occurred');
              } else {
                this.message = this.translate(json.message || 'Registration successful');
                // Redirect ke halaman verifikasi dengan email sebagai parameter
                setTimeout(() => { 
                  window.location.href = '<?= base_url('auth/verify') ?>?email=' + encodeURIComponent(this.email);
                }, 1500);
                // Jangan set loading ke false jika sukses, biarkan tetap loading sampai redirect
                return;
              }
            } catch (e) {
              console.error('Fetch error:', e);
              console.error('Error details:', e.message, e.stack);
              this.message = 'Failed to connect to server: ' + e.message;
            } finally {
              // Hanya set loading ke false jika ada error
              if (this.message && !this.message.includes('successful')) {
                console.log('Setting loading to false due to error');
                this.loading = false;
                console.log('Loading state after finally:', this.loading);
              }
            }
          }
        }
      }
    </script>
  </body>
</html>
