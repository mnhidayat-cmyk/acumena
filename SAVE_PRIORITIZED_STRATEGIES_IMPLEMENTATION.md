# 💾 Implementasi: Menyimpan Prioritized Strategies ke Database

**Tujuan:** Convert dari display-only menjadi persistent storage dengan kemampuan CRUD penuh

---

## 📋 Executive Summary

Untuk menyimpan "Prioritized Strategies", diperlukan:

1. **1 tabel baru** (atau gunakan ulang `project_swot_strategy`)
2. **3 API endpoint baru** (Create, Read, Update)
3. **1 Model baru** (Prioritized_strategy_model.php)
4. **Update View** (matrix-ai.php) untuk save functionality

---

## 🗄️ Opsi Tabel

### ❌ JANGAN: Gunakan `project_swot_strategy` yang ada

```sql
-- Alasan: Design lama, kolom tidak sesuai untuk "prioritized"
-- Tabel saat ini:
-- - category: ENUM('SO','ST','WO','WT','main')  ❌ Tidak fleksibel
-- - Tidak ada priority_rank, status, selection criteria
```

### ✅ REKOMENDASIKAN: Buat Tabel Baru

```sql
CREATE TABLE `project_prioritized_strategies` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `uuid` CHAR(36) NOT NULL UNIQUE,                    -- Unique identifier
  `project_id` BIGINT UNSIGNED NOT NULL,              -- FK to projects
  `ai_strategy_id` BIGINT UNSIGNED,                   -- FK to ai_strategy (nullable)
  `pair_type` ENUM('S-O','W-O','S-T','W-T') NOT NULL, -- Strategy type
  `strategy_code` VARCHAR(10) NOT NULL,               -- SO1, ST2, WO3, etc
  `strategy_statement` TEXT NOT NULL,                 -- Strategi text
  `priority_rank` INT NOT NULL DEFAULT 1,             -- Urutan prioritas (1,2,3...)
  `priority_score` DECIMAL(5,4) DEFAULT NULL,         -- Score dari AI (0.0-1.0)
  `status` ENUM('draft','approved','in_progress','completed','archived')
           NOT NULL DEFAULT 'draft',                   -- Status eksekusi
  `internal_notes` TEXT,                              -- Catatan internal
  `selected_by_user` BOOLEAN DEFAULT FALSE,           -- User explicitly selected?
  `selection_justification` TEXT,                     -- Alasan user memilih
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
              ON UPDATE CURRENT_TIMESTAMP,
  `created_by_user_id` BIGINT UNSIGNED NOT NULL,      -- Siapa yang save
  `is_deleted` TIMESTAMP NULL DEFAULT NULL,           -- Soft delete

  -- Indexes
  KEY `idx_project_id` (`project_id`),
  KEY `idx_pair_type` (`pair_type`),
  KEY `idx_status` (`status`),
  KEY `idx_priority_rank` (`priority_rank`),
  KEY `idx_ai_strategy_id` (`ai_strategy_id`),

  -- Foreign Keys
  CONSTRAINT `fk_ps_project` FOREIGN KEY (`project_id`)
    REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ps_ai_strategy` FOREIGN KEY (`ai_strategy_id`)
    REFERENCES `ai_strategy` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_ps_user` FOREIGN KEY (`created_by_user_id`)
    REFERENCES `users` (`id`) ON DELETE RESTRICT

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🔄 Alur Proses (Dari Display ke Persistent)

### SEBELUMNYA (Current State - Display Only)

```
User Generate Strategies (SO/ST/WO/WT)
  ↓
Display di UI (client-side only)
  ↓
User refresh page
  ↓
❌ DATA HILANG (tidak disimpan)
```

### SESUDAHNYA (Proposed - Persistent Storage)

```
User Generate Strategies (SO/ST/WO/WT)
  ↓
Display di UI
  ↓
User review & klik "Save Prioritized Strategies"
  ↓
