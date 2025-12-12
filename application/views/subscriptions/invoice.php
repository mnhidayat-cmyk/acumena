<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Invoice #<?php echo $invoice->invoice_number;?></title>
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css?time='.time()) ?>" />
</head>
<body class="bg-gray-100 text-gray-800">
  <!-- Toolbar (non-print) -->
  <div class="max-w-4xl mx-auto p-4 print:hidden">
    <div class="flex items-center justify-between">
      <h1 class="text-xl font-semibold">Invoice Preview</h1>
      <div class="flex items-center gap-4">
        <a href="<?php echo base_url('subscription');?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg print:hidden">Back</a>
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition">
        <!-- icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18h12v2H6v-2ZM6 14H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-2"/></svg>
        Print
        </button>
      </div>
      
    </div>
  </div>

  <!-- Invoice Card -->
  <div class="max-w-4xl mx-auto bg-white shadow-sm rounded-2xl p-6 sm:p-10 print:shadow-none print:rounded-none print:p-0">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
      <div>
        <div class="text-2xl font-bold tracking-tight">INVOICE #<?= $invoice->invoice_number; ?></div>
        <?php
          if($invoice->status == 'paid'){
            echo '<span class="font-semibold text-success italic">Paid</span>';
          }elseif($invoice->status == 'unpaid'){
            echo '<span class="font-semibold text-danger italic">Unpaid</span>';
          }elseif($invoice->status == 'processing'){
            echo '<span class="font-semibold text-warning italic">Processing</span>';
          }elseif($invoice->status == 'rejected'){
            echo '<span class="font-semibold text-danger italic">Rejected</span>';
          }else{
            echo '<span class="font-semibold text-gray-300 italic">Canceled</span>';
          }
          ?>
        <p class="text-sm text-gray-500">Issued: <span class="font-medium text-gray-700"><?= date('d M Y', strtotime($invoice->date_created)); ?></span></p>
        <p class="text-sm text-gray-500">Due: <span class="font-medium text-gray-700"><?= date('d M Y', strtotime('+7 days', strtotime($invoice->date_created))); ?></span></p>
      </div>
      <div class="text-right">
        <!-- Logo / Brand -->
        <div class="text-lg font-semibold">Acumena</div>
        <div class="text-sm text-gray-500">hello@acumena.id</div>
        <div class="text-sm text-gray-500">Jl. Meranti No. 12, Jakarta</div>
      </div>
    </div>

    <!-- Bill To / From -->
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
      <div class="rounded-xl border border-gray-200 p-4">
        <div class="text-sm font-semibold text-gray-700 mb-1">Billed To</div>
        <div class="font-semibold"><?= $invoice_user->full_name; ?></div>
        <div class="text-sm text-gray-500"><?= $invoice_user->email; ?></div>
      </div>
      <div class="rounded-xl border border-gray-200 p-4">
        <div class="text-sm font-semibold text-gray-700 mb-1">Payment To</div>
        <div class="font-semibold">Acumena Corp</div>
        <div class="text-sm text-gray-500 mb-2">Bank BCA • 1234567890 - a/n Acumena</div>
        <?php
        if($invoice->status == 'unpaid'){
        ?>
        <a href="#payment-wrapper" class="text-sm text-blue-500 print:hidden">Sudah Melakukan Pembayaran? <span class="font-bold">Konfirmasi Pembayaran</span></a>
        <?php
        }
        ?>
      </div>
    </div>

    <!-- Items Table -->
    <div class="mt-8">
      <div class="overflow-x-auto rounded-xl border border-gray-200">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-gray-700">
            <tr>
              <th class="text-left px-4 py-3 font-semibold">Item</th>
              <th class="text-left px-4 py-3 font-semibold">Qty</th>
              <th class="text-left px-4 py-3 font-semibold">Unit Price</th>
              <th class="text-left px-4 py-3 font-semibold">Amount</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t">
              <td class="px-4 py-3">
                <?= $invoice->subscription_name; ?> – Acumena Subscription
                <!-- <div class="text-xs text-gray-500">Wireframe, high-fidelity, design system</div> -->
              </td>
              <td class="px-4 py-3">1</td>
              <td class="px-4 py-3">Rp<?= number_format($invoice->price, 2, ',', '.'); ?></td>
              <td class="px-4 py-3 font-medium">Rp<?= number_format($invoice->price, 2, ',', '.'); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Totals -->
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
      <div class="rounded-xl border border-gray-200 p-4">
        <div class="text-sm font-semibold text-gray-700 mb-2">Notes</div>
        <ul class="text-sm text-gray-600 list-disc pl-5 space-y-1">
          <li>Payment due within 7 days of invoice date.</li>
          <li>Please include invoice number on transfer note.</li>
        </ul>
      </div>

      <div class="sm:ml-auto">
        <div class="rounded-xl border border-gray-200 p-4">
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-medium ml-2">Rp<?= number_format($invoice->price, 2, ',', '.'); ?></span>
          </div>
          <div class="h-px bg-gray-200 my-3"></div>
          <div class="flex items-center justify-between text-base">
            <span class="font-semibold">Total</span>
            <span class="font-extrabold text-gray-900 ml-2">Rp<?= number_format($invoice->price, 2, ',', '.'); ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div class="text-sm text-gray-500">
        Thank you for your business.<br/>
        For questions, contact hallo@acumena.id
      </div>
    </div>
  </div>

  <!-- Print sizing (A4-ish margins) -->
  <style>
    @media print {
      @page { margin: 18mm; }
      body { background: white; }
    }
  </style>

