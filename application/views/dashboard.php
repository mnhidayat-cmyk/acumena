<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Dashboard
    </h2>

    <div x-data="{ 
        loading:true, 
        error:'', 
        stats:[],
        formatDate(dateString) {
            if (!dateString) return '';
            
            // If already in desired format (contains comma), return as is
            if (dateString.includes(',')) return dateString;
            
            // Try to parse different date formats
            let date;
            if (dateString.includes('-')) {
                // Handle YYYY-MM-DD or similar formats
                date = new Date(dateString);
            } else if (dateString.includes('/')) {
                // Handle MM/DD/YYYY or DD/MM/YYYY formats
                date = new Date(dateString);
            } else {
                // Try to parse as timestamp or other formats
                date = new Date(dateString);
            }
            
            // Check if date is valid
            if (isNaN(date.getTime())) return dateString;
            
            // Format to 'Sept, 30 2025' format
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                          'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
            
            const month = months[date.getMonth()];
            const day = date.getDate();
            const year = date.getFullYear();
            
            return `${month}, ${day} ${year}`;
        }
    }"
     x-init="fetch('<?= base_url('api/project/get_projects_by_user_id?limit=3') ?>', { headers: { 'X-Requested-With':'XMLHttpRequest' }})
        .then(async (r) => {
          if (!r.ok) throw new Error('HTTP ' + r.status)
          const json = await r.json()
          stats = Array.isArray(json) ? json : (json.data ?? [])
        })
        .catch(() => error='Terjadi kesalahan jaringan / format data tidak sesuai')
        .finally(() => loading=false)
     "
     class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">

        <!-- Loading -->
        <template x-if="loading">
            <div class="text-sm text-gray-500">Loading...</div>
        </template>

        <!-- Error -->
        <template x-if="!loading && error">
            <div class="text-sm text-red-600" x-text="error"></div>
        </template>

        <!-- List -->
        <template x-if="!loading && !error && stats && stats.length">
            <!-- Card Add Project -->
            <a href="<?= base_url('project?step=profile') ?>" class="flex items-center p-4 rounded-lg shadow-xs text-green-500 bg-green-100 text-center"
            style="border: 4px dashed #0e9f6e">
                <div class="mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-32 h-32">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span>New Project</span>
                </div>
            </a>
            <template x-for="item in stats.slice(0, 2)" :key="item.uuid">
                <!-- Card -->
                <div class="items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
                style="vertical-align: middle">
                    <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-200" x-text="item.company_name"></h3>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400" x-text="item.description_except"></span>
                    <small class="text-sm text-gray-500 dark:text-gray-400 block mt-4" x-text="formatDate(item.last_update)"></small>
                    <a :href="item.url" class="btn gradient-primary btn-block btn-sm mt-4">
                        Open Project
                    </a>
                </div>
            </template>
            <template x-if="stats.length >= 3">
                <!-- Card -->
                <div style="
                position: relative;
                width: 280px;
                background: #ffffff;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                font-family: Inter, sans-serif;
                transition: all 0.3s ease;
                " class="items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                    <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-200" x-text="stats[2].company_name">
                    </h3>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400" x-text="stats[2].description_except"></span>
                    <small class="text-sm text-gray-500 dark:text-gray-400 block mt-4" x-text="formatDate(stats[2].date_created)"></small>
                    <a :href="stats[2].url" class="btn gradient-primary btn-block btn-sm mt-4">
                        Open Project
                    </a>
                    <!-- Overlay -->
                    <a href="<?= base_url('project') ?>" class="dashboard-project-card-overlay">
                        Show All Projects
                    </a>
                </div>
            </template>
        </template>

        <!-- Kosong -->
        <template x-if="!loading && !error && (!stats || !stats.length)">
            <!-- Card Add Project -->
            <a href="<?= base_url('project?step=profile') ?>" class="flex items-center p-4 rounded-lg shadow-xs text-green-500 bg-green-100 text-center"
            style="border: 4px dashed #0e9f6e">
                <div class="mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-32 h-32">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span>New Project</span>
                </div>
            </a>
        </template>
    </div>


</div>