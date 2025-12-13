<?php
/**
 * Subscription Helper
 * 
 * Helper functions for subscription limits enforcement
 * - Project creation limits
 * - Step access control
 * - AI generation quota tracking
 * - Monthly quota reset
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Get user's subscription plan
 * 
 * @param int $user_id
 * @return object|null Subscription plan object
 */
function get_user_subscription_plan($user_id)
{
    $CI =& get_instance();
    $CI->load->model('Subscription_model');
    
    // Get user's subscription ID from user_subscription table
    $CI->db->select('subscription_id');
    $CI->db->from('user_subscription');
    $CI->db->where('user_id', $user_id);
    $query = $CI->db->get();
    $user_sub = $query->row();
    
    if (!$user_sub) {
        // Return default trial subscription
        $default_sub_id = get_setting('default_subscription_id') ?: 1;
        return $CI->Subscription_model->get_subscription_by_id($default_sub_id);
    }
    
    return $CI->Subscription_model->get_subscription_by_id($user_sub->subscription_id);
}

/**
 * Check if user can create more projects
 * 
 * @param int $user_id
 * @return array ['can_create' => bool, 'reason' => string, 'current' => int, 'limit' => int]
 */
function can_user_create_project($user_id)
{
    $CI =& get_instance();
    $CI->load->model('User_model', 'User_model');
    $CI->load->model('Project_model', 'Project_model');
    $CI->load->model('Subscription_model', 'Subscription_model');
    
    $plan = get_user_subscription_plan($user_id);
    
    if (!$plan) {
        return [
            'can_create' => false,
            'reason' => 'No active subscription',
            'current' => 0,
            'limit' => 0
        ];
    }
    
    $project_count = $CI->Project_model->count_projects_by_user_id($user_id);
    $max_projects = (int)($plan['max_projects'] ?? 1);
    
    if ($project_count >= $max_projects) {
        return [
            'can_create' => false,
            'reason' => "Project limit reached ({$project_count}/{$max_projects})",
            'current' => $project_count,
            'limit' => $max_projects
        ];
    }
    
    return [
        'can_create' => true,
        'reason' => 'OK',
        'current' => $project_count,
        'limit' => $max_projects
    ];
}

/**
 * Check if user can access a specific workflow step
 * 
 * @param int $user_id
 * @param string $step (profile, swot, matrix-ife, matrix-efe, matrix-ie, matrix-ai, full)
 * @return array ['can_access' => bool, 'reason' => string, 'max_step' => string]
 */
function can_user_access_step($user_id, $step)
{
    $CI =& get_instance();
    
    $plan = get_user_subscription_plan($user_id);
    
    if (!$plan) {
        return [
            'can_access' => false,
            'reason' => 'No active subscription',
            'max_step' => null
        ];
    }
    
    $max_step = $plan['max_step'];
    $step_order = [
        'profile' => 1,
        'swot' => 2,
        'matrix-ife' => 3,
        'matrix-efe' => 4,
        'matrix-ie' => 5,
        'matrix-ai' => 6,
        'full' => 6
    ];
    
    // If max_step is 'full', user can access all steps
    if ($max_step === 'full') {
        return [
            'can_access' => true,
            'reason' => 'OK',
            'max_step' => $max_step
        ];
    }
    
    // Check if requested step is within allowed range
    $requested_level = $step_order[$step] ?? 0;
    $max_level = $step_order[$max_step] ?? 0;
    
    if ($requested_level <= $max_level) {
        return [
            'can_access' => true,
            'reason' => 'OK',
            'max_step' => $max_step
        ];
    }
    
    return [
        'can_access' => false,
        'reason' => "Step '{$step}' requires higher subscription level. Maximum allowed: '{$max_step}'",
        'max_step' => $max_step
    ];
}

/**
 * Check if user can generate AI strategy
 * 
 * @param int $user_id
 * @param int $project_id
 * @return array ['can_generate' => bool, 'reason' => string, 'remaining' => int, 'limit' => int]
 */
