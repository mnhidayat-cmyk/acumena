<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_swot extends CI_Controller {

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
        $this->load->model('Project_model');
        $this->load->model('Project_swot_model');
        $this->load->library('form_validation');
    }

    /**
     * Get SWOT data by project UUID
     */
    public function get() {
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
        $project = $this->Project_model->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Project not found']);
            return;
        }

        // Get SWOT data for this project
        $swot_data = $this->Project_swot_model->get_swot_by_project_id($project['id']);
        
        echo json_encode(['success' => true, 'data' => $swot_data]);
    }

    /**
     * Save SWOT data
     */
    public function save() {
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
        $project = $this->Project_model->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Project not found']);
            return;
        }

        $project_id = $project['id'];

        // Delete existing SWOT data for this project
        $this->Project_swot_model->delete_swot_by_project_id($project_id);

        // Save new SWOT data
        $categories = ['S', 'W', 'O', 'T'];
        $category_names = ['strengths', 'weaknesses', 'opportunities', 'threats'];
        
        foreach ($categories as $index => $category) {
            $category_name = $category_names[$index];
            if (isset($input[$category_name]) && is_array($input[$category_name])) {
                foreach ($input[$category_name] as $description) {
                    if (!empty(trim($description))) {
                        $swot_data = [
                            'project_id' => $project_id,
                            'category' => $category,
                            'description' => trim($description),
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        $this->Project_swot_model->insert_swot($swot_data);
                    }
                }
            }
        }

        echo json_encode(['success' => true, 'message' => 'SWOT data saved successfully']);
    }
}