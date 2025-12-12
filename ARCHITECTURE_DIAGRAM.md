# 🎯 IMPLEMENTATION OVERVIEW: Prioritized Strategies Storage

---

## 📐 ARCHITECTURE DIAGRAM

```
┌────────────────────────────────────────────────────────────────┐
│                    FRONTEND LAYER                              │
│                  matrix-ai.php View                            │
│                                                                │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │ SO Strategies  │ ST Strategies │ WO Strategies │ WT       │  │
│  │ ┌────────────┐ │ ┌────────────┐│ ┌────────────┐│Strateg  │  │
│  │ │SO1         │ │ │ST1         ││ │WO1         ││ies      │  │
│  │ │SO2         │ │ │ST2         ││ │WO2         ││ ┌─────┐ │  │
│  │ │SO3         │ │ │ST3         ││ │WO3         ││ │WT1  │ │  │
│  │ └────────────┘ │ └────────────┘│ └────────────┘│ └─────┘ │  │
│  │   [Generate]   │   [Generate]  │   [Generate] │[Generat] │  │
│  └──────────────────────────────────────────────────────────┘  │
│                          ↓                                      │
│                  [NEW] Save to Database Button                 │
│                 (Collect all + POST API)                       │
└────────────────────────────────────────────────────────────────┘
                           ↓
┌────────────────────────────────────────────────────────────────┐
│                    BACKEND LAYER                               │
│                  API Controller                                │
│                                                                │
│  POST /api/project/prioritized-strategies/save                 │
│    ├─ Validate project ownership                              │
│    ├─ Validate strategy data                                  │
│    ├─ Insert to DB (model)                                    │
│    └─ Return success + saved data                             │
│                                                                │
│  GET /api/project/prioritized-strategies                       │
│    ├─ Filter by project_uuid                                  │
│    ├─ Optional filter: pair_type, status                      │
│    └─ Return array of strategies + summary                    │
│                                                                │
│  PUT /api/project/prioritized-strategies/{id}                  │
│    ├─ Update status (draft→approved→in_progress)             │
│    ├─ Update notes/priority_rank                             │
│    └─ Return updated record                                   │
│                                                                │
│  DELETE /api/project/prioritized-strategies/{id}               │
│    ├─ Soft delete (set is_deleted = NOW())                    │
│    └─ Return success                                          │
└────────────────────────────────────────────────────────────────┘
                           ↓
┌────────────────────────────────────────────────────────────────┐
│                    MODEL LAYER                                 │
│            Prioritized_strategy_model.php                      │
│                                                                │
│  - save_multiple()         : Batch insert strategies          │
│  - get_by_project()        : Query by project + filters       │
│  - update_strategy()       : Update status/notes              │
│  - delete_strategy()       : Soft delete                      │
│  - get_status_summary()    : Count by status                  │
└────────────────────────────────────────────────────────────────┘
                           ↓
┌────────────────────────────────────────────────────────────────┐
│                    DATABASE LAYER                              │
│        project_prioritized_strategies TABLE                    │
│                                                                │
│  Columns:                                                      │
│  ├─ id                     : Auto PK                          │
│  ├─ uuid                   : Unique ID                        │
│  ├─ project_id FK          : Link to projects                 │
│  ├─ pair_type              : S-O, W-O, S-T, W-T              │
│  ├─ strategy_code          : SO1, ST2, WO3, WT4              │
│  ├─ strategy_statement     : Full strategy text              │
│  ├─ priority_rank          : 1 (highest), 2, 3...            │
│  ├─ priority_score         : 0.00-1.00 from AI               │
│  ├─ status                 : draft/approved/in_progress       │
│  ├─ selected_by_user       : Boolean (user pick?)             │
│  ├─ selection_justification: Why user picked this            │
│  ├─ internal_notes         : Team notes                       │
│  ├─ created_at/updated_at  : Timestamps                       │
│  ├─ created_by_user_id FK  : Who created                      │
│  └─ is_deleted             : Soft delete (NULL or timestamp)  │
│                                                                │
│  Indexes: project_id, pair_type, status, priority_rank        │
│  Foreign Keys: projects.id, users.id                          │
└────────────────────────────────────────────────────────────────┘
```

