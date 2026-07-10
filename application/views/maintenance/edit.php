<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Edit/Resolve Maintenance View
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="max-w-xl bg-white p-6 rounded border border-slate-200 shadow-sm">
    <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Pemeriksaan Hasil Pemeliharaan Aset</h2>
        <a href="<?= url($prefix . '/maintenance') ?>" class="text-xs text-teal-600 hover:text-teal-800 flex items-center font-semibold">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1"></i>
            Kembali
        </a>
    </div>

    <!-- Info Card: Asset & Issue -->
    <div class="mb-5 p-4 bg-slate-50 border border-slate-200 rounded-lg text-xs space-y-3">
        <div class="flex items-center space-x-3 pb-3 border-b border-slate-200">
            <?php if ($log['item_image']): ?>
                <img src="<?= base_url('uploads/items/' . $log['item_image']) ?>" class="w-10 h-10 rounded border object-cover">
            <?php else: ?>
                <div class="w-10 h-10 bg-slate-100 border text-slate-400 rounded flex items-center justify-center flex-shrink-0">
                    <i data-lucide="image" class="w-5 h-5"></i>
                </div>
            <?php endif; ?>
            <div>
                <h4 class="font-bold text-slate-900"><?= esc($log['item_name']) ?></h4>
                <p class="text-slate-500 font-semibold mt-0.5">Kode: <span class="text-slate-700"><?= esc($log['item_code']) ?></span> | Lokasi: <span class="text-slate-700"><?= esc($log['location_name']) ?></span></p>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="block text-slate-450 uppercase text-[9px] font-bold">Tanggal Mulai Servis</span>
                <span class="font-semibold text-slate-700 mt-0.5 block"><?= date('d F Y', strtotime($log['start_date'])) ?></span>
            </div>
            <div>
                <span class="block text-slate-450 uppercase text-[9px] font-bold">Teknisi / Vendor</span>
                <span class="font-semibold text-slate-700 mt-0.5 block"><?= esc($log['vendor_name'] ?: '-') ?></span>
            </div>
        </div>

        <div>
            <span class="block text-slate-450 uppercase text-[9px] font-bold">Keluhan Kerusakan</span>
            <p class="text-slate-750 mt-1 font-medium bg-white p-2.5 rounded border border-slate-200 leading-relaxed"><?= esc($log['issue_description']) ?></p>
        </div>
    </div>

    <!-- Resolution Form -->
    <form action="<?= url($prefix . '/maintenance/update/' . $log['id']) ?>" method="POST" autocomplete="off">
        <?= csrf_field() ?>

        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-xs font-semibold text-slate-700">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" required
                           value="<?= esc(old('end_date', date('Y-m-d'))) ?>"
                           class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
                    <?php if (error('end_date')): ?>
                        <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('end_date')) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Result Status -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700">Hasil Perbaikan</label>
                    <select name="status" id="status" required
                            class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
                        <option value="selesai" <?= old('status') === 'selesai' ? 'selected' : '' ?>>Selesai Diperbaiki (Kembali Tersedia)</option>
                        <option value="tidak_bisa_diperbaiki" <?= old('status') === 'tidak_bisa_diperbaiki' ? 'selected' : '' ?>>Rusak Total / Afkir (Rusak Permanen)</option>
                    </select>
                    <?php if (error('status')): ?>
                        <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('status')) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Repair Cost -->
            <div>
                <label for="cost" class="block text-xs font-semibold text-slate-700">Biaya Perbaikan (Rupiah)</label>
                <input type="number" name="cost" id="cost" min="0" required
                       value="<?= esc(old('cost', '0')) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                       placeholder="Contoh: 150000">
                <span class="text-[10px] text-slate-400 mt-1 block">Tulis 0 jika perbaikan garansi atau tanpa biaya.</span>
                <?php if (error('cost')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('cost')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Repair Action (Tindakan) -->
            <div>
                <label for="repair_action" class="block text-xs font-semibold text-slate-700">Tindakan Perbaikan</label>
                <textarea name="repair_action" id="repair_action" rows="3" required
                          class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                          placeholder="Jelaskan komponen apa yang diganti atau diperbaiki..."><?= esc(old('repair_action')) ?></textarea>
                <?php if (error('repair_action')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('repair_action')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center space-x-2 pt-4 border-t border-slate-100">
                <button type="submit" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
                    <i data-lucide="check-circle-2" class="w-4 h-4 mr-1.5"></i>
                    Simpan Catatan Servis
                </button>
                <a href="<?= url($prefix . '/maintenance') ?>" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm focus:outline-none transition duration-150">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
