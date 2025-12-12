# 📊 Analisis Tabel Database Tidak Terpakai

**Database:** acumena  
**Total Tabel:** 17  
**Analisis Tanggal:** 12 Desember 2025

---

## 📋 Ringkasan

| Kategori                   | Jumlah | Tabel                                       |
| -------------------------- | ------ | ------------------------------------------- |
| ✅ **AKTIF (Terpakai)**    | 12     | Lihat di bawah                              |
| ⚠️ **SEMI-AKTIF (Jarang)** | 2      | `project_swot_strategy`, `user_menu`        |
| ❌ **TIDAK TERPAKAI**      | 3      | `user_role_menu`, `topk_service` (jika ada) |

---

## ✅ TABEL AKTIF (12 tabel)

### 1. **users** ✨

- **Status:** AKTIF
- **Penggunaan:** Auth & user management
- **Model:** Auth_model.php
- **Data:** 4 records
- **Penting:** Tabel utama untuk autentikasi

### 2. **projects** ✨

- **Status:** AKTIF
- **Penggunaan:** Project/SWOT analysis management
- **Model:** Project_model.php
- **Data:** 3 records
- **Penting:** Core business entity

### 3. **project_swot** ✨

- **Status:** AKTIF
- **Penggunaan:** SWOT items (Strengths, Weaknesses, Opportunities, Threats)
- **Model:** Project_model.php, Swot_model.php
- **Data:** 22 records
- **Penting:** Tabel data SWOT per project

### 4. **subscriptions** ✨

- **Status:** AKTIF
- **Penggunaan:** Subscription plans (Trial, Pro Plan)
- **Model:** Subscription_model.php
- **Data:** 2 records (Trial, Pro Plan)
- **Penting:** Pricing & features management

### 5. **user_subscription** ✨

- **Status:** AKTIF
- **Penggunaan:** Mapping user ke subscription type
- **Model:** Subscription_model.php
- **Data:** 3 records
- **Penting:** User subscription assignment

### 6. **user_subscription_history** ✨

- **Status:** AKTIF
- **Penggunaan:** Subscription transaction history
- **Model:** Subscription_model.php
- **Data:** 4 records (invoice, billing)
- **Penting:** Payment & billing tracking

### 7. **user_password_resets** ✨

- **Status:** AKTIF
- **Penggunaan:** Password reset tokens
- **Model:** Auth_model.php
- **Data:** 3 records
- **Penting:** Security untuk reset password

### 8. **user_verify** ✨

- **Status:** AKTIF
- **Penggunaan:** Email OTP verification
- **Model:** Auth_model.php
- **Data:** 1 record
- **Penting:** Email verification during registration

### 9. **settings** ✨

- **Status:** AKTIF
- **Penggunaan:** Application configuration
- **Model:** Semua (via helper get_setting())
- **Data:** 19 records (API keys, email config, etc)
- **Penting:** Critical configuration storage

### 10. **project_ai_generation_run** ✨

- **Status:** AKTIF
- **Penggunaan:** AI strategy generation tracking
- **Model:** Project_ai_generation_run_model.php
- **Data:** 15 records
- **Penting:** AI generation pipeline tracking

### 11. **ai_pair_filtered** ✨

- **Status:** AKTIF
- **Penggunaan:** AI-generated SWOT pairs & strategies
- **Model:** Ai_pair_filtered_model.php
- **Data:** 1 record
- **Penting:** Output dari AI processing

### 12. **ai_strategy** ✨

- **Status:** AKTIF
- **Penggunaan:** AI-generated strategy text
- **Model:** Ai_strategy_model.php
- **Data:** (empty)
- **Penting:** Strategy output storage

### 13. **user_role** ✨

- **Status:** AKTIF
- **Penggunaan:** User role definition (User, Admin)
- **Model:** Referenced via users.role_id FK
- **Data:** 2 records
- **Penting:** Role-based access control

### 14. **matrix_ie_quadrant_strategies** ✨

- **Status:** AKTIF
- **Penggunaan:** IE Matrix quadrant recommendation strategies
- **Model:** Project_model.php → get_recommendation_strategy()
- **Data:** 27 records
- **Penting:** Master data untuk matrix recommendations

---

## ⚠️ TABEL SEMI-AKTIF (JARANG DIGUNAKAN)

### 1. **project_swot_strategy** ⚠️

- **Status:** SEMI-AKTIF (Tidak ada penggunaan ditemukan)
- **Struktur:**
  ```sql
  - id (bigint)
  - project_id (bigint)
  - description (text)
  - category (enum: SO, ST, WO, WT, main)
  - ai_agent (varchar)
  - date_created (timestamp)
  - is_deleted (tinyint)
  ```
- **Data:** 0 records (kosong)
- **Penggunaan Model:** TIDAK DITEMUKAN
- **Kesimpulan:**
  - ❌ Tidak ada INSERT, SELECT, UPDATE dari kode aplikasi
  - Mungkin abandoned dari design lama atau untuk fitur yang belum diimplementasikan
  - **Rekomendasi:** Dapat dihapus atau diarsipkan

### 2. **user_menu** ⚠️

- **Status:** SEMI-AKTIF (Hanya struktur, tidak ada data)
- **Struktur:**
  ```sql
  - id (bigint)
  - menu_name (varchar)
  - slug (varchar)
  - short (varchar)
  - date_created (timestamp)
  - last_update (timestamp)
  - is_deleted (tinyint)
  ```
- **Data:** 0 records (kosong)
- **Penggunaan Model:** TIDAK DITEMUKAN
- **Kesimpulan:**
  - ❌ Tidak ada penggunaan ditemukan di kode
  - Kemungkinan untuk fitur menu management yang belum diimplementasikan
  - **Rekomendasi:** Dapat dihapus atau dipertahankan untuk fitur masa depan