function can_user_generate_ai($user_id, $project_id)
{
    $CI =& get_instance();
    $CI->load->model('Subscription_model');
    
    $plan = get_user_subscription_plan($user_id);
    
    if (!$plan) {
        return [
            'can_generate' => false,
            'reason' => 'No active subscription',
            'remaining' => 0,
            'limit' => 0
        ];
    }
    
    $max_ai_generation = (int)($plan['max_ai_generation'] ?? 0);
    
    // If limit is 0, unlimited (or no quota tracking needed)
    if ($max_ai_generation === 0) {
        return [
            'can_generate' => true,
            'reason' => 'OK (unlimited)',
            'remaining' => 0,
            'limit' => 0
        ];
    }
    
    $current_usage = $CI->Subscription_model->get_current_month_usage($user_id);
    $remaining = $max_ai_generation - $current_usage;
    
    if ($remaining <= 0) {
        return [
            'can_generate' => false,
            'reason' => "AI generation quota exceeded ({$current_usage}/{$max_ai_generation})",
            'remaining' => 0,
            'limit' => $max_ai_generation
        ];
    }
    
    return [
        'can_generate' => true,
        'reason' => 'OK',
        'remaining' => $remaining,
        'limit' => $max_ai_generation
    ];
}

/**
 * Check if user can generate AI per project
 * 
 * @param int $user_id
 * @param int $project_id
 * @return array ['can_generate' => bool, 'reason' => string, 'remaining' => int, 'limit' => int]
 */
function can_user_generate_ai_per_project($user_id, $project_id)
{
    $CI =& get_instance();
    $CI->load->model('Subscription_model');
    
    $plan = get_user_subscription_plan($user_id);
    
    if (!$plan) {
        return [
            'can_generate' => false,
            'reason' => 'No active subscription',
            'remaining' => 0,
            'limit' => 0
        ];
    }
    
    $max_per_project = (int)($plan['max_ai_per_project'] ?? 0);
    
    // If limit is null or 0, no per-project limit
    if (!$max_per_project) {
        return [
            'can_generate' => true,
            'reason' => 'OK (no per-project limit)',
            'remaining' => 0,
            'limit' => 0
        ];
    }
    
    $current_usage = $CI->Subscription_model->get_project_usage($project_id);
    $remaining = $max_per_project - $current_usage;
    
    if ($remaining <= 0) {
        return [
            'can_generate' => false,
            'reason' => "Per-project AI quota exceeded ({$current_usage}/{$max_per_project})",
            'remaining' => 0,
            'limit' => $max_per_project
        ];
    }
    
    return [
        'can_generate' => true,
        'reason' => 'OK',
        'remaining' => $remaining,
        'limit' => $max_per_project
    ];
}

/**
 * Get user's remaining AI generation quota
 * 
 * @param int $user_id
 * @return array ['remaining' => int, 'limit' => int, 'used' => int, 'reset_date' => string]
 */
function get_user_remaining_quota($user_id)
{
    $CI =& get_instance();
    $CI->load->model('Subscription_model');
    
    $plan = get_user_subscription_plan($user_id);
    
    if (!$plan) {
        return [
            'remaining' => 0,
            'limit' => 0,
            'used' => 0,
            'reset_date' => null
        ];
    }
    
    $limit = (int)($plan['max_ai_generation'] ?? 0);
    $used = $CI->Subscription_model->get_current_month_usage($user_id);
    $remaining = $limit === 0 ? 0 : max(0, $limit - $used);
    
    $reset_date = date('Y-m-d', strtotime('first day of next month'));
    
    return [
        'remaining' => $remaining,
        'limit' => $limit,
        'used' => $used,
        'reset_date' => $reset_date
    ];
}

/**
 * Increment AI usage counter
 * 
 * @param int $user_id
 * @param int $project_id
 * @param string $type ('strategy'|'recommendation'|'pairing_filter'|'custom')
 * @param string $provider ('gemini'|'sumopod')
 * @param int $quota_impact (1=counts against quota, 0=free)
 * @return bool
 */
