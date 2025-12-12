# Strategy Pair Types Explained

## Overview

The TOWS (Threats, Opportunities, Weaknesses, Strengths) matrix creates 4 strategic combinations that must all be present before generating the Final Strategic Recommendation.

---

## The Four Pair Types

### 1. SO Strategies (Strengths-Opportunities)

**Full Name:** Strengths-Opportunities

**Meaning:**

- How to use internal **strengths** to capitalize on external **opportunities**
- The most positive combination
- Growth-focused strategies

**Example:**

- **Strength:** High brand recognition
- **Opportunity:** Growing market demand
- **Strategy:** "Expand to new markets by leveraging existing brand reputation"

**Validation Check:**

- ✅ If found: Strategy can build on competitive advantages
- ❌ If missing: Alert shows "SO Strategies"

**Database:**

- Table: `ai_strategy`
- pair_type: `S-O` or `SO`
- Must have at least 1 strategy for this pair type

---

### 2. ST Strategies (Strengths-Threats)

**Full Name:** Strengths-Threats

**Meaning:**

- How to use internal **strengths** to mitigate external **threats**
- Defensive strategies
- Risk reduction using existing capabilities

**Example:**

- **Strength:** Strong technical expertise
- **Threat:** New competitors entering market
- **Strategy:** "Use technical superiority to establish competitive moat"

**Validation Check:**

- ✅ If found: Organization can defend against threats
- ❌ If missing: Alert shows "ST Strategies"

**Database:**

- Table: `ai_strategy`
- pair_type: `S-T` or `ST`
- Must have at least 1 strategy for this pair type

---

### 3. WO Strategies (Weaknesses-Opportunities)

**Full Name:** Weaknesses-Opportunities

**Meaning:**

- How to overcome internal **weaknesses** by seizing external **opportunities**
- Development/improvement strategies
- Leveraging external factors to fix internal issues

**Example:**

- **Weakness:** Limited marketing resources
- **Opportunity:** Growing digital/social media channels
- **Strategy:** "Build cost-effective digital presence to overcome limited budget"

**Validation Check:**

- ✅ If found: Organization can improve weak areas
- ❌ If missing: Alert shows "WO Strategies"

**Database:**

- Table: `ai_strategy`
- pair_type: `W-O` or `WO`
- Must have at least 1 strategy for this pair type

---

### 4. WT Strategies (Weaknesses-Threats)

**Full Name:** Weaknesses-Threats

**Meaning:**

- How to minimize internal **weaknesses** and mitigate external **threats**
- Survival/defensive strategies
- Most challenging combination (needs most attention)

**Example:**

- **Weakness:** Outdated technology
- **Threat:** Rapid technological change
- **Strategy:** "Invest in technology modernization to stay competitive"

**Validation Check:**

- ✅ If found: Organization addresses critical risks
- ❌ If missing: Alert shows "WT Strategies"

**Database:**

- Table: `ai_strategy`
- pair_type: `W-T` or `WT`
- Must have at least 1 strategy for this pair type

---

## Validation Logic

### What the System Checks

```
For EACH pair type (S-O, S-T, W-O, W-T):
  ├─ Query: SELECT * FROM ai_strategy
  │          WHERE project_id = ? AND pair_type = ?
  │
  ├─ Result = Found?
  │  ├─ YES ✓: Add to valid list
  │  └─ NO ✗: Add to missing list
  │
  └─ After loop:
     ├─ All 4 found? → Allow generation ✓
     └─ Any missing? → Show alert with list ✗
```

### Example Error Messages

**Missing SO & WO:**

```
Semua 4 strategi (SO, ST, WO, WT) harus ada sebelum generate recommendation.
Strategi yang belum ada: SO Strategies, WO Strategies
```

**Missing ST & WT:**

```
Semua 4 strategi (SO, ST, WO, WT) harus ada sebelum generate recommendation.
Strategi yang belum ada: ST Strategies, WT Strategies
```

**All Present:**

```
✓ Validation passed - Ready to generate final recommendation
```

---

## Database Storage

### ai_strategy Table

Each strategy has:

- `id`: Unique identifier
- `run_id`: Links to generation run
- `project_id`: Which project this belongs to
- `pair_type`: One of S-O, S-T, W-O, W-T
- `strategy_statement`: The actual strategy text
- `priority_score`: Ranking (1-10)

