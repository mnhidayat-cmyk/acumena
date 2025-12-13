# 🚀 Subscription System Migration - Quick Reference

## What Changed?

| Old                   | New                    | Purpose                   |
| --------------------- | ---------------------- | ------------------------- |
| `subscriptions` table | `m_subscription_plans` | Follow master data naming |
| Fixed column types    | Enhanced precision     | Better data handling      |
| Limited features      | 7 new columns          | Feature control & quotas  |

---

## New Table: `m_subscription_plans`

### Core Columns (Existing)

```
id                              → Subscription plan ID
name                           → Plan name (Trial, Pro, Enterprise)
project_qty                    → Projects allowed (legacy)
max_step                       → Max workflow step (legacy)
project_api_generate_quota     → AI generation quota (legacy)
price_monthly / price_yearly   → Pricing
label                          → Plan label
date_created / last_update     → Timestamps
is_deleted                     → Soft delete flag
```

### Enhanced Columns (NEW)

```
max_projects              → Max projects user can create
max_ai_generation         → Max AI generations per month
max_ai_per_project        → Max per project (NULL = unlimited)
max_team_members          → Max team members for plan
enable_export             → Allow PDF/Excel export
enable_api_access         → Allow API endpoint access
enable_custom_branding    → Allow custom domain/logo
```

---

## Sample Plans Configuration

### Free Plan

```sql
UPDATE m_subscription_plans SET
    max_projects = 1,
    max_ai_generation = 2,
    max_team_members = 1,
    enable_export = 0,
    enable_api_access = 0
WHERE name = 'Trial';
```

### Pro Plan

```sql
UPDATE m_subscription_plans SET
    max_projects = 5,
    max_ai_generation = 10,
    max_ai_per_project = 3,
    max_team_members = 3,
    enable_export = 1,
    enable_api_access = 1
WHERE name = 'Pro Plan';
```

### Enterprise Plan

```sql
UPDATE m_subscription_plans SET
    max_projects = 999,
    max_ai_generation = 999,
    max_ai_per_project = NULL,
    max_team_members = 999,
    enable_export = 1,
    enable_api_access = 1,
    enable_custom_branding = 1
WHERE name = 'Enterprise';
```

---

## Code Examples

### Get All Plans

```php
$CI->load->model('Subscription_model');
$plans = $CI->subscription->get_all();

foreach ($plans as $plan) {
    echo $plan->name . ': ' . $plan->max_projects . ' projects';
}
```

### Get Specific Plan

```php
$plan = $CI->subscription->get_subscription(1);
echo "Max AI Generations: " . $plan->max_ai_generation;
```

### Get User's Active Subscription

```php
$subscription = $CI->subscription->get_user_active_subscription($user_id);
echo "Plan: " . $subscription->subscription_name;
echo "Max Projects: " . $subscription->max_projects;
```

### Check User Quota

```php
$subscription = $CI->subscription->get_user_active_subscription($user_id);

// Check project limit
if ($user_projects >= $subscription->max_projects) {
    echo "Cannot create more projects";
}

// Check AI quota
if ($user_ai_generations >= $subscription->max_ai_generation) {
    echo "Cannot generate more AI this month";
}

// Check feature access
if (!$subscription->enable_export) {
    echo "Export feature not available for this plan";
}
```

---

## Database Queries

### Get All Active Plans

```sql
SELECT * FROM m_subscription_plans
WHERE is_deleted IS NULL;
```

### Get Plan with Usage Info

```sql
SELECT
    msp.*,
    COUNT(p.id) as project_count
FROM m_subscription_plans msp
LEFT JOIN projects p ON p.subscription_id = msp.id
WHERE msp.is_deleted IS NULL
GROUP BY msp.id;
```

### Get User's Plan Details

```sql
SELECT
    us.user_id,
    msp.name as plan_name,
    msp.max_projects,
    msp.max_ai_generation,
    msp.enable_export,
    COUNT(p.id) as current_projects
FROM user_subscription us
JOIN m_subscription_plans msp ON us.subscription_id = msp.id
LEFT JOIN projects p ON p.user_id = us.user_id
WHERE us.user_id = 1
GROUP BY us.user_id;
```

---

## Related Tables

### `user_subscription`

Stores current plan for each user

```
user_id → subscription_id (links to m_subscription_plans)
```

### `user_subscription_history`

Tracks billing history

```
user_id → subscription_id (links to m_subscription_plans)
```

---

## Migration Files

### Migration Script

📍 `database/migrations/migration_subscriptions_table_rename.sql`

- Executes all steps automatically
- Includes verification queries
- Safe to run multiple times (IGNORE duplicates)

### Backup

📍 `backups/acumena_backup_20251213_104708.sql`

- Full database backup before migration
- Created: Dec 13, 2025, 10:47 UTC
- Retention: 30 days

---

## API Usage (Controllers)

### Subscription Controller

```php
// Already uses m_subscription_plans through Subscription_model
$this->load->model('Subscription_model');
$plans = $this->subscription->get_all();
```

### In Any Controller

```php
// Get current user's plan
$user_plan = $this->subscription->get_user_active_subscription(
    $this->session->userdata('user_id')
);

// Check feature access
if ($user_plan->enable_api_access) {
    // Allow API access
}
```

---

## Testing Queries

```sql
-- Verify table exists
SHOW TABLES LIKE 'm_subscription_plans';

-- Check data migrated
SELECT COUNT(*) FROM m_subscription_plans;

-- Verify JOINs work
SELECT us.user_id, msp.name, msp.max_projects
FROM user_subscription us
JOIN m_subscription_plans msp ON us.subscription_id = msp.id;

-- Check new columns exist
DESCRIBE m_subscription_plans;
```

---

## Troubleshooting

### "Table m_subscription_plans not found"

✅ **Fix:** Run migration script

```bash
mysql -h localhost -u root acumena < migration_subscriptions_table_rename.sql
```

### "Unknown column 'subscriptions.name'"

✅ **Fix:** Update code to use `m_subscription_plans`

- In PHP: Update model queries
- In SQL: Change table reference

### Queries return NULL for new columns

✅ **Normal:** New columns have defaults (max_ai_generation=0 etc)

- Update values: `UPDATE m_subscription_plans SET max_ai_generation = 10 WHERE id = 2;`

### Old table still needed

✅ **Available:** `subscriptions_backup_2025_12_13` (30 days retention)

- Read-only reference
- Will be deleted after 30 days

---

## Timeline

| Date             | Action             | Status |
| ---------------- | ------------------ | ------ |
| 2025-12-13 10:47 | Backup created     | ✅     |
| 2025-12-13 10:50 | Migration executed | ✅     |
| 2025-12-13 10:55 | Code updated       | ✅     |
| 2025-12-13 11:00 | Tests verified     | ✅     |
| 2025-12-13 11:30 | Documentation done | ✅     |

---

## Support

**Questions?** Check:

1. `CHANGELOG_SUBSCRIPTION_REFACTOR.md` - Detailed changes
2. `MIGRATION_EXECUTION_REPORT.md` - Execution details
3. This file - Quick reference

**Issues?**

- Check error logs: `application/logs/`
- Verify backup: `backups/` directory
- Rollback if needed: See CHANGELOG for instructions

---

**Version:** 1.0  
**Last Updated:** 2025-12-13  
**Status:** ✅ Production Ready
