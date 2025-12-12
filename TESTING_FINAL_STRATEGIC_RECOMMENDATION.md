# 🧪 TESTING GUIDE - FINAL STRATEGIC RECOMMENDATION

**Last Updated:** 12 December 2025  
**Status:** Ready for Testing

---

## 📋 QUICK START TESTING

### Prerequisites:

- ✅ Project UUID available
- ✅ IFE & EFE scores calculated
- ✅ Prioritized strategies saved (optional but recommended)
- ✅ AI service (Gemini/OpenAI) configured

---

## 🔍 UNIT TEST: API Endpoint

### Test 1: Valid Request - Quadrant I (Grow & Diversify)

**Endpoint:** `POST /api/project/generate-strategic-recommendation`

**Setup:**

```json
{
	"project_uuid": "f47ac10b-58cc-4372-a567-0e02b2c3d479",
	"ife_score": 3.2,
	"efe_score": 3.5,
	"quadrant": "I - Grow & Diversify"
}
```

**Expected Results:**

- ✅ HTTP Status: 200
- ✅ Response contains: `success`, `message`, `data`
- ✅ Data contains: `company_profile`, `ie_matrix_position`, `recommendation`
- ✅ Recommendation has 5 sections: theme, short-term, long-term, resources, risks
- ✅ Each action has: `action/initiative`, `priority/resources`, `impact/metrics`

**Verification:**

```bash
# Test dengan curl (Windows PowerShell):
$body = @{
  project_uuid = "f47ac10b-58cc-4372-a567-0e02b2c3d479"
  ife_score = 3.2
  efe_score = 3.5
  quadrant = "I - Grow & Diversify"
} | ConvertTo-Json

$response = Invoke-WebRequest -Uri "http://acumena.test/api/project/generate-strategic-recommendation" `
  -Method POST `
  -Body $body `
  -ContentType "application/json" `
  -SessionVariable "session"

$response.Content | ConvertFrom-Json | ConvertTo-Json -Depth 10
```

---

### Test 2: Valid Request - Different Quadrants

Test dengan berbagai kombinasi IFE/EFE scores:

| Test | IFE | EFE | Expected Quadrant | Status |
| ---- | --- | --- | ----------------- | ------ |
| Q1   | 3.2 | 3.5 | Grow & Diversify  | [ ]    |
| Q2   | 1.8 | 3.2 | Turnaround        | [ ]    |
| Q3   | 1.5 | 1.8 | Defensive         | [ ]    |
| Q4   | 3.5 | 1.2 | Cautious Growth   | [ ]    |
| Q5   | 2.5 | 2.5 | Hold & Maintain   | [ ]    |

**Verification:** Confirm recommendations align dengan quadrant characteristics

---

### Test 3: Auto-Calculate Quadrant

**Setup (omit quadrant parameter):**

```json
{
	"project_uuid": "f47ac10b-58cc-4372-a567-0e02b2c3d479",
	"ife_score": 2.8,
	"efe_score": 2.5
}
```

**Expected:**

- ✅ Endpoint calculates quadrant automatically
- ✅ Response includes calculated quadrant
- ✅ Results same as when quadrant is provided

---

### Test 4: Missing Required Parameters

**Setup:**

```json
{
	"project_uuid": "f47ac10b-58cc-4372-a567-0e02b2c3d479"
}
```

**Expected:**

- ✅ HTTP Status: 400 atau 422
- ✅ Error message: "Missing required parameters: ife_score, efe_score"
- ✅ No AI call made

---

### Test 5: Invalid Project UUID

**Setup:**

```json
{
	"project_uuid": "invalid-uuid-12345",
	"ife_score": 2.8,
	"efe_score": 3.1
}
```

**Expected:**

- ✅ HTTP Status: 404 atau 403
- ✅ Error message: "Project not found" atau "Unauthorized"

---

### Test 6: Invalid Score Range

**Setup:**

```json
{
	"project_uuid": "f47ac10b-58cc-4372-a567-0e02b2c3d479",
	"ife_score": 5.5,
	"efe_score": 3.1
}
```

**Expected:**

- ✅ HTTP Status: 400 atau 422
- ✅ Error message: "IFE score must be between 1.0 and 4.0"

---

## 🖥️ INTEGRATION TEST: UI Workflow

### Test 1: Full User Workflow

**Steps:**

1. Navigate to: `http://acumena.test/project?uuid=YOUR-PROJECT-UUID&step=matrix`
2. Verify page loads with IE Matrix
3. Input IFE Score: 2.8
4. Input EFE Score: 3.1
5. Verify Quadrant is calculated/displayed
6. Scroll to "Prioritized Strategies" section
7. Click "Generate Recommendations" button

**Expected Results:**

- ✅ Button is clickable
- ✅ Button text changes to: "Analyzing... Generating Final Strategic Recommendation..."
- ✅ Button is disabled during processing
- ✅ After 3-5 seconds, modal appears
- ✅ Modal displays recommendation with all sections
- ✅ Company name and quadrant shown in header
- ✅ No JavaScript errors in console

