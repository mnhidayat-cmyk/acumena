<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= isset($title) ? $title . ' | ' : '' ?>Acumena</title>
    <link rel="icon" href="<?= base_url('assets/img/acumena-favicon.png') ?>" type="image/png" />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.output.css?time='.time()) ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css?time='.time()) ?>" />
    <!-- css -->
    <?php foreach($css as $file): ?>
        <link rel="stylesheet" href="<?= $file ?>" />
    <?php endforeach; ?>
    <script
      src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js?"
      defer
    ></script>
    <script src="<?= base_url('assets/js/init-alpine.js?time='.time()) ?>"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- js -->
    <?php foreach($js as $file): ?>
        <script src="<?= $file ?>"></script>
    <?php endforeach; ?>
  </head>
  <body>
    <div
      class="flex h-screen bg-gray-50 dark:bg-gray-900"
      :class="{ 'overflow-hidden': isSideMenuOpen }"
    >
      <!-- Desktop sidebar -->
      <?php $this->load->view('components/sidebar-desktop'); ?>

      <!-- Mobile sidebar -->
      <!-- Backdrop -->
      <div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
      ></div>
      <?php $this->load->view('components/sidebar-mobile'); ?>
      <div class="flex flex-col flex-1 w-full">
        <!-- Header -->
        <?php $this->load->view('components/header'); ?>
        <main class="h-full overflow-y-auto" style="padding-bottom: 5em">
          <?php $this->load->view(!empty($content) ? $content : 'content_not_found'); ?>
        </main>
        <!-- footer -->
          <?php $this->load->view('components/footer'); ?>
      </div>
    </div>
    
    <script>
    function confirmLogout(event) {
        event.preventDefault();
        
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: 'Apakah Anda yakin ingin keluar dari akun?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('auth/logout') ?>';
            }
        });
    }
    </script>
  </body>
</html>
