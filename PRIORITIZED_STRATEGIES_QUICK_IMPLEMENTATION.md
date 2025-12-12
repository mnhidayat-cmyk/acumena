# ⚡ Quick Reference: Saving Prioritized Strategies

---

## 🎯 RINGKASAN SINGKAT

### Tabel Baru (Recommended)

```sql
CREATE TABLE `project_prioritized_strategies` (
  `id` BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `uuid` CHAR(36) UNIQUE,
  `project_id` BIGINT UNSIGNED NOT NULL,
  `pair_type` ENUM('S-O','W-O','S-T','W-T'),
  `strategy_code` VARCHAR(10),           -- SO1, ST2, WO3, WT4
  `strategy_statement` TEXT,
  `priority_rank` INT,                   -- 1 = highest priority
  `priority_score` DECIMAL(5,4),         -- 0.0-1.0 from AI
  `status` ENUM('draft','approved','in_progress','completed','archived'),
  `selected_by_user` BOOLEAN,
  `selection_justification` TEXT,
  `internal_notes` TEXT,
  `created_at` TIMESTAMP,
  `updated_at` TIMESTAMP,
  `created_by_user_id` BIGINT UNSIGNED,
  `is_deleted` TIMESTAMP NULL,

  KEY `idx_project_id` (`project_id`),
  KEY `idx_pair_type` (`pair_type`),
  KEY `idx_status` (`status`),
  FK `project_id` → `projects.id`,
  FK `created_by_user_id` → `users.id`
);
```

---

## 📋 Proses: 5 Langkah Implementasi

### 1️⃣ DATABASE LAYER

```
✅ Create project_prioritized_strategies table
✅ Add indexes (project_id, pair_type, status)
✅ Add foreign keys
✅ Test insert/select
```

### 2️⃣ MODEL LAYER

```
✅ Create: Prioritized_strategy_model.php
   Methods:
   - save_multiple($project_id, $strategies, $user_id)
   - get_by_project($project_id, $pair_type)
   - update_strategy($id, $data, $user_id)
   - delete_strategy($id)
   - get_status_summary($project_id)
```

### 3️⃣ CONTROLLER LAYER

```
✅ Add to: Api_project.php
   Endpoints:
   - prioritized_strategies_save()     [POST]
   - prioritized_strategies_get()      [GET]
   - prioritized_strategies_update()   [PUT]
   - prioritized_strategies_delete()   [DELETE]
```

### 4️⃣ ROUTES LAYER

```
✅ Add to: config/routes.php
   $route['api/project/prioritized-strategies/save'] = 'api_project/prioritized_strategies_save';
   $route['api/project/prioritized-strategies'] = 'api_project/prioritized_strategies_get';
   $route['api/project/prioritized-strategies/(:num)'] = 'api_project/prioritized_strategies_update';
```

### 5️⃣ VIEW LAYER

```
✅ Update: application/views/projects/matrix-ai.php
   - Add "Save to Database" button
   - Collect strategies from UI
   - POST to /api/project/prioritized-strategies/save
```

---

## 🔌 API ENDPOINTS

### POST /api/project/prioritized-strategies/save

**Save prioritized strategies untuk project**

```json
{
	"project_uuid": "abc-def-123",
	"strategies": [
		{
			"pair_type": "S-O",
			"strategy_code": "SO1",
			"strategy_statement": "Manfaatkan minimum order kecil",
			"priority_rank": 1,
			"priority_score": 0.85,
			"selected_by_user": true,
			"selection_justification": "Priority utama"
		}
	]
}
```

### GET /api/project/prioritized-strategies

**Retrieve saved strategies**

```
?project_uuid=abc-def-123
?pair_type=S-O  (optional filter)
?status=draft   (optional filter)
```

### PUT /api/project/prioritized-strategies/{id}

**Update strategy status/notes**

```json
{
	"status": "approved",
	"internal_notes": "Ready implementation",
	"priority_rank": 2
}
```

### DELETE /api/project/prioritized-strategies/{id}

**Soft delete strategy**

---

## 📊 Kolom Penting Dijelaskan

| Kolom                     | Tipe    | Contoh                     | Kegunaan                       |
| ------------------------- | ------- | -------------------------- | ------------------------------ |
| `strategy_code`           | VARCHAR | "SO1", "WT3"               | Identitas singkat              |
| `priority_rank`           | INT     | 1, 2, 3                    | Urutan prioritas (1=tertinggi) |
| `priority_score`          | DECIMAL | 0.85, 0.72                 | Score dari AI                  |
| `status`                  | ENUM    | draft→approved→in_progress | Workflow tracking              |
| `selected_by_user`        | BOOLEAN | true/false                 | User explicitly pick?          |
| `selection_justification` | TEXT    | "High ROI"                 | Alasan user pick               |
| `internal_notes`          | TEXT    | "Q1 focus"                 | Notes untuk team               |

---

## 🚀 Data Flow

```
Current (Display Only)
  User Generate Strategies → Display → Refresh → ❌ LOST

Proposed (Persistent)
  User Generate Strategies
    → Display
    → Click "Save to Database"
    → POST /api/project/prioritized-strategies/save
    → Insert to project_prioritized_strategies
    → ✅ SAVED (dapat diakses anytime)
    → User bisa update status, add notes, re-prioritize
```

---

## 🎯 Keuntungan Implementation Ini

✅ **Persistent Storage** - Data tidak hilang saat refresh  
✅ **Audit Trail** - Track siapa create kapan via created_by_user_id & timestamps  
✅ **Status Workflow** - Monitor execution progress (draft→approved→completed)  
✅ **Notes & Justification** - Team collaboration & documentation  
✅ **Soft Delete** - Historical record tetap tersimpan  
✅ **Flexible Query** - Filter by pair_type, status, priority_rank  
✅ **Scalable** - Siap untuk multi-project, multi-team

---

## 📁 Files To Create/Update

### New Files

- [ ] `application/models/Prioritized_strategy_model.php` (50 lines)
- [ ] `SAVE_PRIORITIZED_STRATEGIES_IMPLEMENTATION.md` (documentation) ✅ DONE

### Update Files

- [ ] `application/config/routes.php` (add 3 routes)
- [ ] `application/controllers/Api_project.php` (add 4 methods ~120 lines)
- [ ] `application/views/projects/matrix-ai.php` (add save button & logic)

---

## 💡 Optional Enhancements

**Phase 2 (Future):**

- Dashboard untuk track execution progress
- Email notifications saat status berubah
- Strategy comparison (compare prioritized vs actual results)
- Team collaboration features (assign to team member)
- Attachment support (files, images, links)

---

## ✅ Next Steps

1. Run SQL CREATE TABLE script
2. Create Model class
3. Add API endpoints
4. Add routes
5. Update view with save button
6. Test dengan Postman
7. Test dari UI
8. Go live!

---

**Full Documentation:** Lihat `SAVE_PRIORITIZED_STRATEGIES_IMPLEMENTATION.md`

Generated: 2025-12-12
