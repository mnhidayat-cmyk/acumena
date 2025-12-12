# ⚡ QUICK START: TESTING PRIORITIZED STRATEGIES

**Status:** Implementation COMPLETE - Ready for Testing  
**Time to Test:** ~15 minutes

---

## 🚀 TESTING DENGAN POSTMAN (RECOMMENDED)

### Step 1: Buka Postman

1. Launch Postman
2. Create new Collection: "Acumena - Prioritized Strategies"

### Step 2: Get Project UUID

```
Pergi ke aplikasi Acumena
→ Dashboard → Buka salah satu project
→ Copy UUID dari URL (parameter: uuid=...)
```

**Contoh URL:** `http://acumena.test/project?uuid=f47ac10b-58cc-4372-a567-0e02b2c3d479&step=matrix`

**Project UUID:** `f47ac10b-58cc-4372-a567-0e02b2c3d479`

---

### Step 3: Test API Endpoint #1 - SAVE

**Method:** POST  
**URL:** `http://acumena.test/api/project/prioritized-strategies/save`

**Headers:**

```
Content-Type: application/json
```

**Body (JSON):**

```json
{
	"project_uuid": "f47ac10b-58cc-4372-a567-0e02b2c3d479",
	"strategies": [
		{
			"pair_type": "S-O",
			"strategy_code": "SO1",
			"strategy_statement": "Manfaatkan kekuatan untuk merebut peluang pasar",
			"priority_rank": 1,
			"priority_score": 0.95,
			"selected_by_user": true
		},
		{
			"pair_type": "S-T",
			"strategy_code": "ST1",
			"strategy_statement": "Gunakan kekuatan untuk menghadapi ancaman kompetitor",
			"priority_rank": 2,
			"priority_score": 0.85,
			"selected_by_user": true
		},
		{
			"pair_type": "W-O",
			"strategy_code": "WO1",
			"strategy_statement": "Perbaiki kelemahan untuk memanfaatkan peluang",
			"priority_rank": 3,
			"priority_score": 0.75,
			"selected_by_user": true
		},
		{
			"pair_type": "W-T",
			"strategy_code": "WT1",
			"strategy_statement": "Mitigasi kelemahan untuk hindari ancaman",
			"priority_rank": 4,
			"priority_score": 0.65,
			"selected_by_user": true
		}
	]
}
```

**Expected Response (HTTP 200):**

```json
{
  "success": true,
  "message": "Prioritized strategies saved successfully",
  "data": {
    "saved_count": 4,
    "strategies": [
      {
        "id": 1,
        "uuid": "...",
        "project_id": 1,
        "pair_type": "S-O",
        "strategy_code": "SO1",
        "strategy_statement": "Manfaatkan kekuatan untuk merebut peluang pasar",
        "priority_rank": 1,
        "priority_score": "0.9500",
        "status": "draft",
        "selected_by_user": 1,
        "created_at": "2025-12-12 15:30:45",
        "updated_at": "2025-12-12 15:30:45"
      },
      ...
    ]
  }
}
```

✅ **Jika response 200 → SAVE endpoint WORKING**

---

### Step 4: Test API Endpoint #2 - GET

**Method:** GET  
**URL:** `http://acumena.test/api/project/prioritized-strategies?project_uuid=f47ac10b-58cc-4372-a567-0e02b2c3d479`

**Expected Response (HTTP 200):**

```json
{
  "success": true,
  "data": {
    "project_uuid": "f47ac10b-58cc-4372-a567-0e02b2c3d479",
    "strategies": [
      {
        "id": 1,
        "uuid": "...",
        "pair_type": "S-O",
        "strategy_code": "SO1",
        "status": "draft",
        ...
      }
    ],
    "summary": {
      "total": 4,
      "draft": 4,
      "approved": 0,
      "in_progress": 0,
      "completed": 0,
      "archived": 0
    }
  }
}
```

✅ **Jika response 200 → GET endpoint WORKING**

---

### Step 5: Test API Endpoint #3 - UPDATE

**Method:** PUT  
**URL:** `http://acumena.test/api/project/prioritized-strategies/1`

**Body (JSON):**

```json
{
	"status": "approved",
	"internal_notes": "Ini prioritas utama untuk Q1 2026",
	"priority_rank": 1
}
```

**Expected Response (HTTP 200):**

```json
{
	"success": true,
	"message": "Strategy updated successfully"
}
```

✅ **Jika response 200 → UPDATE endpoint WORKING**

---

### Step 6: Test API Endpoint #4 - DELETE

**Method:** DELETE  
**URL:** `http://acumena.test/api/project/prioritized-strategies/4`

**Expected Response (HTTP 200):**

```json
{
	"success": true,
	"message": "Strategy deleted successfully"
}
```

✅ **Jika response 200 → DELETE endpoint WORKING**

---

## 🧪 TESTING DARI BROWSER UI

### Step 1: Buka Project

1. Buka `http://acumena.test/project?uuid=YOUR-UUID&step=matrix`
2. Pastikan sudah login

### Step 2: Generate Strategies

1. Klik **"Generate Recommendations"** button
2. Tunggu sampai strategies di-generate di SO, ST, WO, WT containers
3. Lihat ada strategi di container (SO1, SO2, ST1, ST2, dll)

### Step 3: Lihat "Save to Database" Button

1. Tombol "Save to Database" harusnya muncul setelah ada strategies
2. Tombol berwarna hijau (gradient-success)
3. Tombol berada di sebelah "Generate Recommendations"

### Step 4: Klik Save Button

