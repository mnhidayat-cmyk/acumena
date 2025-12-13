<!-- Header -->
<div class="container px-6 mx-auto">
  <div class="flex items-center justify-between my-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200"><span class="text-gray-400">Create Project::</span> SWOT Analysis</h2>
        <p class="text-gray-500">Identify your organization's Strengths, Weaknesses, Opportunities, and Threats</p>
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
            <a href="<?= base_url('project/add?step=matrix-ife') ?>" class="text-gray-700 dark:text-white flex items-center gap-2 px-4 py-2 rounded-lg text-sm">
                <!-- Desktop label -->
                <span class="font-bold hidden md:inline">IFE Matrix</span>
                <!-- Mobile label -->
                <span class="font-bold md:hidden inline">IFE</span>
                <!-- Keterangan: tampil hanya di md+ -->
                <span class="hidden md:inline">(Internal)</span>
            </a>

            <!-- EFE -->
            <a href="<?= base_url('project/add?step=matrix-efe') ?>" class="text-gray-700 dark:text-white flex items-center gap-2 px-4 py-2 rounded-lg text-sm">
                <span class="font-bold hidden md:inline">EFE Matrix</span>
                <span class="font-bold md:hidden inline">EFE</span>
                <span class="hidden md:inline">(External)</span>
            </a>

            <!-- IE -->
            <a href="<?= base_url('project/add?step=matrix-ie') ?>" class="text-gray-700 dark:text-white flex items-center gap-2 px-4 py-2 rounded-lg text-sm">
                <span class="font-bold hidden md:inline">IE Matrix</span>
                <span class="font-bold md:hidden inline">IE</span>
                <span class="hidden md:inline">(Combined)</span>
            </a>

            <!-- AI Integration -->
            <a href="<?= base_url('project/add?step=matrix-ai') ?>" class="bg-success text-white shadow-md flex items-center gap-2 px-4 py-2 rounded-lg text-sm">
                <!-- Sama di mobile & desktop: "AI Integration" -->
                <span class="font-bold">AI Integration</span>
                <!-- Keterangan hanya di md+ -->
                <span class="hidden md:inline">(Strategies)</span>
            </a>
        </div>

        <div class="mt-6">
            <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200">AI-Powered Strategy Generation</h2>
            <span class="text-gray-500">Generate strategic recommendations using AI models based on your SWOT analysis and IE Matrix position. The system will analyze your inputs and suggest tailored strategies for your organization.</span>
        </div>


    </div>

    <button class="btn btn-block gradient-primary mt-8" style="border-radius: 10px;">
        <div class="font-bold">Generate All SWOT Strategies</div>
        <span class="text-sm text-white">This will generate strategies for all four quadrants of the SWOT Matrix</span>
    </button>
        
    <!-- GRID -->
    <div class="flex flex-wrap gap-6 items-stretch mt-8">
        <!-- ========== STRENGTHS ========== -->
            <section class="section-swot dark:section-swot">
                <!-- header -->
                <div class="section-swot-header bg-soft-success dark:text-white flex flex-col gap-1">
                    <div class="font-bold" style="margin-bottom: -10px;">SO Strategies</div>
                    <span class="text-sm">Use strengths to take advantage of opportunities</span>
                </div>

                <!-- body -->
                <div class="section-swot-body">
                    <!-- item 1 -->
                    <div id="soStrategiesContainer" class="py-2 mb-4">
                        <div id="soPlaceholder" class="p-4 flex flex-col items-center justify-center text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                width="24" height="24" viewBox="0 0 24 24" 
                                fill="none" stroke="currentColor" stroke-width="2" 
                                stroke-linecap="round" stroke-linejoin="round" 
                                class="h-8 w-8 mb-2 opacity-50">
                                <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"></path>
                                <path d="M9 18h6"></path>
                                <path d="M10 22h4"></path>
                            </svg>
                            <span class="text-sm text-gray-500">No strategies generated yet</span>
                        </div>
                        <ul id="soStrategiesList" style="list-style:none; margin:0; padding:0; display:none;">
                        </ul>
                        <div id="soStatus" class="mt-2 text-xs text-gray-600" style="display:none;"></div>
                    </div>


                    <!-- add button -->
                    <button id="generateSOBtn" class="flex items-center justify-center btn-soft-success rounded-md w-full font-semibold px-4 py-2">
                        <span class="button-text">Generate Strategies</span>
                        <div class="loading-spinner hidden ml-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </button>


                </div>
            </section>

            <!-- ========== OPPORTUNITIES ========== -->
            <section class="section-swot dark:section-swot">
                <div class="section-swot-header bg-soft-primary dark:text-white flex flex-col gap-1">
                    <div class="font-bold" style="margin-bottom: -10px;">ST Strategies</div>
                    <span class="text-sm">Use strengths to minimize threats</span>
                </div>
                <div class="section-swot-body">
                    <div id="stStrategiesContainer" class="py-2 mb-4">
                        <div id="stPlaceholder" class="p-4 flex flex-col items-center justify-center text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                width="24" height="24" viewBox="0 0 24 24" 
                                fill="none" stroke="currentColor" stroke-width="2" 
                                stroke-linecap="round" stroke-linejoin="round" 
                                class="h-8 w-8 mb-2 opacity-50">
                                <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"></path>
                                <path d="M9 18h6"></path>
                                <path d="M10 22h4"></path>
                            </svg>
                            <span class="text-sm text-gray-500">No strategies generated yet</span>
                        </div>
                        <ul id="stStrategiesList" style="list-style:none; margin:0; padding:0; display:none;"></ul>
                        <div id="stStatus" class="mt-2 text-xs text-gray-600" style="display:none;"></div>
                    </div>

                    <button id="generateSTBtn" class="flex items-center justify-center btn-soft-primary rounded-md w-full font-semibold px-4 py-2">
                        <span class="button-text">Generate Strategies</span>
                        <div class="loading-spinner hidden ml-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </button>
                </div>
            </section>

            <!-- ========== WEAKNESSES ========== -->
            <section class="section-swot dark:section-swot">
            <div class="section-swot-header bg-soft-warning dark:text-white flex flex-col gap-1">
                <div class="font-bold" style="margin-bottom: -10px;">WO Strategies</div>
                <span class="text-sm">Use weaknesses to improve opportunities</span>
            </div>
            <div class="section-swot-body">
                <div id="woStrategiesContainer" class="py-2 mb-4">
                    <div id="woPlaceholder" class="p-4 flex flex-col items-center justify-center text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            width="24" height="24" viewBox="0 0 24 24" 
                            fill="none" stroke="currentColor" stroke-width="2" 
                            stroke-linecap="round" stroke-linejoin="round" 
                            class="h-8 w-8 mb-2 opacity-50">
                            <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"></path>
                            <path d="M9 18h6"></path>
                            <path d="M10 22h4"></path>
                        </svg>
                        <span class="text-sm text-gray-500">No strategies generated yet</span>
                    </div>
                    <ul id="woStrategiesList" style="list-style:none; margin:0; padding:0; display:none;"></ul>
                    <div id="woStatus" class="mt-2 text-xs text-gray-600" style="display:none;"></div>
                </div>

                <button id="generateWOBtn" class="flex items-center justify-center btn-soft-warning rounded-md w-full font-semibold px-4 py-2">
                    <span class="button-text">Generate Strategies</span>
                    <div class="loading-spinner hidden ml-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </div>
            </section>

            <!-- ========== THREATS ========== -->
            <section class="section-swot dark:section-swot">
            <div class="section-swot-header bg-soft-danger dark:text-white flex flex-col gap-1">
                <div class="font-bold" style="margin-bottom: -10px;">WT Strategies</div>
                <span class="text-sm">Minimize weaknesses and avoid threats</span>
            </div>
            <div class="section-swot-body">
                <div id="wtStrategiesContainer" class="py-2 mb-4">
                    <div id="wtPlaceholder" class="p-4 flex flex-col items-center justify-center text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            width="24" height="24" viewBox="0 0 24 24" 
                            fill="none" stroke="currentColor" stroke-width="2" 
                            stroke-linecap="round" stroke-linejoin="round" 
                            class="h-8 w-8 mb-2 opacity-50">
                            <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"></path>
                            <path d="M9 18h6"></path>
                            <path d="M10 22h4"></path>
                        </svg>
                        <span class="text-sm text-gray-500">No strategies generated yet</span>
                    </div>
                    <ul id="wtStrategiesList" style="list-style:none; margin:0; padding:0; display:none;"></ul>
                    <div id="wtStatus" class="mt-2 text-xs text-gray-600" style="display:none;"></div>
                </div>

                <button id="generateWTBtn" class="flex items-center justify-center btn-soft-danger rounded-md w-full font-semibold px-4 py-2">
                    <span class="button-text">Generate Strategies</span>
                    <div class="loading-spinner hidden ml-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </div>
            </section>
    </div>

    <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-md mx-auto mt-8 p-6">
        <div class="mt-6">
            <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200">Strategic Recommendations Based on IE Matrix</h2>
        </div>

        <div class="p-4 mt-4 bg-soft-primary rounded-lg">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-blue-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"></path>
                </svg>
                <div>
                    <h3 class="flex gap-2 text-gray-700 dark:text-gray-300">
                            Your position in the IE Matrix (Cell I) suggests a <span class="font-bold">Grow and Build</span> approach.
                    </h3>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Focus on SO and WO strategies that leverage opportunities</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 justify-between mt-8">
            <h3 class="text-gray-700 dark:text-gray-300 font-bold text-lg">Prioritized Strategies</h3>
            <div class="flex gap-2">
                <button class="btn gradient-primary flex gap-2" id="generateRecommendationsBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                                width="24" height="24" viewBox="0 0 24 24" 
                                fill="none" stroke="currentColor" stroke-width="2" 
                                stroke-linecap="round" stroke-linejoin="round" 
                                class="h-5 w-5">
                                <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"></path>
                                <path d="M9 18h6"></path>
                                <path d="M10 22h4"></path>
                    </svg>
                    Generate Recommendations
                </button>
                <button class="btn gradient-success flex gap-2" id="savePrioritizedBtn" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Save to Database
                </button>
            </div>
        </div>

        <div class="mt-4 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 p-4 rounded-lg" id="finalRecommendationsContainer">
            <div class="p-4 flex flex-col items-center justify-center text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            width="60" height="60" viewBox="0 0 24 24" 
                            fill="none" stroke="currentColor" stroke-width="2" 
                            stroke-linecap="round" stroke-linejoin="round" 
                            class="mb-2 opacity-50">
                            <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"></path>
                            <path d="M9 18h6"></path>
                            <path d="M10 22h4"></path>
                        </svg>
                        <span class="text-sm text-gray-500">Generate strategies in the quadrants above, then click "Generate Recommendations" to get prioritized strategies based on your IE Matrix position.</span>
            </div>
        </div>


        <!-- Button Next -->
        <div class="flex justify-between mt-8">
            <!-- <button type="submit" class="btn gradient-primary text-white" style="padding-left: 24px;padding-right: 24px;">
                Next
            </button> -->
            <a href="?step=profile" class="btn btn-soft-secondary" style="padding-left: 24px;padding-right: 24px;">
                Previous
            </a>

