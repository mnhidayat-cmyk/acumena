# 📐 Database Schema: project_prioritized_strategies

---

## 📊 Complete DDL Script

Copy-paste langsung ke MySQL/PhpMyAdmin:

```sql
-- =====================================================
-- Table: project_prioritized_strategies
-- Purpose: Menyimpan prioritized strategies yang di-generate AI
--          dan di-pilih oleh user untuk project
-- =====================================================

CREATE TABLE IF NOT EXISTS `project_prioritized_strategies` (
  -- Primary Key
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Auto-increment ID',
  PRIMARY KEY (`id`),

  -- Unique Identifier
  `uuid` char(36) NOT NULL UNIQUE KEY COMMENT 'UUID v4 untuk API reference',

  -- Foreign Keys
  `project_id` bigint unsigned NOT NULL COMMENT 'Link ke projects table',
  `ai_strategy_id` bigint unsigned COMMENT 'Link ke ai_strategy table (nullable)',
  `created_by_user_id` bigint unsigned NOT NULL COMMENT 'User yang create/save',

  -- Strategy Classification
  `pair_type` enum('S-O','W-O','S-T','W-T') NOT NULL COMMENT 'Tipe pasangan SWOT',
  `strategy_code` varchar(10) NOT NULL COMMENT 'Kode short: SO1, ST2, WO3, WT4',

  -- Strategy Content
  `strategy_statement` text NOT NULL COMMENT 'Isi strategi lengkap',

  -- Prioritization
  `priority_rank` int NOT NULL DEFAULT 1 COMMENT 'Urutan prioritas (1=tertinggi)',
  `priority_score` decimal(5,4) DEFAULT NULL COMMENT 'Score dari AI (0.0000-1.0000)',

  -- Status Workflow
  `status` enum(
    'draft',
    'approved',
    'in_progress',
    'completed',
    'archived'
  ) NOT NULL DEFAULT 'draft' COMMENT 'Status eksekusi strategi',

  -- User Selection & Notes
  `selected_by_user` tinyint(1) DEFAULT false COMMENT 'User explicitly pilih?',
  `selection_justification` text COMMENT 'Alasan user memilih strategy ini',
  `internal_notes` text COMMENT 'Notes internal untuk tim',

  -- Timestamps
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu dibuat',
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    COMMENT 'Terakhir diupdate',

  -- Soft Delete
  `is_deleted` timestamp NULL DEFAULT NULL COMMENT 'Soft delete timestamp',

  -- Indexes
  KEY `idx_project_id` (`project_id`) COMMENT 'Filter by project',
  KEY `idx_pair_type` (`pair_type`) COMMENT 'Filter by SWOT type',
  KEY `idx_status` (`status`) COMMENT 'Filter by status',
  KEY `idx_priority_rank` (`priority_rank`) COMMENT 'Sort by priority',
  KEY `idx_ai_strategy_id` (`ai_strategy_id`) COMMENT 'Join dengan ai_strategy',
  KEY `idx_created_by_user_id` (`created_by_user_id`) COMMENT 'Track by user',
  KEY `idx_is_deleted` (`is_deleted`) COMMENT 'Filter deleted records',

  -- Foreign Key Constraints
  CONSTRAINT `fk_ps_project_id` FOREIGN KEY (`project_id`)
    REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,

  CONSTRAINT `fk_ps_ai_strategy_id` FOREIGN KEY (`ai_strategy_id`)
    REFERENCES `ai_strategy` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,

  CONSTRAINT `fk_ps_created_by_user_id` FOREIGN KEY (`created_by_user_id`)
    REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tabel untuk menyimpan prioritized strategies per project';

-- =====================================================
-- Optional: Create indices untuk better performance
-- =====================================================

-- Composite index untuk query umum
ALTER TABLE `project_prioritized_strategies`
ADD INDEX `idx_project_pair_status` (`project_id`, `pair_type`, `status`);

-- Index untuk sorting
ALTER TABLE `project_prioritized_strategies`
ADD INDEX `idx_project_priority` (`project_id`, `priority_rank`);

-- Index untuk audit
ALTER TABLE `project_prioritized_strategies`
ADD INDEX `idx_user_created` (`created_by_user_id`, `created_at`);

-- =====================================================
-- Verification Queries
-- =====================================================

-- Check if table created
SHOW TABLES LIKE 'project_prioritized_strategies';

-- Describe table
DESCRIBE project_prioritized_strategies;

-- Check indexes
SHOW INDEX FROM project_prioritized_strategies;

-- Check foreign keys
SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_NAME='project_prioritized_strategies';
```

