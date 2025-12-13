# ✅ SUBSCRIPTION LIMITS STATUS REPORT

**Date:** December 13, 2025  
**Status:** ❌ **NOT YET IMPLEMENTED**

---

## Current Implementation Status

### ❌ Permission Checks - NOT Implemented

```
max_projects           → No enforcement in controllers
max_ai_generation      → No quota tracking
max_ai_per_project     → No per-project tracking
max_step               → No step access control
```

### ✅ Database Ready

```
Table: m_subscription_plans ✅
  - 20 columns (13 original + 7 new)
  - All limit columns exist
  - Data migrated: 2/2 ✅

Backup available ✅
  - subscriptions_backup_2025_12_13
  - 30-day retention
```

### ⚠️ Schema Issues to Fix

```
Column issues:
  ❌ max_step is VARCHAR (should be ENUM)
  ❌ project_qty (redundant, use max_projects)
  ❌ project_api_generate_quota (redundant, use max_ai_generation)
  ❌ label (unused)
```

---

## Column Analysis - What to Keep/Remove

### Current Columns (18 total)

**KEEP - Core Plan Info:**

```
✅ id                      → Primary key
✅ name                    → Plan name (Trial, Pro, Enterprise)
✅ price_monthly           → Pricing (used)
✅ price_yearly            → Pricing (used)
✅ date_created            → Metadata
✅ last_update             → Metadata
✅ is_deleted              → Soft delete
```

**KEEP - Limit Columns (NEW):**

```
✅ max_projects            → NEW: Project creation limit
✅ max_ai_generation       → NEW: Monthly AI quota
✅ max_ai_per_project      → NEW: Per-project AI limit
✅ max_step                → CONVERT TO ENUM
✅ max_team_members        → NEW: Team size limit
✅ enable_export           → NEW: Feature flag
✅ enable_api_access       → NEW: Feature flag
✅ enable_custom_branding  → NEW: Feature flag
```

**REMOVE - Redundant:**

```
❌ project_qty             → DUPLICATE of max_projects (not used)
❌ project_api_generate_quota → DUPLICATE of max_ai_generation (not used)
❌ label                   → UNUSED (not in any query or view)
```

---

## Schema Cleanup Required

### Current (18 columns)

```
id, name, project_qty❌, max_step⚠️, project_api_generate_quota❌,
price_monthly, price_yearly, label❌, date_created, last_update,
is_deleted, max_projects, max_ai_generation, max_ai_per_project,
max_team_members, enable_export, enable_api_access, enable_custom_branding
```

### After Cleanup (15 columns)

```
id, name, max_step✅(ENUM), price_monthly, price_yearly, date_created, last_update,
is_deleted, max_projects, max_ai_generation, max_ai_per_project,
max_team_members, enable_export, enable_api_access, enable_custom_branding
```

**Savings:** Remove 3 unused columns

---

## Implementation Checklist

### Phase 1: Schema Cleanup (1 day)

- [ ] Create migration script
- [ ] Change max_step to ENUM with 6 values:
  - 'profile' → Step 1
  - 'swot' → Step 2
  - 'matrix-ie' → Step 3
  - 'strategies' → Step 4
  - 'recommendation' → Step 5
  - 'full' → All steps
- [ ] Drop project_qty column
- [ ] Drop project_api_generate_quota column
- [ ] Drop label column
- [ ] Update SQL dumps (acumena.sql, acumena (1).sql)
- [ ] Verify migration

### Phase 2: Helper Functions (1 day)

- [ ] Create subscription_helper.php with:
  - `can_user_create_project($user_id)`
  - `can_user_access_step($user_id, $step)`
  - `can_user_generate_ai($user_id, $project_id)`
  - `get_user_remaining_quota($user_id)`
  - `increment_ai_usage($user_id, $project_id)`

### Phase 3: Controller Integration (2 days)

- [ ] Project.php:
  - Add step access validation in index()
  - Prevent access to restricted steps
  - Redirect to highest allowed step
- [ ] Api_project.php:
  - Add project creation limit check
  - Add AI generation quota check
  - Implement Gemini/Sumopod fallback logic
- [ ] Ai_strategy_model.php:
  - Separate pairing_filter logic (no quota)
  - Try Gemini first
  - Fallback to Sumopod if error

### Phase 4: Usage Tracking (1 day)

- [ ] Create subscription_usage table
- [ ] Add quota tracking methods
- [ ] Implement monthly reset

### Phase 5: Frontend (1 day)

- [ ] Display remaining quota
- [ ] Show upgrade CTA when limit reached
- [ ] Lock unavailable steps

---

## Gemini vs Sumopod Logic

**Current Flow (to be implemented):**

