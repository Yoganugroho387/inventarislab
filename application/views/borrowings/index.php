<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Borrowings Index View
 */
?>
<!-- Top Toolbar -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <!-- Filter status tabs -->
    <div class="flex flex-wrap gap-1.5 p-1 bg-slate-200/60 rounded border border-slate-200 w-fit text-xs">
        <a href="<?= url($prefix . '/borrowings') ?>" 
           class="px-3 py-1.5 rounded transition font-medium <?= empty($filters['status']) ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-600 hover:text-slate-800' ?>">
            Semua
        </a>
        <a href="<?= url($prefix . '/borrowings?status=menunggu') ?>" 
           class="px-3 py-1.5 rounded transition font-medium <?= $filters['status'] === 'menunggu' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-600 hover:text-slate-800' ?>">
            Menunggu
        </a>
        <a href="<?= url($prefix . '/borrowings?status=dipinjam') ?>" 
           class="px-3 py-1.5 rounded transition font-medium <?= $filters['status'] === 'dipinjam' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-600 hover:text-slate-800' ?>">
            Dipinjam
        </a>
        <a href="<?= url($prefix . '/borrowings?status=dikembalikan') ?>" 
           class="px-3 py-1.5 rounded transition font-medium <?= $filters['status'] === 'dikembalikan' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-600 hover:text-slate-800' ?>">
            Kembali
        </a>
        <a href="<?= url($prefix . '/borrowings?status=ditolak') ?>" 
           class="px-3 py-1.5 rounded transition font-medium <?= $filters['status'] === 'ditolak' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-600 hover:text-slate-800' ?>">
            Ditolak
        </a>
    </div>

    <!-- Create Button -->
    <div class="flex items-center gap-2">
        <a href="<?= url($prefix . '/borrowings/calendar') ?>" 
           class="inline-flex items-center justify-center px-3.5 py-2 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition duration-150">
            <i data-lucide="calendar" class="w-4 h-4 mr-1.5 text-slate-550"></i>
            Kalender Sirkulasi
        </a>
        <a href="<?= url($prefix . '/borrowings/create') ?>" 
           class="inline-flex items-center justify-center px-3.5 py-2 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm transition duration-150">
            <i data-lucide="plus-circle" class="w-4 h-4 mr-1.5"></i>
            Buat Peminjaman
        </a>
    </div>
</div>

<!-- Search Panel -->
<div class="bg-white p-4 rounded border border-slate-200 shadow-sm mb-6">
    <form action="<?= url($prefix . '/borrowings') ?>" method="GET" class="flex items-center space-x-2">
        <?php if (!empty($filters['status'])): ?>
            <input type="hidden" name="status" value="<?= esc($filters['status']) ?>">
        <?php endif; ?>
        
        <div class="relative rounded shadow-sm flex-1 max-w-sm">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                <i data-lucide="search" class="h-3.5 w-3.5 text-slate-400"></i>
            </div>
            <input type="text" name="search" id="search" value="<?= esc($filters['search']) ?>"
                   class="block w-full pl-8 pr-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                   placeholder="Cari nama atau identitas peminjam...">
        </div>
        <button type="submit" 
                class="inline-flex items-center justify-center px-4 py-1.5 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
            Cari
        </button>
        <a href="<?= url($prefix . '/borrowings' . (!empty($filters['status']) ? '?status=' . $filters['status'] : '')) ?>" 
           class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-300 text-xs font-medium rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition duration-150">
            Reset
        </a>
    </form>
</div>

