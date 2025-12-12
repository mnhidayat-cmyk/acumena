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
            <a href="<?= base_url('project?step=matrix-ife&key='.$_GET['key']) ?>" class="text-gray-700 dark:text-white flex items-center gap-2 px-4 py-2 rounded-lg text-sm">
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
            <a href="#" class="bg-success text-white shadow-md dark:text-white flex items-center gap-2 px-4 py-2 rounded-lg text-sm">
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


        <div class="mt-6">
            <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200">Internal-External (IE) Matrix</h2>
            <span class="text-gray-500">The IE Matrix plots your organization's position based on the total weighted scores from both the IFE Matrix (x-axis) and EFE Matrix (y-axis). This helps determine appropriate strategic approaches.</span>
        </div>

        <!-- Company Profile Summary -->
        <div class="mt-8 w-full rounded-lg border border-gray-300 dark:border-gray-600 p-4 bg-white dark:bg-gray-900">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Company Profile Summary</h3>

              <!-- Content grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-8">
                <!-- Company Name -->
                <div>
                <p class="text-sm text-gray-500">Company Name</p>
                <p id="companyName" class="text-base font-semibold text-gray-900 dark:text-white">Loading...</p>
                </div>

                <!-- Industry -->
                <div>
                <p class="text-sm text-gray-500">Industry</p>
                <p id="companyIndustry" class="text-base font-semibold text-gray-900 dark:text-white">Loading...</p>
                </div>

            </div>
        </div>

        <!-- IFE EFE Score Summary -->
        <div class="mt-2 w-full grid grid-cols-2 gap-6">
            <div class="mt-8 w-full rounded-lg border border-gray-300 dark:border-gray-600 p-4 bg-white dark:bg-gray-900">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">IFE Score Summary</h3>
                <div class="flex items-start gap-3 mt-2">
                    <!-- Score badge -->
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                        <span id="ifeScoreDisplay" class="text-gray-900 font-bold">0.00</span>
                    </div>

                    <!-- Text -->
                    <div class="space-y-0.5">
                        <!-- Title with icon -->
                        <div class="flex items-center gap-2" id="ifeScoreIconContainer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            </svg>
                            <span class="text-orange-500 font-semibold" id="ifeScoreText">Internal Factor Score</span>
                        </div>

                        <!-- Subtitle -->
                        <p id="ifeScoreDescription" class="text-sm text-gray-500">
                            Loading internal position analysis...
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-8 w-full rounded-lg border border-gray-300 dark:border-gray-600 p-4 bg-white dark:bg-gray-900">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">EFE Score Summary</h3>
                <div class="flex items-start gap-3 mt-2">
                    <!-- Score badge -->
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                        <span id="efeScoreDisplay" class="text-gray-900 font-bold">0.00</span>
                    </div>

                    <!-- Text -->
                    <div class="space-y-0.5">
                        <!-- Title with icon -->
                        <div class="flex items-center gap-2" id="efeScoreIconContainer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            </svg>
                            <span class="text-orange-500 font-semibold" id="efeScoreText">External Factor Score</span>
                        </div>

                        <!-- Subtitle -->
                        <p id="efeScoreDescription" class="text-sm text-gray-500">
                            Loading external position analysis...
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- chart -->
        <div class="mt-8 w-full rounded-lg border border-gray-300 dark:border-gray-600 p-4 bg-white dark:bg-gray-900">
            <h2 class="text-lg font-bold text-gray-700 dark:text-gray-200">Analisis Matriks IE</h2>
            <span class="text-sm text-gray-500">Matriks Internal-Eksternal berdasarkan skor IFE dan EFE</span>
            <div class="mt-8">
                <div class="relative p-4">
                    <div class="relative">
                        <!-- Canvas Chart -->
                        <canvas id="ie" height="420" style="max-width:1100px;"></canvas>
                    </div>
                </div>

                <!-- Legend rentang skor -->
                <div class="mt-4 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-blue-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <div>
                            <h3 class="flex gap-2 font-semibold text-gray-700 dark:text-gray-300">
                            Posisi Saat Ini
                        </h3>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Berdasarkan skor IFE 3.35 dan EFE 3.00, posisi organisasi Anda berada di Kuadran I.</span>
                        </div>
                    </div>

                    <div class="p-4 mt-8 bg-soft-success rounded-lg">
                        <h3 class="flex gap-2 font-semibold text-gray-700 dark:text-gray-300">
                            Rekomendasi: Grow and Build
                        </h3>

                        <ul class="list-disc ml-5 mt-2" id="recommendationList">
                            <li>Grow and Build: Fokus pada pertumbuhan dan pengembangan organisasi.</li>
                            <li>Focus on Customer Needs: Prioritaskan kepuasan pelanggan dan kebutuhan bisnis.</li>
                            <li>Expand Market Presence: Tingkatkan presensi organisasi di pasar.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- SAVE REPORT -->
         <div class="mt-8 w-full rounded-lg border border-gray-300 dark:border-gray-600 p-4 bg-white dark:bg-gray-900">
            <h2 class="text-lg font-bold text-gray-700 dark:text-gray-200">Full Strategic Analysis Report</h2>
            <span class="text-sm text-gray-500">Generate a comprehensive report with all analysis sections including Company Profile, SWOT Analysis, IFE Matrix, EFE Matrix, and IE Matrix positioning.</span>
            <button class="mt-8 w-full flex items-center justify-center py-4 bg-soft-primary border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8 text-blue-500 mb-2" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>
                <div class="flex flex-col items-start justify-center">
                    <span class="text-base font-semibold text-gray-800 dark:text-gray-200">Export as PDF</span>
                    <span class="text-sm text-gray-500">Document Format</span>
                </div>
            </button>
        </div>

        <!-- Button Next -->
        <div class="flex justify-between mt-8">
            <!-- <button type="submit" class="btn gradient-primary text-white" style="padding-left: 24px;padding-right: 24px;">
                Next
            </button> -->
            <a href="<?= base_url('project?step=matrix-efe&key='.$_GET['key']) ?>" class="btn btn-soft-secondary" style="padding-left: 24px;padding-right: 24px;">
                Previous
            </a>
            <a href="<?= base_url('project?step=matrix-ai&key='.$_GET['key']) ?>" class="btn gradient-primary text-white" style="padding-left: 24px;padding-right: 24px;">
                Next
            </a>
        </div>


    </div>


