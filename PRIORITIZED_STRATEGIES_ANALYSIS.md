# 📌 Analisis: Generate Prioritized Strategies

**Pertanyaan:** Apakah ada tabel untuk menaungi fitur "Generate Prioritized Strategies"?

**Status:** ⚠️ **TIDAK ADA TABEL KHUSUS** - Menggunakan tabel yang sudah ada

---

## 🔍 Temuan Detail

### Lokasi Fitur

- **UI:** `application/views/projects/matrix-ai.php` (baris 262-291)
- **API:** Endpoint `POST /api/project/strategies`
- **Controller:** `Api_project.php` → berbagai method

---

## 📊 Alur "Generate Prioritized Strategies"

```
┌─────────────────────────────────────────────────────────────────┐
│ UI: matrix-ai.php - "Prioritized Strategies" Section             │
│                                                                  │
│ Flow:                                                            │
│ 1. User generate strategi untuk SO/ST/WO/WT (4 quadrant)       │
│ 2. Klik "Generate Recommendations" button                        │
│ 3. System query IE Matrix position (dari IFE + EFE scores)     │
│ 4. Call AI dengan prompt berdasarkan quadrant                   │
│ 5. Display prioritized strategies (TIDAK DISIMPAN)             │
└─────────────────────────────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────────────────────────────┐
│ Data Flow - TABEL YANG DIGUNAKAN                                 │
│                                                                  │
│ READ:                                                            │
│ • project_ai_generation_run        (Cek active run)             │
│ • ai_pair_filtered                 (Ambil prioritized pairs)    │
│ • ai_strategy                      (Ambil generated strategies)  │
│ • matrix_ie_quadrant_strategies    (Ambil rekomendasi template) │
│ • project_swot                     (Ambil SWOT items)           │
│                                                                  │
│ WRITE:                                                           │
│ • NONE (tidak ada insert/update)                                │
│ • Hanya READ & DISPLAY (stateless)                              │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🗄️ Tabel yang Terlibat

### 1. **ai_pair_filtered** ✅ (Digunakan untuk prioritas)

```sql
-- Menyimpan SWOT pairs dengan priority score
-- DIGUNAKAN untuk menampilkan prioritized strategies

SELECT * FROM ai_pair_filtered
WHERE project_id = X
ORDER BY final DESC;  -- Final score untuk prioritas
```

**Kolom penting:**

- `run_id` - Link ke generation run
- `pair_type` - S-O, W-O, S-T, W-T
- `left_text` / `right_text` - Pasangan SWOT
- `priority` - Priority score
- `rel` - Relevance score
- `final` - Final combined score (untuk sorting prioritas)

---

### 2. **ai_strategy** ✅ (Menyimpan hasil AI)

```sql
-- Menyimpan strategy text yang di-generate AI

SELECT * FROM ai_strategy
WHERE run_id = X
ORDER BY priority_score DESC;
```

**Kolom penting:**

- `run_id` - Link ke generation run
- `pair_id` - Link ke ai_pair_filtered (optional)
- `strategy_text` - Strategi yang di-generate
- `type` - Tipe strategi
- `priority_score` - Score untuk prioritas

---

### 3. **matrix_ie_quadrant_strategies** ✅ (Master data rekomendasi)

```sql
-- Menyimpan template rekomendasi strategis per quadrant
-- DIGUNAKAN untuk info box di atas prioritized strategies

SELECT strategy FROM matrix_ie_quadrant_strategies
WHERE quadrant = 'I'
AND is_deleted IS NULL;
```

**Isi:** 27 records dengan strategi per quadrant IE Matrix

---

### 4. **project_swot** ✅ (Data SWOT items)

```sql
-- SWOT items yang akan di-pair untuk generate prioritas

SELECT description FROM project_swot
WHERE project_id = X
AND category = 'S'
AND is_deleted IS NULL;
```

---

### 5. **project_ai_generation_run** ✅ (Pipeline tracking)

```sql
-- Track AI generation runs untuk setiap pair_type

SELECT * FROM project_ai_generation_run
WHERE project_id = X
AND pair_type = 'S-O'
AND is_active = 1;
```

---

## ❌ Tabel TIDAK Digunakan

### `project_swot_strategy` ⚠️ (ABANDONED)

```sql
-- TIDAK DIGUNAKAN untuk menyimpan prioritized strategies
-- SEMUA hasil hanya di-display via API (stateless)
-- Tidak ada INSERT ke tabel ini dari controller

SELECT COUNT(*) FROM project_swot_strategy;  -- Result: 0
```

**Alasan:** Tabel ini designed untuk menyimpan strategi, tapi aplikasi hanya display result dari AI tanpa persist ke database.

---

## 📋 API Endpoints untuk "Prioritized Strategies"

### 1. **Generate Strategies (SO/ST/WO/WT)**

```
POST /api/project/strategies