<!-- Borrowings Table -->
<div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col">
    <div class="px-6 py-4 border-b border-slate-150 bg-slate-50 flex-shrink-0">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Daftar Transaksi Peminjaman</h2>
    </div>

    <div class="overflow-x-auto">
        <?php if (empty($borrowings)): ?>
            <!-- Empty state -->
            <div class="p-12 text-center bg-white">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                    <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-800">Belum ada transaksi peminjaman</h3>
                <p class="text-xs text-slate-500 mt-1">Belum ada aktivitas sirkulasi yang sesuai dengan kriteria filter saat ini.</p>
                <a href="<?= url($prefix . '/borrowings/create') ?>" class="mt-4 inline-flex items-center text-xs font-semibold text-teal-600 hover:text-teal-800 transition">
                    Buat peminjaman baru sekarang <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1"></i>
                </a>
            </div>
        <?php else: ?>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase bg-slate-100 text-slate-500">
                        <th class="px-6 py-3 w-20">ID</th>
                        <th class="px-6 py-3">Nama Peminjam</th>
                        <th class="px-6 py-3 w-32">Identitas</th>
                        <th class="px-6 py-3 w-40">Tanggal Pinjam</th>
                        <th class="px-6 py-3 w-40">Batas Kembali</th>
                        <th class="px-6 py-3">Daftar Barang</th>
                        <th class="px-6 py-3 w-28">Status</th>
                        <th class="px-6 py-3 w-20 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($borrowings as $b): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-3.5 font-bold text-slate-700">#<?= $b['id'] ?></td>
                            <td class="px-6 py-3.5">
                                <div class="flex flex-col">
                                    <span class="font-medium text-slate-800"><?= esc($b['borrower_name']) ?></span>
                                    <span class="text-[10px] text-slate-400 capitalize"><?= esc($b['borrower_type']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-slate-600 font-semibold"><?= esc($b['borrower_identity']) ?></td>
                            <td class="px-6 py-3.5 text-xs text-slate-500 font-medium"><?= date('d M Y', strtotime($b['loan_date'])) ?></td>
                            <td class="px-6 py-3.5 text-xs font-medium <?= ($b['status'] === 'dipinjam' && strtotime($b['due_date']) < time()) ? 'text-rose-600 font-bold' : 'text-slate-500' ?>">
                                <?= date('d M Y', strtotime($b['due_date'])) ?>
                                <?php if ($b['status'] === 'dipinjam' && strtotime($b['due_date']) < time()): ?>
                                    <span class="block text-[9px] text-rose-500 uppercase tracking-tighter mt-0.5">Terlambat</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-slate-600">
                                <div class="max-w-[220px] truncate" title="<?php 
                                    $itemNames = array_map(function($i) { return $i['item_name'] . ' (' . $i['quantity'] . ')'; }, $b['items']);
                                    echo esc(implode(', ', $itemNames));
                                ?>">
                                    <?php if (count($b['items']) == 1): ?>
                                        <?= esc($b['items'][0]['item_name']) ?>
                                        <span class="text-[10px] text-slate-400 font-medium">(x<?= $b['items'][0]['quantity'] ?>)</span>
                                    <?php else: ?>
                                        <?= esc($b['items'][0]['item_name']) ?>
                                        <span class="text-[10px] text-slate-400 font-medium">(x<?= $b['items'][0]['quantity'] ?>)</span>
                                        <span class="block text-[10px] text-teal-650 font-semibold">+<?= count($b['items']) - 1 ?> barang lainnya</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <?php if ($b['status'] === 'menunggu'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 uppercase">
                                        Menunggu
                                    </span>
                                <?php elseif ($b['status'] === 'disetujui'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                                        Disetujui
                                    </span>
                                <?php elseif ($b['status'] === 'dipinjam'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-250 uppercase animate-pulse">
                                        Dipinjam
                                    </span>
                                <?php elseif ($b['status'] === 'dikembalikan'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-500 border border-slate-200 uppercase">
                                        Selesai
                                    </span>
                                <?php elseif ($b['status'] === 'ditolak'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 uppercase">
                                        Ditolak
                                    </span>
                                <?php elseif ($b['status'] === 'terlambat'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-100 text-rose-800 border border-rose-200 uppercase">
                                        Terlambat
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3.5 text-right whitespace-nowrap space-x-2">
                                <!-- View Detail -->
                                <a href="<?= url($prefix . '/borrowings/view/' . $b['id']) ?>" 
                                   class="inline-flex items-center text-slate-400 hover:text-teal-650 transition" 
                                   title="Lihat Detail">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </a>
                                
                                <!-- Return Quick Action -->
                                <?php if ($b['status'] === 'dipinjam' || $b['status'] === 'terlambat'): ?>
                                    <a href="<?= url($prefix . '/returns/create/' . $b['id']) ?>" 
                                       class="inline-flex items-center text-slate-400 hover:text-emerald-650 transition" 
                                       title="Proses Pengembalian">
                                        <i data-lucide="check-square" class="w-3.5 h-3.5"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