</div>

<script>
// Get project UUID from URL
const urlParams = new URLSearchParams(window.location.search);
const projectKey = urlParams.get('key');

let IFE = 0; // Will be loaded from API
let EFE = 0; // Will be loaded from API

// Load project data when page loads
document.addEventListener('DOMContentLoaded', function() {
  if (projectKey) {
    loadProjectData();
    loadIFEData();
    loadEFEData();
  } else {
    console.error('Project key not found in URL');
  }
});

// Load project profile data
async function loadProjectData() {
  try {
    const response = await fetch(`<?= base_url('api/project/profile_get') ?>?uuid=${projectKey}`);
    const result = await response.json();
    
    if (result.success && result.data) {
      const project = result.data;
      
      // Update company profile display
      document.getElementById('companyName').textContent = project.company_name || 'N/A';
      document.getElementById('companyIndustry').textContent = project.industry || 'N/A';
    }
  } catch (error) {
    console.error('Error loading project data:', error);
    document.getElementById('companyName').textContent = 'Error loading data';
    document.getElementById('companyIndustry').textContent = 'Error loading data';
  }
}

// Load IFE data and calculate score
async function loadIFEData() {
  try {
    const response = await fetch(`<?= base_url('api/project/matrix_ife_get') ?>?key=${projectKey}`);
    const result = await response.json();
    
    if (result.success && result.data) {
      const ifeData = result.data;
      
      // Calculate IFE score
      let totalScore = 0;
      let totalWeight = 0;
      
      // Calculate from strengths
      if (ifeData.strengths && Array.isArray(ifeData.strengths)) {
        ifeData.strengths.forEach(item => {
          const weight = parseFloat(item.weight) || 0;
          const rating = parseInt(item.rating) || 1;
          totalScore += weight * rating;
          totalWeight += weight;
        });
      }
      
      // Calculate from weaknesses
      if (ifeData.weaknesses && Array.isArray(ifeData.weaknesses)) {
        ifeData.weaknesses.forEach(item => {
          const weight = parseFloat(item.weight) || 0;
          const rating = parseInt(item.rating) || 1;
          totalScore += weight * rating;
          totalWeight += weight;
        });
      }
      
      IFE = totalScore;
      
      // Update IFE score display
      document.getElementById('ifeScoreDisplay').textContent = IFE.toFixed(2);
      
      // Update description based on score
      const ifeDescription = getIFEDescription(IFE);
      document.getElementById('ifeScoreDescription').textContent = ifeDescription;
      
      // Update icon and color based on score
      updateIFEScoreInterpretation(IFE);
      
      // Update chart after loading data
      updateIEChart();
    }
  } catch (error) {
    console.error('Error loading IFE data:', error);
    document.getElementById('ifeScoreDisplay').textContent = 'Error';
    document.getElementById('ifeScoreDescription').textContent = 'Error loading IFE data';
  }
}