<script>
// Global functions for recommendation handling
async function saveRecommendationToDatabase() {
    if (!window.currentRecommendation) {
        alert('Tidak ada rekomendasi untuk disimpan');
        return;
    }

    // Rekomendasi sudah disimpan otomatis saat generate
    alert('✅ Rekomendasi sudah tersimpan otomatis ke database saat Anda mengklik "Generate Recommendations"!');
}

function downloadRecommendation() {
    if (!window.currentRecommendation) {
        alert('Tidak ada rekomendasi untuk diunduh');
        return;
    }

    try {
        const rec = window.currentRecommendation.recommendation;
        const company = window.currentRecommendation.company_profile;
        
        // Format content untuk download
        let content = `
REKOMENDASI STRATEGI FINAL
==========================
Tanggal: ${new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}

PROFIL PERUSAHAAN
================
Nama: ${company?.company_name || '-'}
Industri: ${company?.industry || '-'}
Visi: ${company?.vision || '-'}
Misi: ${company?.mission || '-'}

POSISI IE MATRIX
================
Kuadran: ${window.currentRecommendation.ie_matrix_position?.quadrant || '-'}
Skor IFE: ${(window.currentRecommendation.ie_matrix_position?.ife_score || 0).toFixed(2)}
Skor EFE: ${(window.currentRecommendation.ie_matrix_position?.efe_score || 0).toFixed(2)}

TEMA STRATEGIS UTAMA
===================
${rec?.strategic_theme || '-'}

KESESUAIAN DENGAN POSISI PERUSAHAAN
==================================
${rec?.alignment_with_position || '-'}

TINDAKAN JANGKA PENDEK (3-6 BULAN)
==================================`;

        if (Array.isArray(rec?.short_term_actions)) {
            rec.short_term_actions.forEach((action, idx) => {
                content += `\n${idx + 1}. ${action.action || '-'}`;
                if (action.priority) content += `\n   Prioritas: ${action.priority}`;
                if (action.impact) content += `\n   Dampak: ${action.impact}`;
            });
        }

        content += `\n\nINISIATIF JANGKA PANJANG (1-3 TAHUN)
==================================`;
        
        if (Array.isArray(rec?.long_term_actions)) {
            rec.long_term_actions.forEach((initiative, idx) => {
                content += `\n${idx + 1}. ${initiative.initiative || initiative.action || '-'}`;
                if (initiative.resources) content += `\n   Sumber Daya: ${initiative.resources}`;
                if (initiative.success_metrics) content += `\n   Metrik Sukses: ${initiative.success_metrics}`;
            });
        }

        content += `\n\nIMPLIKASI SUMBER DAYA
====================`;
        if (rec?.resource_implications) {
            if (rec.resource_implications.budget_allocation) {
                content += `\nAlokasi Anggaran: ${rec.resource_implications.budget_allocation}`;
            }
            if (rec.resource_implications.key_roles) {
                content += `\nPeran Kunci: ${rec.resource_implications.key_roles}`;
            }
            if (rec.resource_implications.skill_development) {
                content += `\nPengembangan Skill: ${rec.resource_implications.skill_development}`;
            }
        }

        content += `\n\nMITIGASI RISIKO
===============`;
        if (Array.isArray(rec?.risk_mitigation)) {
            rec.risk_mitigation.forEach((risk, idx) => {
                content += `\n${idx + 1}. Risiko: ${risk.risk || '-'}`;
                if (risk.mitigation) content += `\n   Mitigasi: ${risk.mitigation}`;
            });
        }

        // Download file
        const element = document.createElement('a');
        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(content));
        element.setAttribute('download', `Strategic-Recommendation-${company?.company_name || 'Report'}-${new Date().getTime()}.txt`);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);

        alert('✅ Rekomendasi berhasil diunduh!');
    } catch (error) {
        console.error('Error downloading recommendation:', error);
        alert('❌ Error download: ' + error.message);
    }
}

