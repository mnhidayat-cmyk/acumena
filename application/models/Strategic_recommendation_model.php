<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Strategic_recommendation_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'strategic_recommendations';
    }

    /**
     * Get recommendation by project_id
     */
    public function get_by_project($project_id) {
        return $this->db
            ->where('project_id', $project_id)
            ->get($this->table)
            ->row_array();
    }

    /**
     * Insert or update recommendation
     */
    public function save($project_id, $data) {
        $existing = $this->get_by_project($project_id);
        
        if ($existing) {
            // Update
            $this->db->where('project_id', $project_id);
            return $this->db->update($this->table, $data);
        } else {
            // Insert
            $data['project_id'] = $project_id;
            return $this->db->insert($this->table, $data);
        }
    }

    /**
     * Get all recommendations
     */
    public function get_all() {
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Delete recommendation
     */
    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

}
?>
