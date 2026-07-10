<?php
/**
 * View Bebas Lab Index Page
 * Developed by Yoga Nugroho
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="max-w-2xl bg-white p-6 rounded border border-slate-200 shadow-sm mb-6">
    <div class="border-b border-slate-100 pb-4 mb-5">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Pengecekan Bebas Tanggungan Laboratorium</h2>
    </div>

    <!-- Search Form -->
    <form action="<?= url($prefix . '/bebas-lab') ?>" method="GET" autocomplete="off" class="flex items-end gap-3.5">
        <div class="flex-1">
            <label for="identity" class="block text-xs font-semibold text-slate-700">Nomor Induk Mahasiswa (NIM) / NIP / KTP</label>
            <div class="mt-1 relative rounded shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <i data-lucide="user" class="h-3.5 w-3.5 text-slate-400"></i>
                </div>
                <input type="text" name="identity" id="identity" required value="<?= esc($identity) ?>"
                       class="block w-full pl-8 pr-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Masukkan NIM/NIP untuk diverifikasi...">
            </div>
        </div>
        <button type="submit" 
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
            <i data-lucide="search" class="w-3.5 h-3.5 mr-1.5"></i>
            Periksa Tanggungan
        </button>
    </form>
</div>

<!-- Search Results -->
<?php if ($is_searched): ?>
    <div class="max-w-2xl bg-white p-6 rounded border border-slate-200 shadow-sm">
        <?php if ($is_free): ?>
            <!-- Clean / Free Status -->
            <div class="bg-emerald-50 border border-emerald-250 rounded-lg p-5 flex items-start space-x-4 mb-6">
                <div class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                    <i data-lucide="check-check" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-emerald-800">Dinyatakan Bebas Lab!</h3>
                    <p class="text-xs text-emerald-600 mt-1 font-medium leading-relaxed">
                        Mahasiswa dengan identitas ini tidak memiliki tanggungan peminjaman alat yang aktif di sistem.
                    </p>
                </div>
            </div>

            <!-- Profile Info & Print Button -->
            <div class="space-y-4">
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Informasi Mahasiswa</h3>
                    <div class="grid grid-cols-3 gap-y-2 text-xs">
                        <div class="text-slate-450 font-medium">NIM / NIP</div>
                        <div class="col-span-2 font-bold text-slate-800"><?= esc($identity) ?></div>

                        <div class="text-slate-455 font-medium">Nama Lengkap</div>
                        <div class="col-span-2 font-bold text-slate-700"><?= esc($student_info['borrower_name'] ?? 'Mahasiswa Baru / Belum Ada Transaksi') ?></div>

                        <div class="text-slate-455 font-medium">Status Peminjam</div>
                        <div class="col-span-2 capitalize"><span class="bg-slate-100 text-slate-650 px-2 py-0.5 rounded text-[10px] font-bold border border-slate-200"><?= esc($student_info['borrower_type'] ?? 'Umum') ?></span></div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                    <a href="<?= url($prefix . '/bebas-lab/print/' . urlencode($identity)) ?>" target="_blank"
                       class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition duration-150">
                        <i data-lucide="printer" class="w-4 h-4 mr-1.5"></i>
                        Cetak Surat Bebas Lab
                    </a>
                </div>
            </div>

        <?php else: ?>
            <!-- Unfree / Active Loans Status -->
            <div class="bg-rose-50 border border-rose-200 rounded-lg p-5 flex items-start space-x-4 mb-6">
                <div class="w-10 h-10 bg-rose-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-rose-800">Tanggungan Peminjaman Ditemukan!</h3>
                    <p class="text-xs text-rose-600 mt-1 font-medium leading-relaxed">
                        Mahasiswa ini masih meminjam barang dan belum dikembalikan. Surat bebas lab tidak dapat dicetak.
                    </p>
                </div>
            </div>

            <!-- Profile Info & Active Loans List -->
            <div class="space-y-4">
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Informasi Mahasiswa</h3>
                    <div class="grid grid-cols-3 gap-y-2 text-xs">
                        <div class="text-slate-455 font-medium">NIM / NIP</div>
                        <div class="col-span-2 font-bold text-slate-800"><?= esc($identity) ?></div>

                        <div class="text-slate-455 font-medium">Nama Lengkap</div>
                        <div class="col-span-2 font-bold text-slate-700"><?= esc($student_info['borrower_name']) ?></div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Daftar Transaksi Yang Belum Kembali</h3>
                    <div class="space-y-3">
                        <?php foreach ($active_loans as $loan): ?>
                            <div class="bg-slate-50 border border-slate-200 rounded p-4 text-xs">
                                <div class="flex items-center justify-between font-bold text-slate-850">
                                    <span>Transaksi Peminjaman #<?= $loan['id'] ?></span>
                                    <span class="text-[10px] px-2 py-0.5 rounded font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200">
                                        <?= esc($loan['status']) ?>
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mt-3 text-slate-500 font-medium leading-relaxed">
                                    <div>Tanggal Pinjam: <strong class="text-slate-700"><?= date('d M Y', strtotime($loan['loan_date'])) ?></strong></div>
                                    <div>Batas Kembali: <strong class="text-slate-700"><?= date('d M Y', strtotime($loan['due_date'])) ?></strong></div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-slate-150">
                                    <a href="<?= url($prefix . '/borrowings/view/' . $loan['id']) ?>" 
                                       class="text-teal-650 hover:text-teal-800 font-semibold flex items-center">
                                        Lihat Rincian Barang Pinjaman <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
