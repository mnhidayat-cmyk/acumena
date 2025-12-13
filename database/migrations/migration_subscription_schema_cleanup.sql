-- migration_subscription_schema_cleanup.sql
-- Created: 2025-12-13
-- Purpose: Schema cleanup, ENUM conversion, column removal

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================================
-- Step 0: Update existing data to match ENUM values
-- ============================================================================
-- Fix any incorrect max_step values - map old to new values
-- Old data: profile, swot, matrix-ife, matrix-efe, matrix-ie, matrix-ai
-- New ENUM: profile, swot, matrix-ife, matrix-efe, matrix-ie, matrix-ai, full
UPDATE m_subscription_plans SET max_step = 'full' WHERE max_step IS NULL;
UPDATE m_subscription_plans SET max_step = 'full' WHERE max_step = 'full';

-- ============================================================================
-- Step 1: Change max_step to ENUM with correct step values
-- ============================================================================
-- Steps are: profile(1), swot(2), matrix-ife(3), matrix-efe(4), matrix-ie(5), matrix-ai(6), full(all)
ALTER TABLE m_subscription_plans 
MODIFY COLUMN max_step ENUM(
    'profile',
    'swot',
    'matrix-ife',
    'matrix-efe',
    'matrix-ie',
    'matrix-ai',
    'full'
) DEFAULT 'full' COMMENT 'Max workflow step: profile(1) -> swot(2) -> matrix-ife(3) -> matrix-efe(4) -> matrix-ie(5) -> matrix-ai(6) -> full(all)';

-- ============================================================================
-- Step 2: Drop redundant columns
-- ============================================================================
-- These columns are duplicates of the new limit columns
ALTER TABLE m_subscription_plans 
DROP COLUMN project_qty,
DROP COLUMN project_api_generate_quota,
DROP COLUMN label;

-- ============================================================================
-- Step 3: Verify changes
-- ============================================================================
SELECT 'Migration completed!' as status;

SELECT 'Table structure after cleanup:' as info;
DESCRIBE m_subscription_plans;

SELECT 'Existing plans after migration:' as info;
SELECT id, name, max_step, max_projects, max_ai_generation FROM m_subscription_plans;

SET FOREIGN_KEY_CHECKS = 1;