### Example Data

```sql
SELECT * FROM ai_strategy
WHERE project_id = 123 AND pair_type IN ('S-O', 'S-T', 'W-O', 'W-T');

-- Results:
-- id  | run_id | project_id | pair_type | strategy_statement                    | priority_score
-- 1   | 456    | 123        | S-O       | "Expand to new markets..."            | 9
-- 2   | 457    | 123        | S-T       | "Use technical expertise..."          | 8
-- 3   | 458    | 123        | W-O       | "Build cost-effective digital..."     | 7
-- 4   | 459    | 123        | W-T       | "Invest in modernization..."          | 9

-- Validation: All 4 pair types present → VALID ✓
```

---

## Generation Process

### Step 1: Generate Individual Strategies

```
For each pair type (SO, ST, WO, WT):
  ├─ Call AI: "Create strategies for {pair_type}"
  ├─ Store in ai_strategy table
  └─ Mark run as complete
```

### Step 2: Validate Completeness

```
Check: All 4 pair types generated?
├─ YES → Proceed to Step 3
└─ NO → Ask user to generate missing ones
```

### Step 3: Generate Final Recommendation

```
Input:
  ├─ Company Profile (vision, mission, industry)
  ├─ 4 TOWS strategies (SO, ST, WO, WT)
  ├─ IE Matrix position (quadrant)
  └─ IFE/EFE scores

Process:
  └─ Call AI: "Synthesize 4 strategies into single recommendation"

Output:
  ├─ Strategic theme
  ├─ Alignment with position
  ├─ Short-term actions
  ├─ Long-term actions
  ├─ Resource implications
  └─ Risk mitigation
```

---

## Common Issues & Solutions

### Issue: System says strategies missing but I can see them

**Cause:** Strategies visible in DOM but not in database

**Solution:**

1. Scroll down and refresh page
2. Verify database: `SELECT COUNT(*) FROM ai_strategy WHERE project_id = ?`
3. Check pair_type is correct (S-O not SO, etc.)

### Issue: SO strategies exist but system says missing

**Cause:** Database pair_type mismatch (S-O vs SO)

**Solution:**

1. Check database: `SELECT DISTINCT pair_type FROM ai_strategy WHERE project_id = ?`
2. Verify your system uses consistent naming
3. Standard is: S-O, S-T, W-O, W-T (with hyphens)

### Issue: Can see 3 strategies but 4th won't generate

**Cause:** AI service returned error for one pair type

**Solution:**

1. Click "Generate [Missing] Strategies" button again
2. Check server logs for AI API errors
3. Verify project has complete SWOT data (all quadrants)
4. Try regenerating all 4 (optional)

### Issue: Page refresh makes strategies disappear

**Cause:** Strategies not fully loaded from database

**Solution:**

1. Wait 2-3 seconds after page load
2. Scroll up to see strategy containers load
3. Then click "Generate Recommendation"
4. System will verify in database (not just DOM)

---

## Testing Each Pair Type

### Test Matrix

| Test Case      | SO  | ST  | WO  | WT  | Expected Result          |
| -------------- | --- | --- | --- | --- | ------------------------ |
| All 4 present  | ✓   | ✓   | ✓   | ✓   | ✅ Generate allowed      |
| Missing SO     | ✗   | ✓   | ✓   | ✓   | ❌ Alert: SO missing     |
| Missing ST     | ✓   | ✗   | ✓   | ✓   | ❌ Alert: ST missing     |
| Missing WO     | ✓   | ✓   | ✗   | ✓   | ❌ Alert: WO missing     |
| Missing WT     | ✓   | ✓   | ✓   | ✗   | ❌ Alert: WT missing     |
| Missing SO, ST | ✗   | ✗   | ✓   | ✓   | ❌ Alert: SO, ST missing |
| All missing    | ✗   | ✗   | ✗   | ✗   | ❌ Alert: All 4 missing  |

### Manual Test Steps