// Load EFE data and calculate score
async function loadEFEData() {
  try {
    const response = await fetch(`<?= base_url('api/project/matrix_efe_get') ?>?key=${projectKey}`);
    const result = await response.json();
    
    if (result.success && result.data) {
      const efeData = result.data;
      
      // Calculate EFE score
      let totalScore = 0;
      let totalWeight = 0;
      
      // Calculate from opportunities
      if (efeData.opportunities && Array.isArray(efeData.opportunities)) {
        efeData.opportunities.forEach(item => {
          const weight = parseFloat(item.weight) || 0;
          const rating = parseInt(item.rating) || 1;
          totalScore += weight * rating;
          totalWeight += weight;
        });
      }
      
      // Calculate from threats
      if (efeData.threats && Array.isArray(efeData.threats)) {
        efeData.threats.forEach(item => {
          const weight = parseFloat(item.weight) || 0;
          const rating = parseInt(item.rating) || 1;
          totalScore += weight * rating;
          totalWeight += weight;
        });
      }
      
      EFE = totalScore;
      
      // Update EFE score display
      document.getElementById('efeScoreDisplay').textContent = EFE.toFixed(2);
      
      // Update description based on score
      const efeDescription = getEFEDescription(EFE);
      document.getElementById('efeScoreDescription').textContent = efeDescription;
      
      // Update icon and color based on score
      updateEFEScoreInterpretation(EFE);
      
      // Update chart after loading data
      updateIEChart();
    }
  } catch (error) {
    console.error('Error loading EFE data:', error);
    document.getElementById('efeScoreDisplay').textContent = 'Error';
    document.getElementById('efeScoreDescription').textContent = 'Error loading EFE data';
  }
}

// Get IFE score description
function getIFEDescription(score) {
  if (score >= 3.0) {
    return 'Strong internal position. Excellent strategic capabilities.';
  } else if (score >= 2.5) {
    return 'Above average internal position. Good strategic foundation.';
  } else if (score >= 2.0) {
    return 'Average internal position. Balanced strengths and weaknesses.';
  } else {
    return 'Below average internal position. Improvement needed.';
  }
}

// Get EFE score description
function getEFEDescription(score) {
  if (score >= 3.0) {
    return 'Strong external position. Excellent strategic capabilities.';
  } else if (score >= 2.5) {
    return 'Above average external position. Good strategic foundation.';
  } else if (score >= 2.0) {
    return 'Average external position. Balanced strengths and weaknesses.';
  } else {
    return 'Below average external position. Improvement needed.';
  }
}

