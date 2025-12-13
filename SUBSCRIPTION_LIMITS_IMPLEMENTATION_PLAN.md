# 📋 SUBSCRIPTION LIMITS IMPLEMENTATION PLAN

**Status:** Planning Phase  
**Date:** December 13, 2025

---

## Current State Analysis

### ❌ Not Implemented Yet

- No permission checks in controllers
- No quota enforcement
- No step access validation
- No API quota tracking

### ✅ Already in Place

- Database table: `m_subscription_plans` dengan kolom limit
- Model: `Subscription_model` dengan helper methods
- Database: 2 plans (Trial, Pro Plan)

---

## Implementation Requirements

### 1. **max_projects** - Project Creation Limit

**Status:** ❌ Not Implemented

**Where to implement:**

- Controller: `Api_project.php` → `create()` method
- Check: User's project count vs max_projects limit
- Action: Prevent create if limit reached

**Logic:**

```
IF user.created_projects >= subscription.max_projects
  THEN return error "Project limit reached"
ELSE allow create
```

---

### 2. **max_ai_generation & max_ai_per_project** - AI Generation Quota

**Status:** ❌ Not Implemented

**Special Rules:**

- ✅ **Exclude from quota:** Pairing filter process (kalkulasi similarity antar pair)
- 🔄 **Pairing logic:**
  - Try Gemini first (free, fast, no quota)
  - If Gemini fails → Use Sumopod (counted as quota)

**Where to implement:**

- Controller: `Api_project.php` → `generate_strategies()` / `generate_*()` methods
- Check: User's AI usage vs max_ai_generation & max_ai_per_project
- Track: Count each API call (except pairing filter)

**Logic:**

```
IF step == "pairing_filter"
  THEN run without counting quota
ELSE
  IF user.ai_calls_this_month >= subscription.max_ai_generation
    THEN return error "Monthly quota exceeded"
  ELSE IF subscription.max_ai_per_project
    AND project.ai_calls >= subscription.max_ai_per_project
    THEN return error "Project quota exceeded"
  ELSE run and increment counter
```

---

### 3. **max_step** - Workflow Step Access Control

**Status:** ❌ Not Implemented

**Current Status:**

- max_step is VARCHAR, stores string like "all" or "matrix-ie"
- Need to convert to ENUM with URL mapping

**Proposed Structure:**

```sql
ENUM(
  'profile',        -- Step 1: Company profile
  'swot',           -- Step 2: SWOT analysis
  'matrix-ie',      -- Step 3: IE Matrix
  'strategies',     -- Step 4: Strategies
  'recommendation', -- Step 5: Strategic recommendation
  'full'            -- All steps
)
```

**Where to implement:**

- Middleware/Helper: Check step access before loading view
- Controller: `Project.php` → `index()` method
- View protection: Hide unavailable steps in UI

**Logic:**

```
$allowed_steps = explode(',', subscription.max_step);
IF requested_step NOT IN allowed_steps
  THEN redirect to highest allowed step
ELSE allow access
```

---

### 4. **Column Cleanup** - Remove Unnecessary Columns

**Status:** To Be Determined

**Current Redundant Columns:**

```
project_qty                    ← Should remove (use max_projects)
project_api_generate_quota     ← Should remove (use max_ai_generation)
label                          ← Should remove (not used)
max_team_members               ← Keep? (for future team feature)
enable_export                  ← Keep (export feature)
enable_api_access              ← Keep (API feature)
enable_custom_branding         ← Keep (branding feature)
```

**Recommendation:**

```
KEEP:
  - max_projects
  - max_ai_generation
  - max_ai_per_project
  - max_step
  - max_team_members
  - enable_export
  - enable_api_access
  - enable_custom_branding
  - price_monthly, price_yearly
  - name

REMOVE:
  - project_qty (replaced by max_projects)
  - project_api_generate_quota (replaced by max_ai_generation)
  - label (unused)
```

---

## Implementation Roadmap

### Phase 1: Database Schema Fix (1 day)

**Tasks:**

- [ ] Alter max_step to ENUM
- [ ] Drop redundant columns (project_qty, project_api_generate_quota, label)
- [ ] Add migration script
- [ ] Update SQL dumps

**Files to modify:**