---

## 📋 Kolom Definisi Detail

### Kolom Identification

```sql
id                 BIGINT UNSIGNED
  → Auto-increment primary key
  → Range: 0 to 18,446,744,073,709,551,615
  → Size: 8 bytes

uuid               CHAR(36)
  → UUID v4 format: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
  → UNIQUE untuk API reference
  → Size: 36 bytes (fixed)
```

### Kolom Classification

```sql
pair_type          ENUM('S-O','W-O','S-T','W-T')
  → Tipe SWOT pair
  → S-O: Strength-Opportunity
  → W-O: Weakness-Opportunity
  → S-T: Strength-Threat
  → W-T: Weakness-Threat
  → Size: 1-2 bytes (enum)

strategy_code      VARCHAR(10)
  → Short identifier: SO1, SO2, ST1, WO3, WT2
  → Kombinasi: pair_type + number
  → Size: variable (max 10 bytes)
```

### Kolom Content

```sql
strategy_statement TEXT
  → Full strategy text
  → Contoh: "Manfaatkan minimum order kecil untuk..."
  → Size: up to 65,535 bytes (64 KB)
  → Collation: utf8mb4_unicode_ci (support emoji, multilingual)
```

### Kolom Priority

```sql
priority_rank      INT
  → Urutan prioritas: 1 (tertinggi), 2, 3, 4, ...
  → User bisa re-rank atau system auto-rank
  → Size: 4 bytes

priority_score     DECIMAL(5,4)
  → Precision: 5 total digits, 4 after decimal
  → Range: 0.0000 to 9.9999
  → Contoh: 0.85, 0.72, 0.95
  → From AI generation atau user manual
  → Size: 3 bytes
```

### Kolom Status

```sql
status             ENUM('draft','approved','in_progress','completed','archived')
  → Workflow states:
  → draft           : Baru disave, belum approved
  → approved        : Sudah approved, siap execute
  → in_progress     : Sedang dikerjakan
  → completed       : Selesai
  → archived        : Untuk referensi, tidak aktif
  → Size: 1-2 bytes (enum)
```

### Kolom User Feedback

```sql
selected_by_user   TINYINT(1)
  → Boolean (0 = false, 1 = true)
  → User explicitly select this strategy?
  → Beda dengan auto-generated
  → Size: 1 byte

selection_justification TEXT
  → Alasan user memilih strategi ini
  → Untuk dokumentasi & team collaboration
  → Contoh: "High ROI potential, feasible Q1"
  → Size: up to 65,535 bytes (optional)

internal_notes     TEXT
  → Notes internal untuk tim
  → Update history, action items, risks
  → Contoh: "Assign to Marketing team, start next week"
  → Size: up to 65,535 bytes (optional)
```

### Kolom Timestamps

```sql
created_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  → Waktu record dibuat
  → Automatic
  → Immutable (tidak berubah setelah insert)

updated_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                   ON UPDATE CURRENT_TIMESTAMP
  → Waktu record terakhir diupdate
  → Automatic update setiap kali ada UPDATE
  → Berubah saat status berubah, notes diupdate, dll

is_deleted         TIMESTAMP NULL DEFAULT NULL
  → Soft delete flag
  → NULL = active / timestamp = deleted
  → Saat delete, set ke NOW()
  → Memungkinkan restore & audit trail
```

---

## 🔗 Foreign Key Relationships

```
project_prioritized_strategies
├── project_id ───────→ projects.id
│                       ├─ ON DELETE CASCADE
│                       └─ Jika project didelete, strategies juga terhapus
│
├── ai_strategy_id ───→ ai_strategy.id (optional)
│                       ├─ ON DELETE SET NULL
│                       └─ Jika AI strategy didelete, FK jadi NULL
│
└── created_by_user_id → users.id
                        ├─ ON DELETE RESTRICT
                        └─ Tidak bisa delete user jika masih punya strategies
```

---

## 🔍 Index Strategy