// Update IFE score interpretation with icon and color
function updateIFEScoreInterpretation(score) {
  let colorClass = 'text-orange-500';
  let iconSvg = '';
  
  if (score >= 3.5) {
    colorClass = 'text-green-600';
    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>`;
  } else if (score >= 3.0) {
    colorClass = 'text-green-500';
    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
    </svg>`;
  } else if (score >= 2.5) {
    colorClass = 'text-blue-500';
    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
      <circle cx="12" cy="12" r="10"/>
      <path d="M12 6v6l4 2"/>
    </svg>`;
  } else if (score >= 2.0) {
    colorClass = 'text-yellow-500';
    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
    </svg>`;
  } else {
    colorClass = 'text-orange-500';
    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
      <circle cx="12" cy="12" r="10"/>
      <line x1="15" y1="9" x2="9" y2="15"/>
      <line x1="9" y1="9" x2="15" y2="15"/>
    </svg>`;
  }
  
  // Update icon and text color in IFE Score section
  const ifeScoreIconContainer = document.getElementById('ifeScoreIconContainer');
  const ifeScoreTextElement = document.getElementById('ifeScoreText');
  
  if (ifeScoreIconContainer && ifeScoreTextElement) {
    // Update icon
    const currentIcon = ifeScoreIconContainer.querySelector('svg');
    if (currentIcon) {
      currentIcon.outerHTML = iconSvg;
    }
    
    // Update text color
    ifeScoreTextElement.className = ifeScoreTextElement.className.replace(/text-\w+-\d+/, colorClass);
  }
}

// Update EFE score interpretation with icon and color
function updateEFEScoreInterpretation(score) {
  let colorClass = 'text-orange-500';
  let iconSvg = '';
  
  if (score >= 3.5) {
    colorClass = 'text-green-600';
    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>`;
  } else if (score >= 3.0) {
    colorClass = 'text-green-500';
    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
    </svg>`;
  } else if (score >= 2.5) {
    colorClass = 'text-blue-500';
    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
      <circle cx="12" cy="12" r="10"/>
      <path d="M12 6v6l4 2"/>
    </svg>`;
  } else if (score >= 2.0) {
    colorClass = 'text-yellow-500';
    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
    </svg>`;
  } else {
    colorClass = 'text-orange-500';
    iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
      <circle cx="12" cy="12" r="10"/>
      <line x1="15" y1="9" x2="9" y2="15"/>
      <line x1="9" y1="9" x2="15" y2="15"/>
    </svg>`;
  }
  
  // Update icon and text color in EFE Score section
  const efeScoreIconContainer = document.getElementById('efeScoreIconContainer');
  const efeScoreTextElement = document.getElementById('efeScoreText');
  
  if (efeScoreIconContainer && efeScoreTextElement) {
    // Update icon
    const currentIcon = efeScoreIconContainer.querySelector('svg');
    if (currentIcon) {
      currentIcon.outerHTML = iconSvg;
    }
    
    // Update text color
    efeScoreTextElement.className = efeScoreTextElement.className.replace(/text-\w+-\d+/, colorClass);
  }
}

// Update IE chart with new data
function updateIEChart() {
  if (typeof ie !== 'undefined' && IFE > 0 && EFE > 0) {
    ie.data.datasets[0].data = [{x: IFE, y: EFE}];
    ie.update();
    
    // Update position description
    updatePositionDescription(IFE, EFE);
  }
}