POST /api/project/prioritized-strategies/save
  ├─ Validasi
  ├─ Insert ke project_prioritized_strategies
  ├─ Calculate priority_rank berdasarkan urutan user
  └─ Return success + IDs
  ↓
User bisa lihat saved list
  ↓
User bisa edit status, notes, dll
  ↓
✅ DATA TERSIMPAN DI DATABASE
```

---

## 📊 Database Design Details

### Primary Columns Penjelasan

| Kolom                     | Type         | Purpose               | Example                                      |
| ------------------------- | ------------ | --------------------- | -------------------------------------------- |
| `id`                      | BIGINT       | Auto-increment PK     | 1, 2, 3                                      |
| `uuid`                    | CHAR(36)     | Unique ID untuk API   | abc-def-123                                  |
| `project_id`              | BIGINT FK    | Link ke project       | 3                                            |
| `ai_strategy_id`          | BIGINT FK    | Link ke hasil AI      | 45 (nullable)                                |
| `pair_type`               | ENUM         | Tipe SWOT pair        | 'S-O', 'W-O', 'S-T', 'W-T'                   |
| `strategy_code`           | VARCHAR(10)  | Kode short            | 'SO1', 'ST2', 'WO3'                          |
| `strategy_statement`      | TEXT         | Isi strategi          | "Manfaatkan minimum order..."                |
| `priority_rank`           | INT          | Urutan prioritas      | 1 (tertinggi), 2, 3, dst                     |
| `priority_score`          | DECIMAL(5,4) | Score dari AI         | 0.85, 0.72, 0.65                             |
| `status`                  | ENUM         | State                 | 'draft'→'approved'→'in_progress'→'completed' |
| `internal_notes`          | TEXT         | Notes untuk tim       | "Fokus Q1 2026"                              |
| `selected_by_user`        | BOOLEAN      | User explicitly pick? | 1 (yes), 0 (no)                              |
| `selection_justification` | TEXT         | Alasan pemilihan      | "High ROI potential"                         |
| `created_at`              | TIMESTAMP    | Waktu dibuat          | 2025-12-12 10:00:00                          |
| `updated_at`              | TIMESTAMP    | Terakhir diupdate     | 2025-12-12 15:30:00                          |
| `created_by_user_id`      | BIGINT FK    | Siapa create          | 1                                            |
| `is_deleted`              | TIMESTAMP    | Soft delete           | NULL (active) atau timestamp                 |

---

## 🔌 API Endpoints Baru

### 1. **CREATE - Save Prioritized Strategies**

```
POST /api/project/prioritized-strategies/save

Headers:
  Content-Type: application/json

Body:
{
  "project_uuid": "abc-def-123",
  "strategies": [
    {
      "pair_type": "S-O",
      "strategy_code": "SO1",
      "strategy_statement": "Manfaatkan minimum order kecil...",
      "priority_rank": 1,
      "priority_score": 0.85,
      "selected_by_user": true,
      "selection_justification": "Ini priority utama kami"
    },
    {
      "pair_type": "S-O",
      "strategy_code": "SO2",
      "strategy_statement": "Tawarkan harga terjangkau...",
      "priority_rank": 2,
      "priority_score": 0.72,
      "selected_by_user": true,
      "selection_justification": "Bisa dilakukan tahun depan"
    },
    ...
  ]
}

Response:
{
  "success": true,
  "message": "Prioritized strategies saved successfully",
  "data": {
    "saved_count": 8,
    "strategies": [
      {
        "id": 1,
        "uuid": "uuid-1",
        "pair_type": "S-O",
        "status": "draft",
        "priority_rank": 1
      },
      ...
    ]
  }
}

HTTP Status: 201 Created
```

---

### 2. **READ - Get All Prioritized Strategies**

```
GET /api/project/prioritized-strategies?project_uuid=abc-def-123

