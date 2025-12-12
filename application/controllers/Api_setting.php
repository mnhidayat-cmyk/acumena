<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model', 'auth');
        
        // Check if user is logged in
        if (!$this->auth->is_logged_in()) {
            return $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => true,
                    'message' => 'Unauthorized access'
                ]));
        }
        
        // Set JSON response header
        $this->output->set_content_type('application/json');
    }

    /**
     * Get current user profile data
     */
    public function index()
    {
        if ($this->input->method() !== 'get') {
            return $this->output
                ->set_status_header(405)
                ->set_output(json_encode([
                    'error' => true,
                    'message' => 'Method not allowed'
                ]));
        }

        $user_id = $this->session->userdata('user_id');
        
        // Get user data
        $user = $this->db->where('id', $user_id)
                         ->where('is_deleted', 0)
                         ->get('users')
                         ->row();

        if (!$user) {
            return $this->output
                ->set_status_header(404)
                ->set_output(json_encode([
                    'error' => true,
                    'message' => 'User not found'
                ]));
        }

        return $this->output
            ->set_status_header(200)
            ->set_output(json_encode([
                'success' => true,
                'data' => [
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'image' => $user->image
                ]
            ]));
    }

    /**
     * Update user profile
     */
    public function update()
    {
        if ($this->input->method() !== 'post') {
            return $this->output
                ->set_status_header(405)
                ->set_output(json_encode([
                    'error' => true,
                    'message' => 'Method not allowed'
                ]));
        }

        $user_id = $this->session->userdata('user_id');
        
        // Get input data
        $full_name = trim($this->input->post('full_name'));
        $email = strtolower(trim($this->input->post('email')));
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');

        // Validation
        $errors = [];

        // Validate full name
        if (empty($full_name)) {
            $errors['full_name'] = 'Full name is required';
        }

        // Validate email
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        } else {
            // Check email uniqueness (exclude current user)
            $emailParts = explode('@', $email);
            if (count($emailParts) === 2) {
                $localPart = str_replace('.', '', $emailParts[0]);
                $normalizedEmail = $localPart . '@' . $emailParts[1];
                
                $this->db->where("CONCAT(REPLACE(SUBSTRING_INDEX(email, '@', 1), '.', ''), '@', SUBSTRING_INDEX(email, '@', -1)) =", $normalizedEmail);
                $this->db->where('id !=', $user_id);
                $this->db->where('is_deleted', 0);
                $exists = $this->db->get('users')->row();
                
                if ($exists) {
                    $errors['email'] = 'Email already exists';
                }
            }
        }

        // Validate password if provided
        if (!empty($password)) {
            $passwordValidation = $this->auth->validatePassword($password);
            if (!$passwordValidation['valid']) {
                $errors['password'] = $passwordValidation['message'];
            } elseif ($password !== $confirm_password) {
                $errors['confirm_password'] = 'Password confirmation does not match';
            }
        }

        // Return validation errors
        if (!empty($errors)) {
            return $this->output
                ->set_status_header(400)
                ->set_output(json_encode([
                    'error' => true,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]));
        }

        // Prepare update data
        $update_data = [
            'full_name' => $full_name,
            'email' => $email,
            'last_update' => date('Y-m-d H:i:s')
        ];

        // Add password to update data if provided
        if (!empty($password)) {
            $update_data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Update user data
        $this->db->where('id', $user_id);
        $updated = $this->db->update('users', $update_data);

        if (!$updated) {
            return $this->output
                ->set_status_header(500)
                ->set_output(json_encode([
                    'error' => true,
                    'message' => 'Failed to update profile'
                ]));
        }

        // Update session data
        $this->session->set_userdata([
            'full_name' => $full_name,
            'email' => $email
        ]);

        return $this->output
            ->set_status_header(200)
            ->set_output(json_encode([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'full_name' => $full_name,
                    'email' => $email
                ]
            ]));
    }
}