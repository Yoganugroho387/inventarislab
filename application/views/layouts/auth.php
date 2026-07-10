<?php
/**
 * Authentication Layout Wrapper
 */
require APPPATH . 'views/layouts/header.php';
?>

<div class="flex min-h-screen flex-col justify-center py-12 sm:px-6 lg:px-8 bg-slate-100">
    <!-- Flash Messages (Centered) -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0 mb-4">
        <?php if ($this->session->flashdata('success') !== NULL): ?>
            <div class="flex items-center p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded text-xs">
                <i data-lucide="check-circle" class="w-4 h-4 mr-2 text-emerald-600 flex-shrink-0"></i>
                <span><?= esc($this->session->flashdata('success')) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error') !== NULL): ?>
            <div class="flex items-center p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded text-xs">
                <i data-lucide="alert-circle" class="w-4 h-4 mr-2 text-rose-600 flex-shrink-0"></i>
                <span><?= esc($this->session->flashdata('error')) ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Inject View Content -->
    <?= $content ?>

    <!-- Copyright Footer -->
    <div class="mt-8 text-center">
        <p class="text-[10px] text-slate-400 font-medium">
            &copy; <?= date('Y') ?> <span class="text-slate-500 font-semibold">Yoga Nugroho</span> &mdash; All Rights Reserved.
        </p>
        <p class="text-[10px] text-slate-400 mt-0.5">089685027530 | Open Jasa Pembuatan Website &amp; Joki Tugas Website</p>
        <a href="https://tako.id/YNGRHO" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-semibold text-teal-600 hover:text-teal-800 transition mt-1">
            ♥ Support Developer
        </a>
    </div>
</div>

<?php
require APPPATH . 'views/layouts/footer.php';
?>