- `migration_subscriptions_table_schema_cleanup.sql` (new)
- `application/models/Subscription_model.php` (update queries)

---

### Phase 2: Permission Checking Layer (1-2 days)

**Tasks:**

- [ ] Create `subscription_helper.php` dengan helper functions
- [ ] Create permission checking middleware
- [ ] Test helper functions

**Files to create:**

- `application/helpers/subscription_helper.php` (new)

**Helper functions:**

```php
can_user_create_project($user_id)
can_user_access_step($user_id, $step)
can_user_generate_ai($user_id, $context)
get_user_remaining_quota($user_id)
check_and_track_ai_usage($user_id, $type)
```

---

### Phase 3: Controller Integration (2-3 days)

**Tasks:**

- [ ] Add permission checks to Project.php (step access)
- [ ] Add permission checks to Api_project.php (project create)
- [ ] Add quota tracking to API strategy generation
- [ ] Handle Gemini vs Sumopod selection

**Files to modify:**

- `application/controllers/Project.php`
- `application/controllers/Api_project.php`
- `application/models/Ai_strategy_model.php` (pairing logic)

---

### Phase 4: Tracking & Quotas (2 days)

**Tasks:**

- [ ] Create `subscription_usage` table
- [ ] Create usage tracking helper
- [ ] Implement monthly quota reset
- [ ] Add quota logging

**Files to create:**

- Database migration for `subscription_usage` table
- Usage tracking methods in Subscription_model.php

---

### Phase 5: Frontend Integration (1-2 days)

**Tasks:**

- [ ] Show quota remaining in dashboard
- [ ] Display upgrade CTA when limit reached
- [ ] Lock unavailable steps in UI
- [ ] Show error messages clearly

---

## Detailed Implementation for Each Feature

### Feature 1: max_projects

```php
// In Api_project.php
public function create() {
    $user_id = $this->session->userdata('user_id');

    // Load subscription model
    $this->load->model('Subscription_model');

    // Get user's active subscription
    $subscription = $this->subscription->get_user_active_subscription($user_id);

    // Count user's existing projects
    $user_projects = $this->db
        ->where('user_id', $user_id)
        ->count_all_results('projects');

    // Check limit
    if ($user_projects >= $subscription->max_projects) {
        return $this->response([
            'success' => false,
            'error' => 'Project limit reached',
            'limit' => $subscription->max_projects,
            'current' => $user_projects,
            'message' => 'Upgrade plan untuk buat lebih banyak project'
        ], 403);
    }

    // Proceed with create...
}
```

---

### Feature 2: max_ai_generation with Gemini Fallback

```php
// In Ai_strategy_model.php
public function generate_with_quota_check($user_id, $project_id, $ai_type) {
    // Check quota
    $this->load->model('Subscription_model');
    $subscription = $this->subscription->get_user_active_subscription($user_id);

    // If pairing filter, skip quota check
    if ($ai_type === 'pairing_filter') {
        return $this->generate_pairing_filter($project_id); // Use Gemini only
    }

    // Check quota for other AI types
    $usage = $this->subscription->get_user_monthly_usage($user_id);
    if ($usage->ai_count >= $subscription->max_ai_generation) {
        return ['error' => 'Quota exceeded'];
    }

    // Try Gemini first (no quota)
    $result = $this->try_gemini($project_id);

    if ($result->success) {
        // Log but don't count quota
        return $result;
    }

    // If Gemini fails, try Sumopod (counts quota)
    $result = $this->try_sumopod($project_id);

    if ($result->success) {
        // Increment quota counter
        $this->subscription->increment_ai_usage($user_id, $project_id);
    }

    return $result;
}
```

---

### Feature 3: max_step Access Control