Query Params:
  - project_uuid (required)
  - pair_type (optional): Filter by S-O, W-O, S-T, W-T
  - status (optional): Filter by status
  - sort (optional): priority_rank (default) / priority_score / created_at

Response:
{
  "success": true,
  "data": {
    "project_uuid": "abc-def-123",
    "strategies": [
      {
        "id": 1,
        "uuid": "uuid-1",
        "pair_type": "S-O",
        "strategy_code": "SO1",
        "strategy_statement": "Manfaatkan minimum order...",
        "priority_rank": 1,
        "priority_score": 0.85,
        "status": "draft",
        "selected_by_user": true,
        "internal_notes": null,
        "selection_justification": "Ini priority utama kami",
        "created_at": "2025-12-12T10:00:00Z",
        "updated_at": "2025-12-12T10:00:00Z"
      },
      ...
    ],
    "summary": {
      "total": 8,
      "draft": 8,
      "approved": 0,
      "in_progress": 0,
      "completed": 0
    }
  }
}

HTTP Status: 200 OK
```

---

### 3. **UPDATE - Update Strategy Status/Notes**

```
PUT /api/project/prioritized-strategies/{id}

Headers:
  Content-Type: application/json

Body:
{
  "status": "approved",
  "internal_notes": "Ready for implementation",
  "priority_rank": 1
}

Response:
{
  "success": true,
  "message": "Strategy updated successfully",
  "data": {
    "id": 1,
    "status": "approved",
    "updated_at": "2025-12-12T15:30:00Z"
  }
}

HTTP Status: 200 OK
```

---

### 4. **DELETE - Soft Delete Strategy**

```
DELETE /api/project/prioritized-strategies/{id}

Headers:
  Content-Type: application/json

Response:
{
  "success": true,
  "message": "Strategy deleted successfully"
}