// Update position description based on IFE and EFE scores
function updatePositionDescription(ifeScore, efeScore) {
  const positionElement = document.querySelector('.flex.items-center.gap-2 span.text-sm');
  if (positionElement) {
    let strategy = '';
    
    // Tentukan sel IE 1–9 berdasarkan skor IFE/EFE
    // Kolom: IFE [1,2) => 1, [2,3) => 2, [3,4] => 3
    // Baris: EFE [3,4] => 1 (atas), [2,3) => 2 (tengah), [1,2) => 3 (bawah)
    const col = (ifeScore < 2) ? 1 : (ifeScore < 3 ? 2 : 3);
    const row = (efeScore >= 3) ? 1 : (efeScore >= 2 ? 2 : 3);
    const cell = (row - 1) * 3 + col; // 1..9
    const romanMap = ['I','II','III','IV','V','VI','VII','VIII','IX'];
    const quadrant = romanMap[cell - 1] || '';

    // Rekomendasi strategi berdasarkan sel (1–9)
    if ([1, 2, 4].includes(cell)) {
      strategy = 'Grow and Build';
    } else if ([3, 5, 7].includes(cell)) {
      strategy = 'Hold and Maintain';
    } else if ([6, 8, 9].includes(cell)) {
      strategy = 'Harvest and Divest';
    }
    
    positionElement.textContent = `Berdasarkan skor IFE ${ifeScore.toFixed(2)} dan EFE ${efeScore.toFixed(2)}, posisi organisasi Anda berada di Kuadran ${quadrant}.`;
    
    // Update strategy recommendation
    const strategyElement = document.querySelector('.bg-soft-success h3');
    if (strategyElement) {
      strategyElement.textContent = `Rekomendasi: ${strategy}`;
    }

    // Fetch dan update daftar rekomendasi berdasarkan kuadran
    updateRecommendations(quadrant);
  }
}

// Ambil rekomendasi strategi berdasarkan kuadran dan isi ke #recommendationList
async function updateRecommendations(quadrant) {
  const list = document.getElementById('recommendationList');
  if (!list) return;

  // Tampilkan loading state singkat
  list.innerHTML = '<li class="text-gray-500">Memuat rekomendasi...</li>';

  try {
    const url = '<?= base_url('api/project/get_recommendation_strategy') ?>' + '?quadrant=' + encodeURIComponent(quadrant);
    const res = await fetch(url);
    const json = await res.json();

    if (!res.ok || !json.success) {
      throw new Error(json.message || 'Gagal mengambil rekomendasi');
    }

    let items = [];
    const data = json.data;

    // Jika API mengembalikan array: gunakan langsung
    if (Array.isArray(data)) {
      items = data.map(it => {
        if (typeof it === 'string') return it.trim();
        if (it && typeof it === 'object') {
          // dukung bentuk {strategy: '...'} jika ada
          if (typeof it.strategy === 'string') return it.strategy.trim();
          return '';
        }
        return '';
      }).filter(Boolean);
    } else if (typeof data === 'string') {
      // Kompatibilitas lama: string dengan berbagai pemisah
      const dataStr = data;
      if (dataStr.includes('\n')) {
        items = dataStr.split('\n').map(s => s.trim()).filter(Boolean);
      } else if (dataStr.includes(';')) {
        items = dataStr.split(';').map(s => s.trim()).filter(Boolean);
      } else if (dataStr.includes('|')) {
        items = dataStr.split('|').map(s => s.trim()).filter(Boolean);
      } else if (dataStr.trim()) {
        items = [dataStr.trim()];
      }
    } else if (data && typeof data === 'object') {
      // Jika object tunggal: coba ambil field 'strategy'
      const s = typeof data.strategy === 'string' ? data.strategy.trim() : '';
      if (s) {
        if (s.includes('\n')) {
          items = s.split('\n').map(x => x.trim()).filter(Boolean);
        } else if (s.includes(';')) {
          items = s.split(';').map(x => x.trim()).filter(Boolean);
        } else if (s.includes('|')) {
          items = s.split('|').map(x => x.trim()).filter(Boolean);
        } else {
          items = [s];
        }
      }
    }

    if (items.length === 0) {
      list.innerHTML = '<li class="text-gray-500">Tidak ada rekomendasi tersedia untuk kuadran ' + quadrant + '.</li>';
    } else {
      list.innerHTML = items.map(it => `<li>${it}</li>`).join('');
    }
  } catch (err) {
    list.innerHTML = `<li class="text-red-600">${err.message}</li>`;
  }
}

