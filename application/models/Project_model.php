<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model','user');
    }

    /**
     * Create a new project
     */
    public function create_project($data) {
        $this->db->insert('projects', $data);
        return $this->db->insert_id();
    }

    /**
     * Get project by UUID and user ID
     */
    public function get_project_by_uuid($uuid, $user_id) {
        $this->db->where('uuid', $uuid);
        $this->db->where('user_id', $user_id);
        $this->db->where('is_deleted', NULL);
        $query = $this->db->get('projects');
        return $query->row_array();
    }

    /**
     * Update project data
     */
    public function update_project($uuid, $user_id, $data) {
        $this->db->where('uuid', $uuid);
        $this->db->where('user_id', $user_id);
        return $this->db->update('projects', $data);
    }

    /**
     * Get all projects for a user
     */
    public function get_user_projects($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('date_created', 'DESC');
        $query = $this->db->get('projects');
        return $query->result();
    }

    /**
     * Get projects by user ID with limit, offset, search, and sort
     */
    public function get_projects_by_user_id($user_id, $limit, $offset = 0, $search = '', $sort = 'date_created', $order = 'DESC', $is_deleted = null) {
        // 1) Sanitasi dasar
        $limit  = max(1, (int) $limit);
        $offset = max(0, (int) $offset);

        // 2) Whitelist kolom sort & order
        $sortable = ['date_created','last_update','company_name','industry']; // sesuaikan
        if (!in_array($sort, $sortable, true)) {
            $sort = 'date_created';
        }
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        // 3) Select kolom + deskripsi 100 char + ellipsis jika terpotong
        //    COALESCE untuk aman dari NULL
        $this->db->select([
            'uuid',
            'company_name',
            'industry',
            "CASE 
                WHEN CHAR_LENGTH(COALESCE(description,'')) > 100 
                    THEN CONCAT(LEFT(COALESCE(description,''), 100), '…')
                ELSE COALESCE(description,'')
            END AS description_except",
            'date_created',
            'last_update',
        ], false);

        // 4) Filter user
        $this->db->where('user_id', $user_id);

        // 5) Filter is_deleted
        if ($is_deleted === null) {
            // raw supaya menjadi "is_deleted IS NULL"
            $this->db->where('is_deleted IS NULL', null, false);
        } else {
            $this->db->select('is_deleted');
            $this->db->where('is_deleted', (int) $is_deleted);
        }

        // 6) Pencarian (prefix search: term%)
        if ($search !== '') {
            $search = trim($search);
            if ($search !== '') {
                $this->db->like('company_name', $search, 'after');
                // Jika ingin OR ke kolom lain:
                // $this->db->or_like('industry', $search, 'after');
            }
        }

        // 7) Urut & paginasi
        $this->db->order_by($sort, $order);
        $this->db->limit($limit, $offset);

        // 8) Eksekusi
        $query   = $this->db->get('projects');
        $results = $query->result();

        // 9) Tambahkan URL (di PHP; lebih fleksibel dan tidak mengikat DB)
        foreach ($results as $row) {
            $row->url = base_url('project?step=profile&key=' . $row->uuid);
        }

        return $results;
    }

    /**
     * Count projects by user ID with search filter
     */
    public function count_projects_by_user_id($user_id, $search = '', $is_deleted = null) {
        // 1) Filter user
        $this->db->where('user_id', $user_id);

        // 2) Filter is_deleted
        if ($is_deleted === null) {
            // raw supaya menjadi "is_deleted IS NULL"
            $this->db->where('is_deleted IS NULL', null, false);
        } else {
            $this->db->where('is_deleted', (int) $is_deleted);
        }

        // 3) Pencarian (prefix search: term%)
        if ($search !== '') {
            $search = trim($search);
            if ($search !== '') {
                $this->db->like('company_name', $search, 'after');
                // Jika ingin OR ke kolom lain:
                // $this->db->or_like('industry', $search, 'after');
            }
        }

        // 4) Count
        return $this->db->count_all_results('projects');
    }

    /**
     * Soft delete project
     * Hanya izinkan owner atau admin (role_id = 2)
     * Return: bool (true jika 1 row terhapus-soft)
     */
    public function delete_project($uuid)
    {
        // Ambil user aktif
        $user = $this->user->get_user($this->session->userdata('email'), 'email');
        if (!$user) {
            return false;
        }

        $is_admin = ((int) $user->role_id === 2);

        // (Opsional) validasi format UUID sederhana
        // if (!preg_match('/^[0-9a-fA-F-]{36}$/', $uuid)) return false;

        // Soft delete dalam satu langkah untuk hindari race condition
        $this->db->set('is_deleted', date('Y-m-d H:i:s'));
        // Jika Anda punya kolom deleted_by, aktifkan baris berikut:
        // $this->db->set('deleted_by', (int) $user->id);

        $this->db->where('uuid', $uuid);
        $this->db->where('is_deleted IS NULL', null, false); // raw "IS NULL"

        // Kalau bukan admin, batasi hanya milik user tsb
        if (!$is_admin) {
            $this->db->where('user_id', (int) $user->id);
        }

        $this->db->limit(1);
        $this->db->update('projects');

        // Sukses jika tepat 1 baris terpengaruh
        return ($this->db->affected_rows() === 1);
    }


    /**
     * Insert SWOT data
     */
    public function insert_swot($data) {
        $this->db->insert('project_swot', $data);
        return $this->db->insert_id();
    }

    /**
     * Get SWOT data by project ID
     */
    public function get_swot_by_project_id($project_id,$category = null) {
        $this->db->where('project_id', $project_id);
        $this->db->where('is_deleted IS NULL');
        $this->db->order_by('category', 'ASC');
        $this->db->order_by('date_created', 'ASC');
        $query = $this->db->get('project_swot');
        $results = $query->result_array();
        
        if($category){
            $results = array_filter($results, function($item) use ($category) {
                return $item['category'] == $category;
            });
        }
        
        // Group by category
        $grouped = [
            'S' => [], // Strengths
            'W' => [], // Weaknesses
            'O' => [], // Opportunities
            'T' => []  // Threats
        ];
        
        foreach ($results as $row) {
            if (isset($grouped[$row['category']])) {
                $grouped[$row['category']][] = $row;
            }
        }
        
        return $grouped;
    }

    /**
     * Soft delete all SWOT data for a project
     */
    public function delete_swot_by_project_id($project_id) {
        $this->db->where('project_id', $project_id);
        $this->db->set('is_deleted', date('Y-m-d H:i:s'));
        return $this->db->update('project_swot');
    }

    /**
     * Update SWOT item
     */
    public function update_swot($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('project_swot', $data);
    }

    /**
     * Soft delete specific SWOT item
     */
    public function delete_swot($id) {
        $this->db->where('id', $id);
        $this->db->set('is_deleted', date('Y-m-d H:i:s'));
        return $this->db->update('project_swot');
    }

    /**
     * Get SWOT item by ID
     */
    public function get_swot_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('project_swot');
        return $query->row_array();
    }

    /**
     * Update or insert SWOT data for a project (smart update based on position)
     */
    public function update_or_insert_swot_data($project_id, $swot_data) {
        // Get existing SWOT data for this project (only non-deleted records)
        $existing_data = $this->get_swot_by_project_id($project_id);
        
        $categories = ['S', 'W', 'O', 'T'];
        $category_names = ['strengths', 'weaknesses', 'opportunities', 'threats'];
        
        foreach ($categories as $index => $category) {
            $category_name = $category_names[$index];
            $new_items = isset($swot_data[$category_name]) ? array_values(array_filter(array_map('trim', $swot_data[$category_name]))) : [];
            $existing_items = isset($existing_data[$category]) ? $existing_data[$category] : [];
            
            // Remove empty items from new_items
            $new_items = array_filter($new_items, function($item) {
                return !empty($item);
            });
            $new_items = array_values($new_items); // Re-index array
            
            $new_count = count($new_items);
            $existing_count = count($existing_items);
            
            // Process each position
            for ($i = 0; $i < max($new_count, $existing_count); $i++) {
                $has_new = $i < $new_count;
                $has_existing = $i < $existing_count;
                
                if ($has_new && $has_existing) {
                    // Both exist - check if update needed
                    $new_desc = trim($new_items[$i]);
                    $existing_desc = trim($existing_items[$i]['description']);
                    
                    if ($new_desc !== $existing_desc) {
                        // Update existing item
                        $update_data = [
                            'description' => $new_desc,
                            'last_update' => date('Y-m-d H:i:s')
                        ];
                        $this->update_swot($existing_items[$i]['id'], $update_data);
                    }
                    // If same, do nothing
                    
                } elseif ($has_new && !$has_existing) {
                    // New item - insert
                    $insert_data = [
                        'project_id' => $project_id,
                        'category' => $category,
                        'description' => trim($new_items[$i]),
                        'date_created' => date('Y-m-d H:i:s'),
                        'is_deleted' => NULL
                    ];
                    $this->insert_swot($insert_data);
                    
                } elseif (!$has_new && $has_existing) {
                    // Item removed - soft delete
                    $this->delete_swot($existing_items[$i]['id']);
                }
            }
        }
        
        return true;
    }

    /**
     * Update weight and rating for SWOT items in Matrix IFE and EFE
     */
    public function update_swot_weight_rating($project_id, $matrix_data) {
        // Start transaction
        $this->db->trans_start();
        
        try {
            // Process strengths (IFE)
            if (isset($matrix_data['strengths']) && is_array($matrix_data['strengths'])) {
                foreach ($matrix_data['strengths'] as $item) {
                    if (isset($item['id'], $item['weight'], $item['rating'])) {
                        $update_data = [
                            'weight' => floatval($item['weight']),
                            'rating' => intval($item['rating']),
                            'last_update' => date('Y-m-d H:i:s')
                        ];
                        
                        $this->db->where('id', $item['id']);
                        $this->db->where('project_id', $project_id);
                        $this->db->where('category', 'S');
                        $this->db->where('is_deleted IS NULL');
                        $this->db->update('project_swot', $update_data);
                    }
                }
            }
            
            // Process weaknesses (IFE)
            if (isset($matrix_data['weaknesses']) && is_array($matrix_data['weaknesses'])) {
                foreach ($matrix_data['weaknesses'] as $item) {
                    if (isset($item['id'], $item['weight'], $item['rating'])) {
                        $update_data = [
                            'weight' => floatval($item['weight']),
                            'rating' => intval($item['rating']),
                            'last_update' => date('Y-m-d H:i:s')
                        ];
                        
                        $this->db->where('id', $item['id']);
                        $this->db->where('project_id', $project_id);
                        $this->db->where('category', 'W');
                        $this->db->where('is_deleted IS NULL');
                        $this->db->update('project_swot', $update_data);
                    }
                }
            }
            
            // Process opportunities (EFE)
            if (isset($matrix_data['opportunities']) && is_array($matrix_data['opportunities'])) {
                foreach ($matrix_data['opportunities'] as $item) {
                    if (isset($item['id'], $item['weight'], $item['rating'])) {
                        $update_data = [
                            'weight' => floatval($item['weight']),
                            'rating' => intval($item['rating']),
                            'last_update' => date('Y-m-d H:i:s')
                        ];
                        
                        $this->db->where('id', $item['id']);
                        $this->db->where('project_id', $project_id);
                        $this->db->where('category', 'O');
                        $this->db->where('is_deleted IS NULL');
                        $this->db->update('project_swot', $update_data);
                    }
                }
            }
            
            // Process threats (EFE)
            if (isset($matrix_data['threats']) && is_array($matrix_data['threats'])) {
                foreach ($matrix_data['threats'] as $item) {
                    if (isset($item['id'], $item['weight'], $item['rating'])) {
                        $update_data = [
                            'weight' => floatval($item['weight']),
                            'rating' => intval($item['rating']),
                            'last_update' => date('Y-m-d H:i:s')
                        ];
                        
                        $this->db->where('id', $item['id']);
                        $this->db->where('project_id', $project_id);
                        $this->db->where('category', 'T');
                        $this->db->where('is_deleted IS NULL');
                        $this->db->update('project_swot', $update_data);
                    }
                }
            }
            
            // Complete transaction
            $this->db->trans_complete();
            
            return $this->db->trans_status();
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return false;
        }
    }

    /**
     * Get Recommendation strategy based on quadrant
     */
    public function get_recommendation_strategy($quadrant) {
        $this->db->select('id, strategy');
        $this->db->from('matrix_ie_quadrant_strategies');
        $this->db->where('quadrant', $quadrant);
        $this->db->where('is_deleted', NULL);

        $query = $this->db->get();
        $rows = $query->result_array();

        if (!$rows) {
            return [];
        }

        // Return array of strategy strings (trimmed, non-empty)
        $strategies = array_map(function($row) {
            $s = isset($row['strategy']) ? $row['strategy'] : '';
            return is_string($s) ? trim($s) : '';
        }, $rows);

        // Filter out empty strings and reindex
        $strategies = array_values(array_filter($strategies, function($s) {
            return $s !== '';
        }));

        return $strategies;
    }

    /**
     * Get Top-K pairs of S-O, S-T, W-O, W-T (mis. K=12)
     */
    public function get_top_k_pairs($project_id, $pair_type = 'S-O', $k = 12) {
        // Validate pair_type
        if (!in_array($pair_type, ['S-O', 'S-T', 'W-O', 'W-T'])) {
            return [];
        }

        // Keep original pair_type string for caching lookup, and parts for category filters
        $pair_type_str = $pair_type;
        $pair_type = explode('-', $pair_type);

        $this->db->select('s.id as x_id, s.description as x_description, s.category as x_category, s.weight as x_weight, s.rating as x_rating,
                           o.id as y_id, o.description as y_description, o.category as y_category, o.weight as y_weight, o.rating as y_rating,
                           ((COALESCE(s.weight,0) * COALESCE(s.rating,1)) * (COALESCE(o.weight,0) * COALESCE(o.rating,1))) as score');
        $this->db->from('project_swot s');
        $this->db->join('project_swot o', 's.project_id = o.project_id');
        $this->db->where('s.project_id', $project_id);
        $this->db->where('s.category', $pair_type[0]);
        $this->db->where('o.category', $pair_type[1]);
        $this->db->where('s.is_deleted', NULL);
        $this->db->where('o.is_deleted', NULL);
        $this->db->order_by('score', 'DESC');
        $this->db->limit($k);
        
        $query = $this->db->get();
        $top_pairs = $query->result_array();

        // --- 5️⃣ Cek apakah sudah ada run terbaru untuk project & pair_type ---
        $existing = $this->ai_generation_run_get_by_hash($project_id, $pair_type_str, null);

        if ($existing) {
            return [
                'existing' => 1,
                'data' => [
                    'run_id' => $existing['id'],
                    'stage'  => $existing['stage'],
                    'is_cached' => true
                ]
            ];
        }

        // --- 6️⃣ Simpan run baru ---
        $run_id = $this->ai_generation_run_insert([
            'project_id' => $project_id,
            'pair_type'  => $pair_type_str,
            'model'      => 'gemini-1.5-flash',
            'temperature' => 0.2,
            'max_output_tokens' => 1200,
            'stage'      => 'initialized',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return [
            'existing' => 0,
            'data' => [
                'run_id' => $run_id,
                'stage'  => 'initialized',
                'is_cached' => false,
                'pairs' => $top_pairs
            ]
        ];
    }

    /**
     * Semantic filter
     */
    public function semantic_filter($project_id, $run_id, $k = 12) {

        //konsisten respon
        $respond = function($success, $message, $data = []) {
            return [
                'success' => $success,
                'message' => $message,
                'data'    => $data
            ];
        };

        //1) Get run
        $run = $this->ai_generation_run_get($run_id);
        if (!$run || (int)$run['project_id'] !== (int)$project_id) {
            return $respond(false, 'Invalid run_id or project_id');
        }

        // Kalau sudah pernah semantic (atau bahkan strategy), langsung ambil dari cache
        if (in_array($run['stage'], ['semantic_done', 'strategy_done'], true)) {
            $pairs = $this->ai_pair_filtered_get_by_run($run_id);

            return $respond(true, 'Semantic filtering already completed', [
                'run_id'    => $run_id,
                'stage'     => $run['stage'],
                'is_cached' => true,
                'pairs'     => $pairs
            ]);
        }

        // 2) Ambil Top-K pairs (harus sama logikanya dengan endpoint /init)
        //    Di sini diasumsikan kamu punya service/helper yang menghasilkan
        //    Top-K SO pairs dalam format x/y (atau s/o) yang konsisten.
        $topK = $this->topk_service->get_top_k_pairs($project_id, $pair_type, 12);
        if (empty($topK['data'])) {
            // tandai gagal
            $this->project_ai_generation_run_model->update_stage($run_id, 'failed');
            return $respond(false, 'No Top-K pairs available for this run');
        }

        // 3) Convert Top-K ke format yang dipakai semantic_filter_top_k_pairs
        // Jika topK sudah dalam format left_ids/right_ids/left_text/right_text/score, tinggal pakai.
        // Kalau masih s_/o_, map dulu:
        $pairsForFilter = [];
        foreach ($topK['data'] as $row) {
            // sesuaikan dengan strukturmu (kalau sudah pakai x_/y_, ganti di sini)
            $pairsForFilter[] = [
                'left_ids'   => [ (string)$row['s_id'] ],
                'right_ids'  => [ (string)$row['o_id'] ],
                'left_text'  => $row['s_description'],
                'right_text' => $row['o_description'],
                'priority'   => (float)$row['score'],
            ];
        }

        try {
            // 4) Panggil AI untuk semantic filter (pakai helper yang sudah kita buat)
            // Versi simple: langsung panggil function khusus yang menerima pairs mentah.
            $filtered = $this->semantic_filter_pairs_array($pairsForFilter, $pair_type, 5);

            if (empty($filtered)) {
                $this->project_ai_generation_run_model->update_stage($run_id, 'failed');
                return $respond(false, 'Semantic filtering failed or returned empty result');
            }

            // 5) Simpan ke ai_pair_filtered (hapus data lama run ini dulu, antisipasi retry)
            $this->ai_pair_filtered_model->delete_by_run($run_id);

            $insertBatch = [];
            foreach ($filtered as $p) {
                $insertBatch[] = [
                    'run_id'     => $run_id,
                    'project_id' => $project_id,
                    'pair_type'  => $pair_type,
                    'left_id'    => (int)($p['left_ids'][0] ?? 0),
                    'right_id'   => (int)($p['right_ids'][0] ?? 0),
                    'left_text'  => $p['left_text'],
                    'right_text' => $p['right_text'],
                    'priority'   => $p['priority'] ?? 0,
                    'rel'        => $p['rel'] ?? 0,
                    'final'      => $p['final'] ?? 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
            $this->ai_pair_filtered_model->insert_batch($insertBatch);

            // 6) Update stage run → semantic_done
            $this->project_ai_generation_run_model->update_stage($run_id, 'semantic_done');

            return $respond(true, 'Semantic filtering completed', [
                'run_id' => $run_id,
                'stage'  => 'semantic_done',
                'pairs'  => $insertBatch
            ]);

        } catch (\Throwable $e) {
            log_message('error', 'SO semantic run failed: '.$e->getMessage());
            $this->project_ai_generation_run_model->update_stage($run_id, 'failed');
            return $respond(false, 'Semantic filtering error');
        }
    }

    /**
     * cek apakah sudah ada run dengan input sama
     * @param int    $project_id
     * @param string $pair_type   'S-O' | 'S-T' | 'W-O' | 'W-T'
     * @param string $input_hash  hash unik dari input
     * @return array
     */
    private function ai_generation_run_get_by_hash($project_id, $pair_type, $input_hash = null) {
        // Schema baru tidak memiliki kolom input_hash/status.
        // Ambil run terbaru berdasarkan project_id & pair_type.
        $this->db->select('id, project_id, pair_type, model, temperature, max_output_tokens, created_at, stage');
        $this->db->from('project_ai_generation_run');
        $this->db->where('project_id', $project_id);
        $this->db->where('pair_type', $pair_type);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Insert new run
     * @param array $data
     * @return int
     */
    private function ai_generation_run_insert($data) {
        $this->db->insert('project_ai_generation_run', $data);
        return $this->db->insert_id();
    }

    /**
     * get run by id
     * @param int $run_id
     * @return array
     */
    private function ai_generation_run_get($run_id) {
        $this->db->select('id, project_id, pair_type, model, temperature, max_output_tokens, created_at, stage');
        $this->db->from('project_ai_generation_run');
        $this->db->where('id', $run_id);

        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Filter Top-K pairs berdasarkan relevansi semantik (S-O, S-T, W-O, W-T)
     *
     * @param int    $project_id
     * @param string $pair_type   'S-O' | 'S-T' | 'W-O' | 'W-T'
     * @param int    $final_n     ambil N terbaik untuk hasil akhir (default=5)
     * @return array
     */
    public function semantic_filter_top_k_pairs($project_id, $pair_type,$final_n = 5) {
        // Default internal settings
        $take  = 12;   // ambil top-K awal (angka) sebelum AI filter
        $alpha = 0.4;  // kontribusi skor angka
        $beta  = 0.6;  // kontribusi relevansi AI

        // 1) Ambil Top-K awal dari sumbermu
        $resp = $this->get_top_k_pairs($project_id, $pair_type, $take);
        $respArray = is_string($resp) ? json_decode($resp, true) : $resp;

        if (empty($respArray)) return [];

        // Adapt to get_top_k_pairs return shape: either plain array of rows,
        // or structured with data.pairs on fresh runs.
        if (isset($respArray['existing']) || isset($respArray['data'])) {
            $rawPairs = $respArray['data']['pairs'] ?? [];
        } else {
            $rawPairs = $respArray;
        }

        if (empty($rawPairs)) return [];

        // Mapping nama kolom tergantung jenis S-O / S-T / W-O / W-T
        // Gunakan alias kolom yang konsisten dari query: sisi kiri selalu "s_*", kanan selalu "o_*"
        switch ($pair_type) {
            case 'S-O':
            case 'S-T':
            case 'W-O':
            case 'W-T':
                $leftKeyId = 'x_id';   $leftKeyText = 'x_description';
                $rightKeyId = 'y_id';  $rightKeyText = 'y_description';
                break;
            default:
                return [];
        }

        // 2) Siapkan data kandidat Top-K untuk AI
        $pairs = [];
        $maxPriority = 0.0;
        foreach ($rawPairs as $i => $row) {
            $score = (float)($row['score'] ?? 0.0);
            $pairs[] = [
                '__idx'      => $i + 1,
                'left_ids'   => [(string)$row[$leftKeyId]],
                'right_ids'  => [(string)$row[$rightKeyId]],
                'left_text'  => $row[$leftKeyText],
                'right_text' => $row[$rightKeyText],
                'priority'   => $score
            ];
            $maxPriority = max($maxPriority, $score);

            if (count($pairs) >= $take) break;
        }

        if (!$pairs) return [];

        // 3) Prompt untuk nilai relevansi AI
        $labelL = $this->leftLabelByType($pair_type);
        $labelR = $this->rightLabelByType($pair_type);
        $lines  = [];

        foreach ($pairs as $p) {
            $lines[] = $p['__idx'] . ') ' . $labelL . '="' . $p['left_text'] .
                    '" + ' . $labelR . '="' . $p['right_text'] . '"';
        }

        $prompt = $this->buildRelevancePrompt($pair_type, $lines);

        // Schema JSON sangat sederhana
        $schema = [
            "type" => "object",
            "properties" => [
                "pairs" => [
                    "type" => "array",
                    "items" => [
                        "type" => "object",
                        "properties" => [
                            "id"  => ["type" => "integer"],
                            "rel" => ["type" => "number", "minimum" => 0, "maximum" => 1]
                        ],
                        "required" => ["id","rel"]
                    ]
                ]
            ],
            "required" => ["pairs"]
        ];

        // 4) Panggil AI → skor relevansi semantik
        $ai = gemini_call_json($prompt, $schema, 'gemini-2.5-flash', 0.2, 1200);

        $relMap = [];

        if (isset($ai['pairs'])) {
            foreach ($ai['pairs'] as $r) {
                $relMap[$r['id']] = (float)$r['rel'];
            }
        }

        // 5) Hitung skor final
        foreach ($pairs as &$p) {
            $norm = ($maxPriority > 0) ? $p['priority'] / $maxPriority : 0;
            $rel  = $relMap[$p['__idx']] ?? 0.5;
            $p['rel']   = round($rel, 4);
            $p['final'] = round($alpha * $norm + $beta * $rel, 6);
            unset($p['__idx']);
        }

        // 6) Urutkan final score & ambil top-N
        usort($pairs, fn($a, $b) => $b['final'] <=> $a['final']);
        return array_slice($pairs, 0, $final_n);
    }

    /** Label sisi kiri berdasar tipe pair */
    private function leftLabelByType(string $pair_type): string {
        return in_array($pair_type, ['S-O','S-T'], true) ? 'S' : 'W';
    }

    /** Label sisi kanan berdasar tipe pair */
    private function rightLabelByType(string $pair_type): string {
        return in_array($pair_type, ['S-O','W-O'], true) ? 'O' : 'T';
    }

    /** Potong teks supaya hemat token */
    private function shorten(string $text, int $len = 80): string {
        $text = trim(preg_replace('/\s+/', ' ', $text));
        return (mb_strlen($text) <= $len) ? $text : (mb_substr($text, 0, $len - 1) . '…');
    }

    /** Bangun prompt ringkas */
    private function buildRelevancePrompt(string $pair_type, array $lines): string {
        $meaning = [
            'S-O' => 'Use internal strengths to exploit external opportunities.',
            'S-T' => 'Use internal strengths to mitigate external threats.',
            'W-O' => 'Fix internal weaknesses to exploit external opportunities.',
            'W-T' => 'Minimize weaknesses to reduce risk from external threats.'
        ][$pair_type] ?? 'Evaluate semantic business fit of left+right.';

        $body = implode("\n", $lines);
        return "You are a business analyst. Return JSON only per schema.
    Task: Score SEMANTIC BUSINESS RELEVANCE for each pair (0..1).
    Higher = stronger strategic fit for {$pair_type}.
    Guidance: {$meaning}
    Do NOT explain. Do NOT change IDs. No extra text.

    Pairs:
    {$body}

    Schema: {\"pairs\":[{\"id\":1,\"rel\":0.0}]}
    ";
    }

}
