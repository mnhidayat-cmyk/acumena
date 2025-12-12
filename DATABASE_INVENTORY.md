# 📋 Database Tabel Inventory

## Semua 17 Tabel di Database `acumena`

| #   | Tabel                           | Status    | Records | Used In Model                   | Relasi                                    | Notes                        |
| --- | ------------------------------- | --------- | ------- | ------------------------------- | ----------------------------------------- | ---------------------------- |
| 1   | `users`                         | ✅ AKTIF  | 4       | Auth_model.php                  | PK FK (role_id)                           | User accounts, essential     |
| 2   | `projects`                      | ✅ AKTIF  | 3       | Project_model.php               | PK 1:N users                              | Main entity                  |
| 3   | `project_swot`                  | ✅ AKTIF  | 22      | Swot_model, Project_model       | FK projects                               | SWOT analysis items          |
| 4   | `subscriptions`                 | ✅ AKTIF  | 2       | Subscription_model              | PK 1:N user_sub                           | Pricing plans                |
| 5   | `user_subscription`             | ✅ AKTIF  | 3       | Subscription_model              | FK users, FK subscriptions                | User plan assignment         |
| 6   | `user_subscription_history`     | ✅ AKTIF  | 4       | Subscription_model              | FK users, FK subscriptions                | Billing history              |
| 7   | `user_password_resets`          | ✅ AKTIF  | 3       | Auth_model                      | FK users                                  | Password reset tokens        |
| 8   | `user_verify`                   | ✅ AKTIF  | 1       | Auth_model                      | None (by email)                           | Email OTP verification       |
| 9   | `user_role`                     | ✅ AKTIF  | 2       | Via FK in users                 | PK 1:N users, 1:N user_role_menu          | Role definition (User/Admin) |
| 10  | `settings`                      | ✅ AKTIF  | 19      | Helper get_setting()            | No FK                                     | App configuration            |
| 11  | `project_ai_generation_run`     | ✅ AKTIF  | 15      | Project_ai_generation_run_model | FK projects, 1:N ai_pair, 1:N ai_strategy | AI pipeline tracking         |
| 12  | `ai_pair_filtered`              | ✅ AKTIF  | 1       | Ai_pair_filtered_model          | FK run_id, FK project_id                  | AI SWOT pairs                |
| 13  | `ai_strategy`                   | ✅ AKTIF  | 0       | Ai_strategy_model               | FK run_id, FK project_id                  | AI strategy output           |
| 14  | `matrix_ie_quadrant_strategies` | ✅ AKTIF  | 27      | Project_model                   | No FK (master data)                       | IE Matrix recommendations    |
| 15  | `project_swot_strategy`         | ❌ UNUSED | 0       | NONE                            | FK projects                               | Legacy/abandoned             |
| 16  | `user_menu`                     | ⚠️ SEMI   | 0       | NONE                            | PK 1:N user_role_menu                     | Abandoned feature            |
| 17  | `user_role_menu`                | ❌ ORPHAN | 0       | NONE                            | FK user_role, FK user_menu                | Orphan table                 |

---

## 🎨 Status Legend

| Status    | Meaning                      | Action      |
| --------- | ---------------------------- | ----------- |
| ✅ AKTIF  | Digunakan aplikasi, ada data | KEEP        |
| ⚠️ SEMI   | Minimal usage atau kosong    | REVIEW      |
| ❌ UNUSED | No code reference, orphan    | DELETE SAFE |
| ❌ ORPHAN | Hanya FK constraint          | DELETE SAFE |

---

## 🗑️ Candidates for Deletion (3 tabel)

```
Priority 1: Delete these FIRST (no dependencies)
  ❌ user_role_menu      (0 rows, no code ref, orphan FK)
  ❌ project_swot_strategy (0 rows, no code ref)

Priority 2: Delete these SECOND (conditional)
  ⚠️ user_menu          (0 rows, only ref'd by user_role_menu)
```

---

## 🔗 Key Relationships Summary

### Main Flow (Users → Projects → SWOT → AI)

```
users (4)
  ↓ (1:N)
projects (3)
  ↓ (1:N)
project_swot (22)
  ↓ (via project_id)
project_ai_generation_run (15)
  ├→ ai_pair_filtered (1)
  └→ ai_strategy (0)
```

### Subscription Flow

```
subscriptions (2)
  ↓ (1:N)
user_subscription (3)
  ↓ & user_subscription_history (4)
```

### Auth Flow

```
user_role (2) ← users.role_id
user_verify (1) ← email verification
user_password_resets (3) ← reset tokens
```

### Config

```
settings (19) ← global config
matrix_ie_quadrant_strategies (27) ← master data
```

---

## 📊 Database Metrics

| Metric            | Value   | Notes                                                                                        |
| ----------------- | ------- | -------------------------------------------------------------------------------------------- |
| Total Tables      | 17      |                                                                                              |
| Active Tables     | 14      | 82%                                                                                          |
| Unused Tables     | 3       | 18%                                                                                          |
| Total Rows        | ~128    | Estimated                                                                                    |
| Empty Tables      | 6       | ai_strategy, user_menu, user_role_menu, project_swot_strategy, user_password_resets (mostly) |
| Estimated DB Size | ~2-3 MB | Small                                                                                        |
| Primary Keys      | 17      | All tables have PK                                                                           |
| Foreign Keys      | ~8      | Good relational design                                                                       |

---

## ✨ Database Health Score

```
Schema Cleanliness:     7/10  (3 unused tables)
Relational Integrity:   9/10  (Good FK design)
Usage Optimization:     8/10  (Mostly active)
Data Consistency:       9/10  (Well maintained)
Documentation:         NONE  (Add comments!)

─────────────────────────────
Overall Score:             8.25/10

Recommendation: Good health, but clean up unused tables
```

---

Generated: 2025-12-12
