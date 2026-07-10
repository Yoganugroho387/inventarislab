<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * View Maintenance Logs List
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden">
    <!-- Top Filter Header -->
    <div class="p-4 sm:p-5 border-b border-slate-150 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Search bar & filters -->
        <form action="<?= url($prefix . '/maintenance') ?>" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:max-w-md">
            <div class="relative w-full sm:max-w-xs">
                <input type="text" name="search" value="<?= esc($filters['search'] ?? '') ?>"
                       class="block w-full pl-9 pr-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                       placeholder="Cari aset atau vendor...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            
            <select name="status" onchange="this.form.submit()"
                    class="block px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
                <option value="">-- Semua Status --</option>
                <option value="proses" <?= ($filters['status'] ?? '') === 'proses' ? 'selected' : '' ?>>Dalam Proses</option>
                <option value="selesai" <?= ($filters['status'] ?? '') === 'selesai' ? 'selected' : '' ?>>Selesai Servis</option>
                <option value="tidak_bisa_diperbaiki" <?= ($filters['status'] ?? '') === 'tidak_bisa_diperbaiki' ? 'selected' : '' ?>>Rusak Total / Afkir</option>
            </select>
            <button type="submit" class="hidden">Filter</button>
        </form>

        <!-- Action triggers -->
        <div class="flex items-center gap-2 self-end sm:self-auto">
            <?php if (!empty($filters['search']) || !empty($filters['status'])): ?>
                <a href="<?= url($prefix . '/maintenance') ?>" 
                   class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
                    Reset
                </a>
            <?php endif; ?>

            <a href="<?= url($prefix . '/maintenance/create') ?>" 
               class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm transition duration-150">
                <i data-lucide="plus" class="w-3.5 h-3.5 mr-1"></i>
                Ajukan Perbaikan
            </a>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="overflow-x-auto">
        <?php if (empty($logs)): ?>
            <div class="p-8 text-center bg-white">
                <p class="text-xs text-slate-500 font-medium">Belum ada riwayat pemeliharaan atau perbaikan aset.</p>
            </div>
        <?php else: ?>
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-[10px] font-semibold uppercase bg-slate-100/50 text-slate-500">
                        <th class="px-5 py-3 w-12 text-center">ID</th>
                        <th class="px-5 py-3">Aset Inventaris</th>
                        <th class="px-5 py-3">Vendor Servis</th>
                        <th class="px-5 py-3">Linimasa</th>
                        <th class="px-5 py-3 text-right w-28">Biaya Servis</th>
                        <th class="px-5 py-3">Status Kerja</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-5 py-3.5 font-bold text-slate-700 text-center">#<?= $log['id'] ?></td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center space-x-3">
                                    <?php if ($log['item_image']): ?>
                                        <img src="<?= base_url('uploads/items/' . $log['item_image']) ?>" class="w-8 h-8 rounded border object-cover">
                                    <?php else: ?>
                                        <div class="w-8 h-8 bg-slate-100 border text-slate-400 rounded flex items-center justify-center">
                                            <i data-lucide="image" class="w-4 h-4"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800"><?= esc($log['item_code']) ?></span>
                                        <span class="text-slate-650 font-medium mt-0.5"><?= esc($log['item_name']) ?></span>
                                        <span class="text-[9px] text-slate-400 font-medium">Lokasi: <?= esc($log['location_name']) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-slate-750">
                                <?= esc($log['vendor_name'] ?: '-') ?>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex flex-col text-[10px] text-slate-600 font-medium">
                                    <span>Mulai: <?= date('d M Y', strtotime($log['start_date'])) ?></span>
                                    <?php if ($log['end_date']): ?>
                                        <span class="text-emerald-700">Selesai: <?= date('d M Y', strtotime($log['end_date'])) ?></span>
                                    <?php else: ?>
                                        <span class="text-amber-600 font-semibold animate-pulse">Sedang Diperbaiki</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-slate-800">
                                Rp <?= number_format($log['cost'], 0, ',', '.') ?>
                            </td>
                            <td class="px-5 py-3.5">
                                <?php if ($log['status'] === 'proses'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase">Proses Perbaikan</span>
                                <?php elseif ($log['status'] === 'selesai'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-250 uppercase">Selesai (Tersedia)</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-250 uppercase">Rusak Total / Afkir</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <?php if ($log['status'] === 'proses'): ?>
                                    <a href="<?= url('/admin/maintenance/edit/' . $log['id']) ?>" 
                                       class="inline-flex items-center justify-center px-2 py-1 border border-teal-600 text-[10px] font-semibold rounded text-teal-600 bg-white hover:bg-teal-50 transition shadow-sm">
                                        Periksa Hasil
                                        <i data-lucide="check" class="w-3.5 h-3.5 ml-1"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-slate-400 font-medium text-[10px]" title="Servis telah diselesaikan">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
