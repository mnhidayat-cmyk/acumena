<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Load Auth_model
		$this->load->model('Auth_model', 'auth');
		// Check if user is logged in
		if (!$this->auth->is_logged_in()) {
			redirect(base_url('auth'));
			return;
		}
	}
		
	public function index()
	{
        $data = [
            'title' => 'Setting',
			'content' => 'settings/index',
			'css' => [],
			'js' => []
        ];
		$this->load->view('template',$data);
	}
}
