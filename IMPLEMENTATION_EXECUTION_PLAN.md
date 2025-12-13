# 📋 SUBSCRIPTION LIMITS IMPLEMENTATION - STEP BY STEP EXECUTION

**Date:** December 13, 2025  
**Total Steps:** 5 Phases  
**Estimated Duration:** 5-6 hours

---

## Correct Step Sequence (Updated)

Based on actual view files in `/application/views/projects/`:

```
Step 1: profile         → /project?step=profile
Step 2: swot            → /project?step=swot&key={uuid}
Step 3: matrix-ife      → /project?step=matrix-ife&key={uuid}
Step 4: matrix-efe      → /project?step=matrix-efe&key={uuid}
Step 5: matrix-ai       → /project?step=matrix-ai&key={uuid}
         ├─ Strategies Generate
         └─ Strategic Recommendation (same page)

Full Access: all above steps
```

---

## Phase 1: Schema Cleanup & ENUM Conversion

### Step 1.1: Create Migration Script

**File:** `database/migrations/migration_subscription_schema_cleanup.sql`

SQL Commands:

1. Change max_step to ENUM with correct values
2. Drop redundant columns (project_qty, project_api_generate_quota, label)
3. Verify changes

### Step 1.2: Execute Migration

- Run migration script
- Verify table structure
- Update SQL dumps

### Step 1.3: Update Subscription_model.php

- Remove references to dropped columns
- Update queries if needed

---

## Phase 2: Create Helper Functions

### Step 2.1: Create subscription_helper.php

**File:** `application/helpers/subscription_helper.php`

Functions:

- `can_user_create_project($user_id)`
- `can_user_access_step($user_id, $step)`
- `get_user_remaining_quota($user_id)`
- `can_user_generate_ai($user_id, $project_id)`
- `increment_ai_usage($user_id, $project_id, $type)`

### Step 2.2: Create subscription_usage table

**File:** `database/migrations/migration_create_subscription_usage_table.sql`

Table structure:

- user_id, subscription_id
- billing_period_start, billing_period_end
- ai_generation_count, ai_generation_used (JSON)
- timestamps

---

## Phase 3: Controller Integration

### Step 3.1: Update Project.php

- Add step access validation in index() method
- Prevent access to steps beyond max_step
- Redirect to highest allowed step

### Step 3.2: Update Api_project.php

- Add project creation limit check in create() method
- Check before allowing project creation

### Step 3.3: Update Ai_strategy_model.php

- Separate pairing_filter logic (no quota)
- Try Gemini first for other AI calls
- Fallback to Sumopod with quota increment
- Check quota before generating

---

## Phase 4: Usage Tracking

### Step 4.1: Add Tracking Methods

- Track AI generation usage
- Monthly quota reset logic
- Get remaining quota

---

## Phase 5: Frontend Updates

### Step 5.1: Display Quota Info

- Show remaining quota in dashboard
- Show step restrictions

---

## EXECUTION ORDER

1️⃣ Phase 1: Schema (30 min)
2️⃣ Phase 2: Helpers (60 min)
3️⃣ Phase 3: Controllers (120 min)
4️⃣ Phase 4: Tracking (45 min)
5️⃣ Phase 5: Frontend (30 min)

**Total: ~6 hours**

---

**Proceed with execution?**
