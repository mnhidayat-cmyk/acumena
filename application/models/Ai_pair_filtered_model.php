<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ai_pair_filtered_model extends CI_Model
{
    protected $table = 'ai_pair_filtered';

    public function get_by_run($run_id)
    {
        return $this->db
            ->where('run_id', $run_id)
            ->order_by('final', 'DESC')
            ->get($this->table)
            ->result_array();
    }

    public function delete_by_run($run_id)
    {
        $this->db->where('run_id', $run_id)->delete($this->table);
    }

    public function insert_batch($batch)
    {
        if (!empty($batch)) {
            $this->db->insert_batch($this->table, $batch);
        }
    }
}
