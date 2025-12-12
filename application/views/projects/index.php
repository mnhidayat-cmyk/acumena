<!-- Header -->
<div class="container px-6 mx-auto">
  <div class="flex items-center justify-between my-6">
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Projects</h2>
    <a href="<?= base_url('project?step=profile') ?>"
       class="gradient-primary flex items-center justify-center px-4 py-2 text-white font-medium rounded-lg transition hover:opacity-90">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
           stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
      </svg>
      Create Project
    </a>
  </div>
</div>

<!-- Wrapper -->
<div class="container mx-auto px-6" x-data="projectsUI()" x-init="load()">
  <!-- Search + Toggle -->
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <div class="flex items-center gap-3">
      <!-- Input Search -->
      <input
        x-model="query"
        @input="search()"
        type="text"
        placeholder="Search Project..."
        class="flex-1 px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 focus:bg-white dark:focus:bg-gray-900"
      />

      <!-- Toggle View -->
      <button
        @click="view = (view === 'list' ? 'grid' : 'list'); localStorage.setItem('projectView', view)"
        class="p-2 rounded-lg text-blue-500 dark:text-blue-500 focus:outline-none"
        :title="view === 'list' ? 'Ubah ke Grid' : 'Ubah ke List'"
      >
        <!-- Icon list -->
        <svg x-show="view === 'grid'" xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M3.75 6A2.25 2.25 0 0 1 6 3.75h12A2.25 2.25 0 0 1 20.25 6v0A2.25 2.25 0 0 1 18 8.25H6A2.25 2.25 0 0 1 3.75 6ZM3.75 12A2.25 2.25 0 0 1 6 9.75h12A2.25 2.25 0 0 1 20.25 12v0A2.25 2.25 0 0 1 18 14.25H6A2.25 2.25 0 0 1 3.75 12ZM3.75 18A2.25 2.25 0 0 1 6 15.75h12A2.25 2.25 0 0 1 20.25 18v0A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18Z"/>
        </svg>
        <!-- Icon grid -->
        <svg x-show="view === 'list'" xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 8.25V6ZM13.5 6A2.25 2.25 0 0 1 15.75 3.75H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25A2.25 2.25 0 0 1 13.5 8.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 15.75V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
        </svg>
      </button>
    </div>

    <!-- Info -->
    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
      <span x-text="filtered.length"></span> Results
      <span class="mx-1">•</span>
      View: <span class="font-medium" x-text="view"></span>
      <!-- DEBUG sementara: tampilkan query -->
      <!-- <span class="mx-1">•</span> Query: <code x-text="query"></code> -->
    </div>
  </div>

  <!-- LIST VIEW -->
  <template x-if="view === 'list'">
    <div class="p-0" x-transition.opacity.duration.200ms>
      <ul class="space-y-4">
        <template x-if="loading">
          <li class="text-sm text-gray-500">Loading...</li>
        </template>

        <template x-if="!loading && !filtered.length">
          <li class="text-sm text-gray-500">No projects found.</li>
        </template>

        <template x-for="item in filtered" :key="item.uuid">
          <li class="shadow-md flex rounded-lg items-center justify-between gap-4 px-4 py-3 bg-white dark:bg-gray-800 hover:shadow-md transition hover:-translate-y-0.5">
            <div>
              <a :href="item.url" class="block font-semibold text-lg text-gray-800 dark:text-gray-100 hover:underline"
                 x-text="item.company_name"></a>
              <span class="text-sm text-gray-500 dark:text-gray-400">
                Created at <span x-text="formatDate(item.date_created)"></span>
              </span>
              <span class="text-gray-400"> | </span>
              <span class="text-sm text-gray-500 dark:text-gray-400">
                Last updated at <span x-text="formatDate(item.last_update)"></span>
              </span>
            </div>
            <div class="flex items-center gap-2">
              <a :href="item.url" class="btn btn-soft-primary btn-sm flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="20" width="20">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg> Open
              </a>
              <button class="btn btn-soft-danger btn-sm flex items-center justify-center gap-2" :data-id="item.uuid" @click="deleteProject(item.uuid, item.company_name)">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                Delete
              </button>
            </div>
          </li>
        </template>
      </ul>
    </div>
  </template>

  <!-- GRID VIEW -->
  <template x-if="view === 'grid'">
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4" x-transition.opacity.duration.200ms>
      <template x-if="loading">
        <div class="text-sm text-gray-500">Loading...</div>
      </template>

      <template x-if="!loading && !filtered.length">
        <div class="text-sm text-gray-500">No projects found.</div>
      </template>

      <template x-for="item in filtered" :key="item.uuid">
        <div class="items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800" style="vertical-align: middle">
          <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-200" x-text="item.company_name"></h3>
          <span class="text-sm font-medium text-gray-600 dark:text-gray-400" x-text="item.description_except"></span>
          <small class="text-sm text-gray-500 dark:text-gray-400 block mt-4" x-text="formatDate(item.last_update)"></small>
          <div class="grid grid-cols-3 gap-2">
            <a :href="item.url" class="btn gradient-primary btn-block btn-sm mt-4 col-span-2">Open Project</a>
            
            <button class="py-0 btn btn-danger btn-sm flex items-center justify-center gap-2 mt-4" :data-id="item.uuid" @click="deleteProject(item.uuid, item.company_name)">
              <svg xmlns="http://www.w3.org/2000/svg" height="20"  width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
            </button>
          </div>
        </div>
      </template>
    </div>
  </template>
  
  <!-- Pagination UI -->
  <div x-show="!loading && totalPages > 1" class="mt-6 flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
    <div class="flex flex-1 justify-between sm:hidden">
      <!-- Mobile pagination -->
      <button 
        @click="prevPage()" 
        :disabled="currentPage <= 1"
        :class="currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700">
        Previous
      </button>
      <button 
        @click="nextPage()" 
        :disabled="currentPage >= totalPages"
        :class="currentPage >= totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700">
        Next
      </button>
    </div>
    
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Showing
          <span class="font-medium" x-text="((currentPage - 1) * itemsPerPage) + 1"></span>
          to
          <span class="font-medium" x-text="Math.min(currentPage * itemsPerPage, totalItems)"></span>
          of
          <span class="font-medium" x-text="totalItems"></span>
          results
        </p>
      </div>
      
      <div>
        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
          <!-- Previous button -->
          <button 
            @click="prevPage()" 
            :disabled="currentPage <= 1"
            :class="currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            <span class="sr-only">Previous</span>
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
            </svg>
          </button>
          
          <!-- Page numbers -->
          <template x-for="page in Array.from({length: Math.min(5, totalPages)}, (_, i) => {
            const start = Math.max(1, Math.min(currentPage - 2, totalPages - 4));
            return start + i;
          })" :key="page">
            <button 
              @click="goToPage(page)" 
              :class="page === currentPage ? 
                'relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' : 
                'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0'"
              x-text="page">
            </button>
          </template>
          
          <!-- Next button -->
          <button 
            @click="nextPage()" 
            :disabled="currentPage >= totalPages"
            :class="currentPage >= totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            <span class="sr-only">Next</span>
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
            </svg>
          </button>
        </nav>
      </div>
    </div>
  </div>
