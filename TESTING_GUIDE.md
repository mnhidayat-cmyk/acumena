# Testing Guide: Improved Strategy Validation

## Pre-Testing Setup

### Requirements

- Local development environment running (Laragon)
- Database with test project
- User logged in
- Browser DevTools access for debugging

### Initial Conditions

1. Ensure you have a test project with multiple strategies already generated
2. Have browser console open to monitor API calls and errors
3. Keep application logs open to check backend validation

---

## Test Scenario 1: Fresh Generation (New Project)

### Steps

1. Create a new project (Company Profile step)
2. Complete SWOT Analysis (add strengths, weaknesses, opportunities, threats)
3. Navigate to Matrix AI Integration page
4. Generate all 4 strategies:
   - Click "Generate SO Strategies" and wait for completion
   - Click "Generate ST Strategies" and wait for completion
   - Click "Generate WO Strategies" and wait for completion
   - Click "Generate WT Strategies" and wait for completion
5. Scroll to "Generate Final Strategic Recommendation" button
6. Click the button

### Expected Results

✅ **Button click succeeds**

- No error alert appears
- "Analyzing..." loading state shows
- Modal displays with final recommendation content
- Recommendation saved to database

### Debugging

```javascript
// In browser console:
localStorage.getItem("projectId"); // Verify project ID
document.querySelectorAll(".strategy-item").length; // Count visible strategies
// Check Network tab for: /api/project/validate-strategies and /api/project/generate-strategic-recommendation
```

---

## Test Scenario 2: Persisted Strategies (Previous Session)

### Setup

1. Ensure you have a project with all 4 strategies already generated
2. Note the project UUID
3. Log out completely (clear all sessions)
4. Clear browser local storage and cache
5. Log back in

### Steps