HTTP Status: 200 OK (Soft delete, tidak hard delete)
```

---

## 👨‍💻 Model Class

**File:** `application/models/Prioritized_strategy_model.php`

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prioritized_strategy_model extends CI_Model {

    protected $table = 'project_prioritized_strategies';

    public function __construct() {
        parent::__construct();
        $this->load->model('Project_model', 'project');
    }

    /**
     * Save multiple prioritized strategies for a project
     * @param int $project_id
     * @param array $strategies Array of strategy data
     * @return array Inserted IDs
     */
    public function save_multiple($project_id, $strategies, $user_id) {
        $user = $this->db->where('id', $user_id)->get('users')->row();
        if (!$user) {
            throw new Exception('User not found');
        }

        $inserted_ids = [];

        foreach ($strategies as $strategy) {
            $data = [
                'uuid' => generate_uuid(),
                'project_id' => (int) $project_id,
                'ai_strategy_id' => isset($strategy['ai_strategy_id']) ? (int) $strategy['ai_strategy_id'] : NULL,
                'pair_type' => $strategy['pair_type'],
                'strategy_code' => $strategy['strategy_code'],
                'strategy_statement' => $strategy['strategy_statement'],
                'priority_rank' => isset($strategy['priority_rank']) ? (int) $strategy['priority_rank'] : 1,
                'priority_score' => isset($strategy['priority_score']) ? (float) $strategy['priority_score'] : NULL,
                'status' => 'draft',
                'selected_by_user' => isset($strategy['selected_by_user']) ? (bool) $strategy['selected_by_user'] : FALSE,
                'selection_justification' => $strategy['selection_justification'] ?? NULL,
                'internal_notes' => $strategy['internal_notes'] ?? NULL,
                'created_by_user_id' => (int) $user_id,
            ];

            $this->db->insert($this->table, $data);
            $inserted_ids[] = $this->db->insert_id();
        }

        return $inserted_ids;
    }

    /**
     * Get prioritized strategies by project
     * @param int $project_id
     * @param string $pair_type (optional)
     * @param string $status (optional)
     * @return array
     */
    public function get_by_project($project_id, $pair_type = NULL, $status = NULL) {
        $this->db->where('project_id', (int) $project_id);
        $this->db->where('is_deleted', NULL);

        if ($pair_type) {
            $this->db->where('pair_type', $pair_type);
        }

        if ($status) {
            $this->db->where('status', $status);
        }

        $this->db->order_by('priority_rank', 'ASC');
        $query = $this->db->get($this->table);

        return $query->result_array();
    }

    /**
     * Update strategy status & notes
     * @param int $id
     * @param array $data (status, internal_notes, priority_rank)
     * @param int $user_id
     * @return bool
     */
    public function update_strategy($id, $data, $user_id) {
        $strategy = $this->db->where('id', (int) $id)
                             ->where('is_deleted', NULL)
                             ->get($this->table)
                             ->row();

        if (!$strategy) {
            return false;
        }

        $update_data = [
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (isset($data['status'])) {
            $update_data['status'] = $data['status'];
        }

        if (isset($data['internal_notes'])) {
            $update_data['internal_notes'] = $data['internal_notes'];
        }

        if (isset($data['priority_rank'])) {
            $update_data['priority_rank'] = (int) $data['priority_rank'];
        }

        return $this->db->where('id', (int) $id)
                        ->update($this->table, $update_data);
    }

    /**
     * Soft delete strategy
     * @param int $id
     * @return bool
     */
    public function delete_strategy($id) {
        return $this->db->where('id', (int) $id)
                        ->update($this->table, [
                            'is_deleted' => date('Y-m-d H:i:s')
                        ]);
    }

    /**
     * Get count by status
     * @param int $project_id
     * @return array
     */
    public function get_status_summary($project_id) {
        $this->db->where('project_id', (int) $project_id);
        $this->db->where('is_deleted', NULL);
        $this->db->select('status, COUNT(*) as count');
        $this->db->group_by('status');

        $query = $this->db->get($this->table);
        $results = $query->result_array();

        $summary = [
            'total' => 0,
            'draft' => 0,
            'approved' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'archived' => 0
        ];

        foreach ($results as $row) {
            $summary[$row['status']] = (int) $row['count'];
            $summary['total'] += (int) $row['count'];
        }

        return $summary;
    }
}
```

---

## 🎮 Controller Methods

**File:** `application/controllers/Api_project.php` (tambahkan methods)

