<?php
class User_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get user by id, uuid, or email
     */
    public function get_user($key, $by = 'id')
    {
        $query = "SELECT id, uuid, full_name, email, image, role_id, is_active, (SELECT role_name FROM user_role WHERE user_role.id = users.role_id) AS role_name FROM users WHERE $by = '$key'";
        $result = $this->db->query($query);
        return $result->row();
        // $this->db->where($by, $key);
        // $query = $this->db->get('users');
        // return $query->row();
    }
}