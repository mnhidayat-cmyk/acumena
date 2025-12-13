<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ai_strategy_model extends CI_Model
{
    protected $table = 'ai_strategy';

    public function get_by_run($run_id)
    {
        return $this->db
            ->where('run_id', $run_id)
            ->order_by('priority_score', 'DESC')
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

    /**
     * Generate strategies using Gemini API (free, no quota)
     * 
     * @param string $prompt
     * @return array ['success' => bool, 'data' => mixed]
     */
    public function generate_with_gemini($prompt)
    {
        try {
            // Load the appropriate library or helper for Gemini API
            $this->load->helper('sendgrid');
            
            // TODO: Implement Gemini API call
            // This should call your Gemini API integration
            // For now, returning empty as placeholder
            
            return [
                'success' => false,
                'data' => null,
                'error' => 'Gemini API not configured'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => null,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate strategies using Sumopod API (paid, counts quota)
     * 
     * @param string $prompt
     * @return array ['success' => bool, 'data' => mixed]
     */
    public function generate_with_sumopod($prompt)
    {
        try {
            // Load HTTP client for API calls
            $this->load->library('curl');
            
            $api_key = get_setting('sumopod_api_key');
            if (!$api_key) {
                return [
                    'success' => false,
                    'data' => null,
                    'error' => 'Sumopod API key not configured'
                ];
            }
            
            $url = 'https://api.sumopod.com/v1/strategy/generate';
            
            $headers = [
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/json'
            ];
            
            $data = [
                'prompt' => $prompt,
                'model' => 'standard'
            ];
            
            $response = $this->curl->simple_post($url, $data, $headers);
            $result = json_decode($response, true);
            
            if (!empty($result['success'])) {
                return [
                    'success' => true,
                    'data' => $result['data'] ?? null
                ];
            }
            
            return [
                'success' => false,
                'data' => null,
                'error' => $result['message'] ?? 'API error'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => null,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate with automatic fallback from Gemini to Sumopod
     * 
     * @param string $prompt
     * @param int $user_id (optional) - For tracking usage
     * @param int $project_id (optional) - For tracking usage
     * @param string $type (optional) - 'strategy' or 'recommendation' for tracking
     * @return array ['success' => bool, 'provider' => string, 'data' => mixed, 'error' => string]
     */
    public function generate_with_fallback($prompt, $user_id = null, $project_id = null, $type = 'strategy')
    {
        // Try Gemini first (free, no quota impact)
        $gemini_result = $this->generate_with_gemini($prompt);
        if ($gemini_result['success']) {
            // Track Gemini usage (quota_impact=0)
            if ($user_id && $project_id) {
                $this->_record_usage($user_id, $project_id, $type, 'gemini', 0);
            }
            
            return [
                'success' => true,
                'provider' => 'gemini',
                'data' => $gemini_result['data'],
                'error' => null
            ];
        }
        
        // Fallback to Sumopod (paid, counts quota)
        $sumopod_result = $this->generate_with_sumopod($prompt);
        if ($sumopod_result['success']) {
            // Track Sumopod usage (quota_impact=1 for strategy/recommendation)
            if ($user_id && $project_id) {
                $quota_impact = in_array($type, ['strategy', 'recommendation']) ? 1 : 0;
                $this->_record_usage($user_id, $project_id, $type, 'sumopod', $quota_impact);
            }
            
            return [
                'success' => true,
                'provider' => 'sumopod',
                'data' => $sumopod_result['data'],
                'error' => null
            ];
        }
        
        return [
            'success' => false,
            'provider' => null,
            'data' => null,
            'error' => 'Both Gemini and Sumopod providers failed'
        ];
    }

    /**
     * Record AI usage internally (private method)
     * 
     * @param int $user_id
     * @param int $project_id
     * @param string $type
     * @param string $provider
     * @param int $quota_impact
     */
    private function _record_usage($user_id, $project_id, $type, $provider, $quota_impact)
    {
        $this->load->model('Subscription_model');
        $this->Subscription_model->increment_usage($user_id, $project_id, $type, $provider, $quota_impact);
    }
}
