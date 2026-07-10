<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Items Listing View
 */
$role = Auth::role();
$prefix = $role === 'admin' ? '/admin' : '/staff';
?>
<!-- Top Filters Wrapper -->
<div class="bg-white p-5 rounded border border-slate-200 shadow-sm mb-6">
    <form action="<?= url($prefix . '/items') ?>" method="GET" class="space-y-4">
        <!-- Filter Row 1 -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3.5">
            <!-- Search Keyword -->
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-semibold text-slate-700">Cari Barang</label>
                <div class="mt-1 relative rounded shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-3.5 w-3.5 text-slate-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="<?= esc($filters['search']) ?>"
                           class="block w-full pl-8 pr-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                           placeholder="Kode atau nama barang...">
                </div>
            </div>

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-xs font-semibold text-slate-700">Kategori</label>
                <select name="category_id" id="category_id" 
                        class="mt-1 block w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $filters['category_id'] == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Location -->
            <div>
                <label for="location_id" class="block text-xs font-semibold text-slate-700">Lokasi Lab</label>
                <select name="location_id" id="location_id" 
                        class="mt-1 block w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <option value="">Semua Lokasi</option>
                    <?php foreach ($locations as $l): ?>
                        <option value="<?= $l['id'] ?>" <?= $filters['location_id'] == $l['id'] ? 'selected' : '' ?>><?= esc($l['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Item Type -->
            <div>
                <label for="item_type" class="block text-xs font-semibold text-slate-700">Jenis Barang</label>
                <select name="item_type" id="item_type" 
                        class="mt-1 block w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <option value="">Semua Jenis</option>
                    <option value="inventaris" <?= $filters['item_type'] === 'inventaris' ? 'selected' : '' ?>>Inventaris Tetap</option>
                    <option value="habis_pakai" <?= $filters['item_type'] === 'habis_pakai' ? 'selected' : '' ?>>Habis Pakai</option>
                </select>
            </div>
        </div>

        <!-- Filter Row 2 (Actions & Toggles) -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-2 border-t border-slate-100 gap-3">
            <div class="flex items-center">
                <!-- Low Stock Toggle Checkbox -->
                <input type="checkbox" name="low_stock" id="low_stock" value="1" <?= !empty($filters['low_stock']) ? 'checked' : '' ?>
                       class="w-4 h-4 text-teal-600 border-slate-300 rounded focus:ring-teal-500 cursor-pointer">
                <label for="low_stock" class="ml-2 text-xs font-semibold text-slate-600 cursor-pointer select-none">
                    Tampilkan Stok Menipis / Habis (Habis Pakai saja)
                </label>
            </div>
            
            <div class="flex items-center space-x-2">
                <button type="submit" 
                        class="inline-flex items-center justify-center px-4 py-1.5 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
                    <i data-lucide="filter" class="w-3.5 h-3.5 mr-1.5"></i>
                    Terapkan Filter
                </button>
                
                <a href="<?= url($prefix . '/items') ?>" 
                   class="inline-flex items-center justify-center px-4 py-1.5 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm focus:outline-none transition duration-150">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Items Table Wrapper -->
<div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col">
    <!-- Table Header with count and add button -->
    <div class="px-6 py-4 border-b border-slate-150 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50 flex-shrink-0">
        <div>
            <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Katalog Barang Laboratorium</h2>
        </div>
        <?php if ($role === 'admin'): ?>
            <div class="flex items-center gap-2">
                <button type="button" onclick="toggleImportModal()"
                   class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition duration-150">
                    <i data-lucide="upload-cloud" class="w-4 h-4 mr-1.5 text-slate-500"></i>
                    Impor CSV
                </button>
                <a href="<?= url('/admin/items/create') ?>" 
                   class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm transition duration-150">
                    <i data-lucide="plus-circle" class="w-4 h-4 mr-1.5"></i>
                    Tambah Barang Baru
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Table container -->
    <div class="overflow-x-auto">
        <?php if (empty($items)): ?>
            <!-- Empty state -->
            <div class="p-12 text-center bg-white">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3.5">
                    <i data-lucide="box" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-800">Tidak ada barang ditemukan</h3>
                <p class="text-xs text-slate-500 mt-1">Coba sesuaikan kata kunci pencarian atau bersihkan filter pencarian Anda.</p>
                <?php if ($role === 'admin'): ?>
                    <a href="<?= url('/admin/items/create') ?>" class="mt-4 inline-flex items-center text-xs font-semibold text-teal-600 hover:text-teal-800 transition">
                        Tambah barang pertama sekarang <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Table content -->
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase bg-slate-100 text-slate-500">
                        <th class="px-6 py-3 w-32">Kode</th>
                        <th class="px-6 py-3">Nama Barang</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Lokasi</th>
                        <th class="px-6 py-3 w-28">Jenis</th>
                        <th class="px-6 py-3 w-24">Stok</th>
                        <th class="px-6 py-3 w-28">Kondisi / Status</th>
                        <th class="px-6 py-3 w-24 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($items as $item): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-3 font-semibold text-slate-700 whitespace-nowrap">
                                <a href="<?= url($prefix . '/items/view/' . $item['id']) ?>" class="hover:text-teal-600 transition flex items-center">
                                    <i data-lucide="qr-code" class="w-3.5 h-3.5 mr-1 text-slate-400"></i>
                                    <?= esc($item['code']) ?>
                                </a>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center space-x-3">
                                    <?php if (!empty($item['image']) && file_exists('./uploads/' . $item['image'])): ?>
                                        <img src="<?= base_url('uploads/' . $item['image']) ?>" alt="foto" class="w-7 h-7 object-cover rounded border border-slate-200 shadow-sm flex-shrink-0">
                                    <?php else: ?>
                                        <div class="w-7 h-7 rounded bg-slate-100 text-slate-400 flex items-center justify-center flex-shrink-0 border border-slate-200">
                                            <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex flex-col min-w-0">
                                        <a href="<?= url($prefix . '/items/view/' . $item['id']) ?>" class="font-medium text-slate-800 hover:text-teal-600 transition truncate max-w-[200px]"><?= esc($item['name']) ?></a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-slate-500 font-medium whitespace-nowrap"><?= esc($item['category_name']) ?></td>
                            <td class="px-6 py-3 text-slate-500 font-medium whitespace-nowrap"><?= esc($item['location_name']) ?></td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <?php if ($item['item_type'] === 'inventaris'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 uppercase">
                                        Inventaris
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-teal-50 text-teal-700 border border-teal-200 uppercase">
                                        Habis Pakai
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <?php if ($item['item_type'] === 'habis_pakai' && $item['stock'] <= $item['minimum_stock']): ?>
                                    <?php if ($item['stock'] <= 0): ?>
                                        <span class="inline-flex items-center text-xs font-bold text-rose-600">
                                            <i data-lucide="alert-octagon" class="w-3.5 h-3.5 mr-1 text-rose-500 flex-shrink-0"></i>
                                            Habis (0)
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center text-xs font-bold text-amber-600" title="Stok berada di bawah batas minimum: <?= $item['minimum_stock'] ?>">
                                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1 text-amber-500 flex-shrink-0"></i>
                                            <?= $item['stock'] ?> <span class="text-[10px] text-slate-400 font-normal ml-0.5"><?= esc($item['unit_name']) ?></span>
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-xs font-medium text-slate-700">
                                        <?= $item['stock'] ?> <span class="text-[10px] text-slate-400 font-normal ml-0.5"><?= esc($item['unit_name']) ?></span>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <?php if ($item['condition_status'] === 'tersedia'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-250 uppercase">
                                        Tersedia
                                    </span>
                                <?php elseif ($item['condition_status'] === 'rusak'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 uppercase">
                                        Rusak
                                    </span>
                                <?php elseif ($item['condition_status'] === 'maintenance'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-250 uppercase">
                                        Servis
                                    </span>
                                <?php elseif ($item['condition_status'] === 'habis'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-400 border border-slate-200 uppercase">
                                        Habis
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
                                <!-- View Details -->
                                <a href="<?= url($prefix . '/items/view/' . $item['id']) ?>" 
                                   class="inline-flex items-center text-slate-400 hover:text-teal-600 transition" 
                                   title="Lihat Detail">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </a>

                                <?php if ($role === 'admin'): ?>
                                    <!-- Edit -->
                                    <a href="<?= url('/admin/items/edit/' . $item['id']) ?>" 
                                       class="inline-flex items-center text-slate-400 hover:text-teal-600 transition" 
                                       title="Edit Barang">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    </a>

                                    <!-- Delete -->
                                    <form action="<?= url('/admin/items/delete/' . $item['id']) ?>" method="POST" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-slate-400 hover:text-rose-600 transition" title="Hapus Barang">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php if ($role === 'admin'): ?>
<!-- Import CSV Modal -->
<div id="importModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="toggleImportModal()"></div>
    
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-lg bg-white border border-slate-200 text-left shadow-xl transition-all w-full max-w-md p-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center">
                    <i data-lucide="upload-cloud" class="w-4.5 h-4.5 mr-1.5 text-teal-650"></i>
                    Impor Barang via CSV
                </h3>
                <button type="button" class="text-slate-400 hover:text-slate-650" onclick="toggleImportModal()">
                    <i data-lucide="x" class="w-4.5 h-4.5"></i>
                </button>
            </div>
            
            <form action="<?= url('/admin/items/import_csv') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="space-y-4">
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Anda dapat mengimpor data barang secara massal menggunakan file CSV. Unduh template di bawah ini untuk melihat contoh format pengisian yang benar.
                    </p>
                    
                    <a href="<?= url('/admin/items/download_template') ?>" 
                       class="inline-flex items-center text-xs font-bold text-teal-600 hover:text-teal-800 transition mb-2">
                        <i data-lucide="download" class="w-3.5 h-3.5 mr-1"></i>
                        Unduh Template CSV
                    </a>
                    
                    <div class="border-2 border-dashed border-slate-200 rounded-lg p-5 text-center bg-slate-50/50">
                        <input type="file" name="userfile" id="csv_file" accept=".csv" required class="hidden" onchange="updateFileName(this)">
                        <label for="csv_file" class="cursor-pointer block">
                            <i data-lucide="file-spreadsheet" class="w-8 h-8 mx-auto text-slate-400 mb-2"></i>
                            <span class="block text-xs font-semibold text-slate-700" id="file-label-text">Pilih File CSV</span>
                            <span class="block text-[10px] text-slate-450 mt-1">Maksimal 2MB (.csv)</span>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                        <button type="button" onclick="toggleImportModal()"
                                class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition duration-150">
                            Batal
                        </button>
                        <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm transition duration-150">
                            <i data-lucide="check" class="w-4 h-4 mr-1.5"></i>
                            Impor Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleImportModal() {
        const modal = document.getElementById('importModal');
        modal.classList.toggle('hidden');
        if (!modal.classList.contains('hidden')) {
            // Re-initialize icons inside modal
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    }
    
    function updateFileName(input) {
        const text = document.getElementById('file-label-text');
        if (input.files && input.files.length > 0) {
            text.innerHTML = `<span class="text-teal-750 font-bold">${input.files[0].name}</span>`;
        } else {
            text.textContent = 'Pilih File CSV';
        }
    }
</script>
<?php endif; ?>
