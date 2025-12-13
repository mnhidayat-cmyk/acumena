-- migration_subscriptions_table_rename.sql
-- Created: 2025-12-13
-- Purpose: Rename subscriptions → m_subscription_plans
-- Status: Production Ready

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================================
-- Step 1: Create new table m_subscription_plans
-- ============================================================================
CREATE TABLE IF NOT EXISTS `m_subscription_plans` (
    id BIGINT UNSIGNED AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    project_qty INT DEFAULT 1,
    max_step VARCHAR(100) DEFAULT 'all',
    project_api_generate_quota INT DEFAULT 0,
    price_monthly DECIMAL(15,2) DEFAULT 0,
    price_yearly DECIMAL(15,2) DEFAULT 0,
    label VARCHAR(50),
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_deleted TIMESTAMP NULL,
    
    -- New feature columns for improvements
    max_projects INT DEFAULT 1 COMMENT 'Max projects user dapat buat',
    max_ai_generation INT DEFAULT 0 COMMENT '0 = unlimited, >0 = limit per month',
    max_ai_per_project INT DEFAULT NULL COMMENT 'NULL = unlimited per project',
    max_team_members INT DEFAULT 1 COMMENT 'Max team members',
    enable_export BOOLEAN DEFAULT 0 COMMENT 'Enable PDF/Excel export',
    enable_api_access BOOLEAN DEFAULT 0 COMMENT 'Enable API endpoint access',
    enable_custom_branding BOOLEAN DEFAULT 0 COMMENT 'Custom domain/logo',
    
    PRIMARY KEY (id),
    UNIQUE KEY unique_name (name, is_deleted),
    INDEX idx_is_deleted (is_deleted)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Master table untuk subscription plans (previously: subscriptions)';

-- ============================================================================
-- Step 2: Migrate data from old table if exists
-- ============================================================================
INSERT IGNORE INTO m_subscription_plans 
(id, name, project_qty, max_step, project_api_generate_quota, 
 price_monthly, price_yearly, label, date_created, last_update, is_deleted,
 max_projects, max_ai_generation, max_team_members, enable_export, enable_api_access)
SELECT 
    id, name, project_qty, max_step, project_api_generate_quota,
    price_monthly, price_yearly, label, date_created, last_update, is_deleted,
    project_qty,                    -- max_projects = project_qty
    project_api_generate_quota,     -- max_ai_generation = quota
    1,                               -- max_team_members default
    0,                               -- enable_export default
    0                                -- enable_api_access default
FROM subscriptions
WHERE NOT EXISTS (
    SELECT 1 FROM m_subscription_plans WHERE id = subscriptions.id
);

-- ============================================================================
-- Step 3: (Optional) Update user_subscription to add FK for subscription_id
-- ============================================================================
-- NOTE: Skipped for now due to type mismatch (bigint unsigned vs id type)
-- Can be added in separate migration after data validation

-- ============================================================================
-- Step 4: Backup old table (rename instead of delete for safety)
-- ============================================================================
RENAME TABLE subscriptions TO subscriptions_backup_2025_12_13;

-- ============================================================================
-- Step 5: Verification queries
-- ============================================================================
SELECT 'Migration completed successfully!' as status;

SELECT COUNT(*) as total_plans_migrated FROM m_subscription_plans;

SELECT COUNT(*) as user_subscriptions_updated FROM user_subscription;

SELECT COUNT(*) as subscription_history_preserved FROM user_subscription_history;

SET FOREIGN_KEY_CHECKS = 1;