1. Klik tombol "Save to Database"
2. Tunggu response dari server (button text berubah "Saving...")
3. Lihat alert "Strategies saved successfully!"
4. Tombol berubah "Saved ✓" selama 2 detik

### Step 5: Verifikasi Success

- Alert muncul dengan pesan "Strategies saved successfully!"
- Button kembali ke state normal
- Console tidak ada error (Ctrl+Shift+I → Console tab)

---

## 🗄️ DATABASE VERIFICATION

### Step 1: Check Data di MySQL

```sql
-- Connect ke MySQL
mysql -u root -p acumena

-- Lihat semua strategies yang baru disave
SELECT id, uuid, pair_type, strategy_code, priority_rank, status, created_at
FROM project_prioritized_strategies
ORDER BY created_at DESC
LIMIT 10;

-- Lihat count per status
SELECT status, COUNT(*) as count
FROM project_prioritized_strategies
WHERE is_deleted IS NULL
GROUP BY status;

-- Lihat strategies untuk project tertentu
SELECT *
FROM project_prioritized_strategies
WHERE project_id = (SELECT id FROM projects WHERE uuid = 'YOUR-UUID')
ORDER BY priority_rank;
```

**Expected Result:**

```
mysql> SELECT id, uuid, pair_type, strategy_code, priority_rank, status, created_at
       FROM project_prioritized_strategies
       ORDER BY created_at DESC LIMIT 10;

+----+--------------------------------------+----------+---------------+---------------+--------+---------------------+
| id | uuid                                 | pair_type| strategy_code | priority_rank | status | created_at          |
+----+--------------------------------------+----------+---------------+---------------+--------+---------------------+
|  4 | a1b2c3d4-e5f6-7890-abcd-ef1234567890| W-T      | WT1           |             4 | draft  | 2025-12-12 15:30:45 |
|  3 | b2c3d4e5-f6a7-0891-bcde-f12345678901| W-O      | WO1           |             3 | draft  | 2025-12-12 15:30:45 |
|  2 | c3d4e5f6-a7b8-1902-cdef-123456789012| S-T      | ST1           |             2 | draft  | 2025-12-12 15:30:45 |
|  1 | d4e5f6a7-b8c9-2013-def0-234567890123| S-O      | SO1           |             1 | draft  | 2025-12-12 15:30:45 |
+----+--------------------------------------+----------+---------------+---------------+--------+---------------------+
```

✅ **Jika 4 rows ada → Data tersimpan SUKSES**

---

## 🐛 TROUBLESHOOTING

### Error 1: "Unauthorized access" (401)

```
Cause: Belum login
Fix: Login dulu ke aplikasi sebelum test API
```

### Error 2: "Method not allowed" (405)

```
Cause: HTTP method salah (pastikan POST/GET/PUT/DELETE correct)
Fix: Check Postman method match dengan endpoint
```

### Error 3: "Project not found" (403)

```
Cause: project_uuid salah atau tidak dimiliki user
Fix: Copy UUID yang benar dari URL aplikasi
```

### Error 4: "Invalid JSON" (400)

```
Cause: JSON format salah
Fix: Check JSON syntax di Postman, pastikan valid JSON
```

### Error 5: Button "Save to Database" tidak muncul

```
Cause: Belum ada strategies, atau JavaScript error
Fix:
  1. Generate strategies dulu
  2. Check console (Ctrl+Shift+I) untuk JS error
  3. Refresh halaman
```

### Error 6: Data tidak muncul di database

```
Cause: Connection error atau validation fail
Fix:
  1. Check MySQL running
  2. Check error di console/logs
  3. Verify database credentials
```

---

## 📋 TESTING CHECKLIST

### API Testing ✓

- [ ] **POST Save** - Save 4 strategies successfully (HTTP 200)
- [ ] **GET Retrieve** - Get strategies back with summary (HTTP 200)
- [ ] **PUT Update** - Update strategy status (HTTP 200)
- [ ] **DELETE** - Soft delete strategy (HTTP 200)
- [ ] **GET After Delete** - Deleted strategy tidak include dalam list (HTTP 200)

### UI Testing ✓

- [ ] **Button Visibility** - Save button muncul setelah generate strategies
- [ ] **Button Click** - Click save button triggers POST
- [ ] **Loading State** - Button text change to "Saving..."
- [ ] **Success Message** - Alert show "Strategies saved successfully!"
- [ ] **Data Persistence** - Refresh page, data masih ada (read dari DB)

### Database Testing ✓

- [ ] **Data Insert** - 4 rows created di project_prioritized_strategies
- [ ] **Timestamps** - created_at & updated_at set automatically
- [ ] **Status** - Status='draft' oleh default
- [ ] **Soft Delete** - is_deleted set setelah delete, tapi data masih ada
- [ ] **Foreign Keys** - project_id & created_by_user_id link dengan benar

### Error Handling ✓

- [ ] **Invalid Project UUID** - Return 403 "Project not found"
- [ ] **Unauthorized** - Return 401 if not logged in
- [ ] **Invalid Method** - Return 405 "Method not allowed"
- [ ] **Empty Strategies** - Return 400 "strategies required"

---

## ✅ FINAL VERIFICATION

Ketika semua test di atas PASS, implementasi SUKSES!

```
✅ SAVE (POST) works
✅ GET (Retrieve) works
✅ UPDATE (PUT) works
✅ DELETE works
✅ Button visible & clickable
✅ Data persisted in database
✅ Error handling works
✅ Security validation works

→ READY FOR PRODUCTION DEPLOYMENT
```

---

**Estimated Test Duration:** 15-20 minutes

**Next Step:** Integrate ke dashboard & user flow

Generated: 12 Desember 2025