/**
 * Format actions as HTML
 */
function formatActionsHTML(actions, isLongTerm = false) {
    if (!Array.isArray(actions) || actions.length === 0) {
        return '<p class="text-gray-600 dark:text-gray-400 italic">Tidak ada tindakan spesifik yang didefinisikan.</p>';
    }

    let html = '<div class="space-y-3">';
    actions.forEach((item, idx) => {
        if (isLongTerm) {
            html += `
                <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4">
                    <p class="font-semibold text-gray-900 dark:text-white">${idx + 1}. ${item.initiative || item.action}</p>
                    ${item.resources ? `<p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><strong>Sumber Daya:</strong> ${item.resources}</p>` : ''}
                    ${item.success_metrics ? `<p class="text-sm text-gray-700 dark:text-gray-300"><strong>Metrik Sukses:</strong> ${item.success_metrics}</p>` : ''}
                </div>
            `;
        } else {
            html += `
                <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4">
                    <p class="font-semibold text-gray-900 dark:text-white">${idx + 1}. ${item.action}</p>
                    ${item.priority ? `<p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><strong>Prioritas:</strong> <span class="font-medium">${item.priority}</span></p>` : ''}
                    ${item.impact ? `<p class="text-sm text-gray-700 dark:text-gray-300"><strong>Dampak:</strong> ${item.impact}</p>` : ''}
                </div>
            `;
        }
    });
    html += '</div>';
    return html;
}