<div id="payment-wrapper" class="print:hidden max-w-4xl mx-auto bg-white shadow-sm rounded-2xl p-6 sm:p-10 mt-8 space-y-8 mb-8">

  <?php
  if($invoice->status == 'unpaid'){
  ?>
  <!-- ========== 1) Belum di-upload ========== -->
  <section class="border border-gray-200 rounded-xl p-5">
    <div class="flex items-start justify-between gap-4">
      <div>
        <h3 class="text-lg font-semibold text-gray-800">Upload Bukti Transfer</h3>
        <p class="text-sm text-gray-500">Unggah file bukti (JPG/PNG/PDF, maks 5MB).</p>
      </div>
      <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-700 text-xs font-medium px-2.5 py-1">
        Not Uploaded
      </span>
    </div>

    <!-- Dropzone -->
    <label id="dropUpload"
      for="proof_1"
      class="mt-4 block cursor-pointer rounded-lg border-2 border-dashed border-gray-300 hover:border-blue-400 hover:bg-blue-50/50 transition p-6 text-center">
      <div class="flex flex-col items-center gap-2">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 12l3.5-3.5M12 16l-3.5-3.5M6 20h12" />
        </svg>
        <div class="text-sm"><span class="font-medium text-blue-600">Klik untuk pilih</span> atau seret file ke sini</div>
        <div class="text-xs text-gray-500">JPG • PNG • PDF — Maks 5MB</div>
      </div>
      <input id="proof_1" type="file" class="sr-only" />
    </label>

    <div id="selectedIndicator" class="mt-3 hidden flex items-center gap-2 text-sm text-blue-700 bg-blue-50 border border-blue-200 rounded-lg px-3 py-2">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2 1 21h22L12 2zm1 15h-2v-2h2v2zm0-4h-2V9h2v4z"/></svg>
      <span id="selectedText"></span>
    </div>

    <div id="selectedPreview" class="mt-3 hidden w-full sm:w-56">
      <div class="aspect-[4/3] rounded-lg border border-gray-200 overflow-hidden bg-gray-50">
        <img id="selectedImg" src="" alt="Preview" class="w-full h-full object-cover" />
      </div>
      <p id="selectedMeta" class="mt-2 text-xs text-gray-500 truncate"></p>
    </div>

    <div class="mt-4 flex items-center justify-end gap-2">
      <button class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700">Batal</button>
      <button id="btnUploadProof" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white inline-flex items-center gap-2">
        <svg id="btnUploadSpinner" class="w-4 h-4 animate-spin hidden" viewBox="0 0 24 24" fill="none">
          <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.25"></circle>
          <path d="M12 2a10 10 0 0 1 10 10h-3a7 7 0 0 0-7-7V2z" fill="currentColor" opacity="0.75"></path>
        </svg>
        <span id="btnUploadLabel">Upload</span>
      </button>
    </div>
  </section>
  <?php
  }
  if($invoice->status == 'processing'){
  ?>
  <!-- ========== 2) Sudah upload & proses pengecekan (upload disabled) ========== -->
  <section class="border border-gray-200 rounded-xl p-5">
    <div class="flex items-start justify-between gap-4">
      <div>
        <h3 class="text-lg font-semibold text-gray-800">Bukti Transfer</h3>
        <p class="text-sm text-gray-500">Bukti telah diterima. Sedang proses verifikasi.</p>
      </div>
      <span class="inline-flex items-center rounded-full bg-amber-100 text-amber-700 text-xs font-medium px-2.5 py-1">
        Checking
      </span>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row gap-4">
      <!-- Preview -->
      <div class="w-full sm:w-56">
        <div class="aspect-[4/3] rounded-lg border border-gray-200 overflow-hidden bg-gray-50">
          <img src="<?= base_url('public/paymentproof/'.$invoice->payment_proof) ?>" alt="Bukti transfer"
               class="w-full h-full object-cover" />
        </div>
        <p class="mt-2 text-xs text-gray-500 truncate"><?= basename($invoice->payment_proof) ?></p>
      </div>

      <!-- Info -->
      <div class="flex-1">
        <div class="flex items-center gap-2 text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2 1 21h22L12 2zm1 15h-2v-2h2v2zm0-4h-2V9h2v4z"/></svg>
          Sedang dicek oleh tim.
        </div>
      </div>
    </div>
  </section>
  <?php
  }
  if($invoice->status == 'paid'){
    if($invoice->payment_proof != null){
  ?>

  <!-- ========== 3) Valid ========== -->
  <section class="border border-gray-200 rounded-xl p-5">
    <div class="flex items-start justify-between gap-4">
      <div>
        <h3 class="text-lg font-semibold text-gray-800">Bukti Transfer</h3>
        <p class="text-sm text-gray-500">Bukti telah divalidasi. Terima kasih!</p>
      </div>
      <span class="inline-flex items-center rounded-full bg-emerald-100 text-emerald-700 text-xs font-medium px-2.5 py-1">
        Valid
      </span>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row gap-4">
      <div class="w-full sm:w-56">
        <div class="aspect-[4/3] rounded-lg border border-gray-200 overflow-hidden bg-gray-50">
          <img src="<?= base_url('public/paymentproof/'.$invoice->payment_proof) ?>" alt="Bukti transfer"
               class="w-full h-full object-cover" />
        </div>
        <p class="mt-2 text-xs text-gray-500 truncate"><?= basename($invoice->payment_proof) ?></p>
      </div>

      <div class="flex-1">
        <div class="flex items-center gap-2 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
            <path d="M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/>
          </svg>
          Pembayaran terverifikasi dan dicocokkan.
        </div>
      </div>
    </div>
  </section>
  <?php
    }
  }
  if($invoice->status == 'rejected'){
  ?>

  <!-- ========== 4) Reject ========== -->
  <section class="border border-gray-200 rounded-xl p-5">
    <div class="flex items-start justify-between gap-4">
      <div>
        <h3 class="text-lg font-semibold text-gray-800">Bukti Transfer</h3>
        <p class="text-sm text-gray-500">Bukti tidak valid. Silakan unggah ulang.</p>
      </div>
      <span class="inline-flex items-center rounded-full bg-rose-100 text-rose-700 text-xs font-medium px-2.5 py-1">
        Rejected
      </span>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row gap-4">
      <div class="w-full sm:w-56">
        <div class="aspect-[4/3] rounded-lg border border-gray-200 overflow-hidden bg-gray-50">
          <img src="<?= base_url('public/paymentproof/'.$invoice->payment_proof) ?>" alt="Bukti transfer"
               class="w-full h-full object-cover" />
        </div>
        <p class="mt-2 text-xs text-gray-500 truncate"><?= basename($invoice->payment_proof) ?></p>
      </div>

      <div class="flex-1">
        <div class="flex items-start gap-2 text-sm text-rose-700 bg-rose-50 border border-rose-200 rounded-lg px-3 py-2">
          <svg class="w-4 h-4 mt-0.5" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2 1 21h22L12 2zm1 15h-2v-2h2v2zm0-4h-2V9h2v4z"/>
          </svg>
          <div>
            <div class="font-medium">Alasan:</div>
            <div class="text-gray-600"><?= $invoice->payment_proof_notes ?></div>
          </div>
        </div>

        <!-- Re-upload -->
        <div class="mt-4">
          <label id="dropReupload" for="reup"
            class="block cursor-pointer rounded-lg border-2 border-dashed border-gray-300 hover:border-blue-400 hover:bg-blue-50/50 transition p-5 text-center">
            <div class="flex flex-col items-center gap-2">
              <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 12l3.5-3.5M12 16l-3.5-3.5M6 20h12" />
              </svg>
              <div class="text-sm"><span class="font-medium text-blue-600">Pilih file baru</span> untuk unggah ulang</div>
              <div class="text-xs text-gray-500">JPG • PNG • PDF — Maks 5MB</div>
            </div>
            <input id="reup" type="file" class="sr-only" />
          </label>

          <div id="reSelectedIndicator" class="mt-3 hidden flex items-center gap-2 text-sm text-blue-700 bg-blue-50 border border-blue-200 rounded-lg px-3 py-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2 1 21h22L12 2zm1 15h-2v-2h2v2zm0-4h-2V9h2v4z"/></svg>
            <span id="reSelectedText"></span>
          </div>

          <div id="reSelectedPreview" class="mt-3 hidden w-full sm:w-56">
            <div class="aspect-[4/3] rounded-lg border border-gray-200 overflow-hidden bg-gray-50">
              <img id="reSelectedImg" src="" alt="Preview" class="w-full h-full object-cover" />
            </div>
            <p id="reSelectedMeta" class="mt-2 text-xs text-gray-500 truncate"></p>
          </div>

          <div class="mt-3 flex items-center justify-end gap-2">
            <button id="btnReuploadUpload" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white inline-flex items-center gap-2">
              <svg id="btnReuploadSpinner" class="w-4 h-4 animate-spin hidden" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.25"></circle>
                <path d="M12 2a10 10 0 0 1 10 10h-3a7 7 0 0 0-7-7V2z" fill="currentColor" opacity="0.75"></path>
              </svg>
              <span id="btnReuploadLabel">Upload Ulang</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php
  }
  ?>

