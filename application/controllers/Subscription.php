<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subscription extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auth_model', 'auth');
		$this->load->model('User_model', 'user');
		$this->load->model('Subscription_model', 'subscription');
		$this->load->helper('subscription');

		// Check if user is logged in
		if (!$this->auth->is_logged_in()) {
			redirect(base_url('auth'));
			return;
		}
	}
		
	public function index()
	{
        $user_id = $this->session->userdata('user_id');
        $this->subscription->auto_renew_user($user_id);
        
        // Get quota information
        $quota_info = get_user_remaining_quota($user_id);
        $plan = get_user_subscription_plan($user_id);
        
        // If no plan, use default
        if (!$plan) {
            $plan = $this->subscription->get_subscription_by_id(1);
        }
        
        // Get project count
        $this->load->model('Project_model');
        $project_count = $this->Project_model->count_projects_by_user_id($user_id);
        
        $data = [
            'title' => 'Subscription',
			'content' => 'subscriptions/index',
			'user'		=> $this->user->get_user('id', $user_id),
			'subscription' => $this->subscription->get_user_active_subscription($user_id),
			'invoice_history' => $this->subscription->get_user_invoice_history($user_id),
			'quota_info' => $quota_info,
			'plan' => $plan,
			'project_count' => $project_count,
			'css' => [],
			'js' => []
        ];
		$this->load->view('template',$data);
	}

	public function invoice($id = null)
	{
		if($id == null)
		{
			return redirect('subscription');
		}
		$user = $this->user->get_user($this->session->userdata('user_id'),'id');
		$invoice = $this->subscription->get_invoice_history_by_uuid($id);


        $data = [
            'title' => 'Invoice #'.$id,
			'content' => 'subscriptions/invoice',
			'user'		=> $user,
			'invoice' => $invoice,
			'invoice_user' => $this->user->get_user($invoice->user_id,'id'),
			'css' => [],
			'js' => []
        ];
		$this->load->view('subscriptions/invoice',$data);
	}

	public function package()
	{
		$data = [
            'title' => 'Subscription Package',
			'content' => 'subscriptions/package',
			'user'		=> $this->user->get_user('id', $this->session->userdata('user_id')),
			'subscription' => $this->subscription->get_all(),
			'css' => [],
			'js' => []
        ];
		$this->load->view('template',$data);
	}
}
