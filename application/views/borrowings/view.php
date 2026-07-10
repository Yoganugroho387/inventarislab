<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Borrowing Details View
 */
?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Area: Invoice details (2/3 Width) -->
    <div class="lg:col-span-2 bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <!-- Header status -->
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-slate-700 uppercase">Peminjaman #<?= $borrowing['id'] ?></span>
            </div>
            
            <div>
                <?php if ($borrowing['status'] === 'menunggu'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-250 uppercase">Menunggu Persetujuan</span>
                <?php elseif ($borrowing['status'] === 'disetujui'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 uppercase">Disetujui</span>
                <?php elseif ($borrowing['status'] === 'dipinjam'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-250 uppercase animate-pulse">Sedang Dipinjam</span>
                <?php elseif ($borrowing['status'] === 'dikembalikan'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200 uppercase">Selesai Dikembalikan</span>
                <?php elseif ($borrowing['status'] === 'ditolak'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 uppercase">Ditolak</span>
                <?php elseif ($borrowing['status'] === 'terlambat'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200 uppercase">Terlambat Kembali</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Body Details -->
        <div class="p-6 space-y-6 flex-1">
            <!-- Grid info: borrower & dates -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-slate-100 text-xs">
                <!-- Column 1: Borrower info -->
                <div>
                    <h3 class="text-slate-400 font-semibold uppercase text-[9px] mb-2 tracking-wider">Identitas Peminjam</h3>
                    <div class="space-y-1.5 text-slate-800">
                        <p class="text-sm font-bold text-slate-900"><?= esc($borrowing['borrower_name']) ?></p>
                        <p class="font-semibold text-slate-500">ID / NIM: <span class="text-slate-700"><?= esc($borrowing['borrower_identity']) ?></span></p>
                        <?php if (!empty($borrowing['borrower_phone'])): ?>
                            <p class="font-semibold text-slate-500">WhatsApp: <span class="text-teal-700 font-bold"><?= esc($borrowing['borrower_phone']) ?></span></p>
                        <?php endif; ?>
                        <p class="font-medium text-slate-500">Status: <span class="capitalize text-slate-700"><?= esc($borrowing['borrower_type']) ?></span></p>
                    </div>
                </div>

                <!-- Column 2: Date details -->
                <div>
                    <h3 class="text-slate-400 font-semibold uppercase text-[9px] mb-2 tracking-wider">Linimasa Transaksi</h3>
                    <div class="space-y-1.5 text-slate-700">
                        <p class="font-medium">Tanggal Pinjam: <span class="font-semibold text-slate-900"><?= date('d F Y', strtotime($borrowing['loan_date'])) ?></span></p>
                        <p class="font-medium">Batas Pengembalian: <span class="font-semibold text-slate-900"><?= date('d F Y', strtotime($borrowing['due_date'])) ?></span></p>
                        
                        <?php if (!empty($borrowing['return_date'])): ?>
                            <p class="font-medium">Tanggal Pengembalian: <span class="font-semibold text-emerald-700"><?= date('d F Y', strtotime($borrowing['return_date'])) ?></span></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div>
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-3">Daftar Rincian Barang</h3>
                <div class="border border-slate-200 rounded overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-[10px] font-semibold bg-slate-50 text-slate-500 uppercase">
                                <th class="px-4 py-2.5 w-12">#</th>
                                <th class="px-4 py-2.5">Kode Barang</th>
                                <th class="px-4 py-2.5">Nama Barang</th>
                                <th class="px-4 py-2.5 w-24">Jumlah</th>
                                <th class="px-4 py-2.5">Status Pengembalian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $no = 1; foreach ($borrowing['items'] as $item): ?>
                                <tr>
                                    <td class="px-4 py-3 text-slate-400 font-medium"><?= $no++ ?></td>
                                    <td class="px-4 py-3 font-semibold text-slate-700"><?= esc($item['item_code']) ?></td>
                                    <td class="px-4 py-3 text-slate-800">
                                        <div class="flex flex-col">
                                            <span class="font-medium"><?= esc($item['item_name']) ?></span>
                                            <span class="text-[9px] text-slate-400 capitalize"><?= $item['item_type'] === 'inventaris' ? 'inventaris' : 'habis pakai' ?></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-bold text-slate-800"><?= $item['quantity'] ?> <span class="text-[10px] text-slate-400 font-normal"><?= esc($item['unit_name']) ?></span></td>
                                    <td class="px-4 py-3">
                                        <?php if (!empty($item['return_date'])): ?>
                                            <div class="flex flex-col text-[10px]">
                                                <span class="text-emerald-700 font-bold flex items-center">
                                                    <i data-lucide="check" class="w-3.5 h-3.5 mr-0.5"></i>
                                                    Kembali (<?= date('d/m/y', strtotime($item['return_date'])) ?>)
                                                </span>
                                                <span class="text-slate-450 mt-0.5">
                                                    Kondisi: <span class="capitalize font-semibold text-slate-700"><?= esc($item['return_condition']) ?></span>
                                                    <?= !empty($item['return_notes']) ? ' | ' . esc($item['return_notes']) : '' ?>
                                                </span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-slate-400 font-semibold text-[10px] flex items-center">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-350 mr-1.5"></span>
                                                Belum kembali
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes from form -->
            <?php if (!empty($borrowing['notes'])): ?>
                <div class="bg-slate-50 p-4 rounded border border-slate-200 text-xs">
                    <span class="block text-slate-400 font-semibold uppercase text-[9px] mb-1.5">Keterangan / Catatan Peminjam</span>
                    <p class="text-slate-700 leading-relaxed"><?= esc($borrowing['notes']) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Area: Actions / Approval Logs (1/3 Width) -->
    <div class="space-y-6">
        <!-- Audit Trail / Creators Card -->
        <div class="bg-white p-5 rounded border border-slate-200 shadow-sm text-xs space-y-4">
            <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-100 pb-2 mb-3">Informasi Sistem</h3>
            
            <div>
                <span class="block text-slate-400 uppercase text-[9px] font-semibold">Diajukan Oleh</span>
                <span class="font-bold text-slate-800 mt-0.5 block"><?= esc($borrowing['creator_name']) ?> <span class="text-[10px] text-slate-400 font-normal">(@<?= esc($borrowing['creator_username']) ?>)</span></span>
            </div>

            <div>
                <span class="block text-slate-400 uppercase text-[9px] font-semibold">Tanggal Pengajuan</span>
                <span class="font-semibold text-slate-700 mt-0.5 block"><?= date('d F Y, H:i', strtotime($borrowing['created_at'])) ?></span>
            </div>

            <?php if (!empty($borrowing['approved_by'])): ?>
                <div>
                    <span class="block text-slate-400 uppercase text-[9px] font-semibold">Disetujui Oleh</span>
                    <span class="font-bold text-slate-800 mt-0.5 block"><?= esc($borrowing['approver_name']) ?> <span class="text-[10px] text-slate-400 font-normal">(@<?= esc($borrowing['approver_username']) ?>)</span></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Document Print Card -->
        <?php if ($borrowing['status'] === 'disetujui' || $borrowing['status'] === 'dipinjam' || $borrowing['status'] === 'terlambat' || $borrowing['status'] === 'dikembalikan'): ?>
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm text-xs space-y-3.5">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-100 pb-2 mb-3">Dokumen Peminjaman</h3>
                <p class="text-[11px] text-slate-500 leading-relaxed font-medium">Cetak surat jalan resmi peminjaman untuk dibawa keluar kampus atau izin tertulis acara kampus.</p>
                <div class="pt-1">
                    <a href="<?= url($prefix . '/borrowings/print/' . $borrowing['id']) ?>" target="_blank"
                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm focus:outline-none transition duration-150">
                        <i data-lucide="printer" class="w-4 h-4 mr-1.5 text-slate-500"></i>
                        Cetak Surat Jalan
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Administrative Actions Card (Admin approval triggers) -->
        <?php if ($borrowing['status'] === 'menunggu' && $role === 'admin'): ?>
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm text-xs space-y-4">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-100 pb-2 mb-3">Persetujuan Transaksi</h3>
                
                <p class="text-[11px] text-slate-500 leading-relaxed font-medium">Permohonan peminjaman diajukan oleh laboran/petugas dan memerlukan persetujuan Administrator utama agar stok barang dapat diproses keluar.</p>
                
                <div class="flex flex-col space-y-2 pt-2">
                    <!-- Approve trigger -->
                    <form action="<?= url('/admin/borrowings/approve/' . $borrowing['id']) ?>" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menyetujui peminjaman ini? Stok barang akan segera dipotong.');">
                        <?= csrf_field() ?>
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
                            <i data-lucide="check" class="w-4 h-4 mr-1.5"></i>
                            Setujui Peminjaman
                        </button>
                    </form>

                    <!-- Reject trigger -->
                    <form action="<?= url('/admin/borrowings/reject/' . $borrowing['id']) ?>" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menolak permohonan peminjaman ini?');">
                        <?= csrf_field() ?>
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm focus:outline-none transition duration-150">
                            <i data-lucide="x" class="w-4 h-4 mr-1.5 text-slate-400"></i>
                            Tolak Permohonan
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Active Return Actions trigger -->
        <?php if (($borrowing['status'] === 'dipinjam' || $borrowing['status'] === 'terlambat')): ?>
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm text-xs space-y-3.5">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-100 pb-2 mb-3">Tindakan Sirkulasi</h3>
                
                <p class="text-[11px] text-slate-500 leading-relaxed font-medium">Barang saat ini berada di tangan peminjam. Input kembali barang jika peminjam telah menyerahkannya kembali ke lab.</p>
                
                <div class="pt-1">
                    <a href="<?= url($prefix . '/returns/create/' . $borrowing['id']) ?>" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm focus:outline-none transition duration-150">
                        <i data-lucide="check-square" class="w-4 h-4 mr-1.5"></i>
                        Proses Pengembalian
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