---

## 🔄 PROCESS FLOW COMPARISON

### CURRENT STATE (Display Only)

```
┌──────────────┐
│ User at UI   │
└──────┬───────┘
       │
       ├─ Generate SO Strategies
       ├─ Generate ST Strategies
       ├─ Generate WO Strategies
       └─ Generate WT Strategies
       │
       ▼
┌──────────────────────────┐
│ Display in UI (client    │
│ only, no persistence)    │
└──────┬───────────────────┘
       │
       ▼
┌──────────────────────────┐
│ User Refresh Page        │
│                          │
│ ❌ Data LOST!!!          │
└──────────────────────────┘
```

### PROPOSED STATE (Persistent Storage)

```
┌──────────────┐
│ User at UI   │
└──────┬───────┘
       │
       ├─ Generate SO Strategies
       ├─ Generate ST Strategies
       ├─ Generate WO Strategies
       └─ Generate WT Strategies
       │
       ▼
┌──────────────────────────┐
│ Display in UI            │
│ (client-side preview)    │
└──────┬───────────────────┘
       │
       ▼
┌──────────────────────────┐
│ [NEW] Click "Save"       │
│ Button                   │
└──────┬───────────────────┘
       │
       ▼
┌──────────────────────────────────────────────┐
│ POST /api/project/prioritized-strategies/save│
│                                              │
│ Body:                                        │
│ {                                            │
│   "project_uuid": "...",                     │
│   "strategies": [                            │
│     {                                        │
│       "pair_type": "S-O",                    │
│       "strategy_code": "SO1",                │
│       "strategy_statement": "...",           │
│       "priority_rank": 1,                    │
│       "priority_score": 0.85,                │
│       "selected_by_user": true               │
│     },                                       │
│     ...                                      │
│   ]                                          │
│ }                                            │
└──────┬───────────────────────────────────────┘
       │
       ▼
┌────────────────────────────────────────────────┐
│ Controller: Api_project::                      │
│ prioritized_strategies_save()                  │
│                                                │
│ 1. Verify project ownership                   │
│ 2. Validate strategy data                     │
│ 3. Call Model::save_multiple()                │
│ 4. Return success + IDs                       │
└────────┬─────────────────────────────────────┘
         │
         ▼
┌────────────────────────────────────────────────┐
│ Model: Prioritized_strategy_model::            │
│ save_multiple()                                │
│                                                │
│ For each strategy:                             │
│ - Generate UUID                                │
│ - Set timestamps                               │
│ - Set created_by_user_id                       │
│ - INSERT to project_prioritized_strategies     │
└────────┬─────────────────────────────────────┘
         │
         ▼
┌────────────────────────────────────────────────┐
│ DATABASE: project_prioritized_strategies       │
│                                                │
│ ✅ Data SAVED (persistent)                    │
│                                                │
│ User can now:                                  │
│ ✅ View saved strategies anytime              │
│ ✅ Update status (draft→approved→in_progress) │
│ ✅ Add internal notes                         │
│ ✅ Re-prioritize                              │
│ ✅ Delete/archive                             │
│ ✅ Track audit trail (who, when)              │
└────────────────────────────────────────────────┘
```

---

## 📊 DATABASE RELATIONSHIP