</div>

<!-- Alpine state -->
<script>
  function projectsUI () {
    return {
      // state
      items: [],
      query: "",
      view: "list",
      loading: true,
      
      // pagination state
      currentPage: 1,
      totalPages: 1,
      itemsPerPage: 10,
      totalItems: 0,
      
      // search debounce
      searchTimeout: null,

      // computed: hasil filter SELALU mengikuti query/items
      get filtered() {
        // Untuk server-side pagination, kita tidak perlu filter di client
        // karena filtering sudah dilakukan di server
        return this.items;
      },

      // Format date function
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
      },

      init() {
        // preferensi tampilan
        this.view = localStorage.getItem('projectView') || 'list';
        // load data pertama kali
        this.load();
      },

      async load(page = null) {
        try {
          this.loading = true;
          
          // Set current page jika diberikan
          if (page !== null) {
            this.currentPage = page;
          }
          
          // Hitung offset berdasarkan current page
          const offset = (this.currentPage - 1) * this.itemsPerPage;
          
          // Build URL dengan parameter pagination dan search
          const params = new URLSearchParams({
            limit: this.itemsPerPage,
            offset: offset,
            search: this.query || '',
            sort: 'date_created',
            order: 'desc'
          });
          
          const url = '<?= base_url('api/project/get_projects_by_user_id') ?>?' + params.toString();
          const r = await fetch(url);
          
          if (!r.ok) throw new Error('HTTP ' + r.status);
          const json = await r.json();
          
          if (json.success) {
            this.items = Array.isArray(json.data) ? json.data : [];
            
            // Update pagination info
            if (json.pagination) {
              this.totalItems = json.pagination.total;
              this.totalPages = json.pagination.total_pages;
              this.currentPage = json.pagination.current_page;
            }
          } else {
            throw new Error(json.message || 'Failed to load data');
          }
        } catch (e) {
          console.error(e);
          this.items = [];
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal memuat data projects: ' + e.message
          });
        } finally {
          this.loading = false;
        }
      },

      // Method untuk navigasi pagination
      goToPage(page) {
        if (page >= 1 && page <= this.totalPages && page !== this.currentPage) {
          this.load(page);
        }
      },

      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.goToPage(this.currentPage + 1);
        }
      },

      prevPage() {
        if (this.currentPage > 1) {
          this.goToPage(this.currentPage - 1);
        }
      },

      // Method untuk search dengan debounce
      search() {
        // Clear timeout sebelumnya
        if (this.searchTimeout) {
          clearTimeout(this.searchTimeout);
        }
        
        // Set timeout baru untuk debounce
        this.searchTimeout = setTimeout(() => {
          this.currentPage = 1; // Reset ke halaman pertama saat search
          this.load();
        }, 500); // 500ms debounce
      },

      async deleteProject(uuid, projectName) {
        // Show SweetAlert confirmation
        const result = await Swal.fire({
          title: 'Hapus Project?',
          text: `Apakah Anda yakin ingin menghapus project "${projectName}"? Tindakan ini tidak dapat dibatalkan.`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
          try {
            // Show loading
            Swal.fire({
              title: 'Menghapus...',
              text: 'Sedang menghapus project',
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              }
            });

            // Make AJAX call to delete endpoint
            const response = await fetch('<?= base_url('api/project/delete') ?>', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                uuid: uuid
              })
            });

            const result = await response.json();

            if (response.ok && result.success) {
              // Remove item from local array
              this.items = this.items.filter(item => item.uuid !== uuid);
              
              // Show success message
              Swal.fire({
                title: 'Berhasil!',
                text: 'Project berhasil dihapus.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
              });
            } else {
              throw new Error(result.message || 'Gagal menghapus project');
            }
          } catch (error) {
            console.error('Delete error:', error);
            Swal.fire({
              title: 'Error!',
              text: error.message || 'Terjadi kesalahan saat menghapus project.',
              icon: 'error'
            });
          }
        }
      },
    }
  }
</script>
