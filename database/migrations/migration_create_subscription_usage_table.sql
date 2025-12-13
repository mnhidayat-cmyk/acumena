-- migration_create_subscription_usage_table.sql
-- Created: 2025-12-13
-- Purpose: Create subscription_usage table for quota tracking

-- ============================================================================
-- Step 1: Create subscription_usage table
-- ============================================================================
CREATE TABLE IF NOT EXISTS subscription_usage (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    project_id INT UNSIGNED,
    usage_type ENUM('strategy', 'recommendation', 'custom') DEFAULT 'strategy',
    api_provider ENUM('gemini', 'sumopod') DEFAULT 'gemini' COMMENT 'Which API was used',
    quota_impact TINYINT(1) DEFAULT 0 COMMENT '1 if counted against quota, 0 if free',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_user_month (user_id, created_at),
    INDEX idx_project (project_id),
    INDEX idx_provider (api_provider),
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tracks AI generation usage for subscription quota management';

-- ============================================================================
-- Step 2: Verify table creation
-- ============================================================================
SELECT 'Subscription usage table created successfully!' as status;
DESCRIBE subscription_usage;
