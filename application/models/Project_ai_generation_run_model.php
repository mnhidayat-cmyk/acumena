<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_ai_generation_run_model extends CI_Model
{
    protected $table = 'project_ai_generation_run';

    public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }

    public function update_stage($run_id, $stage)
    {
        $this->db->where('id', $run_id)
                 ->update($this->table, ['stage' => $stage]);
    }

    public function deactivate_previous_runs($project_id, $pair_type)
    {
        $this->db->where('project_id', $project_id)
                 ->where('pair_type', $pair_type)
                 ->where('is_active', 1)
                 ->update($this->table, [
                     'is_active'   => 0,
                     'archived_at' => date('Y-m-d H:i:s')
                 ]);
    }

    public function get_active_run($project_id, $pair_type)
    {
        return $this->db
            ->where('project_id', $project_id)
            ->where('pair_type', $pair_type)
            ->where('is_active', 1)
            ->where('archived_at IS NULL', null, false)
            ->order_by('created_at', 'DESC')
            ->get($this->table)
            ->row_array();
    }
}
