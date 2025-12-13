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
        $this->db->from('m_subscription_plans');
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
        $query = $this->db->get('m_subscription_plans');
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
        $this->db->select('user_subscription_history.*, m_subscription_plans.name AS subscription_name, m_subscription_plans.price_monthly');
        $this->db->from('user_subscription_history');
        $this->db->join('m_subscription_plans', 'm_subscription_plans.id = user_subscription_history.subscription_id', 'inner');
        $this->db->where('user_subscription_history.user_id', $user_id);
        $this->db->where('user_subscription_history.is_active', 1);
        $query = $this->db->get();
        return $query->row();
    }

    // Get User Invoice History with data from user_subscription_history
    public function get_user_invoice_history($user_id){
        $this->db->select('user_subscription_history.*, m_subscription_plans.name AS subscription_name, m_subscription_plans.price_monthly');
        $this->db->from('user_subscription_history');
        $this->db->join('m_subscription_plans', 'm_subscription_plans.id = user_subscription_history.subscription_id', 'inner');
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

    /**
     * Get current month AI generation usage for a user
     * 
     * @param int $user_id
     * @return int Total usage count for current month
     */
    public function get_current_month_usage($user_id)
    {
        $first_day = date('Y-m-01 00:00:00');
        $last_day = date('Y-m-t 23:59:59');
        
        $this->db->select('COUNT(*) as total');
        $this->db->from('subscription_usage');
        $this->db->where('user_id', $user_id);
        $this->db->where('quota_impact', 1);
        $this->db->where('created_at >=', $first_day);
        $this->db->where('created_at <=', $last_day);
        
        $query = $this->db->get();
        $result = $query->row();
        
        return $result ? (int)$result->total : 0;
    }

    /**
     * Get AI generation usage for a specific project
     * 
     * @param int $project_id
     * @return int Total usage count for project
     */
    public function get_project_usage($project_id)
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('subscription_usage');
        $this->db->where('project_id', $project_id);
        
        $query = $this->db->get();
        $result = $query->row();
        
        return $result ? (int)$result->total : 0;
    }

    /**
     * Record AI generation usage
     * 
     * @param int $user_id
     * @param int $project_id
     * @param string $type (strategy|recommendation|custom)
     * @param string $provider (gemini|sumopod)
     * @param int $quota_impact (1 if counts against quota, 0 if free)
     * @return bool
     */
    public function increment_usage($user_id, $project_id, $type = 'strategy', $provider = 'gemini', $quota_impact = 0)
    {
        $data = [
            'user_id' => $user_id,
            'project_id' => $project_id,
            'usage_type' => $type,
            'api_provider' => $provider,
            'quota_impact' => $quota_impact,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert('subscription_usage', $data);
    }

    /**
     * Get subscription plan by ID with all columns
     * 
     * @param int $subscription_id
     * @return array|null
     */
    public function get_subscription_by_id($subscription_id)
    {
        $this->db->where('id', $subscription_id);
        $this->db->where('is_deleted', null);
        $query = $this->db->get('m_subscription_plans');
        return $query->row_array();
    }
}