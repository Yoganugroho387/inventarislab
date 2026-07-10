<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Create Disbursement Form View
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="max-w-xl bg-white p-6 rounded border border-slate-200 shadow-sm">
    <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Catat Pengeluaran Bahan Habis Pakai</h2>
        <a href="<?= url($prefix . '/disbursements') ?>" class="text-xs text-teal-600 hover:text-teal-800 flex items-center font-semibold">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1"></i>
            Kembali
        </a>
    </div>

    <form action="<?= url($prefix . '/disbursements/store') ?>" method="POST" autocomplete="off" onsubmit="return validateForm()">
        <?= csrf_field() ?>

        <div class="space-y-4">
            <!-- Select Consumable Item -->
            <div>
                <label for="item_id" class="block text-xs font-semibold text-slate-700">Pilih Bahan Habis Pakai</label>
                <select name="item_id" id="item_id" required onchange="updateStockLimits(this)"
                        class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
                    <option value="">-- Pilih Bahan --</option>
                    <?php foreach ($items as $item): ?>
                        <option value="<?= $item['id'] ?>" data-stock="<?= $item['stock'] ?>" data-unit="<?= esc($item['unit_name']) ?>">
                            <?= esc($item['code']) ?> - <?= esc($item['name']) ?> (Stok: <?= $item['stock'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <span id="stock-info" class="text-[10px] text-teal-700 mt-1 block font-medium"></span>
                <?php if (error('item_id')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('item_id')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Quantity to disburse -->
            <div>
                <label for="quantity" class="block text-xs font-semibold text-slate-700">Jumlah Pengeluaran</label>
                <input type="number" name="quantity" id="quantity" min="1" required onchange="validateQtyInput(this)" onkeyup="validateQtyInput(this)"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                       placeholder="Jumlah barang yang dikeluarkan">
                <?php if (error('quantity')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('quantity')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Receiver Name -->
            <div>
                <label for="receiver_name" class="block text-xs font-semibold text-slate-700">Nama Penerima</label>
                <input type="text" name="receiver_name" id="receiver_name" required
                       value="<?= esc(old('receiver_name')) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                       placeholder="Nama lengkap penerima">
                <?php if (error('receiver_name')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('receiver_name')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Receiver Identity -->
            <div>
                <label for="receiver_identity" class="block text-xs font-semibold text-slate-700">NIM / NIP / Identitas Penerima</label>
                <input type="text" name="receiver_identity" id="receiver_identity" required
                       value="<?= esc(old('receiver_identity')) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                       placeholder="Nomor Induk Mahasiswa/Pegawai">
                <?php if (error('receiver_identity')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('receiver_identity')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Purpose -->
            <div>
                <label for="purpose" class="block text-xs font-semibold text-slate-700">Keperluan / Keterangan</label>
                <textarea name="purpose" id="purpose" rows="3"
                          class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                          placeholder="Keterangan keperluan pengeluaran bahan habis pakai..."><?= esc(old('purpose')) ?></textarea>
                <?php if (error('purpose')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('purpose')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center space-x-2 pt-4 border-t border-slate-100">
                <button type="submit" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
                    <i data-lucide="check" class="w-4 h-4 mr-1.5"></i>
                    Simpan Catatan
                </button>
                <a href="<?= url($prefix . '/disbursements') ?>" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm focus:outline-none transition duration-150">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    function updateStockLimits(selectElement) {
        const qtyInput = document.getElementById('quantity');
        const stockInfo = document.getElementById('stock-info');
        
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        
        if (selectedOption.value === "") {
            stockInfo.textContent = "";
            qtyInput.max = "";
            return;
        }
        
        const stock = parseInt(selectedOption.getAttribute('data-stock'));
        const unit = selectedOption.getAttribute('data-unit');
        
        qtyInput.max = stock;
        stockInfo.innerHTML = `Stok tersedia saat ini: <strong>${stock} ${unit}</strong>`;
        
        validateQtyInput(qtyInput);
    }

    function validateQtyInput(input) {
        const max = parseInt(input.max);
        const val = parseInt(input.value);
        
        if (max && val > max) {
            input.value = max;
        }
        if (val < 1 || isNaN(val)) {
            input.value = 1;
        }
    }

    function validateForm() {
        const itemSelect = document.getElementById('item_id');
        if (itemSelect.value === "") {
            alert("Silakan pilih bahan yang akan dikeluarkan.");
            return false;
        }
        return true;
    }
</script>
