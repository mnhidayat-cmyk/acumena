# 🐛 Debug Guide: Load Existing Strategies

## Masalah: Strategi tidak tampil saat halaman di-reload

### Solusi yang Sudah Diterapkan

#### 1. **Improved Logging in Frontend**

- Ditambahkan console.log yang detail di:
  - `resolveProjectId()` - menunjukkan project key & ID yang diresolve
  - `loadExisting()` - menunjukkan API call, response, dan render status
  - Main load sequence - menunjukkan overall progress

#### 2. **Struktur Data Response**

- **Endpoint:** `GET /api/project/strategies_list?project=X&pair_type=S-O`
- **Response Format:**

```json
{
	"success": true,
	"message": "Existing strategies fetched",
	"data": {
		"run_id": 123,
		"stage": "completed",
		"pair_type": "S-O",
		"strategies": [
			{
				"id": 1,
				"run_id": 123,
				"project_id": 5,
				"pair_type": "S-O",
				"code": "SO1",
				"statement": "Strategy statement here",
				"created_at": "2025-12-12 10:30:00"
			}
		]
	}
}
```

---

## 🔍 Cara Debug

### Step 1: Buka Browser Console (F12)

```
Tekan F12 → Console tab
```

### Step 2: Reload Halaman & Lihat Console Logs

```
Logs yang seharusnya muncul:

[1] "Resolving project ID for key: abc-123-def"
    ↓
[2] "✅ Project ID resolved: 5"
    ↓
[3] "🔄 Starting to load existing strategies for all 4 quadrants..."
    ↓
[4] "Loading existing strategies for SO (S-O) from: /api/project/strategies_list?project=5&pair_type=S-O"
    ↓
[5] "Response for SO: { success: true, message: '...', data: { ... } }"
    ↓
[6] "Found 3 existing strategies for SO"
    ↓
[7] "✅ Rendered 3 strategies for SO"
    (Repeat untuk ST, WO, WT)
    ↓
[8] "✅ All strategies loaded successfully"
```

---

## 🚨 Troubleshooting

### Masalah 1: Project Key Tidak Ditemukan

**Console Log:**

```
❌ Project key tidak ditemukan di URL (parameter ?key=)
```

**Solusi:**

- Pastikan URL memiliki parameter `?key=uuid-project`
- Contoh: `http://localhost/project/add?step=matrix-ai&key=550e8400-e29b-41d4-a716-446655440000`

---

### Masalah 2: Project ID Tidak Bisa Diresolve

**Console Log:**

```
❌ Gagal memuat project ID: [error details]
```

**Solusi:**

- Cek apakah endpoint `/api/project/profile_get?uuid=...` berfungsi:
  ```bash
  curl "http://localhost/api/project/profile_get?uuid=550e8400-e29b-41d4-a716-446655440000"
  ```
- Pastikan project dengan UUID tersebut ada di database
- Cek apakah user sudah login (session check)

---

### Masalah 3: API strategies_list Mengembalikan Error

**Console Log:**

```
⚠️ strategies_list API returned status 404 for SO
```

**Solusi:**

- Cek apakah route sudah di-register di `routes.php`:
  ```php
  $route['api/project/strategies_list'] = 'api_project/strategies_list';
  ```
- Test endpoint manual:
  ```bash
  curl "http://localhost/api/project/strategies_list?project=5&pair_type=S-O"
  ```

---

### Masalah 4: API Response Kosong

**Console Log:**

```
No strategies found for SO - keeping placeholder visible
```

**Penjelasan:**

- Ini **bukan error** - berarti project belum punya strategi yang di-generate
- User perlu klik "Generate Strategies" dulu untuk setiap quadrant

---

## 📊 Testing Checklist

- [ ] Buka halaman dengan URL yang benar (ada parameter `?key=`)
- [ ] Buka Console (F12)
- [ ] Reload halaman
- [ ] Lihat logs muncul sequentially
- [ ] Jika ada error, screenshot console dan bagikan
- [ ] Test di browser berbeda (Chrome, Firefox, Edge)
- [ ] Clear browser cache & reload

---

## 🔧 Advanced: Manual Testing API

### Test profile_get

```bash
curl "http://localhost/api/project/profile_get?uuid=550e8400-e29b-41d4-a716-446655440000"
```

**Expected Response:**

```json
{
	"success": true,
	"data": {
		"id": 5,
		"project_id": 5,
		"uuid": "550e8400-e29b-41d4-a716-446655440000",
		"company_name": "PT Demo"
	}
}
```

### Test strategies_list

```bash
curl "http://localhost/api/project/strategies_list?project=5&pair_type=S-O"
```

**Expected Response (with data):**

```json
{
  "success": true,
  "message": "Existing strategies fetched",
  "data": {
    "run_id": 123,
    "strategies": [...]
  }
}
```

**Expected Response (no data):**

```json
{
	"success": true,
	"message": "Existing strategies fetched",
	"data": {
		"run_id": null,
		"strategies": []
	}
}
```

---

## 📝 Logs Interpretation

| Log | Meaning                          |
| --- | -------------------------------- |
| ✅  | Success - something worked       |
| ⚠️  | Warning - might be an issue      |
| ❌  | Error - definitely a problem     |
| 🔄  | Progress - operation in progress |
| 📥  | Data received                    |

---

## 💡 Tips

1. **Copy-paste console logs** untuk debug lebih mudah
2. **Jangan reload F5** saat testing - gunakan Ctrl+Shift+R (hard refresh)
3. **Check Network tab** (F12 → Network) untuk melihat API calls
4. **Verify database** punya strategi dengan query:
   ```sql
   SELECT * FROM ai_strategy WHERE project_id = 5;
   ```