**Verification:**

```javascript
// Open browser DevTools (F12)
// Check Console tab for errors
// Check Network tab for API call
console.log("Check for any red errors");
```

---

### Test 2: Modal Display & Content

**Setup:** Generate recommendation (completed Test 1 above)

**Verification:**

- [ ] Modal is visible and centered
- [ ] Modal has scrollbar (if content > viewport height)
- [ ] All 5 sections visible:
  - [ ] Strategic Theme
  - [ ] Short-term Actions (3-6 months)
  - [ ] Long-term Actions (1-3 years)
  - [ ] Resource Implications
  - [ ] Risk Mitigation
- [ ] Company profile displayed at top
- [ ] IE Matrix position shown (scores and quadrant)
- [ ] Text is readable (font size appropriate)
- [ ] Monospace font used for structured display

**Modal Sections Checklist:**

```
HEADER:
  [ ] Company name
  [ ] Industry
  [ ] IE Matrix Quadrant

CONTENT:
  [ ] Strategic Theme text (1-2 sentences)
  [ ] Short-term actions (minimum 3 items)
    [ ] Each has action, priority, impact
  [ ] Long-term actions (minimum 3 items)
    [ ] Each has initiative, resources, metrics
  [ ] Resource implications
    [ ] Budget allocation percentages
    [ ] Key roles listed
    [ ] Skill development areas
  [ ] Risk mitigation (minimum 2 items)
    [ ] Each has risk and mitigation approach

FOOTER:
  [ ] Generated date/time
  [ ] Download button
  [ ] Close button
```

---

### Test 3: Download as Text

**Steps:**

1. After recommendation modal displays
2. Click "Download as Text" button
3. File should download: `strategic-recommendation.txt`
4. Open downloaded file

**Verification:**

- [ ] File downloads without error
- [ ] File is named correctly: `strategic-recommendation.txt`
- [ ] File content includes all recommendation sections
- [ ] Formatting is readable (plain text)
- [ ] Company name appears at top
- [ ] Generated timestamp at bottom

**Sample Content:**

```
═══════════════════════════════════════════════════════════
    FINAL STRATEGIC RECOMMENDATION
═══════════════════════════════════════════════════════════

COMPANY: PT Teknologi Indonesia
INDUSTRY: Information Technology

IE MATRIX POSITION: I - Grow & Diversify
IFE Score: 2.80
EFE Score: 3.10

───────────────────────────────────────────────────────────
STRATEGIC THEME:
[Theme text here...]

[Rest of recommendation...]
```

---

### Test 4: Close Modal

**Steps:**

1. After recommendation modal displays
2. Click "Close" button or click outside modal
3. Modal should disappear

**Verification:**

- [ ] Modal closes smoothly
- [ ] Page returns to matrix view
- [ ] All form data preserved (scores still visible)
- [ ] No error messages

---

## 🔴 ERROR HANDLING TESTS

### Test 1: No Prioritized Strategies Saved

**Setup:**

- Create project without saving any prioritized strategies
- Try to generate recommendation

**Expected:**

- ✅ Should still work (strategies are optional)
- ✅ Recommendation mentions no strategies available
- ✅ Focus on IE Matrix position and company profile

---

### Test 2: Network Error During Generation

**Setup:**

- Open DevTools
- Go to Network tab
- Throttle to "Slow 3G"
- Click "Generate Recommendations"
- Wait 30+ seconds

**Expected:**

- ✅ Loading state continues
- ✅ Eventually either:
  - Recommendation displays (slow but successful)
  - Error message displays (timeout)
- ✅ No frozen UI
- ✅ Button remains functional after error

---

### Test 3: AI Service Timeout

**Setup:**

- If AI service configured to slow endpoint
- Click "Generate Recommendations"
- Wait for timeout

**Expected:**

- ✅ Error message appears: "Failed to generate recommendation. Please try again."
- ✅ Button re-enabled
- ✅ User can retry

---

### Test 4: Invalid Session (Unauthorized)

**Setup:**

1. Open browser DevTools → Storage → Cookies
2. Delete session cookie
3. Try to click "Generate Recommendations"

**Expected:**

- ✅ Error message: "Unauthorized" atau "Session expired"
- ✅ Redirect to login page
- ✅ No recommendation generated

---

## 📊 AI OUTPUT QUALITY TESTS

### Test 1: Output Format Validation

**Steps:**

1. Generate recommendation
2. Open DevTools → Network tab
3. Find request to `/api/project/generate-strategic-recommendation`
4. Click "Response" tab
5. Verify JSON structure

**Verification:**

```json
// Response should have this structure:
{
	"success": true,
	"message": "...",
	"data": {
		"company_profile": {
			"company_name": "...",
			"industry": "...",
			"vision": "...",
			"mission": "..."
		},
		"ie_matrix_position": {
			"ife_score": 2.8,
			"efe_score": 3.1,
			"quadrant": "I - Grow & Diversify"
		},
		"recommendation": {
			"strategic_theme": "...",
			"short_term_actions": [
				{
					"action": "...",
					"priority": "High/Medium/Low",
					"impact": "..."
				}
			],
			"long_term_actions": [
				{
					"initiative": "...",
					"resources": "...",
					"success_metrics": "..."
				}
			],
			"resource_implications": {
				"budget_allocation": "...",
				"key_roles": "...",
				"skill_development": "..."
			},
			"risk_mitigation": [
				{
					"risk": "...",
					"mitigation": "..."
				}
			]
		}
	}
}
```

