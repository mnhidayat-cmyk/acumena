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
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
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
                <div id="flash-message" class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded" style="display: none;"></div>

                <form id="resetForm" method="POST">
                  <input type="hidden" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" />
                  <input type="hidden" id="token" name="token" value="<?= htmlspecialchars($token ?? '') ?>" />

                  <label class="block text-sm mb-2">
                    <span class="text-gray-700 dark:text-gray-400">Password Baru</span>
                    <div class="relative">
                      <input
                        id="password"
                        name="password"
                        type="password"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input bg-gray-100 focus:bg-white pr-10"
                        placeholder="Minimal 8 karakter"
                        required
                      />
                      <button
                        type="button"
                        id="togglePassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                        aria-label="Toggle password visibility"
                      >
                        <svg id="iconShowPwd" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg id="iconHidePwd" class="w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                      </button>
                    </div>
                    <!-- Indikator Syarat Password -->
                    <div id="password-indicator" class="mt-2 text-xs hidden">
                      <div class="text-gray-600 dark:text-gray-400 mb-1">Password harus memenuhi:</div>
                      <div class="space-y-1">
                        <div id="req-length" class="flex items-center text-red-600">
                          <span id="tick-length" class="mr-1">✗</span>
                          Minimal 8 karakter
                        </div>
                        <div id="req-uppercase" class="flex items-center text-red-600">
                          <span id="tick-uppercase" class="mr-1">✗</span>
                          Mengandung huruf kapital
                        </div>
                        <div id="req-number" class="flex items-center text-red-600">
                          <span id="tick-number" class="mr-1">✗</span>
                          Mengandung angka
                        </div>
                        <div id="req-special" class="flex items-center text-red-600">
                          <span id="tick-special" class="mr-1">✗</span>
                          Mengandung karakter khusus
                        </div>
                      </div>
                    </div>
                    <small id="password-error" class="mt-1 block text-red-600" style="display:none"></small>
                  </label>

                  <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Konfirmasi Password</span>
                    <div class="relative">
                      <input
                        id="confirm_password"
                        name="confirm_password"
                        type="password"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input bg-gray-100 focus:bg-white pr-10"
                        required
                      />
                      <button
                        type="button"
                        id="toggleConfirmPassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                        aria-label="Toggle confirm password visibility"
                      >
                        <svg id="iconShowConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg id="iconHideConfirm" class="w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                      </button>
                    </div>
                    <small id="confirm-error" class="mt-1 block text-red-600" style="display:none"></small>
                  </label>

                  <button type="submit" id="resetBtn" class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 rounded-lg focus:outline-none gradient-primary disabled:opacity-60 disabled:cursor-not-allowed">
                    <span id="resetBtnText">Set Password Baru</span>
                    <span id="resetBtnLoading" class="hidden">Mengirim...</span>
                  </button>
                </form>

                <p class="mt-4">
                  <a class="text-sm font-medium text-blue-500 dark:text-blue-500 hover:underline" href="<?= base_url('auth/forgot_password') ?>">
                    Minta tautan reset lagi
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      document.getElementById('resetForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const resetBtn = document.getElementById('resetBtn');
        const resetBtnText = document.getElementById('resetBtnText');
        const resetBtnLoading = document.getElementById('resetBtnLoading');
        const flashDiv = document.getElementById('flash-message');

        const formData = new FormData(this);
        const email = formData.get('email');
        const token = formData.get('token');
        const password = formData.get('password');
        const confirm_password = formData.get('confirm_password');

        resetBtn.disabled = true;
        resetBtnText.classList.add('hidden');
        resetBtnLoading.classList.remove('hidden');

        // Client-side validation seperti di halaman registrasi
        const passwordErrors = validatePassword(password);
        const confirmErrors = [];
        if (confirm_password === '') {
          confirmErrors.push('Konfirmasi password wajib diisi');
        } else if (password !== confirm_password) {
          confirmErrors.push('Konfirmasi password tidak cocok');
        }

        const passwordErrorEl = document.getElementById('password-error');
        const confirmErrorEl = document.getElementById('confirm-error');
        passwordErrorEl.style.display = 'none';
        confirmErrorEl.style.display = 'none';
        if (passwordErrors.length > 0) {
          passwordErrorEl.textContent = passwordErrors.join(', ');
          passwordErrorEl.style.display = 'block';
        }
        if (confirmErrors.length > 0) {
          confirmErrorEl.textContent = confirmErrors.join(', ');
          confirmErrorEl.style.display = 'block';
        }

        if (passwordErrors.length > 0 || confirmErrors.length > 0) {
          // Reset button state
          resetBtn.disabled = false;
          resetBtnText.classList.remove('hidden');
          resetBtnLoading.classList.add('hidden');
          return;
        }

        try {
          const response = await fetch('<?= base_url('api/auth/reset_password') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, token, password, confirm_password })
          });

          const result = await response.json();

          if (result.success) {
            flashDiv.textContent = result.message || 'Kata sandi berhasil diubah';
            flashDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
            flashDiv.style.display = 'block';
            setTimeout(() => {
              window.location.href = result.redirect_url || '<?= base_url('auth/login') ?>';
            }, 1500);
          } else {
            flashDiv.textContent = result.message || 'Token tidak valid atau sudah kedaluwarsa';
            flashDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
            flashDiv.style.display = 'block';
            // Reset button state
            resetBtn.disabled = false;
            resetBtnText.classList.remove('hidden');
            resetBtnLoading.classList.add('hidden');
          }
        } catch (error) {
          console.error('Reset error:', error);
          flashDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
          flashDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
          flashDiv.style.display = 'block';
          resetBtn.disabled = false;
          resetBtnText.classList.remove('hidden');
          resetBtnLoading.classList.add('hidden');
        }
      });

      // Toggle show/hide password
      const pwdInput = document.getElementById('password');
      const togglePwdBtn = document.getElementById('togglePassword');
      const iconShowPwd = document.getElementById('iconShowPwd');
      const iconHidePwd = document.getElementById('iconHidePwd');
      togglePwdBtn.addEventListener('click', function() {
        const isText = pwdInput.type === 'text';
        pwdInput.type = isText ? 'password' : 'text';
        iconShowPwd.classList.toggle('hidden', !isText);
        iconHidePwd.classList.toggle('hidden', isText);
      });

      const confirmInput = document.getElementById('confirm_password');
      const toggleConfirmBtn = document.getElementById('toggleConfirmPassword');
      const iconShowConfirm = document.getElementById('iconShowConfirm');
      const iconHideConfirm = document.getElementById('iconHideConfirm');
      toggleConfirmBtn.addEventListener('click', function() {
        const isText = confirmInput.type === 'text';
        confirmInput.type = isText ? 'password' : 'text';
        iconShowConfirm.classList.toggle('hidden', !isText);
        iconHideConfirm.classList.toggle('hidden', isText);
      });

      // Indicator update
      const indicator = document.getElementById('password-indicator');
      const reqLength = document.getElementById('req-length');
      const reqUpper = document.getElementById('req-uppercase');
      const reqNumber = document.getElementById('req-number');
      const reqSpecial = document.getElementById('req-special');
      const tickLength = document.getElementById('tick-length');
      const tickUpper = document.getElementById('tick-uppercase');
      const tickNumber = document.getElementById('tick-number');
      const tickSpecial = document.getElementById('tick-special');

      pwdInput.addEventListener('input', function(ev) {
        const val = ev.target.value || '';
        indicator.classList.toggle('hidden', val.length === 0);
        const okLength = val.length >= 8;
        const okUpper  = /[A-Z]/.test(val);
        const okNumber = /[0-9]/.test(val);
        const okSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(val);

        setReq(reqLength, tickLength, okLength);
        setReq(reqUpper, tickUpper, okUpper);
        setReq(reqNumber, tickNumber, okNumber);
        setReq(reqSpecial, tickSpecial, okSpecial);
      });

      function setReq(rowEl, tickEl, ok) {
        rowEl.classList.toggle('text-green-600', ok);
        rowEl.classList.toggle('text-red-600', !ok);
        tickEl.textContent = ok ? '✓' : '✗';
      }

      function validatePassword(password) {
        const errors = [];
        if (password.length < 8) errors.push('Minimal 8 karakter');
        if (!/[A-Z]/.test(password)) errors.push('Harus mengandung huruf kapital');
        if (!/[0-9]/.test(password)) errors.push('Harus mengandung angka');
        if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) errors.push('Harus mengandung karakter khusus');
        return errors;
      }
    </script>
  </body>
</html>