```php
<?php
// ... existing code ...

class Api_project extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // ... existing ...
        $this->load->model('Prioritized_strategy_model', 'prioritizedStrategy');
    }

    /**
     * SAVE prioritized strategies
     * POST /api/project/prioritized-strategies/save
     */
    public function prioritized_strategies_save() {
        if ($this->input->method() !== 'post') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $json_input = json_decode($this->input->raw_input_stream, true);

        if (!$json_input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
            return;
        }

        $project_uuid = $json_input['project_uuid'] ?? NULL;
        $strategies = $json_input['strategies'] ?? [];

        if (!$project_uuid || empty($strategies)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'project_uuid and strategies required']);
            return;
        }

        // Verify project ownership
        $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Project not found or access denied']);
            return;
        }

        try {
            $inserted_ids = $this->prioritizedStrategy->save_multiple(
                $project['id'],
                $strategies,
                $user_id
            );

            // Get saved records
            $saved_strategies = $this->prioritizedStrategy->get_by_project($project['id']);

            echo json_encode([
                'success' => true,
                'message' => 'Prioritized strategies saved successfully',
                'data' => [
                    'saved_count' => count($inserted_ids),
                    'strategies' => $saved_strategies
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error saving strategies: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * GET prioritized strategies
     * GET /api/project/prioritized-strategies?project_uuid=...
     */
    public function prioritized_strategies_get() {
        if ($this->input->method() !== 'get') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $project_uuid = $this->input->get('project_uuid');
        $pair_type = $this->input->get('pair_type');

        if (!$project_uuid) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'project_uuid required']);
            return;
        }

        // Verify project ownership
        $project = $this->project->get_project_by_uuid($project_uuid, $user_id);
        if (!$project) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Project not found']);
            return;
        }

        $strategies = $this->prioritizedStrategy->get_by_project(
            $project['id'],
            $pair_type
        );

        $summary = $this->prioritizedStrategy->get_status_summary($project['id']);

        echo json_encode([
            'success' => true,
            'data' => [
                'project_uuid' => $project_uuid,
                'strategies' => $strategies,
                'summary' => $summary
            ]
        ]);
    }

    /**
     * UPDATE strategy
     * PUT /api/project/prioritized-strategies/{id}
     */
    public function prioritized_strategies_update() {
        if ($this->input->method() !== 'put') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $id = $this->uri->segment(4); // Extract ID from URL
        $json_input = json_decode($this->input->raw_input_stream, true);

        if (!$json_input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
            return;
        }

        try {
            $updated = $this->prioritizedStrategy->update_strategy($id, $json_input, $user_id);

            if ($updated) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Strategy updated successfully'
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Strategy not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error updating strategy: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * DELETE strategy (soft delete)
     * DELETE /api/project/prioritized-strategies/{id}
     */
    public function prioritized_strategies_delete() {
        if ($this->input->method() !== 'delete') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $id = $this->uri->segment(4); // Extract ID from URL

        try {
            $deleted = $this->prioritizedStrategy->delete_strategy($id);

            if ($deleted) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Strategy deleted successfully'
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Strategy not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error deleting strategy: ' . $e->getMessage()
            ]);
        }
    }
}
```

---

## 📝 Routes Configuration

**File:** `application/config/routes.php` (tambahkan)

```php
// Prioritized Strategies API
$route['api/project/prioritized-strategies/save'] = 'api_project/prioritized_strategies_save';
$route['api/project/prioritized-strategies'] = 'api_project/prioritized_strategies_get';
$route['api/project/prioritized-strategies/(:num)'] = 'api_project/prioritized_strategies_update';
```

---

## 🎨 Frontend Updates

**File:** `application/views/projects/matrix-ai.php` (tambahkan button & logic)

```javascript
// Tambahkan button di section "Prioritized Strategies"
<button id="savePrioritizedBtn" class="btn gradient-primary flex gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
        <polyline points="17 21 17 13 7 13 7 21"></polyline>
        <polyline points="7 3 7 8 15 8"></polyline>
    </svg>
    Save to Database
</button>

<script>
// Collect prioritized strategies and save
document.getElementById('savePrioritizedBtn').addEventListener('click', async () => {
    const projectKey = new URLSearchParams(window.location.search).get('key');

    // Collect all strategies from SO/ST/WO/WT containers
    const strategies = [];

    // Helper function to collect from each quadrant
    const collectFromQuadrant = (quadrant, selector) => {
        const items = document.querySelectorAll(selector + ' .strategy-item');
        let rank = 1;
        items.forEach((item, idx) => {
            strategies.push({
                pair_type: quadrantMap[quadrant],
                strategy_code: quadrant + (idx + 1),
                strategy_statement: item.textContent.trim(),
                priority_rank: rank++,
                priority_score: parseFloat(item.dataset.score) || 0,
                selected_by_user: true,
                selection_justification: ''
            });
        });
    };

    // Collect from all quadrants
    collectFromQuadrant('SO', '#soStrategiesList');
    collectFromQuadrant('ST', '#stStrategiesList');
    collectFromQuadrant('WO', '#woStrategiesList');
    collectFromQuadrant('WT', '#wtStrategiesList');

    if (strategies.length === 0) {
        alert('No strategies to save');
        return;
    }

    try {
        const response = await fetch('<?= base_url('api/project/prioritized-strategies/save') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                project_uuid: projectKey,
                strategies: strategies
            })
        });

        const json = await response.json();

        if (json.success) {
            alert('Strategies saved successfully!');
            // Refresh atau show saved list
        } else {
            alert('Error: ' + json.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to save strategies');
    }
});
</script>
```

