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
        $this->load->model('Project_ai_generation_run_model', 'runModel');
        $this->load->model('Ai_pair_filtered_model', 'pairModel');
        $this->load->model('Ai_strategy_model', 'strategyModel');
        $this->load->model('Swot_model', 'swotModel');
        $this->load->model('Topk_service_model', 'topkService'); // kamu sesuaikan nama servicenya
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
     * Generate Final Strategic Recommendation (AI Synthesis)
     * 
     * Combines 3 data pillars:
     * 1. IE Matrix Position (Quadrant)
     * 2. Prioritized TOWS Strategies
     * 3. Company Profile (Vision, Mission, Industry)
     * 
     * POST /api/project/generate_strategic_recommendation
     */
    /**
     * Validate if all 4 strategies (SO, ST, WO, WT) exist for a project
     * Called from frontend to verify database state
     */
    public function validate_strategies() {
        if ($this->input->method() !== 'post') {
            http_response_code(405);
            echo json_encode(['valid' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $input = json_decode($this->input->raw_input_stream, true);

        if (!$input) {
            http_response_code(400);
            echo json_encode(['valid' => false, 'message' => 'Invalid JSON data']);
            return;
        }

        $project_uuid = $input['project_uuid'] ?? null;
        if (!$project_uuid) {
            http_response_code(400);
            echo json_encode(['valid' => false, 'message' => 'project_uuid is required']);
            return;
        }

        // Verify project ownership
        $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(403);
            echo json_encode(['valid' => false, 'message' => 'Project not found or access denied']);
            return;
        }

        // Check if all 4 strategies exist
        $validation = $this->validate_all_strategies_exist($project['id']);
        
        // Return validation result directly
        echo json_encode($validation);
    }

    public function generate_strategic_recommendation() {
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

        $project_uuid = $input['project_uuid'] ?? null;
        if (!$project_uuid) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'project_uuid is required']);
            return;
        }

        // Verify project ownership
        $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Project not found or access denied']);
            return;
        }

        try {
            // VALIDATION: Check if all 4 strategies (SO, ST, WO, WT) exist
            $strategy_validation = $this->validate_all_strategies_exist($project['id']);
            if (!$strategy_validation['valid']) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $strategy_validation['message']]);
                return;
            }

            // 1. Get Company Profile
            $company_profile = [
                'company_name' => $project['company_name'],
                'industry' => $project['industry'],
                'vision' => $project['vision'],
                'mission' => $project['mission'],
                'description' => $project['description']
            ];

            // 2. Get IE Matrix Position (from IFE & EFE scores)
            $ife_score = $input['ife_score'] ?? null;
            $efe_score = $input['efe_score'] ?? null;
            $quadrant = $input['quadrant'] ?? $this->determine_quadrant($ife_score, $efe_score);

            // 3. Get Prioritized TOWS Strategies
            $prioritized_strategies = [];
            if ($this->project_has_prioritized_strategies($project['id'])) {
                $this->load->model('Prioritized_strategy_model', 'prioritizedStrategy');
                $prioritized_strategies = $this->prioritizedStrategy->get_by_project($project['id']);
            }

            // 4. Prepare AI Prompt for Final Strategic Recommendation
            $ai_prompt = $this->build_strategic_recommendation_prompt(
                $company_profile,
                $quadrant,
                $ife_score,
                $efe_score,
                $prioritized_strategies
            );

            // 5. Call AI to generate synthesis
            $ai_response = $this->call_ai_for_recommendation($ai_prompt);

            if ($ai_response) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Final Strategic Recommendation generated successfully',
                    'data' => [
                        'company_profile' => $company_profile,
                        'ie_matrix_position' => [
                            'ife_score' => $ife_score,
                            'efe_score' => $efe_score,
                            'quadrant' => $quadrant
                        ],
                        'prioritized_strategies_count' => count($prioritized_strategies),
                        'recommendation' => $ai_response
                    ]
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to generate AI recommendation'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error generating recommendation: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Save recommendation to database
     * Endpoint: POST /api/project/save-recommendation
     */
    public function save_recommendation() {
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

        $project_uuid = $input['project_uuid'] ?? null;
        if (!$project_uuid) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'project_uuid is required']);
            return;
        }

        // Verify project ownership
        $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Project not found or access denied']);
            return;
        }

        $recommendation_data = $input['recommendation'] ?? null;
        if (!$recommendation_data) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Recommendation data is required']);
            return;
        }

        try {
            // Prepare data for saving
            $save_data = [
                'project_id' => $project['id'],
                'strategic_theme' => $recommendation_data['recommendation']['strategic_theme'] ?? '',
                'alignment_with_position' => $recommendation_data['recommendation']['alignment_with_position'] ?? '',
                'short_term_actions' => json_encode($recommendation_data['recommendation']['short_term_actions'] ?? []),
                'long_term_actions' => json_encode($recommendation_data['recommendation']['long_term_actions'] ?? []),
                'resource_implications' => json_encode($recommendation_data['recommendation']['resource_implications'] ?? []),
                'risk_mitigation' => json_encode($recommendation_data['recommendation']['risk_mitigation'] ?? []),
                'ife_score' => (float)($recommendation_data['ie_matrix_position']['ife_score'] ?? 0),
                'efe_score' => (float)($recommendation_data['ie_matrix_position']['efe_score'] ?? 0),
                'quadrant' => $recommendation_data['ie_matrix_position']['quadrant'] ?? ''
            ];

            // Load recommendation model
            $this->load->model('Strategic_recommendation_model', 'recommendationModel');

            // Check if already exists - if yes, update; if no, insert
            $existing = $this->db
                ->where('project_id', $save_data['project_id'])
                ->get('strategic_recommendations')
                ->row_array();

            if ($existing) {
                // Update
                $this->db->where('project_id', $save_data['project_id']);
                $this->db->update('strategic_recommendations', $save_data);
                $message = 'Rekomendasi berhasil diperbarui';
            } else {
                // Insert
                $this->db->insert('strategic_recommendations', $save_data);
                $message = 'Rekomendasi berhasil disimpan';
            }

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => $message
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error saving recommendation: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get Recommendation strategy based on quadrant (LEGACY - for reference)
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
     * Helper: Check if project has prioritized strategies saved
     */
    private function project_has_prioritized_strategies($project_id) {
        $this->load->database();
        $query = $this->db->where('project_id', $project_id)
                         ->where('is_deleted', null)
                         ->get('project_prioritized_strategies');
        return $query->num_rows() > 0;
    }

    /**
     * Helper: Determine IE Matrix Quadrant from IFE/EFE scores
     */
    private function determine_quadrant($ife_score, $efe_score) {
        if (!$ife_score || !$efe_score) {
            return 'UNKNOWN';
        }

        // Typically: 2.0 is midpoint
        // Quadrant I (Grow/Diversify): High IFE, High EFE
        // Quadrant II (Turnaround): Low IFE, High EFE
        // Quadrant III (Defensive): Low IFE, Low EFE
        // Quadrant IV (Cautious Growth): High IFE, Low EFE
        // Quadrant V (Hold/Maintain): Medium IFE/EFE

        if ($ife_score > 2.5 && $efe_score > 2.5) {
            return 'I - Grow & Diversify';
        } elseif ($ife_score <= 2.5 && $efe_score > 2.5) {
            return 'II - Turnaround';
        } elseif ($ife_score <= 2.5 && $efe_score <= 2.5) {
            return 'III - Defensive';
        } elseif ($ife_score > 2.5 && $efe_score <= 2.5) {
            return 'IV - Cautious Growth';
        } else {
            return 'V - Hold & Maintain';
        }
    }

    /**
     * Helper: Build comprehensive AI prompt for Final Strategic Recommendation
     */
    private function build_strategic_recommendation_prompt($company_profile, $quadrant, $ife_score, $efe_score, $strategies) {
        $strategies_text = '';
        if (!empty($strategies)) {
            $strategies_text = "Strategi TOWS Prioritas:\n";
            foreach ($strategies as $idx => $s) {
                $strategies_text .= "- [{$s['pair_type']}] {$s['strategy_code']}: {$s['strategy_statement']}\n";
            }
        } else {
            $strategies_text = "Belum ada strategi prioritas yang disimpan.";
        }

        $prompt = <<<PROMPT
Buatlah Rekomendasi Strategi Final yang komprehensif berdasarkan data berikut:

PROFIL PERUSAHAAN:
- Nama Perusahaan: {$company_profile['company_name']}
- Industri: {$company_profile['industry']}
- Visi: {$company_profile['vision']}
- Misi: {$company_profile['mission']}
- Deskripsi: {$company_profile['description']}

ANALISIS IE MATRIX:
- Skor IFE: {$ife_score}
- Skor EFE: {$efe_score}
- Posisi Strategis: {$quadrant}

{$strategies_text}

Buatlah rekomendasi terstruktur yang mencakup:

1. TEMA STRATEGIS UTAMA
   - Arah strategis utama (1-2 kalimat)
   - Bagaimana kesesuaiannya dengan visi/misi perusahaan
   - Mengapa cocok untuk posisi IE Matrix {$quadrant}

2. TINDAKAN JANGKA PENDEK (3-6 bulan)
   - 3-5 item tindakan spesifik
   - Tingkat prioritas (Tinggi/Sedang/Rendah)
   - Dampak yang diharapkan

3. INISIATIF JANGKA PANJANG (1-3 tahun)
   - 3-5 inisiatif strategis
   - Estimasi kebutuhan sumber daya
   - Metrik kesuksesan

4. IMPLIKASI SUMBER DAYA
   - Panduan alokasi anggaran (%)
   - Peran tim kunci yang diperlukan
   - Area pengembangan skill

5. MITIGASI RISIKO
   - Risiko kunci dalam strategi ini
   - Pendekatan mitigasi

Formatkan respons sebagai JSON yang valid dengan field-field ini (dalam BAHASA INDONESIA):
{
  "strategic_theme": "...",
  "alignment_with_position": "...",
  "short_term_actions": [{"action": "...", "priority": "...", "impact": "..."}],
  "long_term_actions": [{"initiative": "...", "resources": "...", "success_metrics": "..."}],
  "resource_implications": {
    "budget_allocation": "...",
    "key_roles": "...",
    "skill_development": "..."
  },
  "risk_mitigation": [{"risk": "...", "mitigation": "..."}]
}

PENTING: Semua teks dalam JSON HARUS dalam BAHASA INDONESIA yang baik dan profesional.
PROMPT;

        return $prompt;
    }

    /**
     * Helper: Check if all 4 TOWS strategies (SO, ST, WO, WT) exist for project
     * IMPORTANT: Only counts strategies from ACTIVE runs
     * Because regenerate feature creates new run and deactivates old ones
     * So we must use the latest/active run for each pair_type
     */
    private function validate_all_strategies_exist($project_id) {
        $this->load->model('Project_ai_generation_run_model', 'runModel');
        $this->load->model('Ai_strategy_model', 'strategyModel');
        
        $pair_types = ['S-O', 'S-T', 'W-O', 'W-T'];
        $missing = [];
        
        foreach ($pair_types as $pair_type) {
            // Get the ACTIVE run for this pair_type
            // This is the "current" generation that's being used
            $active_run = $this->runModel->get_active_run($project_id, $pair_type);
            
            if (!$active_run) {
                // No active run = no valid strategies for this pair_type
                $missing[] = $pair_type;
                continue;
            }
            
            // Check if this active run has strategies
            // Only count strategies from the active run
            $strategies = $this->strategyModel->get_by_run($active_run['id']);
            
            if (empty($strategies)) {
                // Active run exists but has no strategies (shouldn't happen, but handle it)
                $missing[] = $pair_type;
            }
        }
        
        if (!empty($missing)) {
            $missing_labels = [];
            foreach ($missing as $type) {
                if ($type === 'S-O') $missing_labels[] = 'SO Strategies';
                elseif ($type === 'S-T') $missing_labels[] = 'ST Strategies';
                elseif ($type === 'W-O') $missing_labels[] = 'WO Strategies';
                elseif ($type === 'W-T') $missing_labels[] = 'WT Strategies';
            }
            
            return [
                'valid' => false,
                'message' => 'Semua 4 strategi (SO, ST, WO, WT) harus ada sebelum generate recommendation. Strategi yang belum ada: ' . implode(', ', $missing_labels),
                'missing' => $missing
            ];
        }
        
        return ['valid' => true];
    }

    /**
     * Helper: Call AI service to generate recommendation
     */
    private function call_ai_for_recommendation($prompt) {
        try {
            // Use existing AI helper function
            // Try Gemini first, then fallback
            if (function_exists('gemini_call_json')) {
                $schema = [
                    "type" => "object",
                    "properties" => [
                        "strategic_theme" => [
                            "type" => "string",
                            "description" => "Main strategic direction and alignment with company vision"
                        ],
                        "alignment_with_position" => [
                            "type" => "string",
                            "description" => "How strategy aligns with IE Matrix position"
                        ],
                        "short_term_actions" => [
                            "type" => "array",
                            "items" => [
                                "type" => "object",
                                "properties" => [
                                    "action" => ["type" => "string"],
                                    "priority" => ["type" => "string"],
                                    "impact" => ["type" => "string"]
                                ],
                                "required" => ["action", "priority", "impact"]
                            ],
                            "description" => "Short-term action items (3-6 months)"
                        ],
                        "long_term_actions" => [
                            "type" => "array",
                            "items" => [
                                "type" => "object",
                                "properties" => [
                                    "initiative" => ["type" => "string"],
                                    "resources" => ["type" => "string"],
                                    "success_metrics" => ["type" => "string"]
                                ],
                                "required" => ["initiative", "resources", "success_metrics"]
                            ],
                            "description" => "Long-term strategic initiatives (1-3 years)"
                        ],
                        "resource_implications" => [
                            "type" => "object",
                            "properties" => [
                                "budget_allocation" => ["type" => "string"],
                                "key_roles" => ["type" => "string"],
                                "skill_development" => ["type" => "string"]
                            ],
                            "required" => ["budget_allocation", "key_roles", "skill_development"],
                            "description" => "Resource and budget implications"
                        ],
                        "risk_mitigation" => [
                            "type" => "array",
                            "items" => [
                                "type" => "object",
                                "properties" => [
                                    "risk" => ["type" => "string"],
                                    "mitigation" => ["type" => "string"]
                                ],
                                "required" => ["risk", "mitigation"]
                            ],
                            "description" => "Key risks and mitigation strategies"
                        ]
                    ],
                    "required" => ["strategic_theme", "alignment_with_position", "short_term_actions", "long_term_actions", "resource_implications", "risk_mitigation"]
                ];

                $result = gemini_call_json($prompt, $schema, 'gemini-2.5-flash', 0.2, 2000);
                return $result;
            } elseif (function_exists('sumopod_call_json')) {
                $schema = [
                    "type" => "object",
                    "properties" => [
                        "strategic_theme" => [
                            "type" => "string",
                            "description" => "Main strategic direction and alignment with company vision"
                        ],
                        "alignment_with_position" => [
                            "type" => "string",
                            "description" => "How strategy aligns with IE Matrix position"
                        ],
                        "short_term_actions" => [
                            "type" => "array",
                            "items" => [
                                "type" => "object",
                                "properties" => [
                                    "action" => ["type" => "string"],
                                    "priority" => ["type" => "string"],
                                    "impact" => ["type" => "string"]
                                ],
                                "required" => ["action", "priority", "impact"]
                            ],
                            "description" => "Short-term action items (3-6 months)"
                        ],
                        "long_term_actions" => [
                            "type" => "array",
                            "items" => [
                                "type" => "object",
                                "properties" => [
                                    "initiative" => ["type" => "string"],
                                    "resources" => ["type" => "string"],
                                    "success_metrics" => ["type" => "string"]
                                ],
                                "required" => ["initiative", "resources", "success_metrics"]
                            ],
                            "description" => "Long-term strategic initiatives (1-3 years)"
                        ],
                        "resource_implications" => [
                            "type" => "object",
                            "properties" => [
                                "budget_allocation" => ["type" => "string"],
                                "key_roles" => ["type" => "string"],
                                "skill_development" => ["type" => "string"]
                            ],
                            "required" => ["budget_allocation", "key_roles", "skill_development"],
                            "description" => "Resource and budget implications"
                        ],
                        "risk_mitigation" => [
                            "type" => "array",
                            "items" => [
                                "type" => "object",
                                "properties" => [
                                    "risk" => ["type" => "string"],
                                    "mitigation" => ["type" => "string"]
                                ],
                                "required" => ["risk", "mitigation"]
                            ],
                            "description" => "Key risks and mitigation strategies"
                        ]
                    ],
                    "required" => ["strategic_theme", "alignment_with_position", "short_term_actions", "long_term_actions", "resource_implications", "risk_mitigation"]
                ];

                $result = sumopod_call_json($prompt, $schema, 'gpt-4o-mini', 0.2, 2000);
                return $result;
            } else {
                log_message('error', 'No AI service available for recommendation generation');
                return false;
            }

        } catch (Exception $e) {
            log_message('error', 'AI recommendation error: ' . $e->getMessage());
            return false;
        }
    }

    private function json_response($success, $message, $data = [])
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $success,
                'message' => $message,
                'data'    => $data
            ]));
    }

    /**
     * Get existing strategies for a project and pair_type without triggering generation
     * Endpoint: GET api/project/strategies_list?project=X&pair_type=S-O
     * Must use ACTIVE run because:
     * - Regenerate feature creates new runs and deactivates old ones
     * - Frontend displays and manages strategies from the active run only
     */
    public function strategies_list()
    {
        if ($this->input->method() !== 'get') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $project_id = (int)$this->input->get('project');
        $pair_type  = $this->input->get('pair_type'); // 'S-O','W-O','S-T','W-T'

        if (!$project_id || !in_array($pair_type, ['S-O','W-O','S-T','W-T'], true)) {
            return $this->json_response(false, 'Invalid project or pair_type');
        }

        // Get the ACTIVE run for this pair_type
        // This is the current generation being used
        $run = $this->runModel->get_active_run($project_id, $pair_type);
        
        if (!$run) {
            // No active run = no valid strategies to display
            return $this->json_response(true, 'No active run found', [
                'run_id'     => null,
                'stage'      => null,
                'strategies' => []
            ]);
        }

        // Get strategies from the active run
        $strategies = $this->strategyModel->get_by_run((int)$run['id']);

        return $this->json_response(true, 'Existing strategies fetched', [
            'run_id'     => (int)$run['id'],
            'stage'      => $run['stage'] ?? null,
            'pair_type'  => $run['pair_type'] ?? $pair_type,
            'strategies' => $strategies ?: []
        ]);
    }

    /**
     * 1) GENERATING TOP-K PAIRS
     * Endpoint: POST api/project/generating_top_k_pairs?project=X&pair_type=S-O
     * - Buat run baru
     * - Nonaktifkan run lama pair_type tsb
     * - Hitung top-k (tanpa AI)
     */
    public function generating_top_k_pairs()
    {
        $project_id = (int)$this->input->get('project');
        $pair_type  = $this->input->get('pair_type'); // 'S-O','W-O','S-T','W-T'

        if (!$project_id || !in_array($pair_type, ['S-O','W-O','S-T','W-T'])) {
            return $this->json_response(false, 'Invalid project or pair_type');
        }

        // Nonaktifkan run aktif sebelumnya untuk project+pair_type ini
        $this->runModel->deactivate_previous_runs($project_id, $pair_type);

        // Hitung top-k pairs (local, tanpa AI)
        // Implementasi get_top_k_pairs_by_type ada di Topk_service_model
        $topK = $this->topkService->get_top_k_pairs_by_type($project_id, $pair_type, 12);
        if (empty($topK)) {
            return $this->json_response(false, 'No pairs available for this project/pair_type');
        }

        // Buat run baru
        $run_id = $this->runModel->create([
            'project_id' => $project_id,
            'pair_type'  => $pair_type,
            'stage'      => 'initialized',
            'model'      => 'gemini-1.5-flash',
            'temperature' => 0.2,
            'max_output_tokens' => 1000,
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // (Opsional) Kalau mau simpan topK mentah di tabel lain, lakukan di sini.

        return $this->json_response(true, 'Top-K pairs generated', [
            'run_id'   => $run_id,
            'stage'    => 'initialized',
            'pair_type'=> $pair_type
        ]);
    }

    /**
     * 2) SEMANTIC FILTER
     * Endpoint: POST api/project/semantic_filter?project=X&run=Y
     * - Ambil top-k terkini dari service
     * - Jalankan Gemini untuk filter semantik
     * - Simpan ke ai_pair_filtered
     */
    public function semantic_filter()
    {
        $project_id = (int)$this->input->get('project');
        $run_id     = (int)$this->input->get('run');

        if (!$project_id || !$run_id) {
            return $this->json_response(false, 'Missing project or run');
        }

        $run = $this->runModel->get($run_id);
        if (!$run || (int)$run['project_id'] !== $project_id || !$run['is_active']) {
            return $this->json_response(false, 'Invalid or inactive run');
        }

        $pair_type = $run['pair_type'];

        // Ambil Top-K pairs lagi (supaya selalu pakai SWOT terbaru)
        $topK = $this->topkService->get_top_k_pairs_by_type($project_id, $pair_type, 12);
        if (empty($topK)) {
            $this->runModel->update_stage($run_id, 'failed');
            return $this->json_response(false, 'No Top-K pairs found');
        }

        // Map ke format untuk semantic filter
        $pairsForFilter = [];
        foreach ($topK as $row) {
            // Sesuaikan mapping dengan struktur topK kamu
            $pairsForFilter[] = [
                'left_ids'   => [ (string)$row['x_id'] ],
                'right_ids'  => [ (string)$row['y_id'] ],
                'left_text'  => $row['x_description'],
                'right_text' => $row['y_description'],
                'priority'   => (float)$row['score'],
            ];
        }

        try {
            $filtered = $this->semantic_filter_pairs_array($pairsForFilter, $pair_type, 5);
            if (empty($filtered)) {
                $this->runModel->update_stage($run_id, 'failed');
                return $this->json_response(false, 'Semantic filtering failed or empty');
            }

            // Simpan: hapus dulu data lama untuk run ini
            $this->pairModel->delete_by_run($run_id);

            $batch = [];
            foreach ($filtered as $p) {
                $batch[] = [
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
            $this->pairModel->insert_batch($batch);

            $this->runModel->update_stage($run_id, 'semantic_done');

            return $this->json_response(true, 'Semantic filtering completed', [
                'run_id' => $run_id,
                'stage'  => 'semantic_done'
            ]);

        } catch (\Throwable $e) {
            log_message('error', 'semantic_filter error: '.$e->getMessage());
            $this->runModel->update_stage($run_id, 'failed');
            return $this->json_response(false, 'Semantic filtering error');
        }
    }

    /**
     * 3) GENERATE STRATEGIES
     * Endpoint: POST api/project/strategies?project=X&run=Y
     * - Ambil ai_pair_filtered by run
     * - Panggil Gemini untuk generate strategi
     * - Simpan ke ai_strategy
     */
    public function strategies()
    {
        $project_id = (int)$this->input->get('project');
        $run_id     = (int)$this->input->get('run');
        // Bahasa: 'id' (Indonesia) atau 'en' (English)
        $langParam  = strtolower(trim((string)$this->input->get('lang')));
        $language   = ($langParam === 'en') ? 'en' : 'id';

        if (!$project_id || !$run_id) {
            return $this->json_response(false, 'Missing project or run');
        }

        $run = $this->runModel->get($run_id);
        if (!$run || (int)$run['project_id'] !== $project_id || !$run['is_active']) {
            return $this->json_response(false, 'Invalid or inactive run');
        }

        $pair_type = $run['pair_type'];

        // Ambil hasil semantic filter
        $pairs = $this->pairModel->get_by_run($run_id);
        if (empty($pairs)) {
            return $this->json_response(false, 'No semantic-filtered pairs found for this run');
        }

        try {
            // Gunakan pairs untuk generate strategi dengan bahasa yang dipilih
            $strategies = $this->generate_strategies_from_pairs($pairs, $pair_type, 6, $language);
            if (empty($strategies)) {
                $this->runModel->update_stage($run_id, 'failed');
                return $this->json_response(false, 'Failed to generate strategies');
            }

            // Simpan strategi (hapus lama dulu)
            $this->strategyModel->delete_by_run($run_id);

            $batch = [];
            foreach ($strategies as $s) {
                $batch[] = [
                    'run_id'        => $run_id,
                    'project_id'    => $project_id,
                    'pair_type'     => $pair_type,
                    'code'          => $s['code'],
                    'statement'     => $s['statement'],
                    'priority_score'=> $s['priority_score'],
                    'created_at'    => date('Y-m-d H:i:s'),
                ];
            }
            $this->strategyModel->insert_batch($batch);

            $this->runModel->update_stage($run_id, 'strategy_done');

            return $this->json_response(true, 'Strategies generated', [
                'run_id'     => $run_id,
                'stage'      => 'strategy_done',
                'strategies' => $batch
            ]);

        } catch (\Throwable $e) {
            log_message('error', 'strategies error: '.$e->getMessage());
            $this->runModel->update_stage($run_id, 'failed');
            return $this->json_response(false, 'Error generating strategies: '.$e->getMessage());
        }
    }

    // ---------- Helper semantic + strategy ----------
    // (Isi dengan versi yang sudah kita bahas sebelumnya)

    private function semantic_filter_pairs_array(array $pairs, string $pair_type, int $final_n = 5): array
    {
        if (empty($pairs)) return [];

        // Konstanta weighting
        $take  = 12;   // ambil top-K awal
        $alpha = 0.4;  // kontribusi skor angka
        $beta  = 0.6;  // kontribusi relevansi AI

        // Batasi jumlah kandidat
        $pairs = array_slice($pairs, 0, $take);

        // Hitung max priority
        $maxPriority = 0.0;
        foreach ($pairs as $p) {
            $maxPriority = max($maxPriority, (float)($p['priority'] ?? 0.0));
        }

        // Label kiri/kanan berdasar tipe pasangan
        $labelL = in_array($pair_type, ['S-O','S-T'], true) ? 'S' : 'W';
        $labelR = in_array($pair_type, ['S-O','W-O'], true) ? 'O' : 'T';

        // Helper ringkas teks agar hemat token
        $shorten = function($text, $len = 80) {
            $text = trim(preg_replace('/\s+/', ' ', (string)$text));
            return (mb_strlen($text) <= $len) ? $text : (mb_substr($text, 0, $len - 1) . '…');
        };

        // Susun prompt lines dan beri ID urut untuk AI
        $lines = [];
        foreach ($pairs as $i => &$p) {
            $p['__idx'] = $i + 1;
            $p['left_text']  = $shorten($p['left_text']  ?? '');
            $p['right_text'] = $shorten($p['right_text'] ?? '');
            $lines[] = $p['__idx'] . ') ' . $labelL . '="' . $p['left_text'] . '" + ' . $labelR . '="' . $p['right_text'] . '"';
        }
        unset($p);

        // Makna singkat per tipe pasangan
        $meaning = [
            'S-O' => 'Use internal strengths to exploit external opportunities.',
            'S-T' => 'Use internal strengths to mitigate external threats.',
            'W-O' => 'Fix internal weaknesses to exploit external opportunities.',
            'W-T' => 'Minimize weaknesses to reduce risk from external threats.'
        ][$pair_type] ?? 'Evaluate semantic business fit of left+right.';

        $body = implode("\n", $lines);
        $prompt = "You are a business analyst. Return JSON only per schema.\n" .
            "Task: Score SEMANTIC BUSINESS RELEVANCE for each pair (0..1).\n" .
            "Higher = stronger strategic fit for {$pair_type}.\n" .
            "Guidance: {$meaning}\n" .
            "Do NOT explain. Do NOT change IDs. No extra text.\n\n" .
            "Pairs:\n{$body}\n\n" .
            "Schema: {\"pairs\":[{\"id\":1,\"rel\":0.0}]}";

        // Panggil AI untuk relevansi semantik
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

        try {
            $ai = gemini_call_json($prompt, $schema, 'gemini-2.5-flash', 0.2, 800);
        } catch (\Throwable $e) {
            log_message('error', 'semantic_filter error: '.$e->getMessage());
            return [];
        }

        // Peta hasil relevansi AI per ID
        $relMap = [];
        if (isset($ai['pairs']) && is_array($ai['pairs'])) {
            foreach ($ai['pairs'] as $r) {
                $id = isset($r['id']) ? (int)$r['id'] : null;
                if ($id !== null) {
                    $relMap[$id] = (float)($r['rel'] ?? 0.0);
                }
            }
        }

        // Hitung skor final = alpha*norm(priority) + beta*rel
        foreach ($pairs as &$p) {
            $norm = ($maxPriority > 0) ? ((float)($p['priority'] ?? 0.0)) / $maxPriority : 0.0;
            $rel  = $relMap[$p['__idx']] ?? 0.5;
            $p['rel']   = round($rel, 4);
            $p['final'] = round($alpha * $norm + $beta * $rel, 6);
            unset($p['__idx']);
        }
        unset($p);

        // Urutkan dan ambil top-N
        usort($pairs, function($a, $b) { return ($b['final'] <=> $a['final']); });
        return array_slice($pairs, 0, $final_n);
    }

    private function generate_strategies_from_pairs(array $filteredPairs, string $pair_type, int $want = 6, string $language = 'id'): array
    {
        if (empty($filteredPairs)) return [];

        // Ambil top-N kandidat
        $take = max(1, min($want * 2, count($filteredPairs)));
        $candidates = array_slice($filteredPairs, 0, $take);

        // Label sisi kiri/kanan
        $labelL = in_array($pair_type, ['S-O','S-T'], true) ? 'S' : 'W';
        $labelR = in_array($pair_type, ['S-O','W-O'], true) ? 'O' : 'T';

        // Helper ringkas teks
        $shorten = function($text, $len = 100) {
            $text = trim(preg_replace('/\s+/', ' ', (string)$text));
            return (mb_strlen($text) <= $len) ? $text : (mb_substr($text, 0, $len - 1) . '…');
        };

        // Susun prompt ringkas untuk strategi
        $lines = [];
        foreach ($candidates as $i => $p) {
            $left  = $shorten($p['left_text'] ?? '');
            $right = $shorten($p['right_text'] ?? '');
            $score = isset($p['final']) ? (float)$p['final'] : (float)($p['priority'] ?? 0.0);
            $lines[] = ($i+1) . ') ' . $labelL . '="' . $left . '" + ' . $labelR . '="' . $right . '" (score=' . round($score,3) . ')';
        }

        // Guidance bilingual (ID/EN)
        $guidance_en = [
            'S-O' => 'Propose strategies leveraging strengths to exploit opportunities.',
            'S-T' => 'Propose strategies leveraging strengths to mitigate threats.',
            'W-O' => 'Propose strategies fixing weaknesses to exploit opportunities.',
            'W-T' => 'Propose strategies minimizing weaknesses to reduce threat risks.'
        ];
        $guidance_id = [
            'S-O' => 'Usulkan strategi memanfaatkan kekuatan untuk mengeksploitasi peluang.',
            'S-T' => 'Usulkan strategi memanfaatkan kekuatan untuk mengurangi ancaman.',
            'W-O' => 'Usulkan strategi memperbaiki kelemahan untuk menangkap peluang.',
            'W-T' => 'Usulkan strategi meminimalkan kelemahan untuk mengurangi risiko ancaman.'
        ];
        $guidance = ($language === 'en' ? ($guidance_en[$pair_type] ?? 'Propose concise business strategies based on the pairs.')
                                       : ($guidance_id[$pair_type] ?? 'Usulkan strategi bisnis ringkas berdasarkan pasangan.'));

        $body = implode("\n", $lines);
        if ($language === 'en') {
            $prompt = "You are a business strategist. Return JSON only per schema. No prose.\n" .
                "Task: Generate {$want} ranked strategies for {$pair_type}.\n" .
                "Guidance: {$guidance}\n" .
                "Each strategy must have a short CODE and STATEMENT.\n" .
                "Use English in statements. Keep each statement one sentence (<= 20 words).\n" .
                "Never return an empty array; if pairs are weak or ambiguous, still output at least one generic yet relevant strategy.\n" .
                "Base strategies on these pairs (with scores):\n{$body}\n\n" .
                "Schema: {\"strategies\":[{\"code\":\"SO1\",\"statement\":\"...\",\"priority_score\":0.0}]}";
        } else {
            $prompt = "Anda adalah perencana bisnis. Kembalikan JSON saja sesuai schema. Tanpa penjelasan.\n" .
                "Tugas: Buat {$want} strategi berperingkat untuk {$pair_type}.\n" .
                "Panduan: {$guidance}\n" .
                "Setiap strategi harus memiliki CODE singkat dan STATEMENT.\n" .
                "Gunakan Bahasa Indonesia. Batasi tiap statement satu kalimat (<= 20 kata).\n" .
                "Jangan pernah mengembalikan array kosong; jika pasangan lemah atau ambigu, tetap berikan minimal satu strategi generik yang relevan.\n" .
                "Dasarkan strategi pada pasangan berikut (dengan skor):\n{$body}\n\n" .
                "Schema: {\"strategies\":[{\"code\":\"SO1\",\"statement\":\"...\",\"priority_score\":0.0}]}";
        }

        $schema = [
            "type" => "object",
            "properties" => [
                "strategies" => [
                    "type" => "array",
                    "minItems" => 1,
                    "items" => [
                        "type" => "object",
                        "properties" => [
                            "code"           => ["type" => "string", "maxLength" => 8],
                            "statement"      => ["type" => "string", "maxLength" => 240],
                            "priority_score" => ["type" => "number", "minimum" => 0]
                        ],
                        "required" => ["code","statement","priority_score"]
                    ]
                ]
            ],
            "required" => ["strategies"]
        ];

        // Panggil AI (OpenAI atau Gemini) lalu normalisasi output
        $ai = null;
        try {
            // // Prioritaskan OpenAI bila helper tersedia; jika ingin tetap Gemini, ganti pemanggilan di sini
            // if (function_exists('openai_call_json')) {
                // $openaiModel = function_exists('get_setting') ? (get_setting('openai_model') ?: 'gpt-4o-mini') : 'gpt-4o-mini';
                // $ai = openai_call_json($prompt, $schema, $openaiModel, 0.2, 800);
                // use sumopod
                $ai = sumopod_call_json($prompt, $schema, 'gpt-4o-mini', 0.2, 800);
            // } else {
            //     $ai = gemini_call_json($prompt, $schema, 'gemini-2.5-flash', 0.2, 800);
            // }
            // $ai = gemini_call_json($prompt, $schema, 'gemini-2.5-flash', 0.2, 800);
        } catch (\Throwable $e) {
            log_message('error', 'strategies error: '.$e->getMessage());
            throw new \RuntimeException('Gagal memanggil AI untuk strategi: '.$e->getMessage());
        }

        // Normalisasi hasil: terima bentuk {strategies:[...]} atau array langsung [...]
        $aiStrategies = null;
        if ($ai !== false) {
            if (isset($ai['strategies']) && is_array($ai['strategies'])) {
                $aiStrategies = $ai['strategies'];
            } elseif (is_array($ai) && array_keys($ai) === range(0, count($ai)-1)) {
                $aiStrategies = $ai; // top-level array
            } elseif (isset($ai['data']['strategies']) && is_array($ai['data']['strategies'])) {
                $aiStrategies = $ai['data']['strategies'];
            }
        }

        // Jika hasil awal kosong tapi format valid, coba sekali lagi dengan prompt diperketat
        if ($ai !== false && (empty($aiStrategies) || !is_array($aiStrategies))) {
            $retryPrompt = $prompt . "\n\nSTRICT: Output \"strategies\" with at least 1 item. Never empty.";
            try {
                $aiRetry = gemini_call_json($retryPrompt, $schema, 'gemini-2.5-flash', 0.2, 700);
                if ($aiRetry !== false) {
                    if (isset($aiRetry['strategies']) && is_array($aiRetry['strategies'])) {
                        $aiStrategies = $aiRetry['strategies'];
                    } elseif (is_array($aiRetry) && array_keys($aiRetry) === range(0, count($aiRetry)-1)) {
                        $aiStrategies = $aiRetry; // top-level array
                    } elseif (isset($aiRetry['data']['strategies']) && is_array($aiRetry['data']['strategies'])) {
                        $aiStrategies = $aiRetry['data']['strategies'];
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', 'strategies retry error: '.$e->getMessage());
            }
        }

        // Paksa hanya Gemini: tidak ada fallback ke penyedia lain

        // Validasi hasil AI; jika gagal atau kosong, lempar error agar endpoint menampilkan pesan
        if ($ai === false || !is_array($aiStrategies) || empty($aiStrategies)) {
            throw new \RuntimeException('AI tidak mengembalikan data strategi (respons kosong atau format tidak sesuai).');
        }

        // Bangun hasil hanya dari output AI (tanpa fallback)
        $results = [];
        foreach ($aiStrategies as $i => $s) {
            if (count($results) >= $want) break;
            $code = (string)($s['code'] ?? (($pair_type === 'S-O' ? 'SO' : ($pair_type === 'S-T' ? 'ST' : ($pair_type === 'W-O' ? 'WO' : 'WT'))) . ($i+1)));
            $statement = (string)($s['statement'] ?? '');
            $ps = (float)($s['priority_score'] ?? 0.0);
            $results[] = [
                'code'           => $code,
                'statement'      => $statement,
                'priority_score' => $ps
            ];
        }

        return $results;
    }

    /**
     * SAVE prioritized strategies
     * POST /api/project/prioritized-strategies/save
     */
    public function prioritized_strategies_save() {
        if ($this->input->method() !== 'post') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $json_input = json_decode($this->input->raw_input_stream, true);

        if (!$json_input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
            return;
        }

        $project_uuid = $json_input['project_uuid'] ?? NULL;
        $strategies = $json_input['strategies'] ?? [];

        if (!$project_uuid || empty($strategies)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'project_uuid and strategies required']);
            return;
        }

        // Load model
        $this->load->model('Prioritized_strategy_model', 'prioritizedStrategy');

        // Verify project ownership
        $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Project not found or access denied']);
            return;
        }

        try {
            $inserted_ids = $this->prioritizedStrategy->save_multiple(
                $project['id'],
                $strategies,
                $user_id
            );

            // Get saved records
            $saved_strategies = $this->prioritizedStrategy->get_by_project($project['id']);

            echo json_encode([
                'success' => true,
                'message' => 'Prioritized strategies saved successfully',
                'data' => [
                    'saved_count' => count($inserted_ids),
                    'strategies' => $saved_strategies
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error saving strategies: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * GET prioritized strategies
     * GET /api/project/prioritized-strategies?project_uuid=...
     */
    public function prioritized_strategies_get() {
        if ($this->input->method() !== 'get') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $project_uuid = $this->input->get('project_uuid');
        $pair_type = $this->input->get('pair_type');

        if (!$project_uuid) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'project_uuid required']);
            return;
        }

        // Load model
        $this->load->model('Prioritized_strategy_model', 'prioritizedStrategy');

        // Verify project ownership
        $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Project not found']);
            return;
        }

        $strategies = $this->prioritizedStrategy->get_by_project(
            $project['id'],
            $pair_type
        );

        $summary = $this->prioritizedStrategy->get_status_summary($project['id']);

        echo json_encode([
            'success' => true,
            'data' => [
                'project_uuid' => $project_uuid,
                'strategies' => $strategies,
                'summary' => $summary
            ]
        ]);
    }

    /**
     * UPDATE strategy
     * PUT /api/project/prioritized-strategies/{id}
     */
    public function prioritized_strategies_update() {
        if ($this->input->method() !== 'put') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $id = $this->uri->segment(4); // Extract ID from URL
        $json_input = json_decode($this->input->raw_input_stream, true);

        if (!$json_input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
            return;
        }

        // Load model
        $this->load->model('Prioritized_strategy_model', 'prioritizedStrategy');

        try {
            $updated = $this->prioritizedStrategy->update_strategy($id, $json_input, $user_id);

            if ($updated) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Strategy updated successfully'
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Strategy not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error updating strategy: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * DELETE strategy (soft delete)
     * DELETE /api/project/prioritized-strategies/{id}
     */
    public function prioritized_strategies_delete() {
        if ($this->input->method() !== 'delete') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $id = $this->uri->segment(4); // Extract ID from URL

        // Load model
        $this->load->model('Prioritized_strategy_model', 'prioritizedStrategy');

        try {
            $deleted = $this->prioritizedStrategy->delete_strategy($id);

            if ($deleted) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Strategy deleted successfully'
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Strategy not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error deleting strategy: ' . $e->getMessage()
            ]);
        }
    }

}