---

## ❌ TABEL TIDAK TERPAKAI (3 tabel)

### 1. **user_role_menu** ❌

- **Status:** TIDAK TERPAKAI
- **Struktur:**
  ```sql
  - id (bigint)
  - role_id (bigint FK -> user_role.id)
  - menu_id (bigint FK -> user_menu.id)
  ```
- **Data:** 0 records (kosong)
- **Penggunaan Model:** TIDAK DITEMUKAN
- **Relasi:**
  - FK ke `user_menu` (juga kosong)
  - FK ke `user_role` (aktif, tapi tidak digunakan untuk role-based menu)
- **Kesimpulan:**
  - ❌ Orphan table - referenced tables juga tidak terpakai
  - Desain untuk role-based menu system yang tidak diimplementasikan
  - **Rekomendasi:** DAPAT DIHAPUS DENGAN AMAN

### 2. **Topk_service** (Model exists but table unclear) ⚠️

- **Status:** MUNGKIN TIDAK TERPAKAI
- **Model:** `Topk_service_model.php` ada, tapi tidak jelas tabel mana yang digunakan
- **Catatan:** Tidak ada tabel dengan nama ini di database
- **Penggunaan:** Loaded di Api_project.php tapi tidak digunakan
- **Rekomendasi:** Cek apakah ini legacy code yang bisa dihapus

---

## 📊 Tabel Relationship Diagram

```
┌─────────────────────────────────────────────────────────┐
│                    ACTIVE RELATIONSHIPS                  │
└─────────────────────────────────────────────────────────┘

users (✨)
  ├── FK role_id ──→ user_role (✨)
  ├── 1:N ──→ projects (✨)
  ├── 1:N ──→ user_subscription (✨)
  ├── 1:N ──→ user_subscription_history (✨)
  ├── 1:N ──→ user_password_resets (✨)
  └── 1:N ──→ user_verify (✨)

projects (✨)
  ├── FK user_id ──→ users (✨)
  ├── 1:N ──→ project_swot (✨)
  └── 1:N ──→ project_ai_generation_run (✨)

project_swot (✨)
  └── FK project_id ──→ projects (✨)

project_ai_generation_run (✨)
  ├── FK project_id ──→ projects (✨)
  ├── 1:N ──→ ai_pair_filtered (✨)
  └── 1:N ──→ ai_strategy (✨)

ai_pair_filtered (✨)
  ├── FK run_id ──→ project_ai_generation_run (✨)
  └── FK project_id ──→ projects (✨)

ai_strategy (✨)
  ├── FK run_id ──→ project_ai_generation_run (✨)
  └── FK project_id ──→ projects (✨)

subscriptions (✨)
  └── 1:N ──→ user_subscription (✨)

user_subscription (✨)
  ├── FK user_id ──→ users (✨)
  └── FK subscription_id ──→ subscriptions (✨)

user_subscription_history (✨)
  ├── FK user_id ──→ users (✨)
  └── FK subscription_id ──→ subscriptions (✨)

settings (✨)
  └── Global config (no FK)

matrix_ie_quadrant_strategies (✨)
  └── Master data (no FK, referenced by Project_model.php)

┌─────────────────────────────────────────────────────────┐
│                  UNUSED RELATIONSHIPS                    │
└─────────────────────────────────────────────────────────┘

user_role (✨ - referenced only via FK)
  ├── 1:N ──→ users (✨)
  └── 1:N ──→ user_role_menu (❌ ORPHAN)
       └── FK menu_id ──→ user_menu (⚠️ EMPTY)

project_swot_strategy (⚠️ EMPTY)
  └── FK project_id ──→ projects (✨)
```

---

## 🎯 Rekomendasi Cleanup

### Tier 1: AMAN DIHAPUS (No dependencies, no code reference)

```sql
-- 1. user_role_menu (orphan, kosong)
DROP TABLE user_role_menu;

-- 2. project_swot_strategy (tidak ada kode, kosong)
DROP TABLE project_swot_strategy;

-- Estimasi: Bebas ~10KB storage
```

### Tier 2: PERTIMBANGKAN (Abandoned features)

```sql
-- 1. user_menu (kosong, tapi ada FK di user_role_menu)
-- Hanya hapus JIKA sudah hapus user_role_menu

-- 2. Topk_service_model (check apakah masih diperlukan)
-- Jika tidak, hapus model dan track penggunaan
```

### Tier 3: KEEP (Active atau future-use)

- Semua tabel ✨ AKTIF
- `user_role` (meskipun hanya basic, masih aktif via FK)
- `matrix_ie_quadrant_strategies` (master data aktif)

---

## 📈 Database Optimization Summary

| Metrik                  | Nilai    |
| ----------------------- | -------- |
| Total Tabel             | 17       |
| Tabel Aktif             | 14 (82%) |
| Tabel Tidak Terpakai    | 3 (18%)  |
| Rows Kosong (Unused)    | 3 tabel  |
| Storage Potential Freed | ~15-20KB |
| Cleanup Priority        | MEDIUM   |

---

## ✅ Kesimpulan

Aplikasi Acumena memiliki database design yang cukup bersih. Ada beberapa tabel yang merupakan hasil dari abandoned features atau planning yang tidak terselesaikan:

1. **`user_role_menu` + `user_menu`** - Abandoned role-based menu system
2. **`project_swot_strategy`** - Legacy strategy storage (replaced by `ai_pair_filtered` + `ai_strategy`)
3. **Model `Topk_service_model`** - Unclear purpose, mungkin legacy

**Rekomendasi:** Backup database, kemudian hapus 3 tabel tidak terpakai di Tier 1 untuk keep database clean.

---

_Generated: 2025-12-12_
