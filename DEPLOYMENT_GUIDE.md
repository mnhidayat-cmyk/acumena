# DEPLOYMENT INSTRUCTIONS

## Pre-Deployment Checklist

- [ ] All changes reviewed and approved
- [ ] Code syntax verified (✅ No errors)
- [ ] Testing plan documented
- [ ] Backup plan verified
- [ ] Database backup taken
- [ ] Team notified of deployment window
- [ ] Maintenance window scheduled (if needed)

---

## Files to Deploy

### 1. application/controllers/Api_project.php

**Status:** ✅ Modified  
**Changes:**

- Added `validate_strategies()` method (lines 584-623)
- Updated `validate_all_strategies_exist()` method (lines 837-880)

**Size:** ~1650 lines (file)  
**Deployment:** Replace entire file

### 2. application/views/projects/matrix-ai.php

**Status:** ✅ Modified  
**Changes:**

- Updated `validateAllStrategiesExist()` function (lines 692-750)
- Updated event listener (line 760 - added await)

**Size:** ~966 lines (file)  
**Deployment:** Replace entire file

---

## Deployment Steps

### Step 1: Prepare Environment

```bash
# Stop application (optional, for zero-downtime skip)
# cd /path/to/app

# Take backup of current files
cp application/controllers/Api_project.php application/controllers/Api_project.php.backup.2025-12
cp application/views/projects/matrix-ai.php application/views/projects/matrix-ai.php.backup.2025-12
```

### Step 2: Deploy New Files

```bash
# Copy new files (using FTP, SCP, or local copy)
# FTP method:
# Upload: Api_project.php → application/controllers/
# Upload: matrix-ai.php → application/views/projects/

# SCP method (if you have SSH access):
scp Api_project.php user@server:/path/to/application/controllers/
scp matrix-ai.php user@server:/path/to/application/views/projects/
```

### Step 3: Clear Cache

```bash
# Clear CodeIgniter cache (if using)
rm -rf application/cache/*

# Clear browser cache notification (in docs/DEPLOYMENT_NOTICE.txt)
# "Please clear browser cache (Ctrl+Shift+Delete) for JavaScript changes"
```

### Step 4: Verify Deployment

```bash
# Check file permissions
ls -l application/controllers/Api_project.php
ls -l application/views/projects/matrix-ai.php

# Should show: -rw-r--r-- (readable by web server)
# If not: chmod 644 application/controllers/Api_project.php
```

### Step 5: Test Basic Functionality

```bash
# Test endpoint is accessible
curl -X POST http://your-domain/api/project/validate-strategies \
  -H "Content-Type: application/json" \
  -d '{"project_uuid":"test-uuid"}'

# Expected: Response (valid or invalid, but no 404/500 errors)
```

---

## Rollback Plan (if needed)

### Quick Rollback (0 downtime)

```bash
# Restore from backup
cp application/controllers/Api_project.php.backup.2025-12 application/controllers/Api_project.php
cp application/views/projects/matrix-ai.php.backup.2025-12 application/views/projects/matrix-ai.php

# Clear cache
rm -rf application/cache/*

# Verify
curl -X POST http://your-domain/api/project/generate-strategic-recommendation \
  -H "Content-Type: application/json"
```

### Verify Rollback Success

- No errors in browser console
- Old behavior restored (if you need to)
- Logs show normal operation

---

## Post-Deployment Testing

### Test 1: Fresh Project Generation

**Duration:** 5 minutes

```
1. Create new test project
2. Fill company profile
3. Add SWOT data
4. Generate SO, ST, WO, WT strategies
5. Click "Generate Final Strategic Recommendation"
Expected: ✅ Modal appears, recommendation generated
```

### Test 2: After Page Refresh

**Duration:** 3 minutes

```
1. Use existing project with all 4 strategies
2. Press F5 (refresh)
3. Click "Generate Final Strategic Recommendation" immediately
Expected: ✅ Works (backend verifies from DB, not DOM)
```

### Test 3: After Logout/Login

**Duration:** 5 minutes

```
1. Generate all 4 strategies
2. Logout completely
3. Close all browser tabs
4. Login again after 2+ minutes
5. Go to project, click "Generate"
Expected: ✅ Works (strategies persisted in DB)
```

### Test 4: Missing Strategy Error

**Duration:** 3 minutes

```
1. Create project with only SO, ST, WO (no WT)
2. Click "Generate Final Strategic Recommendation"
Expected: ❌ Error alert shows missing WT
```

### Test 5: Browser Console Check

**Duration:** 2 minutes

```
1. Open DevTools (F12)
2. Go to Console tab
3. Generate recommendation
4. Check for errors
Expected: ✅ No JavaScript errors, no 404s
```

**Total Testing Time:** ~18 minutes

---

## Monitoring Post-Deployment

### Watch These Metrics (First 24 hours)

#### Server Logs

```bash
# Monitor application logs
tail -f application/logs/log-*.php

# Watch for these errors:
# ❌ Fatal error in Api_project.php
# ❌ SQL syntax error in validate_all_strategies_exist
# ❌ Undefined variable in validateAllStrategiesExist

# ✅ Normal operation should show:
# Database query executed
# Validation result returned
```

#### Database Queries

