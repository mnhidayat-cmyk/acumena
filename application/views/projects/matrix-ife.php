<!-- Header -->
<div class="container px-6 mx-auto">
  <div class="flex items-center justify-between my-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200"><span class="text-gray-400">Create Project::</span> Matrix</h2>
        <p class="text-gray-500">Please provide basic information about your company before proceeding with the strategic analysis</p>
    </div>
  </div>
</div>

<div class="container mx-auto px-6">
    <style>
    @media (max-width: 1024px) {
        .steps { gap:6px !important; }
        .step { padding:8px !important; }
        .step__desc { display:none !important; }
        .step__title { font-size:13px !important; line-height:1.2 !important; }
    }
    
    /* Print styles for PDF export */
    @media print {
        /* Hide specific elements during print */
        .no-print {
            display: none !important;
        }
        
        /* Hide all elements by default */
        * {
            visibility: hidden;
        }
        
        /* Show only the printable content and its children */
        .printable-content,
        .printable-content * {
            visibility: visible !important;
        }
        
        /* Position the printable content */
        .printable-content {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 20px !important;
            background: white !important;
        }
        
        /* Ensure table styling for print */
        table {
            width: 100% !important;
            font-size: 11px !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
        }
        
        table th, table td {
            border: 1px solid #000 !important;
            padding: 8px !important;
            word-wrap: break-word !important;
        }
        
        /* Number column styling for print */
        table th:first-child,
        table td:first-child {
            width: 40px !important;
            text-align: center !important;
            font-weight: bold !important;
        }
        
        /* Rating column styling for print - make it wider */
        table th:nth-child(4),
        table td:nth-child(4) {
            width: 150px !important;
            min-width: 150px !important;
            text-align: left !important;
        }
        
        /* Weight column styling for print */
        table th:nth-child(3),
        table td:nth-child(3) {
            width: 120px !important;
            min-width: 120px !important;
            text-align: center !important;
        }
        
        /* Weight Score column styling for print */
        table th:nth-child(5),
        table td:nth-child(5) {
            width: 80px !important;
            min-width: 80px !important;
            text-align: center !important;
        }
        
        /* Chart container styling */
        .chart-container {
            page-break-inside: avoid;
            margin-top: 20px !important;
        }
        
        /* Convert form inputs to text for print */
        .weight-input,
        .rating-select {
            border: none !important;
            background: transparent !important;
            padding: 0 !important;
            font-size: 13px !important;
            color: #000 !important;
            font-weight: bold !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
        }
        
        /* Hide select dropdown arrow */
        .rating-select::-ms-expand {
            display: none !important;
        }
        
        /* Style the selected option text */
        .rating-select option:checked {
            display: block !important;
        }
        
        canvas {
            max-width: 100% !important;
            height: auto !important;
        }
        
        /* Remove margins and padding for better print layout */
        .container {
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Ensure proper page breaks */
        .chart-container {
            page-break-inside: avoid;
        }
    }
    </style>

    <div>
        <div class="steps flex items-stretch justify-between gap-3">

            <!-- STEP 1 (Active) -->
            <div class="step step--inactive bg-white dark:bg-gray-800 rounded-2xl px-4 py-2 items-center border border-gray-200 dark:border-gray-600 gap-2 flex flex-1 basis-0">
                <div class="step__num w-6 h-6 flex items-center justify-center rounded-full shadow-md font-bold  bg-gray-300 dark:bg-gray-700 dark:text-white w-9 h-9 flex-shrink-0 flex-grow-0 basis-9 flex justify-center">1</div>
                <div class="flex flex-col gap-1 min-w-0">
                    <div class="step__title font-bold text-sm dark:text-white">Company Profile</div>
                    <div class="step__desc text-xs text-gray-500">Complete your company data</div>
                </div>
            </div>

            <!-- STEP 2 -->
            <div class="step step--inactive bg-white dark:bg-gray-800 rounded-2xl px-4 py-2 items-center border border-gray-200 dark:border-gray-600 gap-2 flex flex-1 basis-0">
                <div class="step__num w-6 h-6 flex items-center justify-center rounded-full shadow-md font-bold  bg-gray-300 dark:bg-gray-700 dark:text-white w-9 h-9 flex-shrink-0 flex-grow-0 basis-9 flex justify-center">2</div>
                <div class="flex flex-col gap-1 min-w-0">
                    <div class="step__title font-bold text-sm dark:text-white">SWOT Analysis</div>
                    <div class="step__desc text-xs text-gray-500">Assess Strengths, Weaknesses, Opportunities, and Threats</div>
                </div>
            </div>

            <!-- STEP 3 -->
            <div class="step step--active bg-blue-300 rounded-2xl px-4 py-2 items-center gap-2 flex flex-1 basis-0 border border-gray-200">
                <div class="step__num w-6 h-6 flex items-center justify-center rounded-full shadow-md font-bold text-white bg-blue-600 w-9 h-9 flex-shrink-0 flex-grow-0 basis-9 flex justify-center">3</div>
                <div class="flex flex-col gap-1 min-w-0">
                    <div class="step__title font-bold text-sm">Matrix Analysis</div>
                    <div class="step__desc text-xs text-gray-500">Evaluate IE Matrix and strategic recommendations</div>
                </div>
            </div>

        </div>
    </div>

    <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-md mx-auto mt-8 p-6">
        <!-- Tabs Wrapper -->
        <div class="rounded-lg bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 flex flex-wrap p-2 gap-2">

            <!-- IFE -->
            <a href="#" class="bg-success text-white shadow-md flex items-center gap-2 px-4 py-2 rounded-lg text-sm">
                <!-- Desktop label -->
                <span class="font-bold hidden md:inline">IFE Matrix</span>
                <!-- Mobile label -->
                <span class="font-bold md:hidden inline">IFE</span>
                <!-- Keterangan: tampil hanya di md+ -->
                <span class="hidden md:inline">(Internal)</span>
            </a>

            <!-- EFE -->
            <a href="<?= base_url('project?step=matrix-efe&key='.$_GET['key']) ?>" class="text-gray-700 dark:text-white flex items-center gap-2 px-4 py-2 rounded-lg text-sm">
                <span class="font-bold hidden md:inline">EFE Matrix</span>
                <span class="font-bold md:hidden inline">EFE</span>
                <span class="hidden md:inline">(External)</span>
            </a>

            <!-- IE -->
            <a href="<?= base_url('project?step=matrix-ie&key='.$_GET['key']) ?>" class="text-gray-700 dark:text-white flex items-center gap-2 px-4 py-2 rounded-lg text-sm">
                <span class="font-bold hidden md:inline">IE Matrix</span>
                <span class="font-bold md:hidden inline">IE</span>
                <span class="hidden md:inline">(Combined)</span>
            </a>

            <!-- AI Integration -->
            <a href="<?= base_url('project?step=matrix-ai&key='.$_GET['key']) ?>" class="text-gray-700 dark:text-white flex items-center gap-2 px-4 py-2 rounded-lg text-sm">
                <!-- Sama di mobile & desktop: "AI Integration" -->
                <span class="font-bold">AI Integration</span>
                <!-- Keterangan hanya di md+ -->
                <span class="hidden md:inline">(Strategies)</span>
            </a>

        </div>

        <!-- Printable Content Container -->
        <div class="printable-content">
        <div class="mt-6">
            <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200">Internal Factor Evaluation</h2>
            <span class="text-gray-500">Assign weights (0.0-1.0) and ratings (1-4) to your internal strategic factors</span>
        </div>

        <!-- Responsive table wrapper -->
        <div class="mt-8 w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-600">
        <table class="w-full min-w-[900px]">
            <thead>
            <tr class="bg-gray-100 dark:bg-gray-900">
                <th class="px-4 py-3 text-left text-sm font-bold text-gray-700 dark:text-gray-200 sticky top-0 bg-gray-100 dark:bg-gray-900">#</th>
                <th class="px-4 py-2 text-left text-sm font-bold text-gray-700 dark:text-gray-200 sticky top-0 bg-gray-100 dark:bg-gray-900">Internal Factors</th>
                <th class="px-4 py-2 text-left text-sm font-bold text-gray-700 dark:text-gray-200 sticky top-0 bg-gray-100 dark:bg-gray-900">Weight</th>
                <th class="px-4 py-2 text-left text-sm font-bold text-gray-700 dark:text-gray-200 sticky top-0 bg-gray-100 dark:bg-gray-900">Rating</th>
                <th class="px-4 py-2 text-left text-sm font-bold text-gray-700 dark:text-gray-200 sticky top-0 bg-gray-100 dark:bg-gray-900">Weight Score</th>
            </tr>
            </thead>
            <tbody id="ifeTableBody">
                <tr>
                    <td colspan="5" class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top bg-soft-success font-bold text-success">
                        Strengths
                    </td>
                </tr>
                <!-- Strengths rows will be populated by JavaScript -->
                <tr>
                    <td colspan="5" class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top bg-soft-danger font-bold text-danger">
                        Weaknesses
                    </td>
                </tr>
                <!-- Weaknesses rows will be populated by JavaScript -->
                <tr class="bg-gray-100 dark:bg-gray-900">
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top text-right font-bold" colspan="2">Total</td>

                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top font-bold" style="width:70%;">
                        0.00<br/>(Should be 1.0)
                    </td>

                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top"></td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top font-bold">0.00</td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top"></td>
                </tr>
                <!-- more rows ... -->
            </tbody>
        </table>
        </div>

        <!-- Save Button -->
        <div class="mt-4 w-full flex justify-center no-print">
            <button id="saveIFEData" onclick="saveIFEData()" 
                    class="w-full px-6 py-2 btn btn-block gradient-primary rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />
                </svg>
                Save IFE Data
            </button>
        </div>

        <!-- chart -->
        <div class="mt-8 w-full rounded-lg border border-gray-300 dark:border-gray-600 p-4 bg-white dark:bg-gray-900 chart-container">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-700 dark:text-gray-200">Score Analysis</h2>
                <div class="max-w-3xl">
                    <div class="flex items-start gap-3">
                        <!-- Score badge -->
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                        <span class="text-gray-900 font-bold">0.00</span>
                        </div>

                        <!-- Text -->
                        <div class="space-y-0.5">
                        <!-- Title with icon -->
                        <div class="flex items-center gap-2" id="scoreIconContainer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            </svg>
                            <span class="text-orange-500 font-semibold" id="scoreText">Internal Factor Score</span>
                        </div>

                        <!-- Subtitle -->
                        <p class="text-sm text-gray-500">
                            Weak internal position. Strategic improvements are urgently needed.
                        </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8">
                <div class="relative p-4">
                    <div class="relative">
                        <!-- Canvas Chart -->
                        <canvas id="ifeChart" height="300"></canvas>
                    </div>
                </div>

                <!-- Legend rentang skor -->
                <div class="mt-4 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">Score Range Interpretation:</div>
                    <div class="grid grid-cols-5 gap-4">
                    <div>
                        <div style="height:8px;border-radius:999px;background:#fb923c;"></div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-2">1.0–1.99</div>
                        <div class="text-xs font-semibold text-gray-700 dark:text-gray-200">Weak</div>
                    </div>
                    <div>
                        <div style="height:8px;border-radius:999px;background:#f59e0b;"></div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-2">2.0–2.49</div>
                        <div class="text-xs font-semibold text-gray-700 dark:text-gray-200">Below Avg.</div>
                    </div>
                    <div>
                        <div style="height:8px;border-radius:999px;background:#3b82f6;"></div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-2">2.5–2.99</div>
                        <div class="text-xs font-semibold text-gray-700 dark:text-gray-200">Average</div>
                    </div>
                    <div>
                        <div style="height:8px;border-radius:999px;background:#22c55e;"></div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-2">3.0–3.49</div>
                        <div class="text-xs font-semibold text-gray-700 dark:text-gray-200">Above Avg.</div>
                    </div>
                    <div>
                        <div style="height:8px;border-radius:999px;background:#16a34a;"></div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-2">3.5–4.0</div>
                        <div class="text-xs font-semibold text-gray-700 dark:text-gray-200">Strong</div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        </div> <!-- End of printable-content -->

        <!-- SAVE -->
        <div class="mx-auto w-full flex flex-col sm:flex-row gap-4 mt-8 no-print">
            <!-- Export PDF -->
            <button onclick="exportToPDF()" class="flex-1 flex flex-col items-center justify-center py-4 bg-soft-primary border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8 text-blue-500 mb-2" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>
                </div>
                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">Export as PDF</span>
                <span class="text-sm text-gray-500">Document Format</span>
            </button>

            <!-- Export Excel -->
            <button onclick="exportToExcel()" class="flex-1 flex flex-col items-center justify-center py-4 bg-soft-success border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm hover:bg-green-50 transition-colors">
                <div class="text-green-600 mb-3">
                <!-- File icon (Excel) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8 text-green-600 mb-2" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M8 13h2"></path><path d="M14 13h2"></path><path d="M8 17h2"></path><path d="M14 17h2"></path></svg>
                </div>
                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">Export as Excel</span>
                <span class="text-sm text-gray-500">Spreadsheet Format</span>
            </button>
        </div>

        <!-- Button Next -->
        <div class="flex justify-between mt-8 no-print">
            <!-- <button type="submit" class="btn gradient-primary text-white" style="padding-left: 24px;padding-right: 24px;">
                Next
            </button> -->
            <a href="?step=swot&key=<?= $_GET['key'] ?>" class="btn btn-soft-secondary" style="padding-left: 24px;padding-right: 24px;">
                Previous
            </a>
            <a href="?step=matrix-efe&key=<?= $_GET['key'] ?>" class="btn gradient-primary text-white" style="padding-left: 24px;padding-right: 24px;">
                Next
            </a>
        </div>


    </div>


</div>

<script>
  // ====== LOAD SWOT DATA FOR IFE MATRIX ======
  let ifeData = { strengths: [], weaknesses: [] };
  let totalWeightScore = 0;

  // Get project UUID from URL
  const urlParams = new URLSearchParams(window.location.search);
  const projectKey = urlParams.get('key');

  // Load SWOT data when page loads
  document.addEventListener('DOMContentLoaded', function() {
    if (projectKey) {
      loadIFEData();
    }
  });

  async function loadIFEData() {
    try {
      const response = await fetch(`<?= base_url('api/project/matrix_ife_get') ?>?key=${projectKey}`);
      const result = await response.json();
      
      if (result.success) {
        ifeData = result.data;
        populateIFETable();
        calculateTotalScore();
      } else {
        console.error('Failed to load IFE data:', result.message);
      }
    } catch (error) {
      console.error('Error loading IFE data:', error);
    }
  }

  function populateIFETable() {
    const tbody = document.getElementById('ifeTableBody');
    let html = '';
    
    // Strengths header
    html += `
      <tr>
        <td colspan="5" class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top bg-soft-success font-bold text-success">
          Strengths
        </td>
      </tr>
    `;
    
    // Strengths rows
    if (ifeData.strengths && ifeData.strengths.length > 0) {
      ifeData.strengths.forEach((item, index) => {
        html += createIFERow(index + 1, item, 'strength');
      });
    } else {
      html += createEmptyRow('No strengths data available');
    }
    
    // Weaknesses header
    html += `
      <tr>
        <td colspan="5" class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top bg-soft-danger font-bold text-danger">
          Weaknesses
        </td>
      </tr>
    `;
    
    // Weaknesses rows
    if (ifeData.weaknesses && ifeData.weaknesses.length > 0) {
      ifeData.weaknesses.forEach((item, index) => {
        html += createIFERow(index + 1, item, 'weakness');
      });
    } else {
      html += createEmptyRow('No weaknesses data available');
    }
    
    // Total row
    html += `
      <tr class="bg-gray-100 dark:bg-gray-900">
        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top text-right font-bold" colspan="2">Total</td>
        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top font-bold" style="width:70%;">
          <span id="totalWeight">0.00</span><br/>(Should be 1.0)
        </td>
        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top"></td>
        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top font-bold">
          <span id="totalScore">0.00</span>
        </td>
      </tr>
    `;
    
    tbody.innerHTML = html;
  }

  function createIFERow(number, item, type) {
    const defaultWeight = 0.1;
    const defaultRating = type === 'strength' ? 4 : 1;
    const weightScore = (defaultWeight * defaultRating).toFixed(2);
    
    return `
      <tr class="bg-white dark:bg-gray-800" data-item-id="${item.id || ''}">
        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top">${number}</td>
        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top" style="width:70%;">
          ${item.description || ''}
        </td>
        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top">
          <input type="number" min="0.0" max="1.0" step="0.01" value="${item.weight || defaultWeight}" placeholder="0.00"
                  class="weight-input w-[100px] px-4 py-2 border border-gray-300 text-xs rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 focus:bg-white dark:focus:bg-gray-900"
                  onchange="updateCalculations()">
        </td>
        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top">
          <select name="rating" class="rating-select w-[100px] px-4 py-2 border border-gray-300 text-xs rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 focus:bg-white dark:focus:bg-gray-900"
                  onchange="updateCalculations()">
            <option value="1" ${parseInt(item.rating || defaultRating) === 1 ? 'selected' : ''}>1 - Major Weakness</option>
            <option value="2" ${parseInt(item.rating || defaultRating) === 2 ? 'selected' : ''}>2 - Minor Weakness</option>
            <option value="3" ${parseInt(item.rating || defaultRating) === 3 ? 'selected' : ''}>3 - Minor Strength</option>
            <option value="4" ${parseInt(item.rating || defaultRating) === 4 ? 'selected' : ''}>4 - Major Strength</option>
          </select>
        </td>
        <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top weight-score">${((item.weight || defaultWeight) * (item.rating || defaultRating)).toFixed(2)}</td>
      </tr>
    `;
  }

  function createEmptyRow(message) {
    return `
      <tr class="bg-white dark:bg-gray-800">
        <td colspan="6" class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 align-top text-center italic">
          ${message}
        </td>
      </tr>
    `;
  }

  function updateCalculations() {
    const weightInputs = document.querySelectorAll('.weight-input');
    const ratingSelects = document.querySelectorAll('.rating-select');
    const weightScoreCells = document.querySelectorAll('.weight-score');
    
    let totalWeight = 0;
    let totalScore = 0;
    
    weightInputs.forEach((weightInput, index) => {
      const weight = parseFloat(weightInput.value) || 0;
      const rating = parseInt(ratingSelects[index].value) || 1;
      const weightScore = weight * rating;
      
      totalWeight += weight;
      totalScore += weightScore;
      
      if (weightScoreCells[index]) {
        weightScoreCells[index].textContent = weightScore.toFixed(2);
      }
    });
    
    // Update totals
    document.getElementById('totalWeight').textContent = totalWeight.toFixed(2);
    document.getElementById('totalScore').textContent = totalScore.toFixed(2);
    
    // Update chart
    setIFEScore(totalScore);
  }

  function calculateTotalScore() {
    // Initial calculation when data is loaded
    setTimeout(() => {
      updateCalculations();
    }, 100);
  }

  // ====== CONFIGURASI CHART ======
  const ctx = document.getElementById('ifeChart');

  // nilai skor (0..4). Ubah via JS sesuai perhitunganmu
  let ifeScore = 0;

  const chart = new Chart(ctx, {
    type: 'scatter',
    data: {
      datasets: [{
        label: 'IFE Score',
        data: [{ x: 0.5, y: ifeScore }], // x=0.5 untuk titik di tengah sumbu-X
        showLine: false,
        pointRadius: 5,
        pointHoverRadius: 6,
        pointBackgroundColor: '#2563eb',
        pointBorderColor: '#1d4ed8',
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: { padding: { left: 44, right: 16, top: 12, bottom: 28 } },
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: ctx => `IFE: ${ctx.raw.y.toFixed(2)} / 4.0`
          }
        },
        // garis vertikal tengah (seperti grid di contoh)
        annotation: {
          annotations: {
            midLine: {
              type: 'line',
              xMin: 0.5, xMax: 0.5,
              yMin: 0, yMax: 4,
              borderColor: '#d1d5db',
              borderWidth: 1,
              borderDash: [4, 4],
            }
          }
        }
      },
      scales: {
        x: {
          type: 'linear',
          min: 0, max: 1, // kita pakai 0..1, lalu label "IFE" di tengah
          grid: {
            display: false
          },
          ticks: {
            callback: (val) => (val === 0.5 ? 'IFE' : ''),
            maxTicksLimit: 3
          },
          border: {
            color: '#374151', width: 1
          }
        },
        y: {
          min: 0, max: 4,
          ticks: { stepSize: 1 },
          grid: {
            color: '#d1d5db',
            borderDash: [4, 4]
          },
          border: {
            color: '#374151', width: 1
          }
        }
      }
    }
  });

  // ====== FUNGSI OPSIONAL: Tampilkan overlay ringkasan ======
  function showOverlay(show = true) {
    const overlay = document.getElementById('ifeOverlay');
    const tooltip = document.getElementById('ifeTooltip');
    const txt = document.getElementById('ifeScoreText');
    if (show) {
      txt.textContent = (ifeScore ?? 0).toFixed(2);
      overlay.style.display = 'block';
      tooltip.style.display = 'block';
    } else {
      overlay.style.display = 'none';
      tooltip.style.display = 'none';
    }
  }

  // Contoh update skor dari perhitungan kamu:
  // panggil setIFEScore(2.73)
  function setIFEScore(v) {
    ifeScore = Math.max(0, Math.min(4, Number(v) || 0));
    chart.data.datasets[0].data = [{ x: 0.5, y: ifeScore }];
    chart.update();
    
    // Update score display
    const scoreBadge = document.querySelector('.w-12.h-12 span');
    if (scoreBadge) {
      scoreBadge.textContent = ifeScore.toFixed(2);
    }
    
    // Update interpretation
    updateScoreInterpretation(ifeScore);
  }

  function updateScoreInterpretation(score) {
    const interpretationElement = document.querySelector('.text-sm.text-gray-500');
    let interpretation = '';
    let colorClass = 'text-orange-500';
    let iconSvg = '';
    
    if (score >= 3.5) {
      interpretation = 'Strong internal position. Excellent strategic capabilities.';
      colorClass = 'text-green-600';
      iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>`;
    } else if (score >= 3.0) {
      interpretation = 'Above average internal position. Good strategic foundation.';
      colorClass = 'text-green-500';
      iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
      </svg>`;
    } else if (score >= 2.5) {
      interpretation = 'Average internal position. Balanced strengths and weaknesses.';
      colorClass = 'text-blue-500';
      iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <circle cx="12" cy="12" r="10"/>
        <path d="M12 6v6l4 2"/>
      </svg>`;
    } else if (score >= 2.0) {
      interpretation = 'Below average internal position. Improvement needed.';
      colorClass = 'text-yellow-500';
      iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
      </svg>`;
    } else {
      interpretation = 'Weak internal position. Strategic improvements are urgently needed.';
      colorClass = 'text-orange-500';
      iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <circle cx="12" cy="12" r="10"/>
        <line x1="15" y1="9" x2="9" y2="15"/>
        <line x1="9" y1="9" x2="15" y2="15"/>
      </svg>`;
    }
    
    if (interpretationElement) {
      interpretationElement.textContent = interpretation;
    }
    
    // Update icon and text color in Internal Factor Score section
    const scoreIconContainer = document.getElementById('scoreIconContainer');
    const scoreTextElement = document.getElementById('scoreText');
    
    if (scoreIconContainer && scoreTextElement) {
      // Update icon
      const currentIcon = scoreIconContainer.querySelector('svg');
      if (currentIcon) {
        currentIcon.outerHTML = iconSvg;
      }
      
      // Update text color
      scoreTextElement.className = scoreTextElement.className.replace(/text-\w+-\d+/, colorClass);
    }
  }

  // Save IFE Data function
  // Bagian JavaScript (near existing <script> where saveIFEData is defined)
  let isIFEDirty = false;
  
  async function saveIFEData() {
      const saveButton = document.getElementById('saveIFEData');
      const originalText = saveButton.innerHTML;
      
      // Disable button and show loading
      saveButton.disabled = true;
      saveButton.innerHTML = `
        <svg class="inline-block w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Menyimpan...
      `;
  
      try {
        // Get project UUID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const projectUuid = urlParams.get('key');
        
        if (!projectUuid) {
          throw new Error('Project UUID tidak ditemukan');
        }
  
        // Collect data from table
        const tableRows = document.querySelectorAll('#ifeTableBody tr[data-item-id]');
        const strengthsData = [];
        const weaknessesData = [];
        
        tableRows.forEach(row => {
          const itemId = row.getAttribute('data-item-id');
          const weightInput = row.querySelector('.weight-input');
          const ratingSelect = row.querySelector('.rating-select');
          
          if (itemId && weightInput && ratingSelect) {
            const itemData = {
              id: parseInt(itemId),
              weight: parseFloat(weightInput.value) || 0,
              rating: parseInt(ratingSelect.value) || 1
            };
            
            const strengthsHeader = document.querySelector('td[colspan="5"].bg-soft-success');
            const weaknessesHeader = document.querySelector('td[colspan="5"].bg-soft-danger');
            
            if (strengthsHeader && weaknessesHeader) {
              const strengthsIndex = Array.from(strengthsHeader.parentNode.parentNode.children).indexOf(strengthsHeader.parentNode);
              const weaknessesIndex = Array.from(weaknessesHeader.parentNode.parentNode.children).indexOf(weaknessesHeader.parentNode);
              const currentIndex = Array.from(row.parentNode.children).indexOf(row);
              
              if (currentIndex > strengthsIndex && currentIndex < weaknessesIndex) {
                strengthsData.push(itemData);
              } else if (currentIndex > weaknessesIndex) {
                weaknessesData.push(itemData);
              }
            }
          }
        });
  
        const requestData = {
          project_uuid: projectUuid,
          ife_data: {
            strengths: strengthsData,
            weaknesses: weaknessesData
          }
        };
  
        const response = await fetch('<?= base_url('api/project/matrix_ife_save') ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(requestData)
        });
  
        const result = await response.json();
  
        if (result.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data IFE berhasil disimpan!',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true
          });
          isIFEDirty = false;
          return true;
        } else {
          throw new Error(result.message || 'Gagal menyimpan data');
        }
  
      } catch (error) {
        console.error('Error saving IFE data:', error);
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: error.message || 'Terjadi kesalahan saat menyimpan data',
          confirmButtonText: 'OK'
        });
        return false;
      } finally {
        saveButton.disabled = false;
        saveButton.innerHTML = originalText;
      }
  }

  // Function to export to PDF using print
  function exportToPDF() {
    // Convert select elements to text for print
    const selects = document.querySelectorAll('.rating-select');
    const originalSelects = [];
    
    selects.forEach((select, index) => {
      // Store original select element
      originalSelects[index] = select.cloneNode(true);
      
      // Get selected option text
      const selectedOption = select.options[select.selectedIndex];
      const selectedText = selectedOption ? selectedOption.text : select.value;
      
      // Create span element with selected text
      const span = document.createElement('span');
      span.textContent = selectedText;
      span.className = 'rating-text-print';
      span.style.fontSize = '11px';
      span.style.color = '#000';
      
      // Replace select with span
      select.parentNode.replaceChild(span, select);
    });
    
    // Add print-mode class to body
    document.body.classList.add('print-mode');
    
    // Trigger print dialog
    window.print();
    
    // Restore original select elements after print
    setTimeout(() => {
      const textSpans = document.querySelectorAll('.rating-text-print');
      textSpans.forEach((span, index) => {
        if (originalSelects[index]) {
          span.parentNode.replaceChild(originalSelects[index], span);
        }
      });
      
      document.body.classList.remove('print-mode');
    }, 1000);
  }

  // Export to Excel function
  function exportToExcel() {
    // Get the IFE table body
    const tableBody = document.getElementById('ifeTableBody');
    if (!tableBody) {
      alert('Table not found!');
      return;
    }

    // Create workbook and worksheet
    const wb = XLSX.utils.book_new();
    
    // Prepare data array for Excel
    const data = [];
    
    // Add header row
    data.push(['No', 'Internal Factor', 'Weight', 'Rating', 'Weight Score']);
    
    // Get all table rows from tbody
    const rows = tableBody.querySelectorAll('tr');
    
    rows.forEach((row, index) => {
      const cells = row.querySelectorAll('td');
      
      // Handle regular data rows (not section headers)
      if (cells.length >= 5 && !cells[0].hasAttribute('colspan')) {
        const rowData = [];
        
        // Number (first column)
        rowData.push(cells[0].textContent.trim());
        
        // Internal Factor (second column)
        rowData.push(cells[1].textContent.trim());
        
        // Weight (third column - get value from input)
        const weightInput = cells[2].querySelector('.weight-input');
        rowData.push(weightInput ? weightInput.value : cells[2].textContent.trim());
        
        // Rating (fourth column - get selected value from select)
        const ratingSelect = cells[3].querySelector('.rating-select');
        if (ratingSelect) {
          rowData.push(ratingSelect.options[ratingSelect.selectedIndex].text);
        } else {
          rowData.push(cells[3].textContent.trim());
        }
        
        // Weight Score (fifth column)
        rowData.push(cells[4].textContent.trim());
        
        data.push(rowData);
      }
      // Handle Total row (has colspan="2" for first two columns)
       else if (cells.length >= 3 && cells[0].hasAttribute('colspan') && cells[0].textContent.trim() === 'Total') {
         const rowData = [];
         
         // Total label spans 2 columns
         rowData.push('');
         rowData.push('Total');
         
         // Weight total (third column - cells[1])
         const weightTotal = cells[1].textContent.trim().split('\n')[0]; // Get first line before "(Should be 1.0)"
         rowData.push(weightTotal);
         
         // Rating column (empty for total row)
         rowData.push('');
         
         // Weight Score total (fifth column - cells[3])
         rowData.push(cells[3].textContent.trim());
         
         data.push(rowData);
       }
    });
    
    // Check if we have data to export
    if (data.length <= 1) {
      alert('No data to export! Please add some internal factors first.');
      return;
    }
    
    // Create worksheet from data
    const ws = XLSX.utils.aoa_to_sheet(data);
    
    // Set column widths
    ws['!cols'] = [
      { width: 5 },   // No
      { width: 30 },  // Internal Factor
      { width: 10 },  // Weight
      { width: 15 },  // Rating
      { width: 12 }   // Weight Score
    ];
    
    // Add worksheet to workbook
    XLSX.utils.book_append_sheet(wb, ws, 'IFE Matrix');
    
    // Generate filename with current date
    const now = new Date();
    const dateStr = now.getFullYear() + '-' + 
                   String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                   String(now.getDate()).padStart(2, '0');
    const filename = `IFE_Matrix_${dateStr}.xlsx`;
    
    // Save the file
    XLSX.writeFile(wb, filename);
  }

  // Contoh pemakaian:
  // setIFEScore(0); showOverlay(true);
</script>

<!-- SheetJS Library for Excel Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
  // Penanda perubahan IFE
  // Gunakan variabel global yang sudah ada; hindari redeklarasi
  // isIFEDirty dideklarasikan lebih awal di file ini

  // Tambahkan hook untuk "Next", peringatan unsaved changes, dan penanda dirty
  document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('#ifeTableBody .weight-input, #ifeTableBody .rating-select');
    inputs.forEach(el => {
      el.addEventListener('input', () => { isIFEDirty = true; });
      el.addEventListener('change', () => { isIFEDirty = true; });
    });

    // Target ONLY the bottom Next button inside the no-print footer
    const nextLink = document.querySelector('.no-print a.btn.gradient-primary[href*="step=matrix-efe"]');
    if (nextLink) {
      nextLink.addEventListener('click', async (e) => {
        e.preventDefault();
        // Ensure latest weights and scores are calculated before save/validation
        try { updateCalculations(); } catch (err) { console.warn('updateCalculations() failed:', err); }
        const saved = await saveIFEData();
        if (!saved) return;

        const totalWeightText = document.getElementById('totalWeight')?.textContent || '0';
        const totalWeight = parseFloat(totalWeightText) || 0;

        if (totalWeight < 1) {
          Swal.fire({
            icon: 'warning',
            title: 'Total weight belum 1.0',
            text: 'Data sudah disimpan, namun tidak bisa lanjut. Pastikan total weight = 1.0.',
            confirmButtonText: 'OK'
          });
          return;
        }

        window.location.href = nextLink.href;
      });
    }

    document.querySelectorAll('a').forEach(a => {
      if (!nextLink || a !== nextLink) {
        a.addEventListener('click', (e) => {
          if (isIFEDirty) {
            const proceed = confirm('Ada perubahan yang belum disimpan. Tinggalkan halaman?');
            if (!proceed) {
              e.preventDefault();
            }
          }
        });
      }
    });

    window.addEventListener('beforeunload', (e) => {
      if (isIFEDirty) {
        e.preventDefault();
        e.returnValue = '';
      }
    });
  });
</script>
