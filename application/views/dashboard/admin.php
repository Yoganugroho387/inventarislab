<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Admin Dashboard View
 */
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Stats Widgets Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <!-- Total Items -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow duration-200">
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Katalog Barang</span>
            <span class="text-2xl font-bold text-slate-800"><?= $totalItems ?></span>
        </div>
        <div class="p-3 bg-slate-100 text-slate-600 rounded-lg">
            <i data-lucide="box" class="w-5 h-5"></i>
        </div>
    </div>

    <!-- Borrowed Quantity -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow duration-200">
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Barang Sedang Dipinjam</span>
            <span class="text-2xl font-bold text-teal-700"><?= $totalBorrowed ?></span>
        </div>
        <div class="p-3 bg-teal-50 text-teal-600 rounded-lg">
            <i data-lucide="clipboard-list" class="w-5 h-5"></i>
        </div>
    </div>

    <!-- Damaged items -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow duration-200">
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Barang Rusak</span>
            <span class="text-2xl font-bold <?= $totalDamaged > 0 ? 'text-rose-600' : 'text-slate-800' ?>"><?= $totalDamaged ?></span>
        </div>
        <div class="p-3 <?= $totalDamaged > 0 ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-500' ?> rounded-lg">
            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
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

<!-- Visual Analytics Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Trend Peminjaman (Line Chart) -->
    <div class="lg:col-span-2 bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex flex-col justify-between">
        <div>
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 flex items-center">
                <i data-lucide="trending-up" class="w-4 h-4 mr-1.5 text-teal-650"></i>
                Tren Peminjaman Bulanan
            </h3>
        </div>
        <div class="h-64">
            <canvas id="loanTrendChart"></canvas>
        </div>
    </div>
    
    <!-- Rasio Kondisi Barang (Doughnut Chart) -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex flex-col justify-between">
        <div>
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 flex items-center">
                <i data-lucide="pie-chart" class="w-4 h-4 mr-1.5 text-teal-650"></i>
                Status Kondisi Barang
            </h3>
        </div>
        <div class="h-64 relative flex items-center justify-center">
            <canvas id="conditionRatioChart"></canvas>
        </div>
    </div>
</div>