1. Navigate to the previously created project
2. Go to Matrix AI Integration step
3. Observe: Strategy containers initially appear empty
4. Scroll down to "Generate Final Strategic Recommendation" button
5. Click the button immediately (don't wait for strategies to load)

### Expected Results

✅ **Validation succeeds despite empty DOM**

- Backend check confirms strategies exist in database
- Modal appears with recommendation
- No error about missing strategies

### Debugging

```javascript
// In browser console during test:
// Should see both requests:
console.log("Frontend DOM check: checking visible strategies");
// Then:
console.log("Backend validation: querying database");

// Check Network tab for:
// POST /api/project/validate-strategies response: {"valid": true}
// POST /api/project/generate-strategic-recommendation response: {success: true, ...}
```

---

## Test Scenario 3: Partial Strategies (Missing Some)

### Setup

1. Create new test project
2. Generate ONLY SO and ST strategies
3. Do NOT generate WO and WT strategies

### Steps

1. Navigate to Matrix AI Integration page
2. You should see SO and ST strategies displayed
3. Click "Generate Final Strategic Recommendation" button

### Expected Results

❌ **Validation fails as expected**

- Error alert appears with message:
  ```
  Semua 4 strategi (SO, ST, WO, WT) harus ada sebelum generate recommendation.
  Strategi yang belum ada: WO Strategies, WT Strategies
  ```
- Modal does NOT appear
- Button returns to normal state

### Debugging

```javascript
// In browser console:
// Should see console.log from validateAllStrategiesExist():
document.querySelectorAll(".strategy-item").length; // Should be 2 (SO + ST)

// Check Network tab:
// POST /api/project/validate-strategies response:
// {
//   "valid": false,
//   "message": "Semua 4 strategi...",
//   "missing": ["W-O", "W-T"]
// }
```

---

## Test Scenario 4: Page Refresh After Generation

### Steps

1. Create project and generate all 4 strategies (as in Scenario 1)
2. Click "Generate Final Strategic Recommendation" and verify it works
3. Press F5 (refresh page)
4. Wait for page to load
5. Strategy containers will appear empty
6. Click "Generate Final Strategic Recommendation" again

### Expected Results

✅ **Works after refresh**

- Validation succeeds (backend finds strategies)
- Recommendation generated successfully
- DOM empty initially doesn't cause failure

### Debugging

```javascript
// In browser console:
// Before page load completion:
document.querySelectorAll(".strategy-item").length; // = 0

// After validation:
// Network tab shows /api/project/validate-strategies returns {"valid": true}
```

---

## Test Scenario 5: Logout/Login Cycle

### Steps

1. Create project with all 4 strategies
2. Log out completely
3. Close browser tab/window
4. Wait 5+ minutes (to ensure session expiry)
5. Log back in
6. Navigate to same project
7. Go to Matrix AI Integration page
8. Click "Generate Final Strategic Recommendation"

### Expected Results

✅ **Works across login sessions**

- Strategies found in database
- Validation succeeds
- Recommendation generated

### Debugging

```javascript
// Verify session change:
console.log(document.cookie); // Check session ID changed

// Verify backend receives correct user_id:
// Check Network tab response for /api/project/validate-strategies
// Should include correct project_id ownership
```

---

## Test Scenario 6: API Error Handling

### Steps

1. Open browser DevTools Network tab
2. Go to Matrix AI Integration page with valid project + all 4 strategies
3. Right-click on a pending request, select "Block request domain"
4. Block all requests to `/api/project/` temporarily
5. Click "Generate Final Strategic Recommendation"

### Expected Results

⚠️ **Graceful degradation**

- Frontend validation (DOM check) still passes
- Backend validation call fails (network blocked)
- Console shows warning: "Backend validation call failed, but DOM check passed"
- User can decide to proceed or not (depending on error handling)

### Debugging

```javascript
// In browser console:
// Should see warning message:
console.warn("Backend validation call failed, but DOM check passed:", error);
```

---

## Test Scenario 7: Invalid Project UUID

### Steps

1. Go to Matrix AI Integration page with valid project
2. Manually edit URL query parameter: `?uuid=invalid-uuid-123`
3. Refresh page
4. Try to click "Generate Final Strategic Recommendation"

### Expected Results

❌ **Validation fails appropriately**

- Error response from backend
- Message: "Project not found or access denied"
- Modal does NOT appear

### Debugging

```javascript
// In Network tab, check /api/project/validate-strategies response:
// {
//   "valid": false,
//   "message": "Project not found or access denied"
// }
```

---

## Test Scenario 8: Concurrent Generation Attempts

### Steps

1. Project with all 4 strategies ready
2. Click "Generate Final Strategic Recommendation"
3. Before modal appears, click button again (double-click)

### Expected Results

✅ **Prevents duplicate requests**

- Button is disabled during first request
- Double-click ignored
- Only one recommendation generated

### Debugging

```javascript
// Check button state in HTML:
document.getElementById("generateRecommendationsBtn").disabled; // Should be true during request
```

---

## Automated Test Cases (PHP Unit)

### Test 1: Valid Strategies

```php
public function test_validate_all_strategies_exist_with_valid_data() {
    $project_id = $this->create_test_project();
    $this->create_test_strategies($project_id, ['S-O', 'S-T', 'W-O', 'W-T']);

    $result = $this->api_project->validate_all_strategies_exist($project_id);

    $this->assertTrue($result['valid']);
    $this->assertEmpty($result['missing'] ?? []);
}
```

### Test 2: Missing Strategies

```php
public function test_validate_all_strategies_exist_with_missing() {
    $project_id = $this->create_test_project();
    $this->create_test_strategies($project_id, ['S-O']);  // Only SO

    $result = $this->api_project->validate_all_strategies_exist($project_id);

    $this->assertFalse($result['valid']);
    $this->assertCount(3, $result['missing']);  // S-T, W-O, W-T missing
}
```

### Test 3: API Endpoint

```php
public function test_validate_strategies_endpoint() {
    $project_id = $this->create_test_project();
    $this->create_test_strategies($project_id, ['S-O', 'S-T', 'W-O', 'W-T']);
    $uuid = $this->get_project_uuid($project_id);

    $response = $this->post('/api/project/validate-strategies', [
        'project_uuid' => $uuid
    ]);

    $this->assertEquals(200, $response['status']);
    $this->assertTrue($response['json']['valid']);
}
```

---

## Database Verification

### Check Strategies Exist

```sql
-- Check if strategies exist for project
SELECT COUNT(*) as total, pair_type
FROM ai_strategy ast
JOIN project_ai_generation_run par ON ast.run_id = par.id
WHERE par.project_id = 123
GROUP BY pair_type;

-- Should return 4 rows (one for each pair_type: S-O, S-T, W-O, W-T)
-- Each with COUNT > 0
```

### Check Run Status

```sql
-- Check run status (should work regardless)
SELECT id, pair_type, is_active, archived_at
FROM project_ai_generation_run
WHERE project_id = 123;

-- Strategies should be found even if is_active = 0 or archived_at IS NOT NULL
```

### Verify Strategy Count

```sql
-- Total strategies by project
SELECT
    p.company_name,
    COUNT(ast.id) as total_strategies,
    GROUP_CONCAT(DISTINCT par.pair_type) as pair_types
FROM projects p
LEFT JOIN project_ai_generation_run par ON p.id = par.project_id
LEFT JOIN ai_strategy ast ON par.id = ast.run_id
WHERE p.id = 123
GROUP BY p.id;
```

---

## Manual Testing Checklist

### Setup Phase

- [ ] Database has test project with all 4 strategies
- [ ] User logged in
- [ ] Browser console open
- [ ] Network tab monitoring enabled
- [ ] Server logs accessible

### Test Execution

- [ ] Scenario 1: Fresh generation - PASS/FAIL
- [ ] Scenario 2: Persisted strategies - PASS/FAIL
- [ ] Scenario 3: Partial strategies - PASS/FAIL
- [ ] Scenario 4: Page refresh - PASS/FAIL
- [ ] Scenario 5: Logout/login - PASS/FAIL
- [ ] Scenario 6: API error handling - PASS/FAIL
- [ ] Scenario 7: Invalid UUID - PASS/FAIL
- [ ] Scenario 8: Double-click prevention - PASS/FAIL

### Database Checks

- [ ] All 4 pair types found for test project
- [ ] Strategies persist across sessions
- [ ] Run status doesn't affect strategy detection

### Browser Console

- [ ] No JavaScript errors
- [ ] No 404 errors for API endpoints
- [ ] Network requests complete successfully
- [ ] Response times acceptable (<2 seconds)

### Server Logs

- [ ] No PHP errors or warnings
- [ ] No database query errors
- [ ] Authentication checks pass
- [ ] Project ownership verified correctly

---

## Performance Benchmarks

### Expected Response Times

- **DOM Check:** <1ms (local)
- **Backend Validation:** <100ms (database + server)
- **Total Validation:** <500ms (including network)
- **Final Generation:** 10-30 seconds (AI synthesis)

### Acceptable Ranges

- ✅ Good: <200ms validation total
- ⚠️ Fair: 200-500ms validation
- ❌ Poor: >500ms validation (investigate)

### Monitoring

```javascript
// In browser console:
console.time("validation");
// Click button
// ... validation runs ...
console.timeEnd("validation");
```

---

## Troubleshooting Issues

### Issue: Validation fails with "not found or access denied"

**Check:**

1. User logged in: `console.log(document.cookie)`
2. Correct project UUID: Check URL parameters
3. Database has project: `SELECT * FROM projects WHERE uuid = ?`
4. User owns project: `SELECT * FROM projects WHERE id = ? AND created_by_user_id = ?`

### Issue: Validation succeeds but generation fails

**Check:**

1. All 4 strategies in database: See "Database Verification" section
2. IFE/EFE scores available: `document.querySelector('[data-ife-score]')`
3. Quadrant determined: `document.querySelector('[data-quadrant]')`
4. AI service responding: Check server logs

### Issue: Validation takes too long (>2 seconds)

**Check:**

1. Database query performance: Add LIMIT 1 to exit early
2. Network latency: Check Network tab response times
3. Server load: Check CPU/memory on server
4. Large dataset: Check table indexes on ai_strategy, project_ai_generation_run

### Issue: Strategies missing after generation

**Check:**

1. Strategies saved: `SELECT * FROM ai_strategy WHERE run_id = ?`
2. Run created: `SELECT * FROM project_ai_generation_run WHERE id = ?`
3. Foreign key integrity: Verify run_id exists
4. Database transaction rolled back: Check application logs

---

## Sign-Off

**Testing Completed:** ******\_\_\_******  
**Tester Name:** ******\_\_\_******  
**Date:** ******\_\_\_******  
**Issues Found:** ******\_\_\_******  
**All Scenarios Passed:** [ ] Yes [ ] No

## Next Steps

1. All scenarios should PASS before deploying to production
2. Document any failures or issues in GitHub issues
3. For failures, check troubleshooting guide above
4. Run database verification queries if in doubt
5. Check server logs for any errors
6. Review code changes if behavior unexpected
