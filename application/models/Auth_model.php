<?php
class Auth_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Subscription_model','subscription');    
        $this->load->model('User_model','user');

    }

    public function register($data)
    {
        // normalisasi email
        $email = strtolower(trim($data['email'] ?? ''));
        if ($email === '') {
            return ['error' => true, 'message' => 'Email tidak valid'];
        }

        // validasi password
        $password = $data['password'] ?? '';
        $passwordValidation = $this->validatePassword($password);
        if (!$passwordValidation['valid']) {
            return ['error' => true, 'message' => $passwordValidation['message']];
        }

        // hilangkan titik dari email sebelum @ untuk cek keunikan
        $emailParts = explode('@', $email);
        if (count($emailParts) !== 2) {
            return ['error' => true, 'message' => 'Format email tidak valid'];
        }
        
        $localPart = str_replace('.', '', $emailParts[0]); // hilangkan titik hanya dari bagian sebelum @
        $normalizedEmail = $localPart . '@' . $emailParts[1];
        
        // cek duplikasi email (dengan normalisasi titik hanya sebelum @)
        $this->db->where("CONCAT(REPLACE(SUBSTRING_INDEX(email, '@', 1), '.', ''), '@', SUBSTRING_INDEX(email, '@', -1)) =", $normalizedEmail);
        $exists = $this->db->get('users')->row();
        if ($exists) {
            return ['error' => true, 'message' => 'Email sudah terdaftar'];
        }

        // insert data to users table
        $user_register_data = [
            'uuid' => generate_uuid(),
            'full_name' => $data['full_name'],
            'email' => $email,
            'image' => 'default.jpg',
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role_id' => 1,
            'is_active' => 0,
            'date_created' => date('Y-m-d H:i:s'),
            'last_update' => date('Y-m-d H:i:s'),
            'is_deleted' => '',
        ];
        $this->db->insert('users', $user_register_data);
        $user_id = $this->db->insert_id();

        // generate subscription history
        $user_subscription_data = [
            'user_id' => $user_id,
            'subscription_id' => get_setting('default_subscription_id'),
        ];
        $this->db->insert('user_subscription', $user_subscription_data);

        // insert data to user_subscription_history table
        $subscription_id = $this->subscription->generate_user_subscription_history($user_id, get_setting('default_subscription_id'),'paid');

        // Generate OTP
        $otp = generate_otp();

        // ambil data expired otp dari setting (dalam menit)
        $otp_expired = get_setting('user_register_otp_expired');

        $user_verify_data = [
            'email' => $email,
            'otp' => $otp,
            'date_created' => date('Y-m-d H:i:s'),
            'date_expired' => date('Y-m-d H:i:s', strtotime('+' . $otp_expired . ' minutes')),
        ];
        $this->db->insert('user_verify', $user_verify_data);

        // kirim email OTP
        // send_mail($email, 'Verify Account', 'Your OTP code is: ' . $otp);
        sendgrid_send($email, 'Verify Account', 'Your OTP code is: ' . $otp);

        return [
            'user_id' => $user_id,
            'otp_expired' => $user_verify_data['date_expired'],
        ];
    }

    public function verify_otp($email, $otp_code)
    {
        // normalisasi email
        $email = strtolower(trim($email));
        
        // cek apakah OTP valid dan belum expired
        $verify_data = $this->db->where('email', $email)
                               ->where('otp', $otp_code)
                               ->where('date_expired >', date('Y-m-d H:i:s'))
                               ->get('user_verify')
                               ->row();

        if (!$verify_data) {
            return ['error' => true, 'message' => 'Invalid or expired OTP code'];
        }

        // aktifkan user
        $this->db->where('email', $email)->update('users', ['is_active' => 1]);
        
        // hapus data verifikasi yang sudah digunakan
        $this->db->where('email', $email)->delete('user_verify');

        return [
            'email' => $email,
            'verified_at' => date('Y-m-d H:i:s'),
        ];
    }

    public function resend_otp($email)
    {
        // normalisasi email
        $email = strtolower(trim($email));
        
        // cek apakah user dengan email tersebut ada dan belum aktif
        $user = $this->db->where('email', $email)
                         ->where('is_active', 0)
                         ->get('users')
                         ->row();

        if (!$user) {
            return ['error' => true, 'message' => 'User not found or already verified'];
        }

        // hapus OTP lama
        $this->db->where('email', $email)->delete('user_verify');

        // generate OTP baru
        $otp = generate_otp();
        $otp_expired = get_setting('user_register_otp_expired');

        $user_verify_data = [
            'email' => $email,
            'otp' => $otp,
            'date_created' => date('Y-m-d H:i:s'),
            'date_expired' => date('Y-m-d H:i:s', strtotime('+' . $otp_expired . ' minutes')),
        ];
        
        $this->db->insert('user_verify', $user_verify_data);

        // kirim email OTP
        // send_mail($email, 'Verify Account', 'Your OTP code is: ' . $otp);
        sendgrid_send($email, 'Verify Account', 'Your OTP code is: ' . $otp);

        return [
            'email' => $email,
            'otp_expired' => $user_verify_data['date_expired'],
        ];
    }

    public function login($email, $password, $remember = false)
    {
        $email = strtolower(trim($email));
        
        // Validasi input
        if (empty($email) || empty($password)) {
            return ['error' => true, 'message' => 'Email and password are required'];
        }
        
        // Cari user berdasarkan email (tanpa filter is_active dulu)
        $user = $this->db->where('email', $email)
                         ->where('is_deleted', NULL)
                         ->get('users')
                         ->row();
        
        if (!$user) {
            return ['error' => true, 'message' => 'Invalid email or password'];
        }
        
        // Verifikasi password terlebih dahulu
        if (!password_verify($password, $user->password)) {
            return ['error' => true, 'message' => 'Invalid email or password'];
        }
        
        
        
        // Verifikasi password
        if (!password_verify($password, $user->password)) {
            return ['error' => true, 'message' => 'Invalid email or password'];
        }

        // Cek apakah user belum aktif (perlu verifikasi)
        if ($user->is_active == 0) {
            $this->resend_otp($email);
            return [
                'error' => true, 
                'message' => 'Account not verified. Please check your email for verification code.',
                'requires_verification' => true,
                'email' => $email
            ];
        }
        
        // Update last login
        $this->db->where('id', $user->id)
                 ->update('users', ['last_update' => date('Y-m-d H:i:s')]);
        
        // Handle remember token jika diminta
        if ($remember) {
            // Generate remember token
            $remember_token = bin2hex(random_bytes(32));
            $remember_expires_at = date('Y-m-d H:i:s', strtotime('+30 days'));
            
            // Update remember token di database
            $this->db->where('id', $user->id)
                     ->update('users', [
                         'remember_token' => $remember_token,
                         'remember_expires_at' => $remember_expires_at
                     ]);
            
            // Set cookie untuk remember token (30 hari)
            setcookie('remember_token', $remember_token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
            setcookie('remember_user_uuid', (string)$user->uuid, time() + (30 * 24 * 60 * 60), '/', '', false, true);
        }
        
        return [
            'error' => false,
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'uuid' => $user->uuid,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'image' => $user->image,
                'role_id' => $user->role_id
            ]
        ];
    }

    /**
     * Cek remember token untuk auto login
     */
    public function check_remember_token($token)
    {
        if (empty($token)) {
            return ['error' => true, 'message' => 'No remember token provided'];
        }
        
        $user = $this->db->where('remember_token', $token)
                         ->where('remember_expires_at >', date('Y-m-d H:i:s'))
                         ->where('is_active', 1)
                         ->where('is_deleted', 0)
                         ->get('users')
                         ->row();
        
        if (!$user) {
            return ['error' => true, 'message' => 'Invalid or expired remember token'];
        }
        
        return [
            'error' => false,
            'user' => [
                'id' => $user->id,
                'uuid' => $user->uuid,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'image' => $user->image,
                'role_id' => $user->role_id
            ]
        ];
    }

    /**
     * Hapus remember token (untuk logout)
     */
    public function clear_remember_token($user_id)
    {
        $this->db->where('id', $user_id)
                 ->update('users', [
                     'remember_token' => null,
                     'remember_expires_at' => null
                 ]);
        
        // Hapus cookie
        setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        setcookie('remember_user_uuid', '', time() - 3600, '/', '', false, true);
        
        return true;
    }

    /**
     * Validasi password dengan requirements ketat
     */
    public function validatePassword($password)
    {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain uppercase letter';
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain number';
        }
        
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
            $errors[] = 'Password must contain special character';
        }
        
        if (!empty($errors)) {
            return [
                'valid' => false,
                'message' => implode(', ', $errors)
            ];
        }
        
        return ['valid' => true];
    }

    /**
     * Check if user is currently logged in
     */
    public function is_logged_in()
    {
        $CI =& get_instance();
        
        // Check if session data exists
        if ($CI->session->userdata('is_logged_in') && 
            $CI->session->userdata('user_id') && 
            $CI->session->userdata('email')) {
            return true;
        }
        
        // Check remember token if session doesn't exist
        if (isset($_COOKIE['remember_token']) && isset($_COOKIE['remember_user_uuid'])) {
            $result = $this->check_remember_token($_COOKIE['remember_token']);
            if (!$result['error']) {
                $user_data = $result['user'];
                if ((string)$user_data['uuid'] !== (string)$_COOKIE['remember_user_uuid']) {
                    return false;
                }
                $CI->session->set_userdata([
                    'user_id' => $user_data['id'],
                    'email' => $user_data['email'],
                    'full_name' => $user_data['full_name'],
                    'role_id' => $user_data['role_id'],
                    'is_logged_in' => true
                ]);
                return true;
            }
        }
        
        return false;
    }

    /**
     * Forgot password (request reset link) with non-enumeration response.
     * - Always return generic success message
     * - If email exists and active: create reset token and send link via email
     */
    public function forgot_password($email)
    {
        $email = strtolower(trim($email));
        $user = $this->db->where('email', $email)
                         ->where('is_active', 1)
                         ->where('is_deleted', NULL)
                         ->get('users')
                         ->row();

        // Log kondisi awal agar mudah ditrace
        if ($user) {
            log_message('info', "Forgot password requested for active user {$email} (id={$user->id})");
        } else {
            log_message('info', "Forgot password requested for {$email} but no active user found");
        }

        if ($user) {
            // Invalidate previous unused tokens for this user (optional hardening)
            $this->db->where('user_id', $user->id)
                     ->where('used', 0)
                     ->update('user_password_resets', ['used' => 1]);

            // Generate strong random token (not stored in DB)
            $token = bin2hex(random_bytes(32)); // 64 hex chars
            $token_hash = hash('sha256', $token); // CHAR(64)
            $now = date('Y-m-d H:i:s');
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;

            $this->db->insert('user_password_resets', [
                'user_id'            => $user->id,
                'token_hash'         => $token_hash,
                'created_at'         => $now,
                'expires_at'         => $expires_at,
                'used'               => 0,
                'request_ip'         => $ip,
                'request_user_agent' => $ua,
            ]);

            // Build reset URL (prefer HTTPS)
            $base = rtrim(base_url('auth/reset_password'), '/');
            if (strpos($base, 'http://') === 0) {
                $base = 'https://' . substr($base, 7);
            }
            $reset_url = $base . '?token=' . urlencode($token) . '&email=' . urlencode($email);

            // Send email with reset link via SendGrid API
            $subject = 'Reset Password Instructions';
            $message = '<p>Anda menerima email ini karena ada permintaan reset kata sandi.</p>'
                     . '<p>Klik tautan berikut untuk mengatur kata sandi baru:</p>'
                     . '<p><a href="' . htmlspecialchars($reset_url) . '">' . htmlspecialchars($reset_url) . '</a></p>'
                     . '<p>Tautan akan kedaluwarsa dalam 1 jam. Jika Anda tidak meminta reset, abaikan email ini.</p>';
            $resp = sendgrid_send($email, $subject, $message);
            if (!empty($resp['success'])) {
                log_message('info', "Reset password email sent via SendGrid to {$email} (code=" . ($resp['http_code'] ?? 'n/a') . ")");
            } else {
                log_message('error', "Reset password email failed via SendGrid for {$email} (code=" . ($resp['http_code'] ?? 'n/a') . ", err=" . ($resp['error'] ?? 'unknown') . ")");
            }
        }

        // Always return generic message to avoid email enumeration
        return [
            'error' => false,
            'message' => 'Jika email terdaftar, kami telah mengirim instruksi.',
        ];
    }

    /**
     * Verify reset token: check hash match, not expired, not used.
     * Returns array with user and reset record if valid, otherwise null.
     */
    public function verify_reset_token($email, $token)
    {
        $email = strtolower(trim($email));
        $token_hash = hash('sha256', $token);

        $user = $this->db->where('email', $email)
                         ->where('is_active', 1)
                         ->where('is_deleted', NULL)
                         ->get('users')
                         ->row();
        if (!$user) return null;

        $reset = $this->db->where('user_id', $user->id)
                          ->where('token_hash', $token_hash)
                          ->where('used', 0)
                          ->where('expires_at >', date('Y-m-d H:i:s'))
                          ->get('user_password_resets')
                          ->row();
        if (!$reset) return null;

        return ['user' => $user, 'reset' => $reset];
    }

    /**
     * Consume reset token and set new password
     */
    public function reset_password_with_token($email, $token, $new_password)
    {
        $valid = $this->verify_reset_token($email, $token);
        if (!$valid) {
            return ['error' => true, 'message' => 'Token tidak valid atau sudah kedaluwarsa'];
        }

        $user = $valid['user'];
        $reset = $valid['reset'];

        // Update password (bcrypt)
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $this->db->where('id', $user->id)->update('users', [
            'password' => $hashed,
            'last_update' => date('Y-m-d H:i:s'),
        ]);

        // Mark token as used
        $this->db->where('id', $reset->id)->update('user_password_resets', [
            'used' => 1,
        ]);

        // Notify user via email (best effort)
        //send_mail($email, 'Password Changed', '<p>Kata sandi Anda telah diperbarui.</p>');
        sendgrid_send($email, 'Password Changed', '<p>Kata sandi Anda telah diperbarui.</p>');

        return ['error' => false, 'message' => 'Password berhasil diubah'];
    }

    /**
     * Generate random password containing at least one uppercase letter, one number, and one special character
     */
    public function generate_random_password($length = 12)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=';
        $password = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[mt_rand(0, $max)];
        }
        return $password;
    }

}