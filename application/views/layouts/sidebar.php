<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Shared Sidebar Component
 */
$appName = Setting_model::getVal('app_name', 'Inventaris Lab');
$labName = Setting_model::getVal('lab_name', 'Fakultas Teknik');
$user = Auth::user();
$role = Auth::role();
?>
<!-- Sidebar Container -->
<div class="flex flex-col w-64 h-full bg-slate-900 border-r border-slate-800 text-slate-300">
    <!-- Header/Branding -->
    <div class="flex flex-col px-6 py-4 border-b border-slate-800 bg-slate-950">
        <span class="text-white font-semibold text-base tracking-tight truncate"><?= esc($appName) ?></span>
        <span class="text-xs text-brand-400 font-medium truncate mt-0.5"><?= esc($labName) ?></span>
    </div>

    <!-- Navigation List -->
    <div class="flex-1 overflow-y-auto px-4 py-6 space-y-7">
        <?php if ($role === 'admin'): ?>
            <!-- Admin Menu -->
            <div class="space-y-1.5">
                <span class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Menu Utama</span>
                
                <a href="<?= url('/admin/dashboard') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/dashboard') ?>">
                    <i data-lucide="home" class="w-4 h-4 mr-2.5"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="<?= url('/admin/items') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/items') ?>">
                    <i data-lucide="box" class="w-4 h-4 mr-2.5"></i>
                    <span>Inventaris Barang</span>
                </a>
                
                <a href="<?= url('/admin/borrowings') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/borrowings') ?>">
                    <i data-lucide="clipboard-list" class="w-4 h-4 mr-2.5"></i>
                    <span>Peminjaman</span>
                </a>
                
                <a href="<?= url('/admin/returns') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/returns') ?>">
                    <i data-lucide="check-square" class="w-4 h-4 mr-2.5"></i>
                    <span>Pengembalian</span>
                </a>

                <a href="<?= url('/admin/disbursements') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/disbursements') ?>">
                    <i data-lucide="shopping-bag" class="w-4 h-4 mr-2.5"></i>
                    <span>Pengeluaran Bahan</span>
                </a>
                
                <a href="<?= url('/admin/maintenance') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/maintenance') ?>">
                    <i data-lucide="wrench" class="w-4 h-4 mr-2.5"></i>
                    <span>Perbaikan Aset</span>
                </a>
                
                <a href="<?= url('/admin/reports') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/reports') ?>">
                    <i data-lucide="file-bar-chart" class="w-4 h-4 mr-2.5"></i>
                    <span>Laporan</span>
                </a>
                
                <a href="<?= url('/admin/activity-logs') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/activity-logs') ?>">
                    <i data-lucide="clock" class="w-4 h-4 mr-2.5"></i>
                    <span>Log Aktivitas</span>
                </a>
                
                <a href="<?= url('/admin/bebas-lab') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/bebas-lab') ?>">
                    <i data-lucide="file-check-2" class="w-4 h-4 mr-2.5"></i>
                    <span>Bebas Lab</span>
                </a>
            </div>

            <!-- Master Data Sub-menu -->
            <div class="space-y-1.5">
                <span class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Master Data</span>
                
                <a href="<?= url('/admin/categories') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/categories') ?>">
                    <i data-lucide="tag" class="w-4 h-4 mr-2.5"></i>
                    <span>Kategori Barang</span>
                </a>
                
                <a href="<?= url('/admin/locations') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/locations') ?>">
                    <i data-lucide="map-pin" class="w-4 h-4 mr-2.5"></i>
                    <span>Lokasi Lab</span>
                </a>
                
                <a href="<?= url('/admin/units') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/units') ?>">
                    <i data-lucide="layers" class="w-4 h-4 mr-2.5"></i>
                    <span>Satuan Barang</span>
                </a>
                
                <a href="<?= url('/admin/users') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/users') ?>">
                    <i data-lucide="users" class="w-4 h-4 mr-2.5"></i>
                    <span>User Pengguna</span>
                </a>
            </div>

            <!-- Settings & Configuration -->
            <div class="space-y-1.5">
                <span class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Sistem</span>
                
                <a href="<?= url('/admin/settings') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/settings') ?>">
                    <i data-lucide="settings" class="w-4 h-4 mr-2.5"></i>
                    <span>Pengaturan</span>
                </a>
                
                <a href="<?= url('/admin/profile') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/admin/profile') ?>">
                    <i data-lucide="user" class="w-4 h-4 mr-2.5"></i>
                    <span>Profil Saya</span>
                </a>
            </div>

        <?php elseif ($role === 'staff'): ?>
            <!-- Staff Menu -->
            <div class="space-y-1.5">
                <span class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Operasional</span>
                
                <a href="<?= url('/staff/dashboard') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/staff/dashboard') ?>">
                    <i data-lucide="home" class="w-4 h-4 mr-2.5"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="<?= url('/staff/items') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/staff/items') ?>">
                    <i data-lucide="box" class="w-4 h-4 mr-2.5"></i>
                    <span>Lihat Inventaris</span>
                </a>
                
                <a href="<?= url('/staff/borrowings') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/staff/borrowings') ?>">
                    <i data-lucide="clipboard-list" class="w-4 h-4 mr-2.5"></i>
                    <span>Peminjaman</span>
                </a>
                
                <a href="<?= url('/staff/returns') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/staff/returns') ?>">
                    <i data-lucide="check-square" class="w-4 h-4 mr-2.5"></i>
                    <span>Pengembalian</span>
                </a>

                <a href="<?= url('/staff/disbursements') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/staff/disbursements') ?>">
                    <i data-lucide="shopping-bag" class="w-4 h-4 mr-2.5"></i>
                    <span>Pengeluaran Bahan</span>
                </a>
                
                <a href="<?= url('/staff/bebas-lab') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/staff/bebas-lab') ?>">
                    <i data-lucide="file-check-2" class="w-4 h-4 mr-2.5"></i>
                    <span>Bebas Lab</span>
                </a>
                
                <a href="<?= url('/staff/profile') ?>" class="flex items-center px-3 py-2 text-xs rounded transition duration-150 <?= activeClass('/staff/profile') ?>">
                    <i data-lucide="user" class="w-4 h-4 mr-2.5"></i>
                    <span>Profil Saya</span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- User Section Bottom -->
    <div class="p-4 border-t border-slate-800 bg-slate-950 flex items-center justify-between">
        <div class="flex items-center space-x-2.5 min-w-0">
            <div class="w-8 h-8 rounded-full bg-teal-800 text-teal-100 flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                <?= substr(esc($user['name'] ?? 'U'), 0, 2) ?>
            </div>
            <div class="flex flex-col min-w-0">
                <span class="text-xs text-white font-medium truncate"><?= esc($user['name'] ?? 'Guest') ?></span>
                <span class="text-[10px] text-slate-500 capitalize truncate"><?= esc($role) ?></span>
            </div>
        </div>
        
        <a href="<?= url('/logout') ?>" title="Logout" class="p-1.5 text-slate-400 hover:text-red-400 rounded hover:bg-slate-800 transition duration-150">
            <i data-lucide="log-out" class="w-4.5 h-4.5"></i>
        </a>
    </div>
</div>
