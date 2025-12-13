# Improvement: Keep Version History of Strategic Recommendations

## Overview

Changed the recommendation save logic from **UPDATE** (replace old data) to **INSERT + DEACTIVATE** (keep history). Now when a new recommendation is generated, the system:

1. **Inserts** the new recommendation as a fresh record
2. **Deactivates** all previous active recommendations for that project

## Problem This Solves

**Before:** Every time user regenerates a recommendation, the old data was overwritten

```
Generate v1 → Saved in DB
Generate v2 → Old v1 is DELETED, only v2 remains ❌
Generate v3 → Old v2 is DELETED, only v3 remains ❌
```

**After:** All versions are kept, only latest is marked as active

```
Generate v1 → Saved (is_active = 1)
Generate v2 → v1 deactivated (is_active = 0), v2 inserted (is_active = 1) ✅
Generate v3 → v2 deactivated (is_active = 0), v3 inserted (is_active = 1) ✅
```

## Database Changes

### Added Column: `is_active`

```sql
ALTER TABLE strategic_recommendations
ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER updated_at;
```

**Column Details:**

- **Type:** TINYINT(1) - Boolean-like (0 = false/inactive, 1 = true/active)
- **Default:** 1 - New recommendations are active by default
- **Position:** Added after `updated_at` column
- **Migration:** Applied immediately to database

### Table Structure After Change

```
strategic_recommendations:
├── id (auto_increment, primary key)
├── project_id (foreign key)
├── strategic_theme (longtext)
├── alignment_with_position (longtext)
├── short_term_actions (json)
├── long_term_actions (json)
├── resource_implications (json)
├── risk_mitigation (json)
├── ife_score (decimal)
├── efe_score (decimal)
├── quadrant (varchar)
├── created_at (timestamp)
├── updated_at (timestamp)
└── is_active (tinyint) ← NEW!
```

## Code Changes

### File: `Api_project.php`

#### Change 1: In `generate_strategic_recommendation()` method

**Before:**

```php
// Check if already exists - if yes, UPDATE; if no, INSERT
if ($existing) {
    // UPDATE - overwrites old data ❌
    $this->db->where('project_id', $project['id']);
    $this->db->update('strategic_recommendations', $save_data);
} else {
    // INSERT new data
    $this->db->insert('strategic_recommendations', $save_data);
}
```

**After:**

```php
// Always INSERT new data
$save_data['is_active'] = 1;  // Mark as active

// Deactivate all previous active recommendations
$this->db
    ->where('project_id', $project['id'])
    ->where('is_active', 1)
    ->update('strategic_recommendations', ['is_active' => 0]);

// Insert new recommendation (creates new row)
$this->db->insert('strategic_recommendations', $save_data);
```

**Benefits:**

- ✅ No data loss
- ✅ Complete version history maintained
- ✅ Only one active recommendation per project at any time
- ✅ Can rollback to older versions if needed

#### Change 2: In `get_recommendation()` method

**Before:**

```php
// Get ANY recommendation (could be old inactive ones)
$recommendation = $this->db
    ->where('project_id', $project['id'])
    ->get('strategic_recommendations')
    ->row_array();
```

**After:**

```php
// Get ONLY active recommendation
$recommendation = $this->db
    ->where('project_id', $project['id'])
    ->where('is_active', 1)  // Only active ✅
    ->order_by('created_at', 'DESC')  // Most recent if multiple
    ->get('strategic_recommendations')
    ->row_array();
```

**Benefits:**

