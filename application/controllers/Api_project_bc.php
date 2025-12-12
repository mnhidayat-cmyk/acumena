<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_project extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Set JSON response headers
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            exit;
        }
        
        // Load necessary models and libraries
        $this->load->model('Project_model', 'project');
        $this->load->library('form_validation');
    }

    /**
     * Get project data (if needed for editing)
     */
    public function index() {
        if ($this->input->method() !== 'get') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $project_uuid = $this->input->get('uuid');
        
        if ($project_uuid) {
            $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
            if ($project) {
                echo json_encode(['success' => true, 'data' => $project]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Project not found']);
            }
        } else {
            echo json_encode(['success' => true, 'data' => null]);
        }
    }

    /**
     * Get projects by user ID with limit, offset, search, and sort
     */
    public function get_projects_by_user_id() {
        if ($this->input->method() !== 'get') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $limit = $this->input->get('limit', true) ?: 10;
        $offset = $this->input->get('offset', true) ?: 0;
        $search = $this->input->get('search', true) ?: '';
        $sort = $this->input->get('sort', true) ?: 'date_created';
        $order = $this->input->get('order', true) ?: 'desc';

        $projects = $this->project->get_projects_by_user_id($user_id, $limit, $offset, $search, $sort, $order, null);
        $total = $this->project->count_projects_by_user_id($user_id, $search, null);
        
        echo json_encode([
            'success' => true, 
            'data' => $projects,
            'pagination' => [
                'total' => $total,
                'limit' => (int) $limit,
                'offset' => (int) $offset,
                'current_page' => floor($offset / $limit) + 1,
                'total_pages' => ceil($total / $limit)
            ]
        ]);
    }

    /**
     * Soft Delete project by UUID
     */
    public function delete()
    {
        // Force JSON
        $this->output->set_content_type('application/json');

        // 1) Hanya boleh POST
        if ($this->input->method() !== 'post') {
            return $this->output
                ->set_status_header(405)
                ->set_output(json_encode(['success' => false, 'message' => 'Method not allowed']));
        }

        // 2) Same-origin check (opsional tapi bagus untuk keamanan)
        $origin  = $this->input->server('HTTP_ORIGIN') ?: $this->input->server('HTTP_REFERER');
        $hostOK  = true;
        if (!empty($origin)) {
            $originHost = parse_url($origin, PHP_URL_HOST);
            $baseHost   = parse_url(base_url(), PHP_URL_HOST);
            $hostOK     = ($originHost && $baseHost && strcasecmp($originHost, $baseHost) === 0);
        }
        if (!$hostOK) {
            return $this->output
                ->set_status_header(403)
                ->set_output(json_encode(['success' => false, 'message' => 'Cross-site request is not allowed']));
        }

        // 3) Ambil & validasi UUID dari JSON atau form data
        $uuid = null;
        
        // Coba ambil dari JSON body terlebih dahulu
        $json_input = json_decode($this->input->raw_input_stream, true);
        if ($json_input && isset($json_input['uuid'])) {
            $uuid = $json_input['uuid'];
        } else {
            // Fallback ke form data
            $uuid = $this->input->post('uuid', true);
        }
        
        // Trim hanya jika tidak null
        if ($uuid !== null) {
            $uuid = trim($uuid);
        }
        
        if (!$uuid) {
            return $this->output
                ->set_status_header(400)
                ->set_output(json_encode(['success' => false, 'message' => 'UUID parameter is required']));
        }
        // Validasi sederhana UUID v4 (relaks, bisa disesuaikan)
        if (!preg_match('/^[0-9a-fA-F-]{32,36}$/', $uuid)) {
            return $this->output
                ->set_status_header(422)
                ->set_output(json_encode(['success' => false, 'message' => 'Invalid UUID format']));
        }

        // 4) Eksekusi soft delete (otorisasi sudah di-handle di model)
        try {
            $deleted = $this->project->delete_project($uuid);

            if ($deleted) {
                return $this->output
                    ->set_status_header(200)
                    ->set_output(json_encode([
                        'success' => true,
                        'message' => 'Project deleted successfully'
                    ]));
            }

            // Gagal: bisa karena tidak ditemukan / sudah terhapus / bukan owner
            return $this->output
                ->set_status_header(404)
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Project not found or access denied'
                ]));

        } catch (Throwable $e) { // PHP >=7; jika PHP 5.x ganti ke Exception
            return $this->output
                ->set_status_header(500)
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'An error occurred while deleting project'
                    // , 'error' => $e->getMessage() // aktifkan saat debug
                ]));
        }
    }


    /**
     * Get project profile data by UUID
     */
    public function profile_get($uuid = null) {
        if ($this->input->method() !== 'get') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        if (!$uuid) {
            $uuid = $this->input->get('uuid');
        }

        if (!$uuid) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'UUID parameter is required']);
            return;
        }

        try {
            $user_id = $this->session->userdata('user_id');
            $project = $this->project->get_project_by_uuid($uuid, $user_id);
            
            if ($project) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Project data retrieved successfully',
                    'data' => $project
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Project not found or access denied'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while retrieving project data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Save project profile data
     */
    public function profile_saves() {
        if ($this->input->method() !== 'post') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Get JSON input
        $json_input = json_decode($this->input->raw_input_stream, true);
        if (!$json_input) {
            $json_input = $this->input->post();
        }

        // Set validation rules
        $this->form_validation->set_data($json_input);
        $this->form_validation->set_rules('company_name', 'Company Name', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('industry', 'Industry Sector', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('description', 'Brief Description', 'required|trim');
        $this->form_validation->set_rules('vision', 'Vision Statement', 'required|trim');
        $this->form_validation->set_rules('mission', 'Mission Statement', 'required|trim');

        if (!$this->form_validation->run()) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->form_validation->error_array()
            ]);
            return;
        }

        try {
            $user_id = $this->session->userdata('user_id');
            $existing_uuid = isset($json_input['uuid']) ? $json_input['uuid'] : null;
            
            // Prepare data for insertion/update
            $project_data = [
                'company_name' => $json_input['company_name'],
                'industry' => $json_input['industry'],
                'description' => $json_input['description'],
                'vision' => $json_input['vision'],
                'mission' => $json_input['mission'],
                'last_update' => date('Y-m-d H:i:s')
            ];

            if ($existing_uuid) {
                // Update existing project
                $updated = $this->project->update_project($existing_uuid, $user_id, $project_data);
                
                if ($updated) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Project profile updated successfully',
                        'data' => [
                            'uuid' => $existing_uuid,
                            'redirect_url' => base_url('project?step=swot&key=' . $existing_uuid)
                        ]
                    ]);
                } else {
                    throw new Exception('Failed to update project data or project not found');
                }
            } else {
                // Create new project
                $project_uuid = generate_uuid();
                $project_data['uuid'] = $project_uuid;
                $project_data['user_id'] = $user_id;
                $project_data['date_created'] = date('Y-m-d H:i:s');
                
                $project_id = $this->project->create_project($project_data);
                
                if ($project_id) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Project profile saved successfully',
                        'data' => [
                            'project_id' => $project_id,
                            'uuid' => $project_uuid,
                            'redirect_url' => base_url('project?step=swot&key=' . $project_uuid)
                        ]
                    ]);
                } else {
                    throw new Exception('Failed to save project data');
                }
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while saving project data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get project SWOT data by UUID
     */
    public function swot_get() {
        if ($this->input->method() !== 'get') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $project_uuid = $this->input->get('uuid') ?: $this->input->get('key');
        
        if (!$project_uuid) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Project UUID is required']);
            return;
        }

        // Get project by UUID and verify ownership
        $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Project not found']);
            return;
        }

        // Get SWOT data for this project
        $swot_data = $this->project->get_swot_by_project_id($project['id']);
        
        echo json_encode(['success' => true, 'data' => $swot_data]);
    }

    /**
     * Save project SWOT data
     */
    public function swot_save() {
        if ($this->input->method() !== 'post') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $input = json_decode($this->input->raw_input_stream, true);
        
        if (!$input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
            return;
        }

        $project_uuid = isset($input['uuid']) ? $input['uuid'] : (isset($input['key']) ? $input['key'] : null);
        
        if (!$project_uuid) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Project UUID is required']);
            return;
        }

        // Get project by UUID and verify ownership
        $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Project not found']);
            return;
        }

        $project_id = $project['id'];

        // Use the new update method instead of delete and re-insert
        $result = $this->project->update_or_insert_swot_data($project_id, $input);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'SWOT data updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update SWOT data']);
        }
    }

    /**
     * Get SWOT data for Matrix IFE (Internal Factor Evaluation)
     * Returns Strengths (S) and Weaknesses (W) categories
     */
    public function matrix_ife_get() {
        if ($this->input->method() !== 'get') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $project_uuid = $this->input->get('uuid') ?: $this->input->get('key');
        
        if (!$project_uuid) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Project UUID is required']);
            return;
        }

        // Get project by UUID and verify ownership
        $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Project not found']);
            return;
        }

        // Get SWOT data for this project
        $swot_data = $this->project->get_swot_by_project_id($project['id']);
        
        // Filter only Strengths (S) and Weaknesses (W) for IFE Matrix
        $ife_data = [
            'strengths' => isset($swot_data['S']) ? $swot_data['S'] : [],
            'weaknesses' => isset($swot_data['W']) ? $swot_data['W'] : []
        ];
        
        echo json_encode([
            'success' => true, 
            'message' => 'IFE Matrix data retrieved successfully',
            'data' => $ife_data
        ]);
    }

    /**
     * Update weight and rating for SWOT items in Matrix IFE
     */
    public function matrix_ife_save() {
        if ($this->input->method() !== 'post') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $input = json_decode($this->input->raw_input_stream, true);
        
        if (!$input || !isset($input['project_uuid']) || !isset($input['ife_data'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid input data']);
            return;
        }

        $project_uuid = $input['project_uuid'];
        $ife_data = $input['ife_data'];

        // Get project by UUID and verify ownership
        $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Project not found']);
            return;
        }

        $project_id = $project['id'];

        // Update weight and rating for each SWOT item
        $result = $this->project->update_swot_weight_rating($project_id, $ife_data);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'IFE data saved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save IFE data']);
        }
    }

    /**
     * Get SWOT data for Matrix EFE (External Factor Evaluation)
     * Returns Opportunities (O) and Threats (T) categories
     */
    public function matrix_efe_get() {  
        if ($this->input->method() !== 'get') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $project_uuid = $this->input->get('uuid') ?: $this->input->get('key');
        
        if (!$project_uuid) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Project UUID is required']);
            return;
        }

        // Get project by UUID and verify ownership
        $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Project not found']);
            return;
        }

        // Get SWOT data for this project
        $swot_data = $this->project->get_swot_by_project_id($project['id']);
        
        // Filter only Opportunities (O) and Threats (T) for EFE Matrix
        $efe_data = [
            'opportunities' => isset($swot_data['O']) ? $swot_data['O'] : [],
            'threats' => isset($swot_data['T']) ? $swot_data['T'] : []
        ];
        
        echo json_encode([
            'success' => true, 
            'message' => 'EFE Matrix data retrieved successfully',
            'data' => $efe_data
        ]);
    }

    /**
     * Update weight and rating for SWOT items in Matrix EFE
     */
    public function matrix_efe_save() {
        if ($this->input->method() !== 'post') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $input = json_decode($this->input->raw_input_stream, true);
        
        if (!$input || !isset($input['project_uuid']) || !isset($input['efe_data'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid input data']);
            return;
        }

        $project_uuid = $input['project_uuid'];
        $efe_data = $input['efe_data']; 

        // Get project by UUID and verify ownership
        $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Project not found']);
            return;
        }

        $project_id = $project['id'];

        // Update weight and rating for each SWOT item
        $result = $this->project->update_swot_weight_rating($project_id, $efe_data);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'EFE data saved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save EFE data']);
        }
    }

    /**
     * Get Recommendation strategy based on quadrant
     */
    public function get_recommendation_strategy() {
        if ($this->input->method() !== 'get') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $quadrant = $this->input->get('quadrant');
        if (!$quadrant) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Quadrant is required']);
            return;
        }

        $strategy = $this->project->get_recommendation_strategy($quadrant);
        if (!$strategy) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Strategy not found']);
            return;
        }

        echo json_encode([
            'success' => true, 
            'message' => 'Recommendation strategy retrieved successfully',
            'data' => $strategy
        ]);
    }

    /**
     * Hitung Top-K pairs dari faktor Strength dan Opportunity milik project.
     * Buat entri baru di project_ai_generation_run (jika belum ada).
     * Simpan hasil Top-K ke tabel (opsional: ai_pair_raw atau cukup di memori).
     * Return run_id dan status awal (initialized).
     */
    public function generating_top_k_pairs() {
        $project_id = $this->input->get('project_id');
        $pair_type = $this->input->get('pair_type');

        // only accept GET method
        if ($this->input->method() !== 'get') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        if (!$project_id || !$pair_type) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Project ID and Pair Type are required']);
            return;
        }

        $pairs = $this->project->get_top_k_pairs($project_id, $pair_type);

        if($pairs['existing'] == 1) {
            http_response_code(400);
            echo json_encode(['success' => true, 'message' => 'Existing run found', 'data' => $pairs['data']]);
        }else{
            http_response_code(400);
            echo json_encode(['success' => true, 'message' => 'Top-K pairs generated', 'data' => $pairs['data']]);
        }
    }

    /**
     * Semantic Filter
     */
    public function semantic_filter(){
        $project_id = $this->input->get('project_id');
        $run_id = $this->input->get('run_id');
        $pair_type = $this->input->get('pair_type');

        // Biar response konsisten
        $respond = function ($success, $message, $data = []) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => $success,
                    'message' => $message,
                    'data'    => $data
                ]));
        };

        if (!$project_id || !$run_id || !$pair_type) {
            http_response_code(400);
            $respond(false, 'Project ID, Run ID, and Pair Type are required');
            return;
        }


    }


    /**
     * TESTER AREA ========================================================
     */

    /**
     * Test get top-k pairs for project
     */

    public function test_get_top_k_pairs() {
        $project_id = $this->input->get('project_id');
        $pair_type = $this->input->get('pair_type');

        if (!$project_id || !$pair_type) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Project ID and Pair Type are required']);
            return;
        }
        
        $pairs = $this->project->get_top_k_pairs($project_id, $pair_type);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Top-k pairs retrieved successfully',
            'data' => $pairs,
        ]);
    }

    public function test_get_top_k_pairs_filtered() {
        $project_id = $this->input->get('project_id');
        $pair_type = $this->input->get('pair_type');

        if (!$project_id || !$pair_type) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Project ID and Pair Type are required']);
            return;
        }
        
        $pairs = $this->project->semantic_filter_top_k_pairs($project_id, $pair_type);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Top-k pairs retrieved successfully',
            'data' => $pairs,
        ]);
    }

    
}