---

## 📊 Data Flow Lengkap

```
┌─────────────────────────────────────────────────────┐
│ User di matrix-ai.php                               │
└─────────────────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────────────────┐
│ Generate SO/ST/WO/WT Strategies (existing flow)     │
└─────────────────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────────────────┐
│ [NEW] Display "Save to Database" button             │
└─────────────────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────────────────┐
│ User review strategies & click "Save"               │
└─────────────────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────────────────┐
│ POST /api/project/prioritized-strategies/save       │
│                                                     │
│ Data sent:                                          │
│ {                                                   │
│   project_uuid: "abc-def-123",                      │
│   strategies: [                                     │
│     {                                               │
│       pair_type: "S-O",                             │
│       strategy_code: "SO1",                         │
│       strategy_statement: "...",                    │
│       priority_rank: 1,                             │
│       selected_by_user: true                        │
│     },                                              │
│     ...                                             │
│   ]                                                 │
│ }                                                   │
└─────────────────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────────────────┐
│ Controller: Api_project::                           │
│   prioritized_strategies_save()                     │
│                                                     │
│ 1) Verify project ownership                        │
│ 2) Call Prioritized_strategy_model::                │
│    save_multiple()                                  │
│ 3) Insert ke project_prioritized_strategies         │
│ 4) Return success + saved data                      │
└─────────────────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────────────────┐
│ Database: project_prioritized_strategies            │
│                                                     │
│ Tabel menyimpan:                                    │
│ - Strategy statement & code                        │
│ - Priority rank & score                            │
│ - Status (draft/approved/in_progress/completed)    │
│ - User selection justification                     │
│ - Timestamps & user info                           │
└─────────────────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────────────────┐
│ User can:                                           │
│ ✅ View saved strategies                            │
│ ✅ Update status (draft→approved→in_progress)       │
│ ✅ Add internal notes                               │
│ ✅ Re-prioritize (change rank)                      │
│ ✅ Delete/archive                                   │
│ ✅ Retrieve anytime                                 │
└─────────────────────────────────────────────────────┘
```

---

## 🎯 Implementation Checklist

### Phase 1: Database

- [ ] Create `project_prioritized_strategies` table
- [ ] Add foreign keys & indexes
- [ ] Test table creation & constraints

### Phase 2: Backend

- [ ] Create `Prioritized_strategy_model.php`
- [ ] Add 4 API endpoints (save, get, update, delete)
- [ ] Add routes in `routes.php`
- [ ] Test all endpoints with Postman

### Phase 3: Frontend

- [ ] Add "Save to Database" button in matrix-ai.php
- [ ] Collect strategies from UI
- [ ] Send POST request to save endpoint
- [ ] Display success/error messages
- [ ] Add "View Saved Strategies" view

### Phase 4: Enhancement (Optional)

- [ ] Add status workflow (draft → approved → in progress)
- [ ] Add internal notes field for team collaboration
- [ ] Create dashboard to track strategy execution
- [ ] Add history/audit log
- [ ] Email notifications on status change

---

## 📌 Nama Tabel (Final Decision)

**✅ RECOMMENDED:** `project_prioritized_strategies`

**Alasan:**

- Descriptive & clear (siapa baca langsung mengerti)
- Follow naming convention (project\_\* untuk project-related)
- Singular "strategies" consistent dengan project_ai_generation_run pattern
- Membedakan dengan `project_swot_strategy` yang abandoned
- Mencakup "prioritized" untuk clarity

**Alternative Names (jika ingin lebih singkat):**

- `project_strategies` (kurang specific)
- `project_action_plans` (jika ingin strategic action plans)
- `project_strategic_priorities` (terlalu panjang)

---

Generated: 2025-12-12
