<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * View Disbursements List
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden">
    <!-- Top Filter Header -->
    <div class="p-4 sm:p-5 border-b border-slate-150 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Search bar -->
        <form action="<?= url($prefix . '/disbursements') ?>" method="GET" class="flex items-center w-full sm:max-w-xs">
            <div class="relative w-full">
                <input type="text" name="search" value="<?= esc($filters['search'] ?? '') ?>"
                       class="block w-full pl-9 pr-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                       placeholder="Cari penerima atau barang...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <button type="submit" class="hidden">Cari</button>
        </form>

        <!-- Action triggers -->
        <div class="flex items-center gap-2 self-end sm:self-auto">
            <?php if (!empty($filters['search'])): ?>
                <a href="<?= url($prefix . '/disbursements') ?>" 
                   class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
                    Reset
                </a>
            <?php endif; ?>

            <a href="<?= url($prefix . '/disbursements/create') ?>" 
               class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm transition duration-150">
                <i data-lucide="plus" class="w-3.5 h-3.5 mr-1"></i>
                Catat Pengeluaran
            </a>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="overflow-x-auto">
        <?php if (empty($disbursements)): ?>
            <div class="p-8 text-center bg-white">
                <p class="text-xs text-slate-500 font-medium">Belum ada rekaman sirkulasi pengeluaran bahan habis pakai.</p>
            </div>
        <?php else: ?>
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-[10px] font-semibold uppercase bg-slate-100/50 text-slate-500">
                        <th class="px-5 py-3 w-12 text-center">ID</th>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Penerima</th>
                        <th class="px-5 py-3">Barang / Bahan</th>
                        <th class="px-5 py-3 text-center w-24">Jumlah</th>
                        <th class="px-5 py-3">Keperluan</th>
                        <th class="px-5 py-3">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($disbursements as $d): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-5 py-3.5 font-bold text-slate-700 text-center">#<?= $d['id'] ?></td>
                            <td class="px-5 py-3.5 text-slate-650 font-medium"><?= date('d/m/Y', strtotime($d['created_at'])) ?></td>
                            <td class="px-5 py-3.5">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-slate-800"><?= esc($d['receiver_name']) ?></span>
                                    <span class="text-[9px] text-slate-400 font-medium"><?= esc($d['receiver_identity']) ?></span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700"><?= esc($d['item_code']) ?></span>
                                    <span class="text-slate-650 font-medium mt-0.5"><?= esc($d['item_name']) ?></span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded border border-rose-200">
                                    - <?= $d['quantity'] ?> <?= esc($d['unit_name']) ?>
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 font-medium"><?= esc($d['purpose'] ?: '-') ?></td>
                            <td class="px-5 py-3.5 text-slate-650 font-semibold"><?= esc($d['giver_name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
