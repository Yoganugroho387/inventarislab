<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Create Item View
 */
?>
<div class="max-w-3xl bg-white p-6 rounded border border-slate-200 shadow-sm">
    <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Form Registrasi Barang Baru</h2>
        <a href="<?= url('/admin/items') ?>" class="text-xs text-teal-600 hover:text-teal-800 flex items-center">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1"></i>
            Kembali
        </a>
    </div>

    <!-- Note: enctype is required for file uploads -->
    <form action="<?= url('/admin/items/store') ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- 1. Kode Barang -->
            <div>
                <label for="code" class="block text-xs font-semibold text-slate-700">Kode Barang</label>
                <input type="text" name="code" id="code" required
                       value="<?= esc(old('code', $nextCode)) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Misal: LAB-INF-001">
                <?php if (error('code')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('code')) ?></p>
                <?php endif; ?>
            </div>

            <!-- 2. Nama Barang -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700">Nama Barang</label>
                <input type="text" name="name" id="name" required
                       value="<?= esc(old('name')) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Masukkan nama spesifik barang">
                <?php if (error('name')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('name')) ?></p>
                <?php endif; ?>
            </div>

            <!-- 3. Jenis Barang Selection -->
            <div>
                <label for="item_type" class="block text-xs font-semibold text-slate-700">Jenis Barang</label>
                <select name="item_type" id="item_type" required onchange="toggleTypeFields()"
                        class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <option value="inventaris" <?= old('item_type') === 'inventaris' ? 'selected' : '' ?>>Aset Inventaris Tetap (Dapat Dipinjam)</option>
                    <option value="habis_pakai" <?= old('item_type') === 'habis_pakai' ? 'selected' : '' ?>>Bahan Habis Pakai / Stok Komponen</option>
                </select>
                <?php if (error('item_type')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('item_type')) ?></p>
                <?php endif; ?>
            </div>

            <!-- 4. Kategori -->
            <div>
                <label for="category_id" class="block text-xs font-semibold text-slate-700">Kategori</label>
                <select name="category_id" id="category_id" required
                        class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= old('category_id') == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (error('category_id')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('category_id')) ?></p>
                <?php endif; ?>
            </div>

            <!-- 5. Lokasi -->
            <div>
                <label for="location_id" class="block text-xs font-semibold text-slate-700">Lokasi Penyimpanan</label>
                <select name="location_id" id="location_id" required
                        class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <option value="">-- Pilih Lokasi --</option>
                    <?php foreach ($locations as $l): ?>
                        <option value="<?= $l['id'] ?>" <?= old('location_id') == $l['id'] ? 'selected' : '' ?>><?= esc($l['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (error('location_id')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('location_id')) ?></p>
                <?php endif; ?>
            </div>

            <!-- 6. Satuan -->
            <div>
                <label for="unit_id" class="block text-xs font-semibold text-slate-700">Satuan Barang</label>
                <select name="unit_id" id="unit_id" required
                        class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <option value="">-- Pilih Satuan --</option>
                    <?php foreach ($units as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= old('unit_id') == $u['id'] ? 'selected' : '' ?>><?= esc($u['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (error('unit_id')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('unit_id')) ?></p>
                <?php endif; ?>
            </div>

            <!-- 7. Jumlah Stok -->
            <div>
                <label for="stock" class="block text-xs font-semibold text-slate-700">Jumlah Stok Awal</label>
                <input type="number" name="stock" id="stock" required min="0"
                       value="<?= esc(old('stock', '0')) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                <?php if (error('stock')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('stock')) ?></p>
                <?php endif; ?>
            </div>

            <!-- 8. Stok Minimum (Only showing for Habis Pakai) -->
            <div id="min_stock_field">
                <label for="minimum_stock" class="block text-xs font-semibold text-slate-700">Batas Stok Minimum</label>
                <input type="number" name="minimum_stock" id="minimum_stock" min="0"
                       value="<?= esc(old('minimum_stock', '0')) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                <span class="text-[9px] text-slate-400 mt-1 block">Sistem akan memberi peringatan jika stok berada pada batas ini.</span>
                <?php if (error('minimum_stock')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('minimum_stock')) ?></p>
                <?php endif; ?>
            </div>

            <!-- 9. Kondisi Awal (Only showing for Inventaris) -->
            <div id="condition_field">
                <label for="condition_status" class="block text-xs font-semibold text-slate-700">Kondisi Awal Aset</label>
                <select name="condition_status" id="condition_status"
                        class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <option value="tersedia" <?= old('condition_status') === 'tersedia' ? 'selected' : '' ?>>Baik (Tersedia)</option>
                    <option value="rusak" <?= old('condition_status') === 'rusak' ? 'selected' : '' ?>>Rusak Ringan/Berat</option>
                    <option value="maintenance" <?= old('condition_status') === 'maintenance' ? 'selected' : '' ?>>Sedang Diservis (Perawatan)</option>
                </select>
                <?php if (error('condition_status')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('condition_status')) ?></p>
                <?php endif; ?>
            </div>

            <!-- 10. Tahun Pengadaan -->
            <div>
                <label for="procurement_year" class="block text-xs font-semibold text-slate-700">Tahun Pengadaan</label>
                <input type="number" name="procurement_year" id="procurement_year" min="1990" max="2100"
                       value="<?= esc(old('procurement_year', date('Y'))) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
            </div>

            <!-- 11. Sumber Dana -->
            <div>
                <label for="funding_source" class="block text-xs font-semibold text-slate-700">Sumber Dana</label>
                <input type="text" name="funding_source" id="funding_source"
                       value="<?= esc(old('funding_source')) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Misal: DIPA FT 2024, Hibah Dikti">
            </div>

            <!-- 12. Harga Perolehan -->
            <div>
                <label for="acquisition_price" class="block text-xs font-semibold text-slate-700">Harga Perolehan (Rp)</label>
                <input type="number" name="acquisition_price" id="acquisition_price" min="0" step="0.01"
                       value="<?= esc(old('acquisition_price', '0')) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
            </div>

            <!-- 13. Upload Foto Barang -->
            <div>
                <label for="image" class="block text-xs font-semibold text-slate-700">Foto Barang (Format JPG/PNG, Maks 2MB)</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="mt-1 block w-full px-2 py-1 text-xs border border-slate-300 rounded bg-slate-50 text-slate-800 focus:outline-none focus:ring-1 focus:ring-teal-500 transition duration-150 cursor-pointer">
            </div>

            <!-- 14. Deskripsi (Full Width) -->
            <div class="md:col-span-2">
                <label for="description" class="block text-xs font-semibold text-slate-700">Deskripsi / Spesifikasi Barang</label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                          placeholder="Masukkan spesifikasi rinci, seri barang, atau kelengkapan..."><?= esc(old('description')) ?></textarea>
            </div>
        </div>

        <!-- Submit Button Row -->
        <div class="flex items-center space-x-2 pt-5 mt-6 border-t border-slate-100">
            <button type="submit" 
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
                <i data-lucide="save" class="w-4 h-4 mr-1.5"></i>
                Daftarkan Barang
            </button>
            <a href="<?= url('/admin/items') ?>" 
               class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm focus:outline-none transition duration-150">
                Batal
            </a>
        </div>
    </form>
</div>

<!-- Dynamic field toggles script -->
<script>
    function toggleTypeFields() {
        const typeSelect = document.getElementById('item_type');
        const minStockField = document.getElementById('min_stock_field');
        const conditionField = document.getElementById('condition_field');

        if (typeSelect.value === 'habis_pakai') {
            minStockField.style.display = 'block';
            conditionField.style.display = 'none';
        } else {
            // For Inventaris, min stock is usually 0 and condition is customizable
            minStockField.style.display = 'none';
            conditionField.style.display = 'block';
            document.getElementById('minimum_stock').value = 0;
        }
    }
    
    // Run once on load
    document.addEventListener('DOMContentLoaded', toggleTypeFields);
</script>
