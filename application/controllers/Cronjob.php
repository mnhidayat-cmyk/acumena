<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('main');
    }

    public function renew() {
        $now = date('Y-m-d H:i:s');
        $this->db->select('*');
        $this->db->from('user_subscription_history');
        $this->db->where('subscription_id', 1);
        $this->db->where('is_active', 1);
        $this->db->where('is_deleted', 0);
        $this->db->where('date_end <=', $now);
        $expired = $this->db->get()->result();

        $updated = 0;
        $created = 0;

        foreach ($expired as $row) {
            $this->db->where('id', $row->id);
            $this->db->update('user_subscription_history', [
                'is_active' => 0,
                'last_update' => $now,
            ]);
            $updated++;

            $new_start = date('Y-m-d H:i:s', strtotime($row->date_end . ' +1 second'));
            $new_end = date('Y-m-d H:i:s', strtotime($new_start . ' +30 days'));

            $data = [
                'uuid' => generate_uuid(),
                'user_id' => $row->user_id,
                'invoice_number' => generate_invoice_number(),
                'subscription_id' => $row->subscription_id,
                'subscription_name' => $row->subscription_name,
                'price' => $row->price,
                'price_discount' => $row->price_discount,
                'date_start' => $new_start,
                'date_end' => $new_end,
                'payment_proof' => '',
                'status' => 'paid',
                'is_active' => 1,
                'date_created' => $now,
                'last_update' => $now,
                'is_deleted' => 0,
            ];

            $this->db->insert('user_subscription_history', $data);
            $created++;
        }

        $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'processed' => count($expired),
                'updated' => $updated,
                'created' => $created,
            ]));
    }
}