| Index                     | Purpose        | Kolom                            | Use Case                         |
| ------------------------- | -------------- | -------------------------------- | -------------------------------- |
| `idx_project_id`          | Primary filter | project_id                       | Get semua strategies per project |
| `idx_pair_type`           | Type filter    | pair_type                        | Filter S-O vs W-O strategies     |
| `idx_status`              | Status filter  | status                           | Get draft/approved/in_progress   |
| `idx_priority_rank`       | Sorting        | priority_rank                    | Sort by priority order           |
| `idx_ai_strategy_id`      | Join           | ai_strategy_id                   | Link ke AI strategy              |
| `idx_created_by_user_id`  | Audit          | created_by_user_id               | Track by user                    |
| `idx_is_deleted`          | Soft delete    | is_deleted                       | Filter deleted records           |
| `idx_project_pair_status` | Composite      | (project_id, pair_type, status)  | Common filter combo              |
| `idx_project_priority`    | Composite      | (project_id, priority_rank)      | Get top N priorities             |
| `idx_user_created`        | Composite      | (created_by_user_id, created_at) | Audit trail by user              |

---

## 📊 Query Examples

### Get all active strategies per project

```sql
SELECT
  id, uuid, pair_type, strategy_code,
  strategy_statement, priority_rank, status
FROM project_prioritized_strategies
WHERE project_id = 3
  AND is_deleted IS NULL
ORDER BY priority_rank ASC;
```

### Get strategies by status for project

```sql
SELECT
  id, strategy_code, strategy_statement,
  status, updated_at
FROM project_prioritized_strategies
WHERE project_id = 3
  AND status IN ('draft', 'approved')
  AND is_deleted IS NULL
ORDER BY priority_rank ASC;
```

### Count strategies by status

```sql
SELECT
  status, COUNT(*) as count
FROM project_prioritized_strategies
WHERE project_id = 3
  AND is_deleted IS NULL
GROUP BY status;
```

### Get strategies with AI details

```sql
SELECT
  ps.id, ps.strategy_code, ps.strategy_statement,
  ps.priority_rank, ps.priority_score,
  ps.status, ps.selection_justification,
  u.full_name as created_by,
  ps.created_at
FROM project_prioritized_strategies ps
LEFT JOIN users u ON ps.created_by_user_id = u.id
WHERE ps.project_id = 3
  AND ps.is_deleted IS NULL
ORDER BY ps.priority_rank ASC;
```

### Soft delete strategy

```sql
UPDATE project_prioritized_strategies
SET is_deleted = NOW()
WHERE id = 1 AND is_deleted IS NULL;
```

### Restore deleted strategy

```sql
UPDATE project_prioritized_strategies
SET is_deleted = NULL
WHERE id = 1 AND is_deleted IS NOT NULL;
```

---

## 🧪 Test Data

```sql
-- Insert test data (setelah table created)
INSERT INTO project_prioritized_strategies (
  uuid, project_id, pair_type, strategy_code,
  strategy_statement, priority_rank, priority_score,
  status, selected_by_user, selection_justification,
  created_by_user_id
) VALUES (
  UUID(), 3, 'S-O', 'SO1',
  'Manfaatkan minimum order kecil untuk menekan pasar',
  1, 0.85,
  'draft', true, 'Highest ROI potential',
  1
),
(
  UUID(), 3, 'S-O', 'SO2',
  'Tawarkan harga terjangkau untuk menarik konsumen',
  2, 0.72,
  'draft', true, 'Volume strategy',
  1
),
(
  UUID(), 3, 'W-O', 'WO1',
  'Tingkatkan kapasitas produksi untuk ambil peluang',
  3, 0.65,
  'draft', false, null,
  1
);

-- Verify insertion
SELECT * FROM project_prioritized_strategies WHERE project_id = 3;
```

---

## 🚀 Performance Considerations

| Scenario                       | Best Index           | Query Time     |
| ------------------------------ | -------------------- | -------------- |
| Get all strategies per project | idx_project_id       | O(log N)       |
| Get draft strategies           | idx_status           | O(log N)       |
| Get top 5 priorities           | idx_project_priority | O(log N + 5)   |
| Count by status                | idx_status           | O(N) full scan |
| Get user's audit trail         | idx_user_created     | O(log N)       |

**Storage Estimate (per 1000 rows):**

- Base table: ~500 KB
- Indexes: ~100 KB
- Total: ~600 KB

---

Generated: 2025-12-12