```bash
# Monitor slow queries
# Watch query time for /api/project/validate-strategies
# Should be < 100ms

# Expected queries:
# SELECT ast.id FROM ai_strategy ... (new method)
# NOT: SELECT * FROM project_ai_generation_run ... (old method)
```

#### User Activity

```
- Monitor user feedback channels
- Watch for "Error: Project not found" reports
- Check project generation completion rates
- Track time to generate recommendation
```

#### Error Tracking

```
- Set up error alerts (if using error tracking)
- Monitor 404s on /api/project/validate-strategies
- Check JavaScript console errors
- Watch for timeout errors
```

---

## Troubleshooting During Deployment

### Issue: File permissions error

```bash
# Symptoms: "Permission denied" when accessing page
# Fix:
chmod 644 application/controllers/Api_project.php
chmod 644 application/views/projects/matrix-ai.php
chown www-data:www-data application/controllers/Api_project.php
chown www-data:www-data application/views/projects/matrix-ai.php
```

### Issue: 404 on /api/project/validate-strategies

```bash
# Symptoms: "404 Not Found" error
# Cause: Routes not properly configured
# Verify: application/config/routes.php has:
# $route['api/project/(:any)'] = 'api_project/$1';
# Note: This route MUST come after more specific routes
```

### Issue: JavaScript errors in console

```bash
# Symptoms: "validateAllStrategiesExist is not a function"
# Cause: Old cache or file not deployed properly
# Fix:
# 1. Hard refresh: Ctrl+Shift+R (or Cmd+Shift+R on Mac)
# 2. Clear cache: Ctrl+Shift+Delete → Clear all
# 3. Close all tabs and reopen
# 4. Check: curl /api/project/validate-strategies responds
```

### Issue: Database query errors

```bash
# Symptoms: "Invalid column name" or "Unknown table"
# Cause: Column/table name mismatch
# Verify in database:
SELECT * FROM information_schema.COLUMNS
WHERE TABLE_NAME = 'ai_strategy';

# Expected columns:
# - id, run_id, project_id, pair_type, strategy_statement, etc.
```

---

## Database Schema Verification

**IMPORTANT:** No database migration needed, but verify these tables exist:

```sql
-- Table 1: ai_strategy (must exist)
DESCRIBE ai_strategy;
-- Expected columns: id, run_id, project_id, pair_type, strategy_statement, ...

-- Table 2: project_ai_generation_run (must exist)
DESCRIBE project_ai_generation_run;
-- Expected columns: id, project_id, pair_type, is_active, archived_at, ...

-- Table 3: projects (must exist)
DESCRIBE projects;
-- Expected columns: id, uuid, company_name, created_by_user_id, ...
```

**If any table is missing, deployment will fail. Stop and investigate.**

---

## Success Criteria

✅ **Deployment successful when:**

- [ ] No file permission errors
- [ ] No JavaScript console errors
- [ ] /api/project/validate-strategies endpoint responds
- [ ] Test Scenario 1 (Fresh generation) passes
- [ ] Test Scenario 2 (Page refresh) passes
- [ ] Test Scenario 3 (Logout/login) passes
- [ ] Test Scenario 4 (Missing strategy) shows correct error
- [ ] Test Scenario 5 (Console check) shows no errors
- [ ] Server logs show normal operation
- [ ] No performance degradation

❌ **Rollback if:**

- Critical errors in logs
- 404 on new endpoint
- JavaScript console has red errors
- Database connection issues
- Performance significantly degraded (>5x slower)

---

## Communication Plan

### Before Deployment

```
Subject: System Maintenance - Strategy Validation Update
Timeline: [Date] [Time] - [Estimated 5-10 minutes]
Impact: No downtime expected, validation improved
Action: Clear browser cache after deployment
```

### During Deployment

```
Status: "Deploying validation improvements..."
Estimated: "5-10 minutes"
Support: "Contact support if issues arise"
```

### After Deployment

```
Status: "Deployment complete!"
What changed: "Generate Recommendation now works after page refresh"
Testing: "All systems operational"
Users: "No action needed, clear cache if needed"
```

---

## Sign-Off

**Deployment Engineer:** ********\_\_\_********  
**Date/Time:** ********\_\_\_********  
**Approved By:** ********\_\_\_********  
**Duration:** ********\_\_\_********  
**Issues Encountered:** ********\_\_\_********  
**Resolution:** ********\_\_\_********  
**All Tests Passed:** [ ] Yes [ ] No

**If NO, describe issues:**

```
1. ___________________
2. ___________________
3. ___________________
```

**Rollback Status:** [ ] Not needed [ ] Executed

---

## References

- IMPROVED_VALIDATION_FIX.md - Full technical documentation
- CODE_CHANGES_SUMMARY.md - Exact code changes
- TESTING_GUIDE.md - Comprehensive test scenarios
- QUICK_FIX_SUMMARY.md - Quick reference
- FIX_COMPLETE_SUMMARY.md - Executive summary

---

## Emergency Contacts

**Primary:** [Technical Lead Phone/Email]  
**Secondary:** [DevOps Engineer Phone/Email]  
**Database:** [DBA Phone/Email]  
**On-Call:** [On-call Support Phone/Email]

---

## Deployment Complete ✅

Once all steps completed and verified, mark as deployed.

**Deployment Date/Time:** ********\_\_\_********  
**Verified By:** ********\_\_\_********  
**Notes:** ********\_\_\_********
