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
            <a href="<?= base_url('project/add?step=matrix-ife') ?>" class="text-gray-700 dark:text-white flex items-center gap-2 px-4 py-2 rounded-lg text-sm">
                <!-- Desktop label -->
                <span class="font-bold hidden md:inline">IFE Matrix</span>
                <!-- Mobile label -->
                <span class="font-bold md:hidden inline">IFE</span>
                <!-- Keterangan: tampil hanya di md+ -->
                <span class="hidden md:inline">(Internal)</span>
            </a>

            <!-- EFE -->
            <a href="<?= base_url('project/add?step=matrix-efe') ?>" class="bg-success text-white shadow-md dark:text-white flex items-center gap-2 px-4 py-2 rounded-lg text-sm">
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
            <a href="<?= base_url('project/add?step=matrix-ai') ?>" class="text-gray-700 dark:text-white flex items-center gap-2 px-4 py-2 rounded-lg text-sm">
                <!-- Sama di mobile & desktop: "AI Integration" -->
                <span class="font-bold">AI Integration</span>
                <!-- Keterangan hanya di md+ -->
                <span class="hidden md:inline">(Strategies)</span>
            </a>
        </div>


        <div class="mt-6">
            <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200">External Factor Evaluation</h2>
            <span class="text-gray-500">Assign weights (0.0-1.0) and ratings (1-4) to your external strategic factors</span>
        </div>

        <!-- Responsive table wrapper -->
        <div class="mt-8 w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-600">
        <table class="w-full min-w-[900px]">
            <thead>
            <tr class="bg-gray-100 dark:bg-gray-900">
                <th class="px-4 py-3 text-left text-sm font-bold text-gray-700 dark:text-gray-200 sticky top-0 bg-gray-100 dark:bg-gray-900">#</th>
                <th class="px-4 py-2 text-left text-sm font-bold text-gray-700 dark:text-gray-200 sticky top-0 bg-gray-100 dark:bg-gray-900">External Factors</th>
                <th class="px-4 py-2 text-left text-sm font-bold text-gray-700 dark:text-gray-200 sticky top-0 bg-gray-100 dark:bg-gray-900">Weight</th>
                <th class="px-4 py-2 text-left text-sm font-bold text-gray-700 dark:text-gray-200 sticky top-0 bg-gray-100 dark:bg-gray-900">Rating</th>
                <th class="px-4 py-2 text-left text-sm font-bold text-gray-700 dark:text-gray-200 sticky top-0 bg-gray-100 dark:bg-gray-900">Weight Score</th>
            </tr>
            </thead>
            <tbody id="efeTableBody">
                <tr>
                    <td colspan="5" class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top bg-soft-primary font-bold text-primary">
                        Opportunities
                    </td>
                </tr>
                <!-- Opportunities rows will be populated by JavaScript -->
                <tr>
                    <td colspan="5" class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 align-top bg-soft-warning font-bold text-warning">
                        Threats
                    </td>
                </tr>
                <!-- Threats rows will be populated by JavaScript -->
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
                <!-- more rows ... -->
            </tbody>
        </table>
        </div>

        <!-- Save Button -->
        <div class="mt-4 w-full flex justify-center no-print">
            <button id="saveEFEData" onclick="saveEFEData()" 
                    class="w-full px-6 py-2 btn btn-block gradient-primary rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />
                </svg>
                Save EFE Data
            </button>
        </div>

        <!-- chart -->
        <div class="mt-8 w-full rounded-lg border border-gray-300 dark:border-gray-600 p-4 bg-white dark:bg-gray-900">
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
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            </svg>
                            <span class="text-orange-500 font-semibold">External Factor Score</span>
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

        <!-- SAVE -->
        <div class="mx-auto w-full flex flex-col sm:flex-row gap-4 mt-8">
            <!-- Export PDF -->
            <button class="flex-1 flex flex-col items-center justify-center py-4 bg-soft-primary border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8 text-blue-500 mb-2" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>
                </div>
                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">Export as PDF</span>
                <span class="text-sm text-gray-500">Document Format</span>
            </button>

            <!-- Export Excel -->
            <button class="flex-1 flex flex-col items-center justify-center py-4 bg-soft-success border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm">
                <div class="text-green-600 mb-3">
                <!-- File icon (Excel) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8 text-green-600 mb-2" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M8 13h2"></path><path d="M14 13h2"></path><path d="M8 17h2"></path><path d="M14 17h2"></path></svg>
                </div>
                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">Export as Excel</span>
                <span class="text-sm text-gray-500">Spreadsheet Format</span>
            </button>
        </div>

        <!-- Button Next -->
        <div class="flex justify-between mt-8">
            <!-- <button type="submit" class="btn gradient-primary text-white" style="padding-left: 24px;padding-right: 24px;">
                Next
            </button> -->
            <a href="?step=matrix-ife" class="btn btn-soft-secondary" style="padding-left: 24px;padding-right: 24px;">
                Previous
            </a>
            <a href="?step=matrix-ie" class="btn gradient-primary text-white" style="padding-left: 24px;padding-right: 24px;">
                Next
            </a>
        </div>


    </div>


</div>

<script>
  // ====== CONFIGURASI CHART ======
  const ctx = document.getElementById('ifeChart');

  // nilai skor (0..4). Ubah via JS sesuai perhitunganmu
  let ifeScore = 4;

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
  }

  // Contoh pemakaian:
  // setIFEScore(0); showOverlay(true);
</script>