/**
 * Format risks as HTML
 */
function formatRisksHTML(risks) {
    if (!Array.isArray(risks) || risks.length === 0) {
        return '<p class="text-gray-600 dark:text-gray-400 italic">Tidak ada risiko besar yang teridentifikasi.</p>';
    }

    let html = '<div class="space-y-3">';
    risks.forEach((item, idx) => {
        html += `
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4">
                <p class="font-semibold text-gray-900 dark:text-white">${idx + 1}. Risiko: ${item.risk}</p>
                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><strong>Mitigasi:</strong> ${item.mitigation}</p>
            </div>
        `;
    });
    html += '</div>';
    return html;
}

/**
 * Display Final Strategic Recommendation in structured format
 */
function displayFinalRecommendation(data) {
    const rec = data.recommendation;
    
    // Build HTML structure for display
    const recommendationDiv = document.createElement('div');
    recommendationDiv.className = 'p-6 bg-white dark:bg-gray-900 rounded-lg';
    recommendationDiv.innerHTML = `
        <div class="mb-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">📊 REKOMENDASI STRATEGI FINAL</h2>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Perusahaan</p>
                    <p class="font-semibold text-gray-900 dark:text-white">${data.company_profile?.company_name || '-'}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Industri</p>
                    <p class="font-semibold text-gray-900 dark:text-white">${data.company_profile?.industry || '-'}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Posisi IE Matrix</p>
                    <p class="font-semibold text-gray-900 dark:text-white">${data.ie_matrix_position?.quadrant || '-'}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Skor IFE / EFE</p>
                    <p class="font-semibold text-gray-900 dark:text-white">${(data.ie_matrix_position?.ife_score || 0).toFixed(2)} / ${(data.ie_matrix_position?.efe_score || 0).toFixed(2)}</p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">🎯 Tema Strategis Utama</h3>
                <p class="text-gray-700 dark:text-gray-300">${rec.strategic_theme || '-'}</p>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">🔗 Kesesuaian dengan Posisi Perusahaan</h3>
                <p class="text-gray-700 dark:text-gray-300">${rec.alignment_with_position || '-'}</p>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">⚡ Tindakan Jangka Pendek (3-6 Bulan)</h3>
                ${formatActionsHTML(rec.short_term_actions)}
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">🚀 Inisiatif Jangka Panjang (1-3 Tahun)</h3>
                ${formatActionsHTML(rec.long_term_actions, true)}
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">💰 Implikasi Sumber Daya</h3>
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Alokasi Anggaran</p>
                        <p class="text-gray-900 dark:text-white">${rec.resource_implications?.budget_allocation || '-'}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Peran Kunci yang Diperlukan</p>
                        <p class="text-gray-900 dark:text-white">${rec.resource_implications?.key_roles || '-'}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Area Pengembangan Skill</p>
                        <p class="text-gray-900 dark:text-white">${rec.resource_implications?.skill_development || '-'}</p>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">⚠️ Mitigasi Risiko</h3>
                ${formatRisksHTML(rec.risk_mitigation)}
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-6">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm text-green-600 font-semibold">✅ Tersimpan ke Database</p>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Dihasilkan: ${new Date().toLocaleDateString('id-ID', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                })}</p>
            </div>
        </div>
    `;

    // Replace placeholder with recommendation display
    const container = document.getElementById('finalRecommendationsContainer');
    if (container) {
        container.innerHTML = '';
        container.appendChild(recommendationDiv);
    }

    // Store recommendation data globally for saving
    window.currentRecommendation = data;
}

