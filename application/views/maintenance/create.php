<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Create Maintenance Request View
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="max-w-xl bg-white p-6 rounded border border-slate-200 shadow-sm">
    <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Ajukan Perbaikan Aset</h2>
        <a href="<?= url($prefix . '/maintenance') ?>" class="text-xs text-teal-600 hover:text-teal-800 flex items-center font-semibold">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1"></i>
            Kembali
        </a>
    </div>

    <form action="<?= url($prefix . '/maintenance/store') ?>" method="POST" autocomplete="off" onsubmit="return validateForm()">
        <?= csrf_field() ?>

        <div class="space-y-4">
            <!-- Select Asset -->
            <div>
                <label for="item_id" class="block text-xs font-semibold text-slate-700">Pilih Aset Inventaris</label>
                <select name="item_id" id="item_id" required
                        class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
                    <option value="">-- Pilih Barang --</option>
                    <?php foreach ($items as $item): ?>
                        <option value="<?= $item['id'] ?>">
                            <?= esc($item['code']) ?> - <?= esc($item['name']) ?> (Kondisi: <?= esc($item['condition_status']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="text-[10px] text-slate-400 mt-1 block">Hanya menampilkan barang inventaris yang berstatus tersedia di lab atau rusak ringan.</span>
                <?php if (error('item_id')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('item_id')) ?></p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-xs font-semibold text-slate-700">Tanggal Mulai Servis</label>
                    <input type="date" name="start_date" id="start_date" required
                           value="<?= esc(old('start_date', date('Y-m-d'))) ?>"
                           class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
                    <?php if (error('start_date')): ?>
                        <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('start_date')) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Vendor Name -->
                <div>
                    <label for="vendor_name" class="block text-xs font-semibold text-slate-700">Nama Vendor / Reparatur (Opsional)</label>
                    <input type="text" name="vendor_name" id="vendor_name"
                           value="<?= esc(old('vendor_name')) ?>"
                           class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                           placeholder="Nama toko servis atau teknisi">
                    <?php if (error('vendor_name')): ?>
                        <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('vendor_name')) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Issue Description -->
            <div>
                <label for="issue_description" class="block text-xs font-semibold text-slate-700">Kerusakan / Masalah</label>
                <textarea name="issue_description" id="issue_description" rows="3" required
                          class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                          placeholder="Jelaskan detail gejala kerusakan atau servis berkala yang dibutuhkan..."><?= esc(old('issue_description')) ?></textarea>
                <?php if (error('issue_description')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('issue_description')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center space-x-2 pt-4 border-t border-slate-100">
                <button type="submit" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
                    <i data-lucide="check" class="w-4 h-4 mr-1.5"></i>
                    Kirim ke Pemeliharaan
                </button>
                <a href="<?= url($prefix . '/maintenance') ?>" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm focus:outline-none transition duration-150">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    function validateForm() {
        const itemSelect = document.getElementById('item_id');
        if (itemSelect.value === "") {
            alert("Silakan pilih aset inventaris terlebih dahulu.");
            return false;
        }
        return true;
    }
</script>
