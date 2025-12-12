<?php
class Subscription_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all subscriptions with filter is_deleted
     */
    public function get_all($is_deleted = 0){
        $this->db->select('*');
        $this->db->from('subscriptions');
        $this->db->where('is_deleted', $is_deleted);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get subscription by id
     */
    public function get_subscription($subscription_id)
    {
        $this->db->where('id', $subscription_id);
        $query = $this->db->get('subscriptions');
        return $query->row();
    }

    /**
     * Get Invoice History by UUID
     */
    public function get_invoice_history_by_uuid($uuid){
        $this->db->select('*');
        $this->db->from('user_subscription_history');
        $this->db->where('uuid', $uuid);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Generate data subscription history for user
     */
    public function generate_user_subscription_history($user_id, $subscription_id = NULL, $paid = NULL, $start_date = NULL, $end_date = NULL){
        if($subscription_id == NULL){
            $subscription_id = get_setting('default_subscription_id');
        }
        $subscription = $this->subscription->get_subscription($subscription_id);
        if($start_date == NULL){
            $start_date = date('Y-m-d H:i:s');
        }
        if($end_date == NULL){
            $end_date = date('Y-m-d H:i:s', strtotime('+1 month'));
        }
        if($paid == NULL){
            $paid = 'unpaid';
        }

        $user_subscription_history_data = [
            'uuid' => generate_uuid(),
            'user_id' => $user_id,
            'invoice_number' => generate_invoice_number(),
            'subscription_name' => $subscription->name,
            'price' => $subscription->price_monthly,
            'price_discount' => '',
            'date_start' => $start_date,
            'date_end' => $end_date,
            'payment_proof' => '',
            'payment_proof_notes' => '',
            'status' => $paid,
            'date_created' => date('Y-m-d H:i:s'),
            'last_update' => date('Y-m-d H:i:s'),
            'is_deleted' => NULL,
        ];
        $this->db->insert('user_subscription_history', $user_subscription_history_data);
        return $this->db->insert_id();
    }

    // Get User Active Subscription with data from user_subscription_history
    public function get_user_active_subscription($user_id){
        $this->db->select('user_subscription_history.*, subscriptions.name AS subscription_name, subscriptions.price_monthly');
        $this->db->from('user_subscription_history');
        $this->db->join('subscriptions', 'subscriptions.id = user_subscription_history.subscription_id', 'inner');
        $this->db->where('user_subscription_history.user_id', $user_id);
        $this->db->where('user_subscription_history.is_active', 1);
        $query = $this->db->get();
        return $query->row();
    }

    // Get User Invoice History with data from user_subscription_history
    public function get_user_invoice_history($user_id){
        $this->db->select('user_subscription_history.*, subscriptions.name AS subscription_name, subscriptions.price_monthly');
        $this->db->from('user_subscription_history');
        $this->db->join('subscriptions', 'subscriptions.id = user_subscription_history.subscription_id', 'inner');
        $this->db->where('user_subscription_history.user_id', $user_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function auto_renew_user($user_id){
        $now = date('Y-m-d H:i:s');
        $this->db->select('*');
        $this->db->from('user_subscription_history');
        $this->db->where('user_id', $user_id);
        $this->db->where('subscription_id', 1);
        $this->db->where('is_active', 1);
        $this->db->where('date_end <=', $now);
        $this->db->where('(is_deleted = 0 OR is_deleted IS NULL)', NULL, FALSE);
        $expired = $this->db->get()->result();

        foreach ($expired as $row) {
            $this->db->where('id', $row->id);
            $this->db->update('user_subscription_history', [
                'is_active' => 0,
                'last_update' => $now,
            ]);

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
                'payment_proof_notes' => '',
                'status' => $row->status,
                'is_active' => 1,
                'date_created' => $now,
                'last_update' => $now,
                'is_deleted' => 0,
            ];

            $this->db->insert('user_subscription_history', $data);
        }
        return count($expired);
    }
}