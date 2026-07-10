<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Create Borrowing View
 */
?>
<div class="max-w-4xl bg-white p-6 rounded border border-slate-200 shadow-sm">
    <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Form Peminjaman Barang</h2>
        <a href="<?= url($prefix . '/borrowings') ?>" class="text-xs text-teal-600 hover:text-teal-800 flex items-center font-semibold">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1"></i>
            Kembali
        </a>
    </div>

    <form action="<?= url($prefix . '/borrowings/store') ?>" method="POST" autocomplete="off" onsubmit="return validateForm()">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left 1/3: Borrower Details Card -->
            <div class="space-y-4 p-5 bg-slate-50 border border-slate-200 rounded-lg h-fit">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-200 pb-2 mb-3">Data Peminjam</h3>
                
                <!-- Borrower Name -->
                <div>
                    <label for="borrower_name" class="block text-xs font-semibold text-slate-700">Nama Peminjam</label>
                    <input type="text" name="borrower_name" id="borrower_name" required
                           value="<?= esc(old('borrower_name')) ?>"
                           class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                           placeholder="Nama Dosen / Mhs / Staf">
                    <?php if (error('borrower_name')): ?>
                        <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('borrower_name')) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Borrower Identity -->
                <div>
                    <label for="borrower_identity" class="block text-xs font-semibold text-slate-700">NIM / NIP / Identitas</label>
                    <input type="text" name="borrower_identity" id="borrower_identity" required
                           value="<?= esc(old('borrower_identity')) ?>"
                           class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                           placeholder="Nomor Induk Mahasiswa/Pegawai">
                    <?php if (error('borrower_identity')): ?>
                        <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('borrower_identity')) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Borrower Phone (WhatsApp) -->
                <div>
                    <label for="borrower_phone" class="block text-xs font-semibold text-slate-700">Nomor WhatsApp</label>
                    <input type="text" name="borrower_phone" id="borrower_phone"
                           value="<?= esc(old('borrower_phone')) ?>"
                           class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                           placeholder="Contoh: 089685027530">
                    <?php if (error('borrower_phone')): ?>
                        <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('borrower_phone')) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Borrower Type -->
                <div>
                    <label for="borrower_type" class="block text-xs font-semibold text-slate-700">Status Peminjam</label>
                    <select name="borrower_type" id="borrower_type" required
                            class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
                        <option value="mahasiswa" <?= old('borrower_type') === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                        <option value="dosen" <?= old('borrower_type') === 'dosen' ? 'selected' : '' ?>>Dosen</option>
                        <option value="staf" <?= old('borrower_type') === 'staf' ? 'selected' : '' ?>>Staf Lab / Jurusan</option>
                        <option value="umum" <?= old('borrower_type') === 'umum' ? 'selected' : '' ?>>Pihak Luar (Umum)</option>
                    </select>
                </div>

                <!-- Loan Date -->
                <div>
                    <label for="loan_date" class="block text-xs font-semibold text-slate-700">Tanggal Pinjam / Reservasi</label>
                    <input type="date" name="loan_date" id="loan_date" required
                           value="<?= esc(old('loan_date', date('Y-m-d'))) ?>"
                           class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
                    <?php if (error('loan_date')): ?>
                        <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('loan_date')) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Target Return Date (Default 3 days from now) -->
                <div>
                    <label for="due_date" class="block text-xs font-semibold text-slate-700">Rencana Tanggal Kembali</label>
                    <input type="date" name="due_date" id="due_date" required
                           value="<?= esc(old('due_date', date('Y-m-d', strtotime('+3 days')))) ?>"
                           class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
                    <?php if (error('due_date')): ?>
                        <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('due_date')) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-xs font-semibold text-slate-700">Keperluan / Catatan</label>
                    <textarea name="notes" id="notes" rows="2"
                              class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                              placeholder="Keterangan keperluan meminjam..."><?= esc(old('notes')) ?></textarea>
                </div>
            </div>

            <!-- Right 2/3: Items Cart -->
            <div class="md:col-span-2 flex flex-col space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2 bg-white">
                    <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Barang yang Dipinjam</h3>
                    <button type="button" onclick="addItemRow()" 
                            class="inline-flex items-center justify-center px-2.5 py-1 border border-teal-600 text-[11px] font-semibold rounded text-teal-600 bg-white hover:bg-teal-50 transition duration-150">
                        <i data-lucide="plus" class="w-3.5 h-3.5 mr-1"></i>
                        Tambah Barang
                    </button>
                </div>

                <!-- Dynamic Items Container -->
                <div id="items-container" class="space-y-3.5">
                    <!-- First Row (Default) -->
                    <div class="item-row flex flex-col sm:flex-row sm:items-start gap-3 p-3.5 border border-slate-200 rounded bg-slate-50/50">
                        <div class="flex-1">
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-tight">Pilih Barang</label>
                            <select name="items[0][item_id]" onchange="updateStockLimits(this)" required
                                    class="item-selector mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach ($items as $item): ?>
                                    <option value="<?= $item['id'] ?>" data-stock="<?= $item['stock'] ?>" data-unit="<?= esc($item['unit_name']) ?>">
                                        <?= esc($item['code']) ?> - <?= esc($item['name']) ?> (Stok: <?= $item['stock'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="stock-info text-[10px] text-slate-450 mt-1 block font-medium"></span>
                        </div>
                        
                        <div class="w-full sm:w-28">
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-tight">Jumlah</label>
                            <input type="number" name="items[0][quantity]" min="1" value="1" required onchange="validateQtyInput(this)" onkeyup="validateQtyInput(this)"
                                   class="qty-input mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
                        </div>

                        <div class="sm:pt-5 flex items-center justify-end sm:justify-start">
                            <button type="button" onclick="removeItemRow(this)" disabled
                                    class="delete-btn p-1.5 text-slate-300 rounded hover:bg-slate-100 hover:text-rose-600 transition cursor-not-allowed">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button Area -->
                <div class="flex items-center space-x-2 pt-5 border-t border-slate-100 mt-auto">
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
                        <i data-lucide="check" class="w-4 h-4 mr-1.5"></i>
                        Proses Peminjaman
                    </button>
                    <a href="<?= url($prefix . '/borrowings') ?>" 
                       class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm focus:outline-none transition duration-150">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Item Row Template -->
<template id="item-row-template">
    <div class="item-row flex flex-col sm:flex-row sm:items-start gap-3 p-3.5 border border-slate-200 rounded bg-slate-50/50 animate-fade-in">
        <div class="flex-1">
            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-tight">Pilih Barang</label>
            <select name="items[{INDEX}][item_id]" onchange="updateStockLimits(this)" required
                    class="item-selector mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
                <option value="">-- Pilih Barang --</option>
                <?php foreach ($items as $item): ?>
                    <option value="<?= $item['id'] ?>" data-stock="<?= $item['stock'] ?>" data-unit="<?= esc($item['unit_name']) ?>">
                        <?= esc($item['code']) ?> - <?= esc($item['name']) ?> (Stok: <?= $item['stock'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="stock-info text-[10px] text-slate-450 mt-1 block font-medium"></span>
        </div>
        
        <div class="w-full sm:w-28">
            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-tight">Jumlah</label>
            <input type="number" name="items[{INDEX}][quantity]" min="1" value="1" required onchange="validateQtyInput(this)" onkeyup="validateQtyInput(this)"
                   class="qty-input mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
        </div>

        <div class="sm:pt-5 flex items-center justify-end sm:justify-start">
            <button type="button" onclick="removeItemRow(this)"
                    class="delete-btn p-1.5 text-slate-450 rounded hover:bg-slate-100 hover:text-rose-600 transition">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
</template>

<!-- Dynamic inputs script -->
<script>
    let rowIndex = 1;

    function addItemRow() {
        const container = document.getElementById('items-container');
        const template = document.getElementById('item-row-template').innerHTML;
        
        // Replace {INDEX} placeholder
        const rendered = template.replace(/{INDEX}/g, rowIndex);
        
        // Append
        container.insertAdjacentHTML('beforeend', rendered);
        rowIndex++;
        
        // Re-trigger Lucide icon renders
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        updateDeleteButtons();
    }

    function removeItemRow(button) {
        const row = button.closest('.item-row');
        row.remove();
        updateDeleteButtons();
    }

    function updateDeleteButtons() {
        const container = document.getElementById('items-container');
        const rows = container.getElementsByClassName('item-row');
        
        // Disable delete on the first row if it is the only row left
        const deleteButtons = container.getElementsByClassName('delete-btn');
        if (rows.length === 1) {
            deleteButtons[0].disabled = true;
            deleteButtons[0].classList.add('text-slate-300', 'cursor-not-allowed');
            deleteButtons[0].classList.remove('text-slate-450', 'hover:bg-slate-100', 'hover:text-rose-600');
        } else {
            for (let btn of deleteButtons) {
                btn.disabled = false;
                btn.classList.remove('text-slate-300', 'cursor-not-allowed');
                btn.classList.add('text-slate-450', 'hover:bg-slate-100', 'hover:text-rose-600');
            }
        }
    }

    function updateStockLimits(selectElement) {
        const row = selectElement.closest('.item-row');
        const qtyInput = row.querySelector('.qty-input');
        const stockInfo = row.querySelector('.stock-info');
        
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        
        if (selectedOption.value === "") {
            stockInfo.textContent = "";
            qtyInput.max = "";
            return;
        }
        
        const stock = parseInt(selectedOption.getAttribute('data-stock'));
        const unit = selectedOption.getAttribute('data-unit');
        
        qtyInput.max = stock;
        stockInfo.innerHTML = `<span class="text-teal-650 font-bold">Stok tersedia: ${stock} ${unit}</span>`;
        
        // Validate immediately
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
        const container = document.getElementById('items-container');
        const selectors = container.getElementsByClassName('item-selector');
        
        // Check for duplicates
        const selectedIds = [];
        for (let sel of selectors) {
            const id = sel.value;
            if (id === "") {
                alert("Silakan pilih barang pada semua kolom input.");
                return false;
            }
            if (selectedIds.includes(id)) {
                alert("Terdapat barang yang sama dipilih lebih dari sekali. Silakan gabungkan kuantitasnya.");
                return false;
            }
            selectedIds.push(id);
        }
        
        return true;
    }
</script>
