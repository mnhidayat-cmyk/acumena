<!-- Header -->
<div class="container px-6 mx-auto">
  <div class="flex items-center justify-between my-6">
    <div>
      <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200">
        <span class="text-gray-400">Create Project::</span> SWOT Analysis
      </h2>
      <p class="text-gray-500">Identify your organization's Strengths, Weaknesses, Opportunities, and Threats</p>
    </div>
  </div>
</div>

<div class="container mx-auto px-6">
  <style>
    @media (max-width: 1024px) {
      .steps { gap: 6px !important; }
      .step { padding: 8px !important; }
      .step__desc { display: none !important; }
      .step__title { font-size: 13px !important; line-height: 1.2 !important; }
    }
    .section-swot-body-item {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 12px;
    }
    .section-swot,
    .dark\:section-swot {
      background: transparent;
      border-radius: 16px;
      overflow: hidden;
      border: 1px solid rgba(0,0,0,.06);
      box-shadow: 0 1px 2px rgba(0,0,0,.04);
      flex: 1 1 360px;
      min-width: 320px;
    }
    .section-swot-header {
      padding: 12px 16px;
      font-weight: 700;
    }
    .section-swot-body {
      padding: 16px;
    }
    .bg-soft-success { background: #ecfdf5; }
    .bg-soft-primary { background: #eff6ff; }
    .bg-soft-warning { background: #fffbeb; }
    .bg-soft-danger  { background: #fef2f2; }

    .btn-soft-success { background: #ecfdf5; color: #065f46; }
    .btn-soft-primary { background: #eff6ff; color: #1d4ed8; }
    .btn-soft-warning { background: #fffbeb; color: #b45309; }
    .btn-soft-danger  { background: #fef2f2; color: #b91c1c; }
    .btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 14px; border-radius: 8px; font-weight: 600; }
    .gradient-primary { background: linear-gradient(90deg,#250bac,#6ccbe0); }
    .btn-soft-secondary { background: #f3f4f6; color: #111827; }
    .validation-error { 
      border: 2px solid #ef4444 !important; 
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    .validation-error-message {
      color: #ef4444;
      font-size: 12px;
      margin-top: 4px;
      font-weight: 500;
    }
  </style>

  <div>
    <div class="steps flex items-stretch justify-between gap-3">
      <!-- STEP 1-->
      <div class="step step--inactive bg-white dark:bg-gray-800 rounded-2xl px-4 py-2 items-center border border-gray-200 dark:border-gray-600 gap-2 flex flex-1 basis-0">
        <div class="step__num w-6 h-6 flex items-center justify-center rounded-full shadow-md font-bold bg-gray-300 dark:bg-gray-700 dark:text-white w-9 h-9 flex-shrink-0 flex-grow-0 basis-9 flex justify-center">1</div>
        <div class="flex flex-col gap-1 min-w-0">
          <div class="step__title font-bold text-sm dark:text-white">Company Profile</div>
          <div class="step__desc text-xs text-gray-500 dark:text-gray-400">Complete your company data</div>
        </div>
      </div>

      <!-- STEP 2 (Active)-->
      <div class="step step--active bg-blue-300 rounded-2xl px-4 py-2 items-center gap-2 flex flex-1 basis-0 border border-gray-200">
        <div class="step__num w-6 h-6 flex items-center justify-center rounded-full shadow-md font-bold text-white bg-blue-600 w-9 h-9 flex-shrink-0 flex-grow-0 basis-9 flex justify-center">2</div>
        <div class="flex flex-col gap-1 min-w-0">
          <div class="step__title font-bold text-sm">SWOT Analysis</div>
          <div class="step__desc text-xs text-gray-500">Assess Strengths, Weaknesses, Opportunities, and Threats</div>
        </div>
      </div>

      <!-- STEP 3 -->
      <div class="step step--inactive bg-white dark:bg-gray-800 rounded-2xl px-4 py-2 items-center border border-gray-200 dark:border-gray-600 gap-2 flex flex-1 basis-0">
        <div class="step__num w-6 h-6 flex items-center justify-center rounded-full shadow-md font-bold bg-gray-300 dark:bg-gray-700 dark:text-white w-9 h-9 flex-shrink-0 flex-grow-0 basis-9 flex justify-center">3</div>
        <div class="flex flex-col gap-1 min-w-0">
          <div class="step__title font-bold text-sm dark:text-white">Matrix Analysis</div>
          <div class="step__desc text-xs text-gray-500">Evaluate IE Matrix and strategic recommendations</div>
        </div>
      </div>
    </div>
  </div>

  <!-- GRID -->
  <div class="gap-6 items-stretch mt-8 grid grid-cols-2">
    <!-- ========== STRENGTHS ========== -->
    <section class="section-swot dark:section-swot" id="strengths-section">
      <div class="section-swot-header bg-soft-success dark:text-white">
        <div class="font-bold">💪 Strengths</div>
      </div>

      <div class="section-swot-body" id="strengths-container">
        <!-- Dynamic items will be inserted ABOVE this button -->
        <button
          type="button"
          data-role="add"
          class="flex items-center justify-center btn btn-soft-success rounded-md w-full font-semibold px-4 py-2"
          onclick="addSwotItem('strengths', 'strength')">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor" class="mr-2">
            <path d="M11 5a1 1 0 012 0v6h6a1 1 0 010 2h-6v6a1 1 0 01-2 0v-6H5a1 1 0 010-2h6V5z"/>
          </svg>
          Add Strength
        </button>
      </div>
    </section>

    <!-- ========== OPPORTUNITIES ========== -->
    <section class="section-swot dark:section-swot" id="opportunities-section">
      <div class="section-swot-header bg-soft-primary dark:text-white">
        <div class="font-bold">🚀 Opportunities</div>
      </div>
      <div class="section-swot-body" id="opportunities-container">
        <!-- Dynamic items will be inserted ABOVE this button -->
        <button
          type="button"
          data-role="add"
          class="flex items-center justify-center btn btn-soft-primary rounded-md w-full font-semibold px-4 py-2"
          onclick="addSwotItem('opportunities', 'opportunity')">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor" class="mr-2">
            <path d="M11 5a1 1 0 012 0v6h6a1 1 0 010 2h-6v6a1 1 0 01-2 0v-6H5a1 1 0 010-2h6V5z"/>
          </svg>
          Add Opportunity
        </button>
      </div>
    </section>

    <!-- ========== WEAKNESSES ========== -->
    <section class="section-swot dark:section-swot" id="weaknesses-section">
      <div class="section-swot-header bg-soft-warning dark:text-white">
        <div class="font-bold">⚠️ Weaknesses</div>
      </div>
      <div class="section-swot-body" id="weaknesses-container">
        <!-- Dynamic items will be inserted ABOVE this button -->
        <button
          type="button"
          data-role="add"
          class="flex items-center justify-center btn btn-soft-warning rounded-md w-full font-semibold px-4 py-2"
          onclick="addSwotItem('weaknesses', 'weakness')">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor" class="mr-2">
            <path d="M11 5a1 1 0 012 0v6h6a1 1 0 010 2h-6v6a1 1 0 01-2 0v-6H5a1 1 0 010-2h6V5z"/>
          </svg>
          Add Weakness
        </button>
      </div>
    </section>

    <!-- ========== THREATS ========== -->
    <section class="section-swot dark:section-swot" id="threats-section">
      <div class="section-swot-header bg-soft-danger dark:text-white">
        <div class="font-bold">🛡️ Threats</div>
      </div>
      <div class="section-swot-body" id="threats-container">
        <!-- Dynamic items will be inserted ABOVE this button -->
        <button
          type="button"
          data-role="add"
          class="flex items-center justify-center btn btn-soft-danger rounded-md w-full font-semibold px-4 py-2"
          onclick="addSwotItem('threats', 'threat')">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor" class="mr-2">
            <path d="M11 5a1 1 0 012 0v6h6a1 1 0 010 2h-6v6a1 1 0 01-2 0v-6H5a1 1 0 010-2h6V5z"/>
          </svg>
          Add Threat
        </button>
      </div>
    </section>
  </div>

  <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-md mx-auto mt-8 p-6">
    <!-- Button Next -->
    <div class="flex justify-between">
      <a href="?step=profile&key=<?= $_GET['key'] ?>" class="btn btn-soft-secondary" style="padding-left: 24px;padding-right: 24px;">
        Previous
      </a>
      <button id="next-btn" type="button" class="btn gradient-primary text-white" style="padding-left: 24px;padding-right: 24px;" onclick="saveSwotData()">
        Next
      </button>
    </div>
  </div>
</div>

<script>
  let currentProjectUuid = null;

  // Get UUID from URL parameter
  function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
  }

  // UTIL: buat elemen item
  function buildItem(category, placeholder, value = '') {
    const itemDiv = document.createElement('div');
    itemDiv.className = 'section-swot-body-item';
    itemDiv.innerHTML = `
      <div style="flex:1 1 auto;">
        <input
          placeholder="Enter ${placeholder}"
          class="w-full px-4 py-3 border border-gray-300 text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 focus:bg-white dark:focus:bg-gray-900"
          data-category="${category}"
          value="${value ? String(value).replace(/"/g,'&quot;') : ''}"
        />
      </div>
      <button
        type="button"
        title="Delete"
        style="flex:0 0 auto;border:0;padding:11px;border-radius:5px;cursor:pointer;"
        class="btn btn-soft-danger"
        onclick="removeSwotItem(this)"
      >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
        </svg>
      </button>
    `;
    return itemDiv;
  }

  // Add SWOT item (selalu di atas tombol Add)
  function addSwotItem(category, placeholder) {
    const container = document.getElementById(`${category}-container`);
    if (!container) {
      console.error('Container not found for category:', category);
      return;
    }

    // Tombol 'Add' sebagai anchor; harus anak langsung container
    const addButton = container.querySelector('[data-role="add"]');
    if (!addButton || addButton.parentNode !== container) {
      console.error('Add button not found as a direct child of container for:', category);
      return;
    }

    const itemDiv = buildItem(category, placeholder);
    container.insertBefore(itemDiv, addButton);
  }

  // Remove SWOT item
  function removeSwotItem(button) {
    const item = button.closest('.section-swot-body-item');
    if (item && item.parentNode) item.parentNode.removeChild(item);
  }

  // Initialize page
  document.addEventListener('DOMContentLoaded', function () {
    currentProjectUuid = getUrlParameter('key');

    if (currentProjectUuid) {
      loadSwotData();
    } else {
      // Add initial empty items for each category
      addSwotItem('strengths', 'strength');
      addSwotItem('opportunities', 'opportunity');
      addSwotItem('weaknesses', 'weakness');
      addSwotItem('threats', 'threat');
    }
  });

  // Load existing SWOT data
  async function loadSwotData() {
    if (!currentProjectUuid) return;

    try {
      const response = await fetch(`<?= base_url('api/project/swot_get') ?>?uuid=${currentProjectUuid}`);
      const result = await response.json();

      // Helper untuk isi data per kategori
      const fillCategory = (key, category, placeholder) => {
        const container = document.getElementById(`${category}-container`);
        // clear items (selain tombol add)
        [...container.querySelectorAll('.section-swot-body-item')].forEach(n => n.remove());

        if (result?.data?.[key]?.length > 0) {
          result.data[key].forEach(item => {
            const addBtn = container.querySelector('[data-role="add"]');
            const node = buildItem(category, placeholder, item.description || '');
            container.insertBefore(node, addBtn);
          });
        } else {
          addSwotItem(category, placeholder);
        }
      };

      if (result.success && result.data) {
        fillCategory('S', 'strengths', 'strength');
        fillCategory('W', 'weaknesses', 'weakness');
        fillCategory('O', 'opportunities', 'opportunity');
        fillCategory('T', 'threats', 'threat');
      } else {
        addSwotItem('strengths', 'strength');
        addSwotItem('opportunities', 'opportunity');
        addSwotItem('weaknesses', 'weakness');
        addSwotItem('threats', 'threat');
      }
    } catch (error) {
      console.error('Error loading SWOT data:', error);
      addSwotItem('strengths', 'strength');
      addSwotItem('opportunities', 'opportunity');
      addSwotItem('weaknesses', 'weakness');
      addSwotItem('threats', 'threat');
    }
  }

  // Validate SWOT data
  function validateSwotData() {
    const categories = [
      { name: 'strengths', label: 'Strengths', selector: '#strengths-container input[data-category="strengths"]' },
      { name: 'opportunities', label: 'Opportunities', selector: '#opportunities-container input[data-category="opportunities"]' },
      { name: 'weaknesses', label: 'Weaknesses', selector: '#weaknesses-container input[data-category="weaknesses"]' },
      { name: 'threats', label: 'Threats', selector: '#threats-container input[data-category="threats"]' }
    ];

    const errors = [];
    const emptyCategories = [];

    categories.forEach(category => {
      const inputs = document.querySelectorAll(category.selector);
      const filledValues = Array.from(inputs).map(input => input.value.trim()).filter(Boolean);
      
      // Reset previous error styling
      const section = document.getElementById(`${category.name}-section`);
      section.classList.remove('validation-error');
      
      // Remove existing error message
      const existingErrorMsg = section.querySelector('.validation-error-message');
      if (existingErrorMsg) {
        existingErrorMsg.remove();
      }
      
      if (filledValues.length === 0) {
        errors.push(`${category.label} harus memiliki minimal 1 item yang terisi`);
        emptyCategories.push(category.name);
        
        // Add error styling
        section.classList.add('validation-error');
        
        // Add error message
        const errorMsg = document.createElement('div');
        errorMsg.className = 'validation-error-message';
        errorMsg.textContent = `Minimal 1 ${category.label.toLowerCase()} harus diisi`;
        section.appendChild(errorMsg);
      }
    });

    return {
      isValid: errors.length === 0,
      errors: errors,
      emptyCategories: emptyCategories
    };
  }

  // Save SWOT data
  async function saveSwotData() {
    if (!currentProjectUuid) {
      showAlert('Project UUID not found', 'error');
      return;
    }

    // Validate SWOT data first
    const validation = validateSwotData();
    if (!validation.isValid) {
      showAlert(validation.errors.join(', '), 'error');
      return;
    }

    const nextBtn = document.getElementById('next-btn');
    nextBtn.disabled = true;
    nextBtn.textContent = 'Saving...';

    try {
      const strengthInputs = document.querySelectorAll('#strengths-container input[data-category="strengths"]');
      const weaknessInputs = document.querySelectorAll('#weaknesses-container input[data-category="weaknesses"]');
      const opportunityInputs = document.querySelectorAll('#opportunities-container input[data-category="opportunities"]');
      const threatInputs = document.querySelectorAll('#threats-container input[data-category="threats"]');

      const swotData = {
        uuid: currentProjectUuid,
        strengths: Array.from(strengthInputs).map(input => input.value.trim()).filter(Boolean),
        weaknesses: Array.from(weaknessInputs).map(input => input.value.trim()).filter(Boolean),
        opportunities: Array.from(opportunityInputs).map(input => input.value.trim()).filter(Boolean),
        threats: Array.from(threatInputs).map(input => input.value.trim()).filter(Boolean),
      };

      const response = await fetch('<?= base_url('api/project/swot_save') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(swotData),
      });

      const result = await response.json();

      if (result.success) {
        window.location.href = `?step=matrix-ife&key=${currentProjectUuid}`;
      } else {
        showAlert(result.message || 'Failed to save SWOT data', 'error');
      }
    } catch (error) {
      console.error('Error saving SWOT data:', error);
      showAlert('An error occurred while saving data', 'error');
    } finally {
      nextBtn.disabled = false;
      nextBtn.textContent = 'Next';
    }
  }

  // Show alert
  function showAlert(message, type = 'info') {
    const alert = document.createElement('div');
    alert.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg max-w-sm ${
      type === 'error' ? 'bg-red-500 text-white' :
      type === 'success' ? 'bg-green-500 text-white' :
      'bg-blue-500 text-white'
    }`;
    alert.textContent = message;

    document.body.appendChild(alert);
    setTimeout(() => alert.parentNode && alert.parentNode.removeChild(alert), 3000);
  }
</script>