function increment_ai_usage($user_id, $project_id, $type = 'strategy', $provider = 'gemini', $quota_impact = 0)
{
    $CI =& get_instance();
    $CI->load->model('Subscription_model');
    
    // IMPORTANT: Determine quota impact based on rules:
    // - Pairing filter ALWAYS has quota_impact=0 (free, never counted)
    // - Gemini ALWAYS has quota_impact=0 (free, never counted)
    // - Sumopod for strategy/recommendation has quota_impact=1 (paid, counted)
    
    // Override quota_impact based on type and provider
    if ($type === 'pairing_filter') {
        // Pairing filter always free, regardless of provider
        $quota_impact = 0;
    } elseif ($provider === 'gemini') {
        // Gemini always free, regardless of type
        $quota_impact = 0;
    } elseif ($provider === 'sumopod' && in_array($type, ['strategy', 'recommendation'])) {
        // Only Sumopod for strategy/recommendation counts
        $quota_impact = 1;
    }
    
    return $CI->Subscription_model->increment_usage($user_id, $project_id, $type, $provider, $quota_impact);
}

/**
 * Get AI provider with fallback logic
 * 
 * Tries Gemini first (free, no quota), falls back to Sumopod if Gemini fails
 * Only Sumopod usage counts against quota
 * 
 * @param int $user_id
 * @param int $project_id
 * @return array ['provider' => string, 'should_track_quota' => bool]
 */
function get_ai_provider($user_id, $project_id)
{
    // Try Gemini first (free, unlimited, no quota impact)
    $provider = 'gemini';
    $should_track = false;
    
    return [
        'provider' => $provider,
        'should_track_quota' => $should_track,
        'fallback' => 'sumopod'  // If Gemini fails, will use Sumopod and track quota
    ];
}

/**
 * Handle AI generation with provider fallback
 * 
 * Primary: Gemini API (free, no quota)
 * Secondary: Sumopod (paid, counts quota)
 * 
 * @param int $user_id
 * @param int $project_id
 * @param string $prompt
 * @param string $type ('strategy'|'recommendation')
 * @return array ['success' => bool, 'provider' => string, 'result' => mixed, 'error' => string]
 */
function generate_with_fallback($user_id, $project_id, $prompt, $type = 'strategy')
{
    $CI =& get_instance();
    $CI->load->model('Ai_strategy_model');
    
    // Check quota before attempting
    $quota_check = can_user_generate_ai($user_id, $project_id);
    if (!$quota_check['can_generate']) {
        return [
            'success' => false,
            'provider' => null,
            'result' => null,
            'error' => $quota_check['reason']
        ];
    }
    
    // Try Gemini first (free, no quota impact)
    $gemini_result = $CI->Ai_strategy_model->generate_with_gemini($prompt);
    
    if ($gemini_result['success']) {
        // Record usage with quota_impact=0 (Gemini is free)
        increment_ai_usage($user_id, $project_id, $type, 'gemini', 0);
        
        return [
            'success' => true,
            'provider' => 'gemini',
            'result' => $gemini_result['data'],
            'error' => null
        ];
    }
    
    // Fallback to Sumopod if Gemini fails (paid, counts quota)
    $sumopod_result = $CI->Ai_strategy_model->generate_with_sumopod($prompt);
    
    if ($sumopod_result['success']) {
        // Record usage with quota_impact=1 (Sumopod counts toward quota)
        increment_ai_usage($user_id, $project_id, $type, 'sumopod', 1);
        
        return [
            'success' => true,
            'provider' => 'sumopod',
            'result' => $sumopod_result['data'],
            'error' => null
        ];
    }
    
    return [
        'success' => false,
        'provider' => null,
        'result' => null,
        'error' => 'Both Gemini and Sumopod providers failed'
    ];
}

/* End of file subscription_helper.php */
/* Location: ./application/helpers/subscription_helper.php */