---

### Test 2: Content Quality per Quadrant

Generate recommendations for each quadrant and verify quality:

| Quadrant             | Expected Focus                                  | Verify |
| -------------------- | ----------------------------------------------- | ------ |
| I (Grow & Diversify) | Aggressive expansion, new markets, innovation   | [ ]    |
| II (Turnaround)      | Cost optimization, niche positioning, stability | [ ]    |
| III (Defensive)      | Restructuring, consolidation, survival          | [ ]    |
| IV (Cautious)        | Strategic selectivity, efficiency               | [ ]    |
| V (Hold)             | Maintain position, optimize current             | [ ]    |

---

### Test 3: AI Consistency

**Steps:**

1. Generate recommendation for same project twice
2. Compare outputs

**Expected:**

- ✅ Recommendations should be similar (Temperature 0.2 ensures consistency)
- ✅ Same theme and main strategies
- ✅ Minor variations acceptable (action wording, priorities)

---

## 📋 REGRESSION TESTS

### Test 1: Other Features Still Work

After implementing new recommendation feature, verify:

- [ ] Save Prioritized Strategies still works
- [ ] Update Prioritized Strategies still works
- [ ] Delete Prioritized Strategies still works
- [ ] Matrix display still shows correctly
- [ ] IFE/EFE calculation still correct
- [ ] Other project workflows not affected

---

### Test 2: Database State

Verify new feature doesn't affect existing data:

```sql
-- Check no unintended changes
SELECT COUNT(*) FROM projects;
SELECT COUNT(*) FROM project_prioritized_strategies;
SELECT COUNT(*) FROM project_ai_generation_run;

-- Verify no orphaned records
SELECT * FROM project_prioritized_strategies
WHERE project_id NOT IN (SELECT id FROM projects);
```

---

## 📝 TEST EXECUTION CHECKLIST

### Phase 1: API Tests

- [ ] Test 1: Valid Request Q1
- [ ] Test 2: Different Quadrants (Q1-Q5)
- [ ] Test 3: Auto-Calculate Quadrant
- [ ] Test 4: Missing Parameters
- [ ] Test 5: Invalid Project UUID
- [ ] Test 6: Invalid Score Range

**Status:** ****\_\_\_****  
**Date:** ****\_\_\_****  
**Tester:** ****\_\_\_****

---

### Phase 2: UI Tests

- [ ] Test 1: Full Workflow
- [ ] Test 2: Modal Display
- [ ] Test 3: Download
- [ ] Test 4: Close Modal

**Status:** ****\_\_\_****  
**Date:** ****\_\_\_****  
**Tester:** ****\_\_\_****

---

### Phase 3: Error Tests

- [ ] Test 1: No Strategies
- [ ] Test 2: Network Error
- [ ] Test 3: AI Timeout
- [ ] Test 4: Unauthorized

**Status:** ****\_\_\_****  
**Date:** ****\_\_\_****  
**Tester:** ****\_\_\_****

---

### Phase 4: Quality Tests

- [ ] Test 1: Output Format
- [ ] Test 2: Content Quality
- [ ] Test 3: AI Consistency

**Status:** ****\_\_\_****  
**Date:** ****\_\_\_****  
**Tester:** ****\_\_\_****

---

### Phase 5: Regression Tests

- [ ] Test 1: Other Features
- [ ] Test 2: Database State

**Status:** ****\_\_\_****  
**Date:** ****\_\_\_****  
**Tester:** ****\_\_\_****

---

## 🐛 BUG REPORT TEMPLATE

If issues found during testing:

```
## Bug Title
[Short description of issue]

## Steps to Reproduce
1. Step 1
2. Step 2
3. Step 3

## Expected Result
[What should happen]

## Actual Result
[What actually happens]

## Environment
- Browser: [Chrome/Firefox/Safari]
- URL: [Full URL tested]
- User: [Test user if applicable]
- Project UUID: [UUID being tested]

## Severity
[ ] Critical (Feature broken)
[ ] High (Major issue)
[ ] Medium (Feature works but with issues)
[ ] Low (Minor issue)

## Screenshots/Logs
[Attach any relevant console logs or screenshots]
```

---

## ✅ COMPLETION CHECKLIST

When all tests complete:

- [ ] All API tests passed
- [ ] All UI tests passed
- [ ] All error handling works
- [ ] Output quality acceptable
- [ ] No regressions detected
- [ ] Documentation updated
- [ ] Ready for production

---

**Testing Started:** ****\_\_\_****  
**Testing Completed:** ****\_\_\_****  
**Approved By:** ****\_\_\_****