Body:
{
  "project": 3,
  "pair_type": "S-O",
  "run": 15,
  "lang": "id"
}

Response:
{
  "success": true,
  "data": {
    "strategies": [
      {
        "code": "SO1",
        "statement": "Manfaatkan minimum order kecil untuk menekan pasar...",
        "priority_score": 0.85
      },
      ...
    ]
  }
}

Storage: ai_strategy table
```

### 2. **Get Existing Strategies**

```
GET /api/project/strategies_list?project=3&pair_type=S-O

Response:
{
  "success": true,
  "data": {
    "strategies": [
      {
        "code": "SO1",
        "statement": "...",
        "priority_score": 0.85
      }
    ]
  }
}

Source: ai_strategy table
```

### 3. **Get Recommendation (IE Matrix)**

```
GET /api/project/get_recommendation_strategy?quadrant=I

Response:
{
  "success": true,
  "data": [
    "Prioritaskan strategi SO...",
    "Gunakan kekuatan untuk menangkap...",
    "Perkuat posisi kompetitif..."
  ]
}

Source: matrix_ie_quadrant_strategies table
```

---

## 🎯 Data Flow Diagram

```
START: User di matrix-ai.php
  ↓
[Generate Strategies Button (SO/ST/WO/WT)]
  ↓
POST /api/project/strategies
  ├─ Read: project_ai_generation_run (cek active run)
  ├─ Read: project_swot (ambil SWOT items)
  ├─ Call AI (Gemini) dengan prompt
  └─ Write: ai_strategy (simpan hasil)
  ↓
[Display generated strategies dalam UI]
  ├─ Sort by priority_score DESC
  ├─ Show code, statement, score
  ├─ Render di SO/ST/WO/WT containers
  └─ NO PERSIST (stateless display)
  ↓
[Display "Prioritized Strategies" section]
  ├─ Read: matrix_ie_quadrant_strategies (quadrant template)
  ├─ Read: ai_strategy (get top strategies)
  ├─ Read: ai_pair_filtered (get pairs with scores)
  ├─ Sort by final score DESC
  ├─ Display as prioritized list
  └─ NO SAVE (read-only)
  ↓
END: User sees recommendations on screen
```

---

## ✅ Summary Jawaban

| Aspek                                                 | Status     | Detail                                                               |
| ----------------------------------------------------- | ---------- | -------------------------------------------------------------------- |
| **Ada tabel untuk menyimpan prioritized strategies?** | ❌ NO      | Tidak ada tabel khusus                                               |
| **Tabel yang digunakan?**                             | ✅ YES     | `ai_strategy`, `ai_pair_filtered`, `matrix_ie_quadrant_strategies`   |
| **Apakah hasil disimpan?**                            | ⚠️ PARTIAL | Hanya di `ai_strategy`, tidak ada separate table untuk "prioritized" |
| **Fitur sudah lengkap?**                              | ✅ YES     | Sudah berfungsi, hanya display-only                                  |
| **Perlu tabel baru?**                                 | ❓ MAYBE   | Jika ingin save user's prioritized selection, butuh tabel baru       |

---

## 🚀 Rekomendasi

### Opsi 1: CURRENT (Stateless) - SUDAH BERJALAN

```
Status: ✅ Berjalan
Hasil: Display only, tidak disimpan
Pros: Simple, no persistence needed
Cons: User tidak bisa save pilihan prioritas mereka
```

### Opsi 2: SAVE PRIORITIZED SELECTION (Jika diperlukan)

```sql
-- Tambah tabel baru untuk menyimpan user selection
CREATE TABLE prioritized_strategies (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  project_id BIGINT UNSIGNED NOT NULL,
  ai_strategy_id BIGINT UNSIGNED,
  priority_rank INT,
  user_notes TEXT,
  status ENUM('selected', 'in_progress', 'completed'),
  created_at TIMESTAMP DEFAULT NOW(),
  FOREIGN KEY (project_id) REFERENCES projects(id),
  FOREIGN KEY (ai_strategy_id) REFERENCES ai_strategy(id)
);
```

Ini akan replace/complement `project_swot_strategy` yang sekarang kosong.

---

## 📝 Catatan

- **Fitur "Prioritized Strategies"** adalah read-only display dari kombinasi `ai_strategy` + `matrix_ie_quadrant_strategies`
- **Tidak ada persist** ke tabel khusus - hanya query dan display
- **Tabel `project_swot_strategy`** bisa digunakan jika ingin save user's finalized strategy selection (currently abandoned)
- **Flow sudah optimal** untuk demonstration/analysis phase, tapi jika butuh save user choices, perlu tambah tabel atau gunakan ulang `project_swot_strategy`

---

Generated: 2025-12-12
