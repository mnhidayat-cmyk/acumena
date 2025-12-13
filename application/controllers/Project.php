<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Load Auth_model
		$this->load->model('Auth_model', 'auth');
		// Load Project_model
		$this->load->model('Project_model', 'project');
		// Load subscription helper
		$this->load->helper('subscription');

		// Check if user is logged in
		if (!$this->auth->is_logged_in()) {
			redirect(base_url('auth'));
			return;
		}
	}
		
	public function index()
	{
		if(!isset($_GET['step'])){
			$data = [
				'title' => 'Project',
				'content' => 'projects/index',
				'css' => [],
				'js' => []
			];
			$this->load->view('template',$data);
		} else{

			// ambil key jika ada dan cek apakah ada project dengan uuid tersebut, jika tidak ada redirect ke project/index
			if(isset($_GET['key'])){
				$uuid = $_GET['key'];
				$user_id = $this->session->userdata('user_id');
				$project = $this->project->get_project_by_uuid($uuid, $user_id);
				if (!$project) {
					redirect(base_url('project'));
					return;
				}
			}else if(!isset($_GET['key']) && $_GET['step'] != 'profile'){
				redirect(base_url('project'));
				return;
			}

			$step = $_GET['step'];
			$user_id = $this->session->userdata('user_id');
			
			// Check subscription step access
			$access_check = can_user_access_step($user_id, $step);
			if (!$access_check['can_access']) {
				$this->session->set_flashdata('error', $access_check['reason']);
				redirect(base_url('project'));
				return;
			}

			$data = [
				'title' => 'Project',
				'content' => 'projects/'.$step,
				'css' => [],
				'js' => [
					'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js',
					'https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js'
				]
			];
			$this->load->view('template',$data);
		}
	}
}
