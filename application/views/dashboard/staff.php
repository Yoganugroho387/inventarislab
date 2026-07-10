<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Staff Dashboard View
 */
?>
<!-- Stats Widgets Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <!-- Catalog count -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow duration-200">
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Katalog Barang</span>
            <span class="text-2xl font-bold text-slate-800"><?= $totalItems ?></span>
        </div>
        <div class="p-3 bg-slate-100 text-slate-600 rounded-lg">
            <i data-lucide="box" class="w-5 h-5"></i>
        </div>
    </div>

    <!-- Active loans -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow duration-200">
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Pinjaman Aktif Anda</span>
            <span class="text-2xl font-bold text-teal-700"><?= $activeLoans ?></span>
        </div>
        <div class="p-3 bg-teal-50 text-teal-600 rounded-lg">
            <i data-lucide="clipboard-list" class="w-5 h-5"></i>
        </div>
    </div>

    <!-- Due today -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow duration-200">
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Jatuh Tempo Hari Ini</span>
            <span class="text-2xl font-bold <?= $dueToday > 0 ? 'text-rose-600' : 'text-slate-800' ?>"><?= $dueToday ?></span>
        </div>
        <div class="p-3 <?= $dueToday > 0 ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-500' ?> rounded-lg">
            <i data-lucide="calendar-clock" class="w-5 h-5"></i>
        </div>
    </div>

    <!-- Low stock warning -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow duration-200">
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Stok Menipis</span>
            <span class="text-2xl font-bold <?= $lowStockCount > 0 ? 'text-amber-600' : 'text-slate-800' ?>"><?= $lowStockCount ?></span>
        </div>
        <div class="p-3 <?= $lowStockCount > 0 ? 'bg-amber-50 text-amber-600' : 'bg-slate-100 text-slate-500' ?> rounded-lg">
            <i data-lucide="alert-octagon" class="w-5 h-5"></i>
        </div>
    </div>
</div>

<!-- Grid Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Area (2/3 Width) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- 1. My Active Loans -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between flex-shrink-0">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Daftar Pinjaman Aktif Anda</h3>
            </div>

            <div class="overflow-x-auto">
                <?php if (empty($myActiveLoans)): ?>
                    <div class="p-8 text-center bg-white">
                        <p class="text-xs text-slate-500 font-medium">Anda tidak memiliki pinjaman aktif.</p>
                    </div>
                <?php else: ?>
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-[10px] font-semibold uppercase bg-slate-100/50 text-slate-500">
                                <th class="px-5 py-2.5">ID</th>
                                <th class="px-5 py-2.5">Peminjam</th>
                                <th class="px-5 py-2.5">Barang</th>
                                <th class="px-5 py-2.5">Batas Pengembalian</th>
                                <th class="px-5 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($myActiveLoans as $mal): ?>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-5 py-3 font-bold text-slate-700">#<?= $mal['id'] ?></td>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-slate-800"><?= esc($mal['borrower_name']) ?></span>
                                            <span class="text-[9px] text-slate-400 font-medium"><?= esc($mal['borrower_identity']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-slate-600">
                                        <span class="truncate max-w-[150px] block" title="<?php 
                                            $itemNames = array_map(function($i) { return $i['item_name'] . ' (' . $i['quantity'] . ')'; }, $mal['items']);
                                            echo esc(implode(', ', $itemNames));
                                        ?>">
                                            <?= esc($mal['items'][0]['item_name']) ?> (x<?= $mal['items'][0]['quantity'] ?>)
                                            <?php if (count($mal['items']) > 1): ?>
                                                <span class="text-[9px] text-teal-600 block font-semibold">+<?= count($mal['items']) - 1 ?> barang lagi</span>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 font-semibold <?= (strtotime($mal['due_date']) < time()) ? 'text-rose-600' : 'text-slate-500' ?>">
                                        <?= date('d/m/y', strtotime($mal['due_date'])) ?>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="<?= url('/staff/returns/create/' . $mal['id']) ?>" 
                                           class="inline-flex items-center justify-center px-2 py-1 border border-transparent text-[10px] font-semibold rounded text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-sm">
                                            Kembalikan
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- 2. Mini low stock warning -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center space-x-2">
                    <i data-lucide="alert-octagon" class="w-4 h-4 text-amber-500"></i>
                    <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Pemberitahuan Stok Habis Pakai Menipis</h3>
                </div>
            </div>

            <div class="p-1">
                <?php if (empty($lowStockItems)): ?>
                    <p class="text-xs text-slate-500 p-6 text-center font-medium">Stok barang habis pakai di laboratorium dalam kondisi aman.</p>
                <?php else: ?>
                    <div class="divide-y divide-slate-100 text-xs">
                        <?php foreach (array_slice($lowStockItems, 0, 4) as $lsi): ?>
                            <div class="p-3.5 flex items-center justify-between hover:bg-slate-50/50 transition">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800"><?= esc($lsi['name']) ?></span>
                                    <span class="text-[10px] text-slate-400 font-semibold mt-0.5"><?= esc($lsi['code']) ?> | Lokasi: <?= esc($lsi['location_name']) ?></span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                        <?= $lsi['stock'] ?> / <?= $lsi['minimum_stock'] ?> <?= esc($lsi['unit_name']) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Area: Logs Timeline (1/3 Width) -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col h-fit">
        <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between flex-shrink-0">
            <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Aktivitas Anda Terbaru</h3>
        </div>

        <div class="p-5 overflow-y-auto max-h-[360px]">
            <?php if (empty($recentLogs)): ?>
                <p class="text-xs text-slate-500 font-medium text-center py-6">Belum ada catatan aktivitas.</p>
            <?php else: ?>
                <!-- Vertical Timeline -->
                <div class="relative pl-4 border-l border-slate-200 space-y-5 text-xs text-slate-600">
                    <?php foreach ($recentLogs as $log): ?>
                        <div class="relative">
                            <!-- Bullet point pointer -->
                            <div class="absolute -left-[20.5px] top-0.5 w-3 h-3 rounded-full border-2 bg-white border-teal-600 shadow-sm"></div>
                            
                            <div class="font-bold text-slate-800 flex items-center justify-between">
                                <span class="capitalize text-teal-700"><?= esc($log['action']) ?></span>
                                <span class="text-[9px] text-slate-400 font-normal"><?= date('H:i', strtotime($log['created_at'])) ?></span>
                            </div>
                            
                            <p class="text-[10px] text-slate-500 mt-0.5 leading-relaxed"><?= esc($log['details']) ?></p>
                            
                            <div class="mt-0.5 text-[9px] text-slate-400 font-medium">
                                <span><?= date('d M Y', strtotime($log['created_at'])) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