```php
// In Project.php index()
public function index() {
    if (!isset($_GET['step'])) {
        // Show list view
        return;
    }

    $step = $_GET['step'];
    $user_id = $this->session->userdata('user_id');

    // Load subscription model
    $this->load->model('Subscription_model');

    // Get user's subscription
    $subscription = $this->subscription->get_user_active_subscription($user_id);

    // Parse allowed steps
    $allowed_steps = $this->parse_max_step($subscription->max_step);

    // Check access
    if (!in_array($step, $allowed_steps)) {
        $highest_allowed = $this->get_highest_allowed_step($allowed_steps);
        redirect(base_url('project?step=' . $highest_allowed . '&key=' . $_GET['key']));
        return;
    }

    // Proceed with normal flow
}

private function parse_max_step($max_step) {
    $step_map = [
        'profile' => 0,
        'swot' => 1,
        'matrix-ie' => 2,
        'strategies' => 3,
        'recommendation' => 4,
        'full' => 999
    ];

    if ($max_step === 'full') {
        return ['profile', 'swot', 'matrix-ie', 'strategies', 'recommendation'];
    }

    // Build list up to max_step
    $max_level = $step_map[$max_step];
    $steps = ['profile', 'swot', 'matrix-ie', 'strategies', 'recommendation'];
    return array_slice($steps, 0, array_search($max_step, $steps) + 1);
}
```

---

## Database Migration Script

```sql
-- migration_subscription_schema_cleanup.sql

-- Step 1: Change max_step to ENUM
ALTER TABLE m_subscription_plans
MODIFY COLUMN max_step ENUM(
    'profile',
    'swot',
    'matrix-ie',
    'strategies',
    'recommendation',
    'full'
) DEFAULT 'full';

-- Step 2: Drop redundant columns
ALTER TABLE m_subscription_plans
DROP COLUMN project_qty,
DROP COLUMN project_api_generate_quota,
DROP COLUMN label;

-- Step 3: Create subscription_usage table
CREATE TABLE IF NOT EXISTS subscription_usage (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    subscription_id INT UNSIGNED NOT NULL,
    billing_period_start DATE,
    billing_period_end DATE,

    ai_generation_count INT DEFAULT 0,
    ai_generation_used JSON DEFAULT '{}',

    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (subscription_id) REFERENCES m_subscription_plans(id),
    UNIQUE KEY (user_id, billing_period_start)
);

-- Step 4: Verify
SELECT COUNT(*) as total_plans FROM m_subscription_plans;
DESCRIBE m_subscription_plans;
```

---

## Testing Plan

### Test 1: Project Limit

```
User with max_projects=1:
  ✅ Create project 1: Success
  ✅ Create project 2: Error "Limit reached"
  ✅ Upgrade plan
  ✅ Create project 2: Success
```

### Test 2: AI Quota

```
User with max_ai_generation=2:
  ✅ Generate AI #1: Success (uses Gemini, no quota)
  ✅ Generate AI #2: Success (uses Gemini, no quota)
  ✅ Generate AI #3: Tries Gemini, fails, uses Sumopod (quota -1)
  ✅ Generate AI #4: Tries Gemini, fails, uses Sumopod (quota -1)
  ✅ Generate AI #5: Error "Quota exceeded"
```

### Test 3: Step Access

```
User with max_step='matrix-ie':
  ✅ Access profile: Allowed
  ✅ Access swot: Allowed
  ✅ Access matrix-ie: Allowed
  ✅ Access strategies: Redirect to matrix-ie
  ✅ Access recommendation: Redirect to matrix-ie
```

---

## Files to Create/Modify

### Create (New Files)

```
application/helpers/subscription_helper.php
database/migrations/migration_subscription_schema_cleanup.sql
```

### Modify (Existing Files)

```
application/controllers/Project.php              (add step access check)
application/controllers/Api_project.php          (add project limit check)
application/models/Subscription_model.php        (add quota methods)
application/models/Ai_strategy_model.php        (add Gemini/Sumopod logic)
```

---

## Priority Order

1. **High Priority** (Foundation)

   - Schema cleanup (remove redundant columns)
   - max_step ENUM conversion
   - Helper functions creation

2. **Medium Priority** (Core Limits)

   - max_projects enforcement
   - max_ai_generation tracking
   - Step access control

3. **Low Priority** (Polish)
   - Quota display in UI
   - Upgrade CTAs
   - Usage notifications

---

## Success Criteria

✅ User with max_projects=1 cannot create 2nd project  
✅ User with max_ai_generation=2 gets quota error on 3rd call  
✅ User cannot access steps beyond their max_step  
✅ Pairing filter calls don't count against quota  
✅ Gemini tried first, Sumopod fallback on error  
✅ All tests pass  
✅ No breaking changes

---

**Next:** Apakah Anda setuju dengan rencana ini? Atau ada adjustments?