```
Step: Pairing Filter
  └─ Always use Gemini (free)
  └─ Don't count quota
  └─ Error handling: Show error

Step: AI Strategy Generation
  └─ Check max_ai_generation quota
  └─ Check max_ai_per_project quota
  └─ If quota available:
     ├─ Try Gemini first (free, no quota)
     └─ If Gemini fails:
        ├─ Use Sumopod (counts quota)
        └─ Increment usage counter
  └─ If quota exceeded:
     └─ Return error "Upgrade plan"
```

---

## max_step ENUM Mapping

**Proposed Values & Meanings:**

```sql
'profile'         ← Only company profile allowed
'swot'            ← Up to SWOT analysis
'matrix-ie'       ← Up to IE Matrix
'strategies'      ← Up to Strategies generation
'recommendation'  ← Up to Strategic recommendation
'full'            ← All steps available
```

**Access Control Logic:**

```
If user.max_step = 'matrix-ie':
  ✅ Can access: profile, swot, matrix-ie
  ❌ Cannot access: strategies, recommendation
  → Redirect to: matrix-ie (highest allowed)
```

---

## Impact Analysis

### ❌ Breaking Changes

None! All changes:

- Add new enforcement (non-breaking)
- Remove redundant columns (never used)
- Add helper functions (non-breaking)

### ✅ Backward Compatibility

```
Old Code: SELECT * FROM m_subscription_plans
  → Still works (columns just different)

Existing Queries: WHERE project_qty > 0
  → Need update to: WHERE max_projects > 0

Existing Queries: project_api_generate_quota
  → Need update to: max_ai_generation
```

### 🔄 Data Migration

```
project_qty → max_projects (already done in migration)
project_api_generate_quota → max_ai_generation (already done)
label → (just drop, never used)
```

---

## Testing Points

### Test max_projects

```
Plan: Free (max_projects=1)
User creates:
  ✅ Project 1: Success
  ❌ Project 2: Error "Limit reached (1/1)"

After upgrade to Pro (max_projects=5):
  ✅ Project 2: Success
  ✅ Project 3: Success
  ❌ Project 6: Error "Limit reached (5/5)"
```

### Test max_ai_generation

```
Plan: Free (max_ai_generation=2)
User generates:
  ✅ AI Gen #1: Uses Gemini (quota: 2)
  ✅ AI Gen #2: Uses Gemini (quota: 2)
  ✅ AI Gen #3: Tries Gemini → fails → Sumopod (quota: 1)
  ✅ AI Gen #4: Tries Gemini → fails → Sumopod (quota: 0)
  ❌ AI Gen #5: Error "Quota exceeded (2/2)"
```

### Test max_step

```
Plan: Trial (max_step='matrix-ie')
User tries:
  ✅ GET /project?step=profile: Allowed
  ✅ GET /project?step=swot: Allowed
  ✅ GET /project?step=matrix-ie: Allowed
  🔄 GET /project?step=strategies: Redirect to matrix-ie
  🔄 GET /project?step=recommendation: Redirect to matrix-ie

Plan: Pro (max_step='full')
User tries:
  ✅ All steps: Allowed
```

### Test Gemini Fallback

```
API Call: /api/project/generate-strategies

Scenario 1 - Gemini works:
  Gemini: Success → return result (no quota used)

Scenario 2 - Gemini fails:
  Gemini: Error
  Sumopod: Success → return result (quota: -1)

Scenario 3 - Both fail:
  Gemini: Error
  Sumopod: Error → return error message
```

---

## Quick Reference - Implementation Order

**Day 1: Schema**

1. Create migration: rename ENUM, drop columns
2. Update models
3. Update SQL dumps

**Day 2: Helpers** 4. Create subscription_helper.php 5. Write unit tests

**Day 3-4: Controllers** 6. Update Project.php (step access) 7. Update Api_project.php (project create) 8. Update Ai_strategy_model.php (Gemini/Sumopod)

**Day 5: Usage** 9. Create subscription_usage table 10. Add tracking methods

**Day 6: Frontend** 11. Display quotas 12. Add error messages

---

## Files to Create/Modify

### Create

```
application/helpers/subscription_helper.php
database/migrations/migration_subscription_schema_cleanup.sql
```

### Modify

```
application/controllers/Project.php
application/controllers/Api_project.php
application/models/Subscription_model.php
application/models/Ai_strategy_model.php
acumena.sql
acumena (1).sql
```

---

## Summary

**Current Status:**

- ❌ No limits enforced
- ✅ Database ready
- ⚠️ Schema needs cleanup (3 columns to remove)

**To Do:**

- 🔧 Fix schema (change max_step to ENUM, remove redundant columns)
- ✅ Create helper functions
- ✅ Add controller checks
- ✅ Implement quota tracking
- ✅ Add UI indicators

**Total Effort:** ~5-6 days for full implementation

---

**Ready to proceed with implementation?** ✅