```bash
# Test 1: All present
1. Create project
2. Generate: SO ✓ ST ✓ WO ✓ WT ✓
3. Click "Generate Recommendation"
4. Expected: ✅ Works

# Test 2: Missing WT
1. Create project
2. Generate: SO ✓ ST ✓ WO ✓ WT ✗
3. Click "Generate Recommendation"
4. Expected: ❌ Shows "WT Strategies missing"

# Test 3: After page refresh
1. Create project with all 4
2. Refresh page (F5)
3. Strategies containers appear empty
4. Click "Generate Recommendation"
5. Expected: ✅ Works (backend finds in database)
```

---

## Strategic Alignment

### Why All 4 Matter

**Balanced Perspective:**

- **SO**: Offensive (growth)
- **ST**: Defensive (risk management)
- **WO**: Improvement (development)
- **WT**: Survival (critical issues)

**Comprehensive Planning:**
Without all 4, the organization:

- ❌ SO only: Ignores threats
- ❌ ST only: Misses opportunities
- ❌ WO only: Ignores strengths
- ❌ WT only: Focuses only on problems

**Requirement:** All 4 ensures balanced strategic planning

---

## API Reference

### Validation Endpoint

**POST /api/project/validate-strategies**

```json
{
	"project_uuid": "abc-123-def-456"
}
```

**Response - All Valid:**

```json
{
	"valid": true
}
```

**Response - Missing Strategies:**

```json
{
	"valid": false,
	"message": "Semua 4 strategi (SO, ST, WO, WT) harus ada...",
	"missing": ["S-O", "W-O"]
}
```

---

## FAQ

**Q: Can I generate final recommendation with only 1 pair type?**  
A: No. The system requires all 4 (SO, ST, WO, WT) for balanced analysis.

**Q: If I delete a strategy, what happens?**  
A: System will show missing for that pair type next time you try to generate.

**Q: Can I have multiple strategies per pair type?**  
A: Yes. System only needs at least 1 per type. More is fine.

**Q: What if pair_type is named differently (SO vs S-O)?**  
A: Backend query handles both. Use S-O format for consistency.

**Q: How many strategies should I generate per type?**  
A: AI generates multiple, then user prioritizes. No hard limit.

**Q: Can I generate in different order (WO first, then SO)?**  
A: Yes. Order doesn't matter, all 4 just need to exist before generation.

**Q: What's the difference between generation and validation?**  
A: Generation = creating new strategies. Validation = checking if 4 types exist.

---

## Troubleshooting

### Issue: "All strategies exist but system says missing"

**Debug Steps:**

```sql
-- 1. Check what pair types exist
SELECT DISTINCT pair_type FROM ai_strategy
WHERE project_id = 123;

-- 2. Check count per type
SELECT pair_type, COUNT(*) as count FROM ai_strategy
WHERE project_id = 123
GROUP BY pair_type;

-- 3. Check if any are NULL
SELECT id, pair_type FROM ai_strategy
WHERE project_id = 123 AND pair_type IS NULL;

-- 4. Check expected pair types
SELECT * FROM ai_strategy
WHERE project_id = 123
ORDER BY pair_type;

-- 5. Compare with expected list
-- Expected: S-O, S-T, W-O, W-T (all present)
```

### Issue: Generate button disabled but no error shown

**Check:**

1. Browser console for JavaScript errors
2. Network tab for failed API calls
3. Server logs for backend errors
4. Button state: `document.getElementById('generateRecommendationsBtn').disabled`

---

## Summary Table

| Aspect             | SO                      | ST                | WO                       | WT                  |
| ------------------ | ----------------------- | ----------------- | ------------------------ | ------------------- |
| **Full Name**      | Strengths-Opportunities | Strengths-Threats | Weaknesses-Opportunities | Weaknesses-Threats  |
| **Nature**         | Offensive               | Defensive         | Improvement              | Survival            |
| **Typical Focus**  | Growth                  | Risk Mgmt         | Development              | Critical Issues     |
| **Priority Level** | High                    | High              | Medium                   | Critical            |
| **Validation**     | Must exist              | Must exist        | Must exist               | Must exist          |
| **Examples**       | Expand market           | Protect market    | Improve ops              | Fix vulnerabilities |

---

**Final Point:** All 4 pair types must be generated before the system allows Final Strategic Recommendation generation. This ensures comprehensive strategic coverage across all strategic dimensions.