const cells = [
  // x: [1,2), [2,3), [3,4]; y: [3,4], [2,3), [1,2)
  {x:[1,2], y:[3,4], color:'rgba(231,220,167,0.5)', label:'III'},
  {x:[2,3], y:[3,4], color:'rgba(194,235,223,0.6)', label:'II'},
  {x:[3,4], y:[3,4], color:'rgba(194,235,223,0.6)', label:'I'},
  {x:[1,2], y:[2,3], color:'rgba(239,210,218,0.6)', label:'VI'},
  {x:[2,3], y:[2,3], color:'rgba(231,220,167,0.5)', label:'V'},
  {x:[3,4], y:[2,3], color:'rgba(194,235,223,0.6)', label:'IV'},
  {x:[1,2], y:[1,2], color:'rgba(239,210,218,0.6)', label:'IX'},
  {x:[2,3], y:[1,2], color:'rgba(239,210,218,0.6)', label:'VIII'},
  {x:[3,4], y:[1,2], color:'rgba(231,220,167,0.5)', label:'VII'},
];

// Buat box annotations dari sel
const boxAnn = Object.fromEntries(
  cells.map((c, i) => [
    'cell'+i, {
      type:'box',
      xMin:c.x[0], xMax:c.x[1],
      yMin:c.y[0], yMax:c.y[1],
      backgroundColor:c.color,
      borderWidth:0
    }
  ])
);

// Garis panduan di 2 & 3 (vertikal) dan 2 & 3 (horizontal)
const guideAnn = {
  v2:{type:'line', xMin:2, xMax:2, yMin:1, yMax:4, borderColor:'#b0bac8', borderWidth:2, borderDash:[3,3]},
  v3:{type:'line', xMin:3, xMax:3, yMin:1, yMax:4, borderColor:'#b0bac8', borderWidth:2, borderDash:[3,3]},
  h2:{type:'line', yMin:2, yMax:2, xMin:1, xMax:4, borderColor:'#b0bac8', borderWidth:2, borderDash:[3,3]},
  h3:{type:'line', yMin:3, yMax:3, xMin:1, xMax:4, borderColor:'#b0bac8', borderWidth:2, borderDash:[3,3]},
};

// Plugin kecil untuk menulis label romawi besar di tengah sel
const cellLabelPlugin = {
  id:'cellLabels',
  afterDatasetsDraw(chart, args, opts){
    const {ctx, chartArea:{left,right,top,bottom}, scales:{x,y}} = chart;
    ctx.save();
    ctx.fillStyle = '#6b7280';
    ctx.font = '600 28px system-ui,Segoe UI,Roboto,Arial';
    ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
    cells.forEach(c=>{
      const cx = x.getPixelForValue((c.x[0]+c.x[1])/2);
      const cy = y.getPixelForValue((c.y[0]+c.y[1])/2);
      ctx.fillText(c.label, cx, cy);
    });
    ctx.restore();
  }
};

const ie = new Chart(document.getElementById('ie'), {
  type:'scatter',
  data:{
    datasets:[{
      data:[{x:IFE || 2.5, y:EFE || 2.5}], // Default values until data loads
      pointBackgroundColor:'#2563eb',
      pointBorderColor:'#1d4ed8',
      pointRadius:5,
      pointHoverRadius:6
    }]
  },
  options:{
    responsive:true,
    maintainAspectRatio:false,
    plugins:{
      legend:{display:false},
      annotation:{ annotations:{ ...boxAnn, ...guideAnn } },
      tooltip:{
        callbacks:{ label:(ctx)=>`IFE: ${ctx.raw.x.toFixed(2)}, EFE: ${ctx.raw.y.toFixed(2)}` }
      }
    },
    scales:{
      x:{
        min:1, max:4, ticks:{ stepSize:1, color:'#8ca0b3' },
        title:{ display:true, text:'Skor IFE', color:'#8ca0b3' },
        grid:{ color:'rgba(0,0,0,0.05)' }
      },
      y:{
        min:1, max:4, ticks:{ stepSize:1, color:'#8ca0b3' },
        title:{ display:true, text:'Skor EFE', color:'#8ca0b3' },
        grid:{ color:'rgba(0,0,0,0.05)' }
      }
    }
  },
  plugins:[cellLabelPlugin]
});
</script>
