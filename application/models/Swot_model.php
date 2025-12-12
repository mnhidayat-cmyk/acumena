<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Swot_model extends CI_Model
{
    protected $table = 'project_swot';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ambil semua faktor aktif untuk 1 project
     */
    public function get_by_project($project_id)
    {
        return $this->db
            ->where('project_id', (int)$project_id)
            ->where('is_deleted IS NULL', null, false)
            ->order_by('category', 'ASC')
            ->order_by('id', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    /**
     * Ambil faktor aktif berdasarkan kategori (S/W/O/T)
     */
    public function get_by_category($project_id, $category)
    {
        return $this->db
            ->where('project_id', (int)$project_id)
            ->where('category', strtoupper($category))
            ->where('is_deleted IS NULL', null, false)
            ->order_by('id', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    public function get_strengths($project_id)
    {
        return $this->get_by_category($project_id, 'S');
    }

    public function get_weaknesses($project_id)
    {
        return $this->get_by_category($project_id, 'W');
    }

    public function get_opportunities($project_id)
    {
        return $this->get_by_category($project_id, 'O');
    }

    public function get_threats($project_id)
    {
        return $this->get_by_category($project_id, 'T');
    }

    /**
     * Data minimal untuk perhitungan Top-K (lebih hemat).
     * Hanya faktor aktif (is_deleted IS NULL).
     */
    public function get_minimal_for_topk($project_id, $category)
    {
        return $this->db
            ->select('id, project_id, category, description, weight, rating')
            ->from($this->table)
            ->where('project_id', (int)$project_id)
            ->where('category', strtoupper($category))
            ->where('is_deleted IS NULL', null, false)
            ->order_by('id', 'ASC')
            ->get()
            ->result_array();
    }

    /**
     * Ambil 1 faktor
     */
    public function find($id)
    {
        return $this->db
            ->where('id', (int)$id)
            ->where('is_deleted IS NULL', null, false)
            ->get($this->table)
            ->row_array();
    }

    /**
     * Insert faktor baru
     */
    public function create($data)
    {
        $insert = [
            'project_id'  => (int)$data['project_id'],
            'category'    => strtoupper($data['category']),
            'description' => (string)$data['description'],
        ];

        // optional numeric fields
        if (isset($data['weight'])) {
            $insert['weight'] = (float)$data['weight'];
        }
        if (isset($data['rating'])) {
            $insert['rating'] = (int)$data['rating'];
        }

        // date_created pakai default DB jika tidak diset
        if (!empty($data['date_created'])) {
            $insert['date_created'] = $data['date_created'];
        }

        $this->db->insert($this->table, $insert);
        return $this->db->insert_id();
    }

    /**
     * Update faktor (hanya field yang dikirim).
     * last_update akan diisi otomatis oleh DB.
     */
    public function update($id, $data)
    {
        $update = [];

        if (isset($data['category'])) {
            $update['category'] = strtoupper($data['category']);
        }
        if (isset($data['description'])) {
            $update['description'] = (string)$data['description'];
        }
        if (array_key_exists('weight', $data)) {
            $update['weight'] = ($data['weight'] === null || $data['weight'] === '')
                ? null
                : (float)$data['weight'];
        }
        if (array_key_exists('rating', $data)) {
            $update['rating'] = ($data['rating'] === null || $data['rating'] === '')
                ? null
                : (int)$data['rating'];
        }

        if (empty($update)) {
            return false;
        }

        $this->db->where('id', (int)$id)
                 ->where('is_deleted IS NULL', null, false)
                 ->update($this->table, $update);

        return ($this->db->affected_rows() > 0);
    }

    /**
     * Soft delete: isi is_deleted dengan timestamp sekarang
     */
    public function soft_delete($id)
    {
        $this->db->where('id', (int)$id)
                 ->where('is_deleted IS NULL', null, false)
                 ->update($this->table, [
                     'is_deleted' => date('Y-m-d H:i:s')
                 ]);

        return ($this->db->affected_rows() > 0);
    }
}
