<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_subscription extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Subscription_model','subscription');
    }

    /**
     * Get all subscriptions
     */
    public function index(){
        $this->output->set_content_type('application/json')->set_output(json_encode($this->subscription->get_all()));
    }

    /** 
     * Upload Payment Proof with POST method
    */
    public function upload_payment_proof(){
        $this->load->model('Auth_model','auth');
        if (!$this->auth->is_logged_in()) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(['error' => true, 'message' => 'Unauthorized']));
        }

        $user_id = $this->session->userdata('user_id');
        $invoice_uuid = $this->input->post('invoice_uuid');

        if (empty($invoice_uuid)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['error' => true, 'message' => 'invoice_uuid required']));
        }

        $invoice = $this->subscription->get_invoice_history_by_uuid($invoice_uuid);
        if (!$invoice || (int)$invoice->user_id !== (int)$user_id) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode(['error' => true, 'message' => 'Invoice not found']));
        }

        $upload_path = FCPATH.'public/paymentproof';
        if (!is_dir($upload_path)) {
            @mkdir($upload_path, 0755, true);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size']      = 5120; // 5MB
        $config['file_ext_tolower'] = TRUE;
        $config['encrypt_name']  = FALSE;
        // rename file to date-time-invoice_uuid
        $config['file_name'] = date('Y-m-d-H-i-s').'-'.$invoice_uuid;   

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('payment_proof')) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'error' => true,
                    'message' => $this->upload->display_errors('', ''),
                ]));
        }

        $data = $this->upload->data();
        $relative_path = 'public/paymentproof/'.$data['file_name'];

        $this->db->where('uuid', $invoice_uuid)
                 ->update('user_subscription_history', [
                     'payment_proof' => $data['file_name'],
                     'status' => 'processing',
                     'last_update' => date('Y-m-d H:i:s')
                 ]);

        return $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'error' => false,
                'message' => 'Upload successful',
                'file' => base_url($relative_path),
            ]));
    }

    
}