</div>

<script>
(function(){
  const btn = document.getElementById('btnUploadProof');
  const fileInput = document.getElementById('proof_1');
  const btnSpinner = document.getElementById('btnUploadSpinner');
  const btnLabel = document.getElementById('btnUploadLabel');
  const dropUpload = document.getElementById('dropUpload');
  const indicator = document.getElementById('selectedIndicator');
  const selectedText = document.getElementById('selectedText');
  const preview = document.getElementById('selectedPreview');
  const previewImg = document.getElementById('selectedImg');
  const previewMeta = document.getElementById('selectedMeta');
  const reInput = document.getElementById('reup');
  const dropReupload = document.getElementById('dropReupload');
  const reIndicator = document.getElementById('reSelectedIndicator');
  const reSelectedText = document.getElementById('reSelectedText');
  const rePreview = document.getElementById('reSelectedPreview');
  const rePreviewImg = document.getElementById('reSelectedImg');
  const rePreviewMeta = document.getElementById('reSelectedMeta');
  const btnRe = document.getElementById('btnReuploadUpload');
  const btnReSpinner = document.getElementById('btnReuploadSpinner');
  const btnReLabel = document.getElementById('btnReuploadLabel');
  // Jangan hentikan eksekusi jika elemen upload awal tidak ada;
  // pasang listener hanya untuk elemen yang tersedia.
  function setupDropzone(zone, input){
    if (!zone || !input) return;
    ['dragenter','dragover'].forEach(evt => zone.addEventListener(evt, function(e){ e.preventDefault(); zone.classList.add('border-blue-400','bg-blue-50'); }));
    ;['dragleave','drop'].forEach(evt => zone.addEventListener(evt, function(e){ e.preventDefault(); zone.classList.remove('border-blue-400','bg-blue-50'); }));
    zone.addEventListener('drop', function(e){
      e.preventDefault();
      const files = e.dataTransfer.files;
      if (files && files.length) { input.files = files; input.dispatchEvent(new Event('change')); }
    });
  }
  setupDropzone(dropUpload, fileInput);
  setupDropzone(dropReupload, reInput);
  if (fileInput) {
    fileInput.addEventListener('change', function(){
      const f = fileInput.files && fileInput.files[0];
      if (!f) return;
      indicator && indicator.classList.remove('hidden');
      if (selectedText) selectedText.textContent = 'File terpilih: ' + f.name;
      const size = f.size < 1048576 ? (Math.round(f.size/1024)) + ' KB' : (Math.round(f.size/104857.6)/10) + ' MB';
      preview && preview.classList.remove('hidden');
      if (previewImg) {
        if (f.type && f.type.indexOf('image/') === 0) {
          previewImg.src = URL.createObjectURL(f);
        } else {
          previewImg.src = 'https://via.placeholder.com/640x480.png?text=PDF';
        }
      }
      if (previewMeta) previewMeta.textContent = f.name + ' • ' + size;
    });
  }
  if (btn && fileInput) {
    btn.addEventListener('click', async function(){
      const f = fileInput.files && fileInput.files[0];
      if (!f) {
        alert('Silakan pilih file bukti terlebih dahulu');
        return;
      }
      btn.disabled = true;
      if (btnSpinner) btnSpinner.classList.remove('hidden');
      if (btnLabel) btnLabel.textContent = 'Uploading...';
      const fd = new FormData();
      fd.append('payment_proof', f);
      fd.append('invoice_uuid', '<?= $invoice->uuid ?>');
      try {
        const res = await fetch('<?= base_url('api_subscription/upload_payment_proof') ?>', {
          method: 'POST',
          body: fd
        });
        const data = await res.json();
        if (data && data.error === false) {
          window.location.reload();
        } else {
          alert(data && data.message ? data.message : 'Upload gagal');
        }
      } catch (e) {
        alert('Terjadi kesalahan koneksi');
      }
      btn.disabled = false;
      if (btnSpinner) btnSpinner.classList.add('hidden');
      if (btnLabel) btnLabel.textContent = 'Upload';
    });
  }
  if (reInput) {
    reInput.addEventListener('change', function(){
      const f = reInput.files && reInput.files[0];
      if (!f) return;
      reIndicator.classList.remove('hidden');
      reSelectedText.textContent = 'File terpilih: ' + f.name;
      const size = f.size < 1048576 ? (Math.round(f.size/1024)) + ' KB' : (Math.round(f.size/104857.6)/10) + ' MB';
      rePreview.classList.remove('hidden');
      if (f.type && f.type.indexOf('image/') === 0) {
        rePreviewImg.src = URL.createObjectURL(f);
      } else {
        rePreviewImg.src = 'https://via.placeholder.com/640x480.png?text=PDF';
      }
      rePreviewMeta.textContent = f.name + ' • ' + size;
    });
  }
  if (btnRe) {
    btnRe.addEventListener('click', async function(){
      const f = reInput && reInput.files && reInput.files[0];
      if (!f) { alert('Silakan pilih file baru terlebih dahulu'); return; }
      btnRe.disabled = true;
      if (btnReSpinner) btnReSpinner.classList.remove('hidden');
      if (btnReLabel) btnReLabel.textContent = 'Uploading...';
      const fd = new FormData();
      fd.append('payment_proof', f);
      fd.append('invoice_uuid', '<?= $invoice->uuid ?>');
      try {
        const res = await fetch('<?= base_url('api_subscription/upload_payment_proof') ?>', {
          method: 'POST',
          body: fd
        });
        const data = await res.json();
        if (data && data.error === false) {
          window.location.reload();
        } else {
          alert(data && data.message ? data.message : 'Upload gagal');
        }
      } catch (e) {
        alert('Terjadi kesalahan koneksi');
      }
      btnRe.disabled = false;
      if (btnReSpinner) btnReSpinner.classList.add('hidden');
      if (btnReLabel) btnReLabel.textContent = 'Upload Ulang';
    });
  }
})();
</script>

</body>
</html>