```
                       ┌─────────────┐
                       │   users     │
                       │  ┌───────┐  │
                       │  │id (PK)│  │
                       │  │name   │  │
                       │  └───────┘  │
                       └──────▲──────┘
                              │
                    FK created_by_user_id
                              │
┌──────────────┐              │
│ projects     │              │
│ ┌──────────┐ │              │
│ │id (PK)   │ │              │
│ │uuid      │ │              │
│ │user_id FK├─┼──────────────┘
│ │name      │ │
│ └──────────┘ │
└──────┬───────┘
       │
       │ FK project_id
       │
       ▼
┌──────────────────────────────────────────┐
│ project_prioritized_strategies (NEW!)    │
│                                          │
│ ┌────────────────────────────────────┐  │
│ │id (PK)                             │  │
│ │uuid                                │  │
│ │project_id (FK to projects.id)      │  │
│ │pair_type (S-O, W-O, S-T, W-T)     │  │
│ │strategy_code (SO1, ST2, etc)       │  │
│ │strategy_statement (TEXT)           │  │
│ │priority_rank (1, 2, 3...)          │  │
│ │priority_score (0.00-1.00)          │  │
│ │status (draft/approved/in_progress) │  │
│ │selected_by_user (BOOLEAN)          │  │
│ │selection_justification (TEXT)      │  │
│ │internal_notes (TEXT)               │  │
│ │created_at (TIMESTAMP)              │  │
│ │updated_at (TIMESTAMP)              │  │
│ │created_by_user_id (FK to users.id) │  │
│ │is_deleted (TIMESTAMP NULL)         │  │
│ └────────────────────────────────────┘  │
│                                          │
│ Indexes:                                 │
│ - idx_project_id                         │
│ - idx_pair_type                          │
│ - idx_status                             │
│ - idx_priority_rank                      │
│ - idx_created_by_user_id                 │
│ - Composite: (project_id, priority_rank) │
└──────────────────────────────────────────┘
```

---

## 🎯 Implementation Timeline

```
Day 1: DATABASE
  ├─ Review SQL DDL
  ├─ Create table
  ├─ Verify indexes
  └─ Test queries
     ✓ Time: 30 min

Day 2: BACKEND
  ├─ Create Model class
  ├─ Add Controller methods
  ├─ Add Routes
  ├─ Test with Postman
  └─ Fix any issues
     ✓ Time: 2-3 hours

Day 3: FRONTEND
  ├─ Update matrix-ai.php view
  ├─ Add save button
  ├─ Add JavaScript logic
  ├─ Test from UI
  └─ Fix any issues
     ✓ Time: 1-2 hours

Day 4: INTEGRATION TEST
  ├─ End-to-end testing
  ├─ Multiple user scenarios
  ├─ Edge cases
  └─ Performance check
     ✓ Time: 1-2 hours

TOTAL: ~1-2 days (experienced dev)
       ~2-3 days (beginner)
```

---

## 📁 FILES STRUCTURE

```
acumena/
├── application/
│   ├── models/
│   │   ├── Prioritized_strategy_model.php    [NEW - CREATE]
│   │   └── ... (existing)
│   │
│   ├── controllers/
│   │   ├── Api_project.php                   [UPDATE - ADD 4 METHODS]
│   │   └── ... (existing)
│   │
│   ├── config/
│   │   ├── routes.php                        [UPDATE - ADD 3 ROUTES]
│   │   └── ... (existing)
│   │
│   └── views/
│       └── projects/
│           ├── matrix-ai.php                 [UPDATE - ADD SAVE BUTTON]
│           └── ... (existing)
│
├── DOCUMENTATION/
│   ├── SAVE_PRIORITIZED_STRATEGIES_IMPLEMENTATION.md        ✅ READY
│   ├── DATABASE_SCHEMA_PRIORITIZED_STRATEGIES.md            ✅ READY
│   ├── PRIORITIZED_STRATEGIES_QUICK_IMPLEMENTATION.md       ✅ READY
│   ├── IMPLEMENTATION_SUMMARY.md                            ✅ READY
│   └── ARCHITECTURE_DIAGRAM.md                              ✅ THIS FILE
│
└── database/
    └── project_prioritized_strategies table                 [CREATE]
```

---

## ✅ READY TO IMPLEMENT?

**Start Here:**

1. Read: `DATABASE_SCHEMA_PRIORITIZED_STRATEGIES.md`
2. Execute: SQL CREATE TABLE script
3. Create: `Prioritized_strategy_model.php` (copy from implementation doc)
4. Update: `Api_project.php` (add 4 methods)
5. Update: `routes.php` (add 3 routes)
6. Update: `matrix-ai.php` (add save button)
7. Test everything
8. Deploy

---

Generated: 2025-12-12
