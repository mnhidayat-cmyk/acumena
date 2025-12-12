<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function index()
	{
        redirect('auth/login');
	}

	public function login()
	{
		// Load Auth_model
		$this->load->model('Auth_model', 'auth');
		
		// Check if user is already logged in
		if ($this->auth->is_logged_in()) {
			redirect(base_url());
			return;
		}
		
		$data = [
            'title' => 'Login',
			'css' => [],
			'js' => []
        ];
		$this->load->view('auth/login',$data);
	}

	public function register()
	{
		// Load Auth_model
		$this->load->model('Auth_model', 'auth');
		
		// Check if user is already logged in
		if ($this->auth->is_logged_in()) {
			redirect(base_url());
			return;
		}
		
        $data = [
            'title' => 'Register',
			'css' => [],
			'js' => []
        ];
		$this->load->view('auth/register',$data);
	}

	public function verify(){
		// Load Auth_model
		$this->load->model('Auth_model', 'auth');
		
		// Check if user is already logged in
		if ($this->auth->is_logged_in()) {
			redirect(base_url());
			return;
		}
		
		// ambil email dari GET parameter atau POST
    	$email = $this->input->get('email', true) ?: $this->input->post('email', true);

		// jika email kosong/null, redirect ke halaman login
		if (empty($email)) {
			redirect('auth');
			return;
		}

		// Set flash message jika belum ada
		if (!$this->session->flashdata('otp_message')) {
			$this->session->set_flashdata('otp_message', 'OTP has been sent to your email. Please check your inbox or spam folder.');
		}

		// data untuk view
		$data = [
			'title' => 'Verify Account',
			'email' => $email, // kirim ke view jika perlu ditampilkan
			'css' => [],
			'js' => []
		];

		// load view
		$this->load->view('auth/verify', $data);
	}

	public function logout()
	{
		// Load Auth_model
		$this->load->model('Auth_model', 'auth');
		
		// Get user_id from session before clearing
		$user_id = $this->session->userdata('user_id');
		
		// Clear remember token if user_id exists
		if ($user_id) {
			$this->auth->clear_remember_token($user_id);
		}
		
		// Clear all session data
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('full_name');
		$this->session->unset_userdata('role_id');
		$this->session->unset_userdata('is_logged_in');
		
		// Destroy the entire session
		$this->session->sess_destroy();
		
		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logged out!</div>');
		redirect('auth');
	}

    public function forgot_password()
    {
		log_message('info', 'API /auth/forgot_password invoked');
		
        $data = [
            'title' => 'Forgot Password',
            'css' => [],
            'js' => []
        ];
        $this->load->view('auth/forgot_password',$data);
    }

    public function reset_password()
    {
        $data = [
            'title' => 'Reset Password',
            'css' => [],
            'js' => [],
            'email' => $this->input->get('email'),
            'token' => $this->input->get('token'),
        ];
        $this->load->view('auth/reset_password', $data);
    }
}
