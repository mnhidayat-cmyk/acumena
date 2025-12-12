<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api_auth extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model','auth');

    }
	public function index()
	{
        show_404();
	}

    public function register()
    {
        // hanya bisa diakses dengan POST dan domain yang sama dengan base_url
        $host = $this->input->server('HTTP_HOST');
        $baseHost = parse_url(base_url(), PHP_URL_HOST);
        if ($this->input->server('REQUEST_METHOD') !== 'POST' || $host !== $baseHost) {
            show_404();
        }

        $full_name = trim($this->input->post('full_name') ?? '');
        $email = trim($this->input->post('email') ?? '');
        $password = (string)($this->input->post('password') ?? '');

        // Validasi per-field
        $errors = [];
        if ($full_name === '') {
            $errors['full_name'] = 'Nama lengkap wajib diisi';
        }
        if ($email === '') {
            $errors['email'] = 'Email wajib diisi';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid';
        }
        if ($password === '') {
            $errors['password'] = 'Password wajib diisi';
        } else {
            // Validasi password requirements
            $passwordErrors = $this->validatePassword($password);
            if (!empty($passwordErrors)) {
                $errors['password'] = implode(', ', $passwordErrors);
            }
        }

        if (!empty($errors)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => true,
                    'message' => 'Validasi input gagal',
                    'errors' => $errors,
                ]));
        }

        $data = [
            'full_name' => $full_name,
            'email' => $email,
            'password' => $password,
        ];

        $result = $this->auth->register($data);

        if (is_array($result) && isset($result['error']) && $result['error'] === true) {
            // Petakan error dari model ke field yang relevan
            $modelMessage = $result['message'] ?? 'Registrasi gagal';
            $modelErrors = [];
            if (stripos($modelMessage, 'Email') !== false) {
                $modelErrors['email'] = $modelMessage;
            }

            return $this->output
                ->set_status_header(stripos($modelMessage, 'terdaftar') !== false ? 409 : 400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => true,
                    'message' => $modelMessage,
                    'errors' => $modelErrors,
                ]));
        }

        return $this->output
            ->set_status_header(201)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'error' => false,
                'message' => 'Registration successful. Please check your email for OTP verification.',
                'data' => $result
            ]));
    }

    public function verify()
    {
        // hanya bisa diakses dengan POST dan domain yang sama dengan base_url
        $host = $this->input->server('HTTP_HOST');
        $baseHost = parse_url(base_url(), PHP_URL_HOST);
        if ($this->input->server('REQUEST_METHOD') !== 'POST' || $host !== $baseHost) {
            show_404();
        }

        $email = trim($this->input->post('email') ?? '');
        $otp_code = trim($this->input->post('otp_code') ?? '');

        // Validasi per-field
        $errors = [];
        if ($email === '') {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
        if ($otp_code === '') {
            $errors['otp_code'] = 'OTP code is required';
        } elseif (strlen($otp_code) !== 6 || !ctype_digit($otp_code)) {
            $errors['otp_code'] = 'OTP code must be 6 digits';
        }

        if (!empty($errors)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => true,
                    'message' => 'Validation failed',
                    'errors' => $errors,
                ]));
        }

        $result = $this->auth->verify_otp($email, $otp_code);

        if (is_array($result) && isset($result['error']) && $result['error'] === true) {
            $modelMessage = $result['message'] ?? 'Verification failed';
            $modelErrors = [];
            if (stripos($modelMessage, 'OTP') !== false || stripos($modelMessage, 'expired') !== false) {
                $modelErrors['otp_code'] = $modelMessage;
            }

            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => true,
                    'message' => $modelMessage,
                    'errors' => $modelErrors,
                ]));
        }

        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'error' => false,
                'message' => 'Account verified successfully. You can now login.',
                'data' => $result
            ]));
    }

    public function resend_otp()
    {
        // hanya bisa diakses dengan POST dan domain yang sama dengan base_url
        $host = $this->input->server('HTTP_HOST');
        $baseHost = parse_url(base_url(), PHP_URL_HOST);
        if ($this->input->server('REQUEST_METHOD') !== 'POST' || $host !== $baseHost) {
            show_404();
        }

        $email = trim($this->input->post('email') ?? '');

        // Validasi email
        if ($email === '') {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => true,
                    'message' => 'Email is required',
                ]));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => true,
                    'message' => 'Invalid email format',
                ]));
        }

        $result = $this->auth->resend_otp($email);

        if (is_array($result) && isset($result['error']) && $result['error'] === true) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => true,
                    'message' => $result['message'] ?? 'Failed to resend OTP',
                ]));
        }

        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'error' => false,
                'message' => 'OTP has been resent to your email. Please check your inbox or spam folder.',
                'data' => $result
            ]));
    }

    public function login()
    {
        // hanya bisa diakses dengan POST dan domain yang sama dengan base_url
        $host = $this->input->server('HTTP_HOST');
        $baseHost = parse_url(base_url(), PHP_URL_HOST);
        if ($this->input->server('REQUEST_METHOD') !== 'POST' || $host !== $baseHost) {
            show_404();
        }

        // Get JSON input
        $json_input = json_decode($this->input->raw_input_stream, true);
        
        // Handle both JSON and form data
        $email = '';
        $password = '';
        $remember = false;
        
        if ($json_input) {
            // JSON input
            $email = trim($json_input['email'] ?? '');
            $password = (string)($json_input['password'] ?? '');
            $remember = (bool)($json_input['remember'] ?? false);
        } else {
            // Form data fallback
            $email = trim($this->input->post('email') ?? '');
            $password = (string)($this->input->post('password') ?? '');
            $remember = (bool)($this->input->post('remember') ?? false);
        }

        // Validasi per-field
        $errors = [];
        if ($email === '') {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
        if ($password === '') {
            $errors['password'] = 'Password is required';
        }

        if (!empty($errors)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => true,
                    'message' => 'Validation failed',
                    'errors' => $errors,
                ]));
        }

        $result = $this->auth->login($email, $password, $remember);

        if (is_array($result) && isset($result['error']) && $result['error'] === true) {
            $modelMessage = $result['message'] ?? 'Login failed';
            
            // Check if user requires verification
            if (isset($result['requires_verification']) && $result['requires_verification'] === true) {
                return $this->output
                    ->set_status_header(403)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'error' => true,
                        'message' => $modelMessage,
                        'requires_verification' => true,
                        'redirect_url' => base_url('auth/verify?email=' . urlencode($result['email']))
                    ]));
            }
            
            $modelErrors = [];
            if (stripos($modelMessage, 'email') !== false || stripos($modelMessage, 'password') !== false) {
                $modelErrors['email'] = $modelMessage;
                $modelErrors['password'] = $modelMessage;
            }

            return $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => true,
                    'message' => $modelMessage,
                    'errors' => $modelErrors,
                ]));
        }

        // Set session data untuk user yang berhasil login
        $user_data = $result['user'];
        $this->session->set_userdata([
            'user_id' => $user_data['id'],
            'email' => $user_data['email'],
            'full_name' => $user_data['full_name'],
            'role_id' => $user_data['role_id'],
            'is_logged_in' => true
        ]);

        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'message' => 'Login successful! Redirecting to dashboard...',
                'data' => [
                    'user' => $user_data,
                    'redirect_url' => base_url('dashboard')
                ]
            ]));
    }

    /**
     * Validasi password dengan requirements ketat
     */
    private function validatePassword($password)
    {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'Password minimal 8 karakter';
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password harus mengandung huruf kapital';
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password harus mengandung angka';
        }
        
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
            $errors[] = 'Password harus mengandung karakter khusus';
        }
        
        return $errors;
    }

    public function forgot_password()
    {
        // Baca email dari JSON body jika Content-Type application/json, fallback ke POST biasa
        $email = '';
        $contentType = $this->input->server('CONTENT_TYPE') ?: $this->input->get_request_header('Content-Type');
        if ($contentType && stripos($contentType, 'application/json') !== false) {
            $raw = $this->input->raw_input_stream;
            $data = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                $email = strtolower(trim((string)($data['email'] ?? '')));
            }
        } else {
            $email = strtolower(trim((string)($this->input->post('email') ?? '')));
        }

        // Log masuknya request agar dapat ditrace walaupun email tidak valid
        log_message('info', 'API /auth/forgot_password invoked; email payload="' . ($email !== '' ? $email : '(empty)') . '"');

        // Selalu jawab pesan generik untuk mencegah enumerasi akun
        // Tetap panggil model untuk memproses jika email valid/terdaftar
        if ($email) {
            $this->auth->forgot_password($email);
            log_message('info', 'API forgot_password processed for email: ' . $email);
        } else {
            log_message('info', 'API forgot_password skipped processing due to empty email');
        }

        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'message' => 'Jika email terdaftar, kami telah mengirim instruksi. Silakan periksa email Anda.'
            ]));
    }

    /**
     * Reset password: verify token and set new password
     */
    public function reset_password()
    {
        // Baca input dari JSON body jika Content-Type application/json, fallback ke POST biasa
        $email = '';
        $token = '';
        $password = '';
        $confirm = '';

        $contentType = $this->input->server('CONTENT_TYPE') ?: $this->input->get_request_header('Content-Type');
        if ($contentType && stripos($contentType, 'application/json') !== false) {
            $raw = $this->input->raw_input_stream;
            $data = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                $email = strtolower(trim((string)($data['email'] ?? '')));
                $token = (string)($data['token'] ?? '');
                $password = (string)($data['password'] ?? '');
                $confirm = (string)($data['confirm_password'] ?? '');
            }
        } else {
            $email = strtolower(trim((string)($this->input->post('email') ?? '')));
            $token = (string)($this->input->post('token') ?? '');
            $password = (string)($this->input->post('password') ?? '');
            $confirm = (string)($this->input->post('confirm_password') ?? '');
        }

        // Always respond with clear message on invalid/expired token
        $errors = [];
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email tidak valid';
        }
        if ($token === '') {
            $errors['token'] = 'Token wajib diisi';
        }
        if ($password === '' || $confirm === '') {
            $errors['password'] = 'Password dan konfirmasi wajib diisi';
        } elseif ($password !== $confirm) {
            $errors['confirm_password'] = 'Konfirmasi password tidak cocok';
        } else {
            $pwErrors = $this->validatePassword($password);
            if (!empty($pwErrors)) {
                $errors['password'] = implode(', ', $pwErrors);
            }
        }

        if (!empty($errors)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => true,
                    'message' => 'Validasi gagal',
                    'errors' => $errors,
                ]));
        }

        $result = $this->auth->reset_password_with_token($email, $token, $password);
        if (is_array($result) && isset($result['error']) && $result['error'] === true) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => true,
                    'message' => $result['message'] ?? 'Token tidak valid atau sudah kedaluwarsa',
                    'retry_url' => base_url('auth/forgot_password'),
                ]));
        }

        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'message' => 'Kata sandi berhasil diubah',
                'redirect_url' => base_url('auth/login'),
            ]));
    }
}