// Auto-load final recommendation from database if it exists
async function loadFinalRecommendation(projectUuid) {
    if (!projectUuid) {
        console.log('No project UUID - skipping final recommendation load');
        return;
    }
    
    try {
        const apiBase = '<?= base_url('api/project') ?>';
        const response = await fetch(`${apiBase}/get-recommendation?uuid=${projectUuid}`);
        const json = await response.json();
        
        if (json.success && json.found && json.data) {
            console.log('✅ Final recommendation found - auto-displaying');
            displayFinalRecommendation(json.data);
        } else {
            console.log('No final recommendation in database yet');
        }
    } catch (e) {
        console.warn('Failed to load final recommendation:', e);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // SO/ST/WO/WT buttons and lists
    const btnSO = document.getElementById('generateSOBtn');
    const btnST = document.getElementById('generateSTBtn');
    const btnWO = document.getElementById('generateWOBtn');
    const btnWT = document.getElementById('generateWTBtn');

    const listSO = document.getElementById('soStrategiesList');
    const listST = document.getElementById('stStrategiesList');
    const listWO = document.getElementById('woStrategiesList');
    const listWT = document.getElementById('wtStrategiesList');

    const phSO = document.getElementById('soPlaceholder');
    const phST = document.getElementById('stPlaceholder');
    const phWO = document.getElementById('woPlaceholder');
    const phWT = document.getElementById('wtPlaceholder');

    const stSO = document.getElementById('soStatus');
    const stST = document.getElementById('stStatus');
    const stWO = document.getElementById('woStatus');
    const stWT = document.getElementById('wtStatus');

    // Konfigurasi API
    const apiBase = '<?= base_url('api/project') ?>';
    const urlParams = new URLSearchParams(window.location.search);
    const projectKey = urlParams.get('key');
    let projectId = null;
    const language = 'id'; // default Indonesia; bisa diganti ke 'en'
    const mapTypeGlobal = { SO: 'S-O', ST: 'S-T', WO: 'W-O', WT: 'W-T' };

    async function resolveProjectId() {
        try {
            if (!projectKey) {
                console.error('Project key tidak ditemukan di URL (parameter ?key=)');
                return null;
            }
            console.log('Resolving project ID for key:', projectKey);
            const resp = await fetch(`${apiBase}/profile_get?uuid=${projectKey}`);
            const json = await resp.json();
            projectId = json?.data?.id || json?.data?.project_id || null;
            if (projectId) {
                console.log('✅ Project ID resolved:', projectId);
            } else {
                console.warn('⚠️ Could not resolve project ID from response:', json);
            }
            return projectId;
        } catch (e) {
            console.error('❌ Gagal memuat project ID:', e);
            return null;
        }
    }

    // Placeholder loader & status text updater
    function placeholderSetLoading(placeholderEl, message) {
        if (!placeholderEl.dataset.original) {
            placeholderEl.dataset.original = placeholderEl.innerHTML;
        }
        const msg = message.endsWith('...') ? message : `${message}...`;
        placeholderEl.style.display = 'block';
        placeholderEl.innerHTML = `
            <div class="p-4 flex flex-col items-center justify-center text-center">
                <svg class="animate-spin h-8 w-8 text-gray-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm text-gray-600">${msg}</span>
            </div>
        `;
    }
    function placeholderSetError(placeholderEl, message) {
        const msg = message;
        placeholderEl.style.display = 'block';
        placeholderEl.innerHTML = `
            <div class="p-4 flex flex-col items-center justify-center text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <span class="text-sm text-red-600">${msg}</span>
            </div>
        `;
    }
    function placeholderReset(placeholderEl) {
        if (placeholderEl.dataset.original) {
            placeholderEl.innerHTML = placeholderEl.dataset.original;
        }
    }

    function typeText(element, text, callback) {
        let index = 0;
        element.textContent = '';
        function typeNextChar() {
            if (index < text.length) {
                element.textContent += text.charAt(index);
                index++;
                setTimeout(typeNextChar, Math.random() * 50 + 30);
            } else {
                callback();
            }
        }
        typeNextChar();
    }

    function renderStrategies(listEl, strategies, btnEl) {
        let i = 0;
        function addNext() {
            if (i >= strategies.length) {
                btnEl.disabled = false;
                btnEl.querySelector('.button-text').textContent = 'Regenerate Strategies';
                return;
            }
            const s = strategies[i];
            const li = document.createElement('li');
            li.style.cssText = 'padding:12px 20px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:top; opacity:0; transform:translateY(10px); transition:all 0.3s ease;';
            const idSpan = document.createElement('span');
            idSpan.style.cssText = 'font-weight:bold;margin-right:20px;';
            idSpan.textContent = s.id;
            const textSpan = document.createElement('span');
            textSpan.style.cssText = 'flex:1;';
            li.appendChild(idSpan);
            li.appendChild(textSpan);
            listEl.appendChild(li);
            setTimeout(() => { li.style.opacity = '1'; li.style.transform = 'translateY(0)'; }, 100);
            typeText(textSpan, s.text, () => { i++; setTimeout(addNext, 400); });
        }
        addNext();
    }

    // Render tanpa efek ketik untuk data yang sudah ada
    function renderStrategiesImmediate(listEl, strategies, btnEl) {
        listEl.innerHTML = '';
        strategies.forEach((s) => {
            const li = document.createElement('li');
            li.style.cssText = 'padding:12px 20px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:top; opacity:1; transform:translateY(0); transition:all 0.3s ease;';
            const idSpan = document.createElement('span');
            idSpan.style.cssText = 'font-weight:bold;margin-right:20px;';
            idSpan.textContent = s.id;
            const textSpan = document.createElement('span');
            textSpan.style.cssText = 'flex:1;';
            textSpan.textContent = s.text;
            li.appendChild(idSpan);
            li.appendChild(textSpan);
            listEl.appendChild(li);
        });
        btnEl.disabled = false;
        btnEl.querySelector('.button-text').textContent = 'Regenerate Strategies';
    }

    async function runGeneration(quadrant, btnEl, placeholderEl, listEl, statusEl) {
        const textEl = btnEl.querySelector('.button-text');
        const spinnerEl = btnEl.querySelector('.loading-spinner');
        const mapType = { SO: 'S-O', ST: 'S-T', WO: 'W-O', WT: 'W-T' };

        // pastikan projectId
        if (!projectId) await resolveProjectId();
        if (!projectId) {
            placeholderSetError(placeholderEl, 'Gagal: project tidak ditemukan di URL (key).');
            return;
        }

        // UI awal
        btnEl.disabled = true;
        textEl.textContent = 'Generating...';
        spinnerEl.classList.remove('hidden');
        listEl.style.display = 'none';
        listEl.innerHTML = '';
        statusEl.style.display = 'none';
        placeholderSetLoading(placeholderEl, 'Mengambil Top-K pairs');

        try {
            // Step 1: generating_top_k_pairs
            const r1 = await fetch(`${apiBase}/generating_top_k_pairs?project=${projectId}&pair_type=${mapType[quadrant]}`, { method: 'POST' });
            const j1 = await r1.json();
            if (!j1.success || !j1.data || !j1.data.run_id) {
                placeholderSetError(placeholderEl, 'Gagal mengambil Top-K pairs');
                throw new Error(j1.message || 'Top-K pairs failed');
            }
            const runId = j1.data.run_id;
            placeholderSetLoading(placeholderEl, 'Menjalankan Semantic Filter');

            // Step 2: semantic_filter
            const r2 = await fetch(`${apiBase}/semantic_filter?project=${projectId}&run=${runId}`, { method: 'POST' });
            const j2 = await r2.json();
            if (!j2.success) {
                placeholderSetError(placeholderEl, 'Gagal menjalankan Semantic Filter');
                throw new Error(j2.message || 'Semantic filter failed');
            }
            placeholderSetLoading(placeholderEl, 'Menghasilkan Strategi');

            // Step 3: strategies
            const r3 = await fetch(`${apiBase}/strategies?project=${projectId}&run=${runId}&lang=${language}`, { method: 'POST' });
            const j3 = await r3.json();
            if (!j3.success || !Array.isArray(j3.data?.strategies)) {
                placeholderSetError(placeholderEl, 'Gagal menghasilkan strategi');
                throw new Error(j3.message || 'Strategies generation failed');
            }

            // render hasil dengan penyesuaian prefix kode sesuai quadrant (SO/ST/WO/WT)
            const items = j3.data.strategies.map((s, idx) => {
                const rawCode = typeof s.code === 'string' ? s.code : '';
                const m = rawCode.match(/^(SO|ST|WO|WT)(\d+)/i);
                const id = m ? `${quadrant}${m[2]}` : `${quadrant}${idx + 1}`;
                return { id, text: s.statement };
            });
            placeholderReset(placeholderEl);
            placeholderEl.style.display = 'none';
            listEl.style.display = 'block';
            spinnerEl.classList.add('hidden');
            textEl.textContent = 'Generating Strategies...';
            renderStrategies(listEl, items, btnEl);
        } catch (e) {
            spinnerEl.classList.add('hidden');
            btnEl.disabled = false;
            textEl.textContent = 'Generate Strategies';
            console.error('Error:', e);
        }
    }

    // Memuat strategi yang sudah ada saat halaman diload (tanpa efek ketik)
    async function loadExisting(quadrant, btnEl, placeholderEl, listEl) {
        try {
            if (!projectId) {
                console.warn(`loadExisting: projectId not set for ${quadrant}`);
                return;
            }
            
            const pairType = mapTypeGlobal[quadrant];
            const url = `${apiBase}/strategies_list?project=${projectId}&pair_type=${pairType}`;
            console.log(`Loading existing strategies for ${quadrant} (${pairType}) from:`, url);
            
            const res = await fetch(url);
            if (!res.ok) {
                console.warn(`strategies_list API returned status ${res.status} for ${quadrant}`);
                return;
            }
            
            const json = await res.json();
            console.log(`Response for ${quadrant}:`, json);
            
            // Endpoint returns: { success: true, message: '...', data: { run_id, stage, pair_type, strategies: [] } }
            const strategies = json?.data?.strategies || [];
            
            if (json.success && Array.isArray(strategies) && strategies.length > 0) {
                console.log(`Found ${strategies.length} existing strategies for ${quadrant}`);
                const items = strategies.map((s, idx) => {
                    const rawCode = typeof s.code === 'string' ? s.code : '';
                    const m = rawCode.match(/^(SO|ST|WO|WT)(\d+)/i);
                    const id = m ? `${quadrant}${m[2]}` : `${quadrant}${idx + 1}`;
                    return { id, text: s.statement };
                });
                placeholderReset(placeholderEl);
                placeholderEl.style.display = 'none';
                listEl.style.display = 'block';
                renderStrategiesImmediate(listEl, items, btnEl);
                console.log(`✅ Rendered ${items.length} strategies for ${quadrant}`);
            } else {
                console.log(`No strategies found for ${quadrant} - keeping placeholder visible`);
            }
        } catch (e) {
            // Abaikan kesalahan, user masih bisa generate
            console.warn(`Gagal memuat strategi existing untuk ${quadrant}:`, e);
        }
    }

    btnSO?.addEventListener('click', () => runGeneration('SO', btnSO, phSO, listSO, stSO));
    btnST?.addEventListener('click', () => runGeneration('ST', btnST, phST, listST, stST));
    btnWO?.addEventListener('click', () => runGeneration('WO', btnWO, phWO, listWO, stWO));
    btnWT?.addEventListener('click', () => runGeneration('WT', btnWT, phWT, listWT, stWT));

    // Resolusi project dan muat strategi existing
    // Load all 4 strategies in PARALLEL for better performance
    resolveProjectId().then(() => {
        if (!projectId) {
            console.error('❌ Gagal resolve projectId - strategi tidak bisa dimuat');
            return;
        }
        
        console.log('🔄 Starting to load existing strategies for all 4 quadrants...');
        
        // Load all 4 in parallel (faster than sequential)
        Promise.all([
            loadExisting('SO', btnSO, phSO, listSO),
            loadExisting('ST', btnST, phST, listST),
            loadExisting('WO', btnWO, phWO, listWO),
            loadExisting('WT', btnWT, phWT, listWT)
        ]).then(() => {
            // All strategies loaded
            console.log('✅ All strategies loaded successfully');
            
            // Also auto-load final recommendation if exists
            console.log('🔄 Attempting to load final recommendation...');
            loadFinalRecommendation(projectKey);
        }).catch(e => {
            // If any fail, they're handled individually in loadExisting()
            console.warn('⚠️ Some strategies failed to load:', e);
        });
    });

    // Function to collect and save prioritized strategies
    function collectStrategies() {
        const strategies = [];
        const pairs = {
            'SO': { elem: listSO, pairType: 'S-O' },
            'ST': { elem: listST, pairType: 'S-T' },
            'WO': { elem: listWO, pairType: 'W-O' },
            'WT': { elem: listWT, pairType: 'W-T' }
        };

        let rank = 1;
        for (const [quadrant, data] of Object.entries(pairs)) {
            const items = data.elem.querySelectorAll('.strategy-item');
            items.forEach((item, idx) => {
                const codeEl = item.querySelector('[data-code]');
                const textEl = item.querySelector('[data-text]');
                
                if (codeEl && textEl) {
                    strategies.push({
                        pair_type: data.pairType,
                        strategy_code: codeEl.getAttribute('data-code') || `${quadrant}${idx + 1}`,
                        strategy_statement: textEl.getAttribute('data-text') || textEl.textContent,
                        priority_rank: rank++,
                        selected_by_user: true
                    });
                }
            });
        }

        return strategies;
    }

    // Handle Save button click
    const savePrioritizedBtn = document.getElementById('savePrioritizedBtn');
    if (savePrioritizedBtn) {
        savePrioritizedBtn.addEventListener('click', async (e) => {
            e.preventDefault();

            const strategies = collectStrategies();
            if (strategies.length === 0) {
                alert('No strategies to save. Please generate strategies first.');
                return;
            }

            savePrioritizedBtn.disabled = true;
            const originalText = savePrioritizedBtn.textContent;
            savePrioritizedBtn.textContent = 'Saving...';

            try {
                const projectUuid = new URLSearchParams(window.location.search).get('key');
                
                const response = await fetch('/api/project/prioritized-strategies/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        project_uuid: projectUuid,
                        strategies: strategies
                    })
                });

                const json = await response.json();
                
                if (json.success) {
                    alert('Strategies saved successfully!');
                    savePrioritizedBtn.textContent = 'Saved ✓';
                    setTimeout(() => {
                        savePrioritizedBtn.textContent = originalText;
                    }, 2000);
                } else {
                    throw new Error(json.message || 'Failed to save strategies');
                }
            } catch (error) {
                console.error('Error saving strategies:', error);
                alert('Error saving strategies: ' + error.message);
            } finally {
                savePrioritizedBtn.disabled = false;
                savePrioritizedBtn.textContent = originalText;
            }
        });
    }

    // Show save button when strategies exist
    function checkAndShowSaveButton() {
        const hasStrategies = (
            listSO.querySelectorAll('.strategy-item').length > 0 ||
            listST.querySelectorAll('.strategy-item').length > 0 ||
            listWO.querySelectorAll('.strategy-item').length > 0 ||
            listWT.querySelectorAll('.strategy-item').length > 0
        );
        
        if (savePrioritizedBtn) {
            savePrioritizedBtn.style.display = hasStrategies ? 'flex' : 'none';
        }
    }

    // Monitor for strategy changes
    const observer = new MutationObserver(() => {
        checkAndShowSaveButton();
    });

    [listSO, listST, listWO, listWT].forEach(list => {
        if (list) {
            observer.observe(list, { childList: true, subtree: true });
        }
    });

    // Check on load
    checkAndShowSaveButton();

    // ============================================
    // Validation: Check if all 4 strategies exist
    // ============================================
    async function validateAllStrategiesExist() {
        const projectUuid = new URLSearchParams(window.location.search).get('key');
        
        // PRIMARY CHECK: Backend verification (database - source of truth)
        // This is the authoritative check
        try {
            const response = await fetch('/api/project/validate-strategies', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    project_uuid: projectUuid
                })
            });
            
            const json = await response.json();
            
            // Backend check is authoritative
            // If backend says valid, strategies definitely exist
            // If backend says invalid, show the error with missing list
            if (!json.valid) {
                return {
                    valid: false,
                    message: json.message || 'Semua 4 strategi (SO, ST, WO, WT) harus ada sebelum generate recommendation.'
                };
            }
            
            // Backend verification passed
            return { valid: true };
            
        } catch (error) {
            // If backend check fails (network error, etc.)
            // Fall back to DOM check as secondary verification
            console.warn('Backend validation call failed, using DOM check as fallback:', error);
            
            const soContainer = document.getElementById('soStrategiesContainer');
            const stContainer = document.getElementById('stStrategiesContainer');
            const woContainer = document.getElementById('woStrategiesContainer');
            const wtContainer = document.getElementById('wtStrategiesContainer');
            
            const soStrategies = soContainer ? soContainer.querySelectorAll('.strategy-item').length : 0;
            const stStrategies = stContainer ? stContainer.querySelectorAll('.strategy-item').length : 0;
            const woStrategies = woContainer ? woContainer.querySelectorAll('.strategy-item').length : 0;
            const wtStrategies = wtContainer ? wtContainer.querySelectorAll('.strategy-item').length : 0;
            
            const allExistInDOM = soStrategies > 0 && stStrategies > 0 && woStrategies > 0 && wtStrategies > 0;
            
            if (!allExistInDOM) {
                const missing = [];
                if (soStrategies === 0) missing.push('SO Strategies');
                if (stStrategies === 0) missing.push('ST Strategies');
                if (woStrategies === 0) missing.push('WO Strategies');
                if (wtStrategies === 0) missing.push('WT Strategies');
                
                return {
                    valid: false,
                    message: 'Semua 4 strategi (SO, ST, WO, WT) harus ada sebelum generate recommendation. Strategi yang belum ada: ' + missing.join(', ')
                };
            }
            
            // DOM check passed when backend failed
            return { valid: true };
        }
    }

    // ============================================
    // Generate Recommendations Handler (UPDATED)
    // ============================================
    const generateRecommendationsBtn = document.getElementById('generateRecommendationsBtn');
    
    // Helper function to calculate matrix scores
    async function calculateMatrixScores(projectKey) {
        try {
            console.log('📊 Calculating IFE and EFE scores...');
            
            // Fetch IFE Matrix data
            const ifeResponse = await fetch(`${apiBase}/matrix_ife_get?key=${projectKey}`);
            const ifeJson = await ifeResponse.json();
            
            let ifeScore = 0;
            if (ifeJson.success && ifeJson.data) {
                const strengths = ifeJson.data.strengths || [];
                const weaknesses = ifeJson.data.weaknesses || [];
                let totalScore = 0;
                
                // Calculate IFE score: weight * rating for each factor
                strengths.forEach(s => {
                    totalScore += (s.weight || 0) * (s.rating || 0);
                });
                weaknesses.forEach(w => {
                    totalScore += (w.weight || 0) * (w.rating || 0);
                });
                
                ifeScore = totalScore;
                console.log('✅ IFE Score calculated:', ifeScore.toFixed(2));
            }
            
            // Fetch EFE Matrix data
            const efeResponse = await fetch(`${apiBase}/matrix_efe_get?key=${projectKey}`);
            const efeJson = await efeResponse.json();
            
            let efeScore = 0;
            if (efeJson.success && efeJson.data) {
                const opportunities = efeJson.data.opportunities || [];
                const threats = efeJson.data.threats || [];
                let totalScore = 0;
                
                // Calculate EFE score: weight * rating for each factor
                opportunities.forEach(o => {
                    totalScore += (o.weight || 0) * (o.rating || 0);
                });
                threats.forEach(t => {
                    totalScore += (t.weight || 0) * (t.rating || 0);
                });
                
                efeScore = totalScore;
                console.log('✅ EFE Score calculated:', efeScore.toFixed(2));
            }
            
            // Determine quadrant based on scores
            // Scale scores to 0-4 range (typical IE Matrix scale)
            const scaledIFE = ifeScore / 2.0;  // Assuming max possible is 4.0
            const scaledEFE = efeScore / 2.0;
            
            let quadrant = 'UNKNOWN';
            if (scaledIFE >= 2.0 && scaledEFE >= 2.0) {
                quadrant = 'I'; // Grow and Build
            } else if (scaledIFE < 2.0 && scaledEFE >= 2.0) {
                quadrant = 'II'; // Hold and Maintain
            } else if (scaledIFE < 2.0 && scaledEFE < 2.0) {
                quadrant = 'III'; // Harvest or Divest
            } else if (scaledIFE >= 2.0 && scaledEFE < 2.0) {
                quadrant = 'IV'; // Retrench
            }
            
            console.log('✅ Quadrant determined:', quadrant);
            
            return {
                ife_score: ifeScore,
                efe_score: efeScore,
                quadrant: quadrant
            };
        } catch (error) {
            console.error('❌ Error calculating scores:', error);
            throw new Error('Gagal menghitung skor IFE dan EFE: ' + error.message);
        }
    }
    
    if (generateRecommendationsBtn) {
        generateRecommendationsBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            
            // VALIDATION: Check if all 4 strategies exist first (with both DOM and backend verification)
            const validation = await validateAllStrategiesExist();
            if (!validation.valid) {
                alert(validation.message);
                return;
            }
            
            const projectUuid = new URLSearchParams(window.location.search).get('key');
            const containerEl = document.getElementById('finalRecommendationsContainer');
            
            generateRecommendationsBtn.disabled = true;
            const originalText = generateRecommendationsBtn.innerHTML;
            generateRecommendationsBtn.innerHTML = '<span class="spinner">Analyzing...</span> Generating Final Strategic Recommendation...';
            
            // Show loading state in container
            if (containerEl) {
                placeholderSetLoading(containerEl, 'Menganalisis data matrix dan strategi');
            }
            
            try {
                // Calculate IFE/EFE scores and determine quadrant
                console.log('🔄 Starting to calculate matrix scores...');
                const matrixData = await calculateMatrixScores(projectUuid);
                
                // Update loading message to show next step
                if (containerEl) {
                    placeholderSetLoading(containerEl, 'Menghasilkan rekomendasi strategis');
                }
                
                console.log('📤 Sending to generate-strategic-recommendation endpoint...');
                // Call new endpoint for Final Strategic Recommendation
                const response = await fetch('/api/project/generate-strategic-recommendation', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        project_uuid: projectUuid,
                        ife_score: matrixData.ife_score,
                        efe_score: matrixData.efe_score,
                        quadrant: matrixData.quadrant
                    })
                });

                const json = await response.json();
                
                if (json.success && json.data.recommendation) {
                    // Display comprehensive Final Strategic Recommendation
                    console.log('✅ Recommendation generated successfully');
                    displayFinalRecommendation(json.data);
                } else {
                    throw new Error(json.message || 'Failed to generate recommendation');
                }
                
            } catch (error) {
                console.error('Error generating recommendation:', error);
                if (containerEl) {
                    placeholderSetError(containerEl, 'Gagal membuat rekomendasi: ' + error.message);
                }
                alert('Error: ' + error.message);
            } finally {
                generateRecommendationsBtn.disabled = false;
                generateRecommendationsBtn.innerHTML = originalText;
            }
        });
    }});

</script>
        </div>
    </div>



</div>
