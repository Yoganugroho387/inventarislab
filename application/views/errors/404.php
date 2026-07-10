<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * 404 Page Not Found View
 */
?>
<div class="sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0 text-center animate-fade-in">
    <div class="bg-white py-8 px-6 shadow sm:rounded-lg sm:px-10 border border-slate-200">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-rose-50 text-rose-600 mb-3">
            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">404</h1>
        <h2 class="text-sm font-semibold text-slate-700 mt-1">Halaman Tidak Ditemukan</h2>
        <p class="text-xs text-slate-500 mt-2 mb-6 leading-relaxed">
            Maaf, kami tidak dapat menemukan halaman yang Anda cari. Halaman mungkin telah dihapus atau URL yang diakses salah.
        </p>
        <div>
            <a href="<?= url('/login') ?>"
               class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded shadow-sm text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-teal-500 transition duration-150">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1.5"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