- ✅ Always shows active/current recommendation
- ✅ Ignores deactivated old versions
- ✅ If multiple active (shouldn't happen but safe), gets most recent

## How It Works

### Step-by-Step Process

```
User clicks "Generate Recommendations"
    ↓
Frontend shows loading animation
    ↓
Backend calculates IFE/EFE scores
    ↓
Backend calls AI to generate recommendation
    ↓
Backend prepares save data with is_active = 1
    ↓
Backend query #1: UPDATE WHERE project_id=X AND is_active=1
                 SET is_active=0
    ↓ (All previous active recommendations are now deactivated)
    ↓
Backend query #2: INSERT INTO strategic_recommendations
                 (project_id, strategic_theme, ..., is_active)
                 VALUES (X, '...', ..., 1)
    ↓ (New recommendation inserted as active)
    ↓
Frontend gets response: "Rekomendasi baru berhasil disimpan
                         ke database (versi lama dinonaktifkan)"
    ↓
Frontend displays new recommendation
```

## Database Queries Examples

### Check Version History for Project ID 3

```sql
-- Show ALL versions (active and inactive)
SELECT id, created_at, is_active, strategic_theme
FROM strategic_recommendations
WHERE project_id = 3
ORDER BY created_at DESC;

-- Result:
-- id | created_at          | is_active | strategic_theme
-- 5  | 2025-12-12 16:30:00 | 1         | "Grow and Build..."  ← Latest (active)
-- 4  | 2025-12-12 15:45:00 | 0         | "Grow and Build..."  ← Previous
-- 3  | 2025-12-12 14:20:00 | 0         | "Hold and Maintain..." ← Older
-- 2  | 2025-12-12 13:00:00 | 0         | "Harvest..." ← Oldest
```

### Get Only Current Active Recommendation

```sql
-- What the API uses
SELECT *
FROM strategic_recommendations
WHERE project_id = 3 AND is_active = 1;

-- Result: Only latest active version (id=5)
```

### Restore Previous Version (Future Enhancement)

```sql
-- If user wants to use an older version:
UPDATE strategic_recommendations
SET is_active = 0
WHERE project_id = 3 AND is_active = 1;

UPDATE strategic_recommendations
SET is_active = 1
WHERE id = 4;  -- Activate previous version
```

## Impact on Frontend

### No Changes Required

Frontend code continues to work exactly the same:

- ✅ Uses `/api/project/get-recommendation` endpoint
- ✅ Gets current active recommendation
- ✅ Displays as before
- ✅ No behavior changes from user perspective

### User Experience

**Before:** After regenerating recommendation 3 times, had 1 record
**After:** After regenerating recommendation 3 times, has 3 records but only 1 shows active ✅

### Benefits to User

1. **Auditability:** Can see when recommendations were generated
2. **Rollback Capability:** Could restore older versions if needed
3. **Change Tracking:** Can see how recommendation evolved
4. **Data Safety:** Nothing is deleted, only marked inactive

## Database Impact

### Storage

- Minimal increase - only new rows for new generations
- Each recommendation stores: ~5KB (depending on strategy details)
- Example: 10 regenerations = ~50KB extra per project

### Performance

- Query speed: Same as before (single row fetch)
- Index on `(project_id, is_active)` recommended for optimal performance

### Backup Integrity

- ✅ Complete history preserved
- ✅ All data safe for auditing
- ✅ Can restore any previous version

## Sample Scenario

### Project: "PT Tech Indonesia"

**Timeline:**

| Time  | Action                     | Result                                     |
| ----- | -------------------------- | ------------------------------------------ |
| 14:00 | Generate recommendation v1 | id=100, is_active=1                        |
| 14:15 | View recommendation        | Shows id=100 ✓                             |
| 14:30 | Regenerate (IFE changed)   | id=100 → is_active=0, id=101 → is_active=1 |
| 14:35 | View recommendation        | Shows id=101 ✓                             |
| 15:00 | Regenerate (EFE changed)   | id=101 → is_active=0, id=102 → is_active=1 |
| 15:05 | View recommendation        | Shows id=102 ✓                             |

**Database State:**

```
id  | created_at | is_active | strategic_theme
----|------------|-----------|------------------
100 | 14:00      | 0         | "Grow and Build"
101 | 14:30      | 0         | "Hold and Maintain"
102 | 15:00      | 1         | "Grow and Build"  ← Current
```

User always sees id=102 (current) but history preserved for auditing.

## Migration Path (Already Done)

✅ Column `is_active` added to production database
✅ Default value 1 ensures backward compatibility
✅ Existing recommendations default to is_active=1 (still active)
✅ No data migration needed

## Future Enhancements

1. **Admin View:** Show version history UI
2. **Rollback Function:** API endpoint to restore older versions
3. **Diff View:** Compare two recommendation versions
4. **Archive Old Data:** Move inactive recommendations to archive table after N days
5. **Audit Log:** Track who generated each version and when

## Testing Checklist

- [x] Database column added successfully
- [x] Syntax check passed
- [x] Logic updated in generate method
- [x] Logic updated in get method
- [x] Deactivation happens before insert
- [x] Only active recommendation returned on fetch
- [x] No data loss

## Rollout Notes

### Changes Applied:

1. ✅ Added `is_active` column to `strategic_recommendations` table
2. ✅ Updated `generate_strategic_recommendation()` logic
3. ✅ Updated `get_recommendation()` logic
4. ✅ PHP syntax verified

### No Migration Needed:

- Default value = 1 means existing records are active
- Frontend code unchanged
- API contract unchanged

### Backward Compatibility:

- ✅ Existing records work as before
- ✅ New logic works with old records
- ✅ Data integrity maintained

## Summary

| Aspect                    | Before            | After                  |
| ------------------------- | ----------------- | ---------------------- |
| Data on regenerate        | Overwritten       | Preserved              |
| Version history           | None ❌           | Complete ✅            |
| Active recommendation     | Latest (implicit) | Explicit (is_active=1) |
| Data loss risk            | High ❌           | None ✅                |
| Query for active          | Any record        | is_active=1            |
| Audit trail               | Not available     | Fully available        |
| Database rows per project | 1                 | N (versions)           |
| Storage impact            | Same              | Minimal increase       |

---

**Status:** ✅ **COMPLETED AND DEPLOYED**  
**Date:** December 12, 2025  
**Version:** 1.0  
**Backward Compatible:** YES ✅  
**Data Safe:** YES ✅
