<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prioritized_strategy_model extends CI_Model {

    protected $table = 'project_prioritized_strategies';

    public function __construct() {
        parent::__construct();
        $this->load->model('Project_model', 'project');
    }

    /**
     * Save multiple prioritized strategies for a project
     * @param int $project_id
     * @param array $strategies Array of strategy data
     * @param int $user_id
     * @return array Inserted IDs
     */
    public function save_multiple($project_id, $strategies, $user_id) {
        $user = $this->db->where('id', $user_id)->get('users')->row();
        if (!$user) {
            throw new Exception('User not found');
        }

        $inserted_ids = [];

        foreach ($strategies as $strategy) {
            $data = [
                'uuid' => generate_uuid(),
                'project_id' => (int) $project_id,
                'ai_strategy_id' => isset($strategy['ai_strategy_id']) ? (int) $strategy['ai_strategy_id'] : NULL,
                'pair_type' => $strategy['pair_type'],
                'strategy_code' => $strategy['strategy_code'],
                'strategy_statement' => $strategy['strategy_statement'],
                'priority_rank' => isset($strategy['priority_rank']) ? (int) $strategy['priority_rank'] : 1,
                'priority_score' => isset($strategy['priority_score']) ? (float) $strategy['priority_score'] : NULL,
                'status' => 'draft',
                'selected_by_user' => isset($strategy['selected_by_user']) ? (bool) $strategy['selected_by_user'] : FALSE,
                'selection_justification' => $strategy['selection_justification'] ?? NULL,
                'internal_notes' => $strategy['internal_notes'] ?? NULL,
                'created_by_user_id' => (int) $user_id,
            ];

            $this->db->insert($this->table, $data);
            $inserted_ids[] = $this->db->insert_id();
        }

        return $inserted_ids;
    }

    /**
     * Get prioritized strategies by project
     * @param int $project_id
     * @param string $pair_type (optional)
     * @param string $status (optional)
     * @return array
     */
    public function get_by_project($project_id, $pair_type = NULL, $status = NULL) {
        $this->db->where('project_id', (int) $project_id);
        $this->db->where('is_deleted', NULL);

        if ($pair_type) {
            $this->db->where('pair_type', $pair_type);
        }

        if ($status) {
            $this->db->where('status', $status);
        }

        $this->db->order_by('priority_rank', 'ASC');
        $query = $this->db->get($this->table);

        return $query->result_array();
    }

    /**
     * Get prioritized strategies by project UUID
     * @param string $project_uuid
     * @param int $user_id (for ownership verification)
     * @param string $pair_type (optional)
     * @param string $status (optional)
     * @return array
     */
    public function get_by_project_uuid($project_uuid, $user_id, $pair_type = NULL, $status = NULL) {
        $project = $this->db->where('uuid', $project_uuid)
                            ->where('user_id', $user_id)
                            ->where('is_deleted', NULL)
                            ->get('projects')
                            ->row();

        if (!$project) {
            return array();
        }

        return $this->get_by_project($project->id, $pair_type, $status);
    }

    /**
     * Update strategy status & notes
     * @param int $id
     * @param array $data (status, internal_notes, priority_rank)
     * @param int $user_id
     * @return bool
     */
    public function update_strategy($id, $data, $user_id) {
        $strategy = $this->db->where('id', (int) $id)
                             ->where('is_deleted', NULL)
                             ->get($this->table)
                             ->row();

        if (!$strategy) {
            return false;
        }

        $update_data = [
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (isset($data['status'])) {
            $update_data['status'] = $data['status'];
        }

        if (isset($data['internal_notes'])) {
            $update_data['internal_notes'] = $data['internal_notes'];
        }

        if (isset($data['priority_rank'])) {
            $update_data['priority_rank'] = (int) $data['priority_rank'];
        }

        return $this->db->where('id', (int) $id)
                        ->update($this->table, $update_data);
    }

    /**
     * Soft delete strategy
     * @param int $id
     * @return bool
     */
    public function delete_strategy($id) {
        return $this->db->where('id', (int) $id)
                        ->update($this->table, [
                            'is_deleted' => date('Y-m-d H:i:s')
                        ]);
    }

    /**
     * Get count by status
     * @param int $project_id
     * @return array
     */
    public function get_status_summary($project_id) {
        $this->db->where('project_id', (int) $project_id);
        $this->db->where('is_deleted', NULL);
        $this->db->select('status, COUNT(*) as count');
        $this->db->group_by('status');

        $query = $this->db->get($this->table);
        $results = $query->result_array();

        $summary = [
            'total' => 0,
            'draft' => 0,
            'approved' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'archived' => 0
        ];

        foreach ($results as $row) {
            $summary[$row['status']] = (int) $row['count'];
            $summary['total'] += (int) $row['count'];
        }

        return $summary;
    }

}
