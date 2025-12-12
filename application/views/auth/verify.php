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
    <script
      src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
      defer
    ></script>
    <script src="<?= base_url('assets/js/init-alpine.js') ?>"></script>
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
              <div class="w-full" x-data="verifyForm()">
                <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200 text-center">
                  Verify Your Account
                </h1>
                
                <!-- Flash Message untuk OTP -->
                <?php if ($this->session->flashdata('otp_message')): ?>
                <div class="mb-4 p-3 bg-blue-100 border border-blue-400 text-blue-700 rounded">
                  <?= $this->session->flashdata('otp_message') ?>
                </div>
                <?php endif; ?>
                
                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400 text-center">
                  Enter the 6-digit OTP code sent to <strong><?= htmlspecialchars($email) ?></strong>
                </p>

                <label class="block text-sm">
                  <span class="text-gray-700 dark:text-gray-400">OTP Code</span>
                  <input 
                    x-model="otp_code" 
                    :class="{ 'border-red-500 focus:border-red-500': errors.otp_code }" 
                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input bg-gray-100 dark:bg-gray-600 focus:bg-white text-center py-6 font-bold text-2xl tracking-widest" 
                    placeholder="000000"
                    maxlength="6"
                    type="text"
                  />
                  <small x-show="errors.otp_code" x-text="errors.otp_code" class="mt-1 block text-red-600"></small>
                </label>

                <button
                  type="button"
                  @click="submitVerify"
                  :disabled="loading || verificationSuccess"
                  class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 rounded-lg focus:outline-none gradient-primary disabled:opacity-60 disabled:cursor-not-allowed"
                >
                  <span x-show="!loading && !verificationSuccess" class="flex items-center justify-center">
                    Verify Account
                  </span>
                  <span x-show="loading && !verificationSuccess" class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Verifying...
                  </span>
                  <span x-show="verificationSuccess" class="flex items-center justify-center">
                    <svg class="mr-2 h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Verified! Redirecting...
                  </span>
                </button>

                <div class="mt-4 text-center">
                  <button 
                    type="button" 
                    @click="resendOTP" 
                    :disabled="resendLoading || resendCooldown > 0"
                    class="text-sm text-blue-500 hover:underline disabled:text-gray-400"
                  >
                    <span x-show="!resendLoading && resendCooldown === 0">Resend OTP</span>
                    <span x-show="resendLoading">Sending...</span>
                    <span x-show="!resendLoading && resendCooldown > 0" x-text="'Resend in ' + resendCooldown + 's'"></span>
                  </button>

                  <!-- login link -->
                  <a href="<?= base_url('auth') ?>" class="block mt-4 text-sm text-center text-blue-500 hover:underline">
                    Back to Login
                  </a>
                </div>

                <input type="hidden" name="email" :value="email">
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>

    <script>
      function verifyForm() {
        return {
          otp_code: '',
          email: '<?= htmlspecialchars($email) ?>',
          loading: false,
          verificationSuccess: false,
          resendLoading: false,
          resendCooldown: 0,
          errors: {},
          message: '',
          get hasErrors() { return Object.keys(this.errors).length > 0; },
          
          async submitVerify() {
            this.errors = {}; 
            this.message = '';
            
            // Client-side validation
            if (this.otp_code.trim() === '') {
              this.errors.otp_code = 'OTP code is required';
              return;
            }
            
            if (this.otp_code.trim().length !== 6) {
              this.errors.otp_code = 'OTP code must be 6 digits';
              return;
            }
            
            if (!/^\d{6}$/.test(this.otp_code.trim())) {
              this.errors.otp_code = 'OTP code must contain only numbers';
              return;
            }

            this.loading = true;
            try {
              const formData = new FormData();
              formData.append('email', this.email);
              formData.append('otp_code', this.otp_code.trim());

              const res = await fetch('<?= base_url('api/auth/verify') ?>', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData,
              });
              const json = await res.json();

              if (!res.ok || json.error) {
                this.errors = json.errors || {};
                this.message = json.message || 'Verification failed';
                this.loading = false;
              } else {
                this.message = json.message || 'Account verified successfully';
                this.verificationSuccess = true;
                this.loading = false;
                // Set flash message untuk halaman login
                sessionStorage.setItem('verification_success', 'Account verified successfully! You can now login.');
                // Redirect ke login setelah sukses
                setTimeout(() => { 
                  window.location.href = '<?= base_url('auth/login') ?>'; 
                }, 1500);
              }
            } catch (e) {
              this.message = 'Failed to connect to server';
              this.loading = false;
            }
          },
          
          async resendOTP() {
            if (this.resendCooldown > 0) return;
            
            this.resendLoading = true;
            this.message = '';
            
            try {
              const formData = new FormData();
              formData.append('email', this.email);

              const res = await fetch('<?= base_url('api/auth/resend_otp') ?>', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData,
              });
              const json = await res.json();

              if (!res.ok || json.error) {
                this.message = json.message || 'Failed to resend OTP';
              } else {
                this.message = json.message || 'OTP has been resent to your email';
                this.startCooldown();
              }
            } catch (e) {
              this.message = 'Failed to connect to server';
            } finally {
              this.resendLoading = false;
            }
          },
          
          startCooldown() {
            this.resendCooldown = 60; // 60 detik cooldown
            const interval = setInterval(() => {
              this.resendCooldown--;
              if (this.resendCooldown <= 0) {
                clearInterval(interval);
              }
            }, 1000);
          }
        }
      }
    </script>
  </body>
</html>