<!-- Main Dashboard Grid Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Area (2/3 Width) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- 1. Pending Approvals list -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Persetujuan Peminjaman Tertunda</h3>
                </div>
                <span class="text-[10px] font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded border border-amber-200">
                    PENDING: <?= count($pendingBorrowings) ?>
                </span>
            </div>

            <div class="overflow-x-auto">
                <?php if (empty($pendingBorrowings)): ?>
                    <div class="p-8 text-center">
                        <p class="text-xs text-slate-500 font-medium">Tidak ada permohonan peminjaman yang menunggu persetujuan.</p>
                    </div>
                <?php else: ?>
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-[10px] font-semibold uppercase bg-slate-100/50 text-slate-500">
                                <th class="px-5 py-2.5">ID</th>
                                <th class="px-5 py-2.5">Nama Peminjam</th>
                                <th class="px-5 py-2.5">Barang</th>
                                <th class="px-5 py-2.5">Batas Kembali</th>
                                <th class="px-5 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($pendingBorrowings as $pb): ?>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-5 py-3 font-bold text-slate-700">#<?= $pb['id'] ?></td>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-slate-800"><?= esc($pb['borrower_name']) ?></span>
                                            <span class="text-[9px] text-slate-400 font-medium">Oleh: <?= esc($pb['creator_name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-slate-600 font-medium">
                                        <span class="truncate max-w-[150px] block" title="<?php 
                                            $itemNames = array_map(function($i) { return $i['item_name'] . ' (' . $i['quantity'] . ')'; }, $pb['items']);
                                            echo esc(implode(', ', $itemNames));
                                        ?>">
                                            <?= esc($pb['items'][0]['item_name']) ?> (x<?= $pb['items'][0]['quantity'] ?>)
                                            <?php if (count($pb['items']) > 1): ?>
                                                <span class="text-[9px] text-teal-600 block font-semibold">+<?= count($pb['items']) - 1 ?> barang lagi</span>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-slate-500 font-medium"><?= date('d/m/y', strtotime($pb['due_date'])) ?></td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="<?= url('/admin/borrowings/view/' . $pb['id']) ?>" 
                                           class="inline-flex items-center justify-center px-2 py-1 border border-slate-300 text-[10px] font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
                                            Tinjau
                                            <i data-lucide="arrow-right" class="w-3 h-3 ml-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- 2. Low stock items warning list -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center space-x-2">
                    <i data-lucide="alert-octagon" class="w-4 h-4 text-amber-500"></i>
                    <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Notifikasi Stok Bahan Menipis</h3>
                </div>
            </div>

            <div class="overflow-x-auto">
                <?php if (empty($lowStockItems)): ?>
                    <div class="p-8 text-center bg-white">
                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 mb-1.5">
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">Semua stok bahan habis pakai dalam kondisi aman.</p>
                    </div>
                <?php else: ?>
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-[10px] font-semibold uppercase bg-slate-100/50 text-slate-500">
                                <th class="px-5 py-2.5">Kode</th>
                                <th class="px-5 py-2.5">Nama Barang</th>
                                <th class="px-5 py-2.5">Lokasi</th>
                                <th class="px-5 py-2.5 w-24">Stok Saat Ini</th>
                                <th class="px-5 py-2.5 w-24">Batas Min</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach (array_slice($lowStockItems, 0, 5) as $lsi): ?>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-5 py-3 font-semibold text-slate-700"><?= esc($lsi['code']) ?></td>
                                    <td class="px-5 py-3 font-medium text-slate-800"><?= esc($lsi['name']) ?></td>
                                    <td class="px-5 py-3 text-slate-500"><?= esc($lsi['location_name']) ?></td>
                                    <td class="px-5 py-3">
                                        <?php if ($lsi['stock'] <= 0): ?>
                                            <span class="text-rose-600 font-bold bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200">KOSONG (0)</span>
                                        <?php else: ?>
                                            <span class="text-amber-700 font-bold bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200"><?= $lsi['stock'] ?> <?= esc($lsi['unit_name']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-5 py-3 text-slate-400 font-medium"><?= $lsi['minimum_stock'] ?> <?= esc($lsi['unit_name']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Area: Logs Timeline (1/3 Width) -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
        <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between flex-shrink-0">
            <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Aktivitas Sistem Terbaru</h3>
        </div>

        <div class="p-5 overflow-y-auto max-h-[460px] flex-1">
            <?php if (empty($recentLogs)): ?>
                <p class="text-xs text-slate-500 font-medium text-center py-6">Belum ada catatan aktivitas.</p>
            <?php else: ?>
                <!-- Vertical Timeline -->
                <div class="relative pl-4 border-l border-slate-200 space-y-5 text-xs text-slate-600">
                    <?php foreach ($recentLogs as $log): ?>
                        <div class="relative">
                            <!-- Bullet point pointer -->
                            <div class="absolute -left-[20.5px] top-0.5 w-3 h-3 rounded-full border-2 bg-white border-teal-600 shadow-sm"></div>
                            
                            <div class="font-semibold text-slate-900 flex items-center justify-between">
                                <span class="capitalize text-teal-700 font-bold"><?= esc($log['action']) ?></span>
                                <span class="text-[9px] text-slate-400 font-normal"><?= date('H:i', strtotime($log['created_at'])) ?></span>
                            </div>
                            
                            <p class="text-[11px] text-slate-500 mt-1 leading-relaxed"><?= esc($log['details']) ?></p>
                            
                            <div class="mt-1 flex items-center justify-between text-[9px] text-slate-400 font-medium">
                                <span>User: <strong class="text-slate-600 font-semibold"><?= esc($log['user_name'] ?: 'Guest') ?></strong></span>
                                <span><?= date('d M y', strtotime($log['created_at'])) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Line Chart: Tren Peminjaman
    const ctxTrend = document.getElementById('loanTrendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartMonths) ?>,
            datasets: [{
                label: 'Jumlah Transaksi',
                data: <?= json_encode($chartMonthlyLoans) ?>,
                borderColor: '#0d9488',
                backgroundColor: 'rgba(13, 148, 136, 0.05)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#0d9488'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#64748b', font: { family: 'Inter', size: 10 } },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    ticks: { color: '#64748b', font: { family: 'Inter', size: 10 } },
                    grid: { display: false }
                }
            }
        }
    });

    // 2. Doughnut Chart: Kondisi Barang
    const ctxRatio = document.getElementById('conditionRatioChart').getContext('2d');
    new Chart(ctxRatio, {
        type: 'doughnut',
        data: {
            labels: ['Tersedia', 'Rusak', 'Servis', 'Habis'],
            datasets: [{
                data: [
                    <?= $chartConditions['tersedia'] ?>,
                    <?= $chartConditions['rusak'] ?>,
                    <?= $chartConditions['maintenance'] ?>,
                    <?= $chartConditions['habis'] ?>
                ],
                backgroundColor: ['#0d9488', '#e11d48', '#d97706', '#64748b'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        color: '#475569',
                        font: { family: 'Inter', size: 10, weight: 600 }
                    }
                }
            },
            cutout: '70%'
        }
    });
});
</script>
