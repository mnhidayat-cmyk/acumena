# 🔍 Quick Reference: Tabel Tidak Terpakai

## 📌 TL;DR (Top-Level Summary)

```
Database: acumena
Total Tabel: 17

AKTIF: 14 tabel ✅ (82%)
  - user, projects, project_swot, subscriptions
  - user_subscription, user_subscription_history
  - user_password_resets, user_verify, user_role
  - project_ai_generation_run, ai_pair_filtered, ai_strategy
  - settings, matrix_ie_quadrant_strategies

TIDAK TERPAKAI: 3 tabel ❌ (18%)
  1. user_role_menu      → Orphan table, kosong
  2. user_menu           → Abandoned feature, kosong
  3. project_swot_strategy → Legacy, replaced by ai_pair_filtered
```

---

## 🗑️ Tabel Yang DAPAT DIHAPUS

### 1️⃣ `user_role_menu` - AMAN DIHAPUS

```sql
-- Status: Completely unused orphan table
-- Records: 0
-- Code References: NONE
-- Dependencies: FK to user_menu.id (juga kosong)

-- Safe to delete:
DROP TABLE IF EXISTS user_role_menu;
```

**Alasan:**

- Dirancang untuk role-based menu management
- Tidak ada implementasi di aplikasi
- Tidak ada data sama sekali
- Foreign key hanya point ke tabel kosong (`user_menu`)

---

### 2️⃣ `project_swot_strategy` - AMAN DIHAPUS

```sql
-- Status: Abandoned, no code usage
-- Records: 0
-- Code References: NONE
-- Reason: Replaced by (ai_pair_filtered + ai_strategy)

-- Safe to delete:
DROP TABLE IF EXISTS project_swot_strategy;
```

**Alasan:**

- Desain lama untuk strategy storage
- Replaced oleh kombinasi tabel AI yang lebih baik
- Tidak ada INSERT/SELECT/UPDATE dalam kode aplikasi
- Kosong dan tidak dibutuhkan

---

### 3️⃣ `user_menu` - CONDITIONAL DELETE

```sql
-- Status: Empty, no direct usage
-- Records: 0
-- Code References: NONE (except in FK constraint)
-- Note: Only referenced by user_role_menu (juga kosong)

-- Safe to delete IF:
-- 1. Already deleted user_role_menu
-- 2. No future plans untuk menu management

DROP TABLE IF EXISTS user_menu;
```

**Alasan:**

- Desain untuk dynamic menu system yang tidak diimplementasikan
- Tidak ada data atau code usage
- Hanya ada orphan FK constraint dari `user_role_menu`

---

## ⚠️ Tabel Yang PERLU DIPERTIMBANGKAN

### `Topk_service_model` - UNCLEAR

```
Status: Model exists, tapi table unclear
File: application/models/Topk_service_model.php
Usage: Loaded di Api_project.php tapi TIDAK DIGUNAKAN
Action: Review, then remove if not needed
```

---

## 📊 Impact Analysis

### Storage Impact (Before vs After)

```
BEFORE cleanup:
  user_role_menu:        ~100 bytes (0 rows)
  user_menu:             ~100 bytes (0 rows)
  project_swot_strategy: ~100 bytes (0 rows)
  ─────────────────────
  Total unused:          ~300 bytes

AFTER cleanup:
  All removed
  ─────────────────────
  Storage freed:         ~300 bytes (minimal, but cleaner)
```

### Code Impact

```
BEFORE: 17 tables to manage
AFTER:  14 tables to maintain

Reduction: 18% fewer tables to worry about
Benefit: Cleaner schema, less maintenance
```

### Backup Recommendation

Before deleting, backup these statements:

```sql
-- Backup untuk safety
CREATE TABLE user_role_menu_backup LIKE user_role_menu;
INSERT INTO user_role_menu_backup SELECT * FROM user_role_menu;

CREATE TABLE user_menu_backup LIKE user_menu;
INSERT INTO user_menu_backup SELECT * FROM user_menu;

CREATE TABLE project_swot_strategy_backup LIKE project_swot_strategy;
INSERT INTO project_swot_strategy_backup SELECT * FROM project_swot_strategy;
```

---

## 🎯 Cleanup Checklist

- [ ] Backup database (IMPORTANT!)
- [ ] Backup unused tables (if paranoid)
- [ ] Drop `user_role_menu`
- [ ] Drop `project_swot_strategy`
- [ ] Drop `user_menu` (optional, if no future plans)
- [ ] Review `Topk_service_model.php` (remove if unused)
- [ ] Test application thoroughly
- [ ] Commit changes

---

## 🚀 Step-by-Step Cleanup Script

```sql
-- Step 1: Backup database (gunakan phpmyadmin export)
-- SKIPPED (do manually first)

-- Step 2: Drop orphan tables
DROP TABLE IF EXISTS user_role_menu;
DROP TABLE IF EXISTS project_swot_strategy;
DROP TABLE IF EXISTS user_menu;

-- Step 3: Verify database integrity
-- Run these to verify
SELECT COUNT(*) FROM projects;
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM project_ai_generation_run;
-- (all should work fine)

-- Step 4: Done! Clean database
SHOW TABLES; -- Should show 14 tables only
```

---

## 📝 Notes

- **Tidak ada risk** untuk delete 3 tabel ini - tidak digunakan di aplikasi
- **Semua tabel utama** sudah aman dan aktif
- **Design improvements** bisa dilakukan untuk cleanup schema
- **Rekomendasi:** Delete minimal `user_role_menu` dan `project_swot_strategy` untuk cleaner DB

---

Generated: 2025-12-12
