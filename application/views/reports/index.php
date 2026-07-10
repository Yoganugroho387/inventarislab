<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Reports Index View
 */
$months = [
    'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April',
    'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus',
    'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
];
$tgl_sekarang = date('d F Y');
foreach ($months as $eng => $indo) {
    $tgl_sekarang = str_replace($eng, $indo, $tgl_sekarang);
}
?>
<!-- Dynamic Filter Header -->
<div class="bg-white p-5 rounded border border-slate-200 shadow-sm mb-6 print:hidden">
    <form action="<?= url('/admin/reports') ?>" method="GET" id="report-form" class="space-y-4">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <!-- 1. Report Type Selector -->
            <div>
                <label for="report_type" class="block text-xs font-semibold text-slate-700">Jenis Laporan</label>
                <select name="report_type" id="report_type" onchange="toggleFilterFields()" required
                        class="mt-1 block w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <option value="items" <?= $reportType === 'items' ? 'selected' : '' ?>>Laporan Stok & Inventaris Barang</option>
                    <option value="borrowings" <?= $reportType === 'borrowings' ? 'selected' : '' ?>>Laporan Transaksi Peminjaman</option>
                    <option value="returns" <?= $reportType === 'returns' ? 'selected' : '' ?>>Laporan Pengembalian Barang</option>
                </select>
            </div>

            <!-- 2. Category Selector (Only for Items) -->
            <div class="filter-item-only">
                <label for="category_id" class="block text-xs font-semibold text-slate-700">Kategori</label>
                <select name="category_id" id="category_id" 
                        class="mt-1 block w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $filters['category_id'] == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- 3. Location Selector (Only for Items) -->
            <div class="filter-item-only">
                <label for="location_id" class="block text-xs font-semibold text-slate-700">Lokasi Lab</label>
                <select name="location_id" id="location_id" 
                        class="mt-1 block w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <option value="">Semua Lokasi</option>
                    <?php foreach ($locations as $l): ?>
                        <option value="<?= $l['id'] ?>" <?= $filters['location_id'] == $l['id'] ? 'selected' : '' ?>><?= esc($l['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- 4. Item Type Selector (Only for Items) -->
            <div class="filter-item-only">
                <label for="item_type" class="block text-xs font-semibold text-slate-700">Jenis Barang</label>
                <select name="item_type" id="item_type" 
                        class="mt-1 block w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <option value="">Semua Jenis</option>
                    <option value="inventaris" <?= $filters['item_type'] === 'inventaris' ? 'selected' : '' ?>>Inventaris Tetap</option>
                    <option value="habis_pakai" <?= $filters['item_type'] === 'habis_pakai' ? 'selected' : '' ?>>Habis Pakai</option>
                </select>
            </div>

            <!-- 5. Start Date Selector (Only for Transactions) -->
            <div class="filter-date-only">
                <label for="start_date" class="block text-xs font-semibold text-slate-700">Tanggal Awal</label>
                <input type="date" name="start_date" id="start_date" value="<?= esc($filters['start_date']) ?>"
                       class="mt-1 block w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
            </div>

            <!-- 6. End Date Selector (Only for Transactions) -->
            <div class="filter-date-only">
                <label for="end_date" class="block text-xs font-semibold text-slate-700">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" value="<?= esc($filters['end_date']) ?>"
                       class="mt-1 block w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
            </div>

            <!-- 7. Condition Selector (Shared but dynamically set) -->
            <div id="status-container">
                <label for="status" id="status-label" class="block text-xs font-semibold text-slate-700">Status Kondisi</label>
                <select name="status" id="status" 
                        class="mt-1 block w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <!-- Options are injected via Javascript based on type selection -->
                </select>
            </div>
        </div>

        <!-- Submission buttons -->
        <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
            <button type="submit" 
                    class="inline-flex items-center justify-center px-4 py-1.5 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
                <i data-lucide="eye" class="w-3.5 h-3.5 mr-1.5"></i>
                Preview Laporan
            </button>
            <a href="<?= url('/admin/reports') ?>" 
               class="inline-flex items-center justify-center px-4 py-1.5 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm focus:outline-none transition duration-150">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Preview Results Area -->
<div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col print:border-none print:shadow-none">
    
    <!-- Print Report Header Details (Visible in printing mode only) -->
    <div class="hidden print:block mb-6">
        <table class="w-full border-collapse border-b-2 border-double border-slate-900 pb-3" style="border-bottom: 3px double #000;">
            <tr>
                <td class="w-16 text-left pb-3">
                    <?php 
                    $instLogo = Setting_model::getVal('institution_logo', '');
                    if ($instLogo && file_exists('./uploads/' . $instLogo)): 
                    ?>
                        <img src="<?= base_url('uploads/' . $instLogo) ?>" alt="Logo" class="max-h-16 max-w-16 object-contain">
                    <?php endif; ?>
                </td>
                <td class="text-center pb-3 pr-16">
                    <h4 class="text-[10px] font-normal uppercase tracking-wide text-slate-800" style="font-family: 'Times New Roman', serif;">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h4>
                    <h3 class="text-sm font-bold uppercase text-slate-900" style="font-family: 'Times New Roman', serif; font-size: 13px;"><?= esc(Setting_model::getVal('institution_name', 'POLITEKNIK NEGERI JAKARTA')) ?></h3>
                    <h3 class="text-sm font-bold uppercase text-slate-900" style="font-family: 'Times New Roman', serif; font-size: 13px;"><?= esc(Setting_model::getVal('lab_name', 'LABORATORIUM')) ?></h3>
                    <p class="text-[8px] italic text-slate-500 mt-1" style="font-family: 'Times New Roman', serif; font-size: 9px;"><?= esc(Setting_model::getVal('lab_address', '')) ?></p>
                </td>
            </tr>
        </table>
        
        <div class="text-center mt-5">
            <h2 class="text-xs font-bold uppercase text-slate-800 tracking-wider">
                LAPORAN 
                <?php 
                    if ($reportType === 'items') echo 'KATALOG STOK & KONDISI BARANG';
                    if ($reportType === 'borrowings') echo 'DAFTAR TRANSAKSI PEMINJAMAN';
                    if ($reportType === 'returns') echo 'CATATAN PENGEMBALIAN BARANG';
                ?>
            </h2>
            <?php if ($filters['start_date'] || $filters['end_date']): ?>
                <p class="text-[9px] text-slate-500 font-semibold mt-1">
                    Periode: <?= $filters['start_date'] ? date('d/m/Y', strtotime($filters['start_date'])) : 'Awal' ?> s.d <?= $filters['end_date'] ? date('d/m/Y', strtotime($filters['end_date'])) : 'Sekarang' ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Preview Toolbar controls -->
    <div class="px-6 py-4 border-b border-slate-150 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50 flex-shrink-0 print:hidden">
        <div>
            <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Pratinjau Hasil Laporan</h2>
        </div>
        
        <?php if (!empty($results)): ?>
            <div class="flex items-center space-x-2">
                <!-- CSV export trigger -->
                <a href="<?= url('/admin/reports/export?' . http_build_query($_GET)) ?>" 
                   class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-350 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition duration-150">
                    <i data-lucide="download" class="w-3.5 h-3.5 mr-1.5 text-slate-550"></i>
                    Ekspor Ke CSV
                </a>
                
                <!-- Print trigger -->
                <button onclick="window.print()" 
                        class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm transition duration-150">
                    <i data-lucide="printer" class="w-3.5 h-3.5 mr-1.5"></i>
                    Cetak Halaman
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Preview Table Container -->
    <div class="overflow-x-auto print:overflow-visible">
        <?php if (empty($results)): ?>
            <div class="p-12 text-center bg-white">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3.5">
                    <i data-lucide="file-bar-chart" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-800">Tidak ada data untuk dilaporkan</h3>
                <p class="text-xs text-slate-500 mt-1">Silakan sesuaikan jenis laporan atau filter pencarian dan klik "Preview Laporan".</p>
            </div>
        <?php else: ?>
            
            <!-- Type 1: Items Report -->
            <?php if ($reportType === 'items'): ?>
                <table class="w-full text-left border-collapse text-xs print:text-[10px]">
                    <thead>
                        <tr class="border-b border-slate-300 text-[10px] print:text-[9px] font-bold uppercase bg-slate-100 text-slate-500">
                            <th class="px-5 py-3 w-12">No</th>
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Nama Barang</th>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3">Lokasi</th>
                            <th class="px-5 py-3">Jenis</th>
                            <th class="px-5 py-3 w-20">Stok</th>
                            <th class="px-5 py-3">Kondisi</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 print:hidden">Harga Perolehan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php $no = 1; foreach ($results as $r): ?>
                            <tr class="hover:bg-slate-50/30 transition">
                                <td class="px-5 py-2.5 text-slate-400 font-medium"><?= $no++ ?></td>
                                <td class="px-5 py-2.5 font-semibold text-slate-850"><?= esc($r['code']) ?></td>
                                <td class="px-5 py-2.5 font-medium text-slate-800"><?= esc($r['name']) ?></td>
                                <td class="px-5 py-2.5 text-slate-500"><?= esc($r['category_name']) ?></td>
                                <td class="px-5 py-2.5 text-slate-500"><?= esc($r['location_name']) ?></td>
                                <td class="px-5 py-2.5 capitalize"><?= $r['item_type'] === 'inventaris' ? 'Inventaris' : 'Habis Pakai' ?></td>
                                <td class="px-5 py-2.5 font-bold text-slate-900"><?= $r['stock'] ?> <span class="text-[9px] text-slate-400 font-normal"><?= esc($r['unit_name']) ?></span></td>
                                <td class="px-5 py-2.5 capitalize"><?= esc($r['condition_status']) ?></td>
                                <td class="px-5 py-2.5 capitalize"><?= esc($r['item_status']) ?></td>
                                <td class="px-5 py-2.5 font-medium text-slate-800 print:hidden"><?= formatRupiah($r['acquisition_price']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <!-- Type 2: Borrowings Report -->
            <?php elseif ($reportType === 'borrowings'): ?>
                <table class="w-full text-left border-collapse text-xs print:text-[10px]">
                    <thead>
                        <tr class="border-b border-slate-300 text-[10px] print:text-[9px] font-bold uppercase bg-slate-100 text-slate-500">
                            <th class="px-5 py-3 w-12">No</th>
                            <th class="px-5 py-3">Peminjam</th>
                            <th class="px-5 py-3">Identitas</th>
                            <th class="px-5 py-3">Tgl Pinjam</th>
                            <th class="px-5 py-3">Batas Kembali</th>
                            <th class="px-5 py-3">Barang Dipinjam</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 print:hidden">Diinput Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php $no = 1; foreach ($results as $r): ?>
                            <tr class="hover:bg-slate-50/30 transition">
                                <td class="px-5 py-2.5 text-slate-400 font-medium"><?= $no++ ?></td>
                                <td class="px-5 py-2.5 font-bold text-slate-800"><?= esc($r['borrower_name']) ?> <span class="text-[9px] text-slate-400 font-normal capitalize">(<?= esc($r['borrower_type']) ?>)</span></td>
                                <td class="px-5 py-2.5 font-semibold text-slate-655"><?= esc($r['borrower_identity']) ?></td>
                                <td class="px-5 py-2.5"><?= date('d/m/y', strtotime($r['loan_date'])) ?></td>
                                <td class="px-5 py-2.5"><?= date('d/m/y', strtotime($r['due_date'])) ?></td>
                                <td class="px-5 py-2.5 text-[11px] text-slate-600">
                                    <?php 
                                        $itemsStr = array_map(function($i) { return $i['item_name'] . ' (x' . $i['quantity'] . ')'; }, $r['items']);
                                        echo esc(implode(', ', $itemsStr));
                                    ?>
                                </td>
                                <td class="px-5 py-2.5 capitalize font-semibold"><?= esc($r['status']) ?></td>
                                <td class="px-5 py-2.5 text-slate-500 print:hidden"><?= esc($r['creator_name']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <!-- Type 3: Returns Report -->
            <?php elseif ($reportType === 'returns'): ?>
                <table class="w-full text-left border-collapse text-xs print:text-[10px]">
                    <thead>
                        <tr class="border-b border-slate-300 text-[10px] print:text-[9px] font-bold uppercase bg-slate-100 text-slate-500">
                            <th class="px-5 py-3 w-12">No</th>
                            <th class="px-5 py-3">Peminjam</th>
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Barang</th>
                            <th class="px-5 py-3 w-16">Jumlah</th>
                            <th class="px-5 py-3">Tgl Pinjam</th>
                            <th class="px-5 py-3">Tgl Kembali</th>
                            <th class="px-5 py-3">Kondisi</th>
                            <th class="px-5 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php $no = 1; foreach ($results as $r): ?>
                            <tr class="hover:bg-slate-50/30 transition">
                                <td class="px-5 py-2.5 text-slate-400 font-medium"><?= $no++ ?></td>
                                <td class="px-5 py-2.5 font-bold text-slate-800"><?= esc($r['borrower_name']) ?> <span class="text-[9px] text-slate-400 font-normal">(<?= esc($r['borrower_identity']) ?>)</span></td>
                                <td class="px-5 py-2.5 font-semibold text-slate-655"><?= esc($r['item_code']) ?></td>
                                <td class="px-5 py-2.5 font-medium text-slate-800"><?= esc($r['item_name']) ?></td>
                                <td class="px-5 py-2.5 font-bold"><?= $r['quantity'] ?> <?= esc($r['unit_name']) ?></td>
                                <td class="px-5 py-2.5"><?= date('d/m/y', strtotime($r['loan_date'])) ?></td>
                                <td class="px-5 py-2.5 font-bold text-emerald-700"><?= date('d/m/y', strtotime($r['return_date'])) ?></td>
                                <td class="px-5 py-2.5 capitalize font-semibold"><?= esc($r['return_condition']) ?></td>
                                <td class="px-5 py-2.5 text-slate-550 leading-tight"><?= esc($r['return_notes'] ?: '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            
            <!-- Printing signature sign-off section (Only visible in printing) -->
            <div class="hidden print:block mt-12 text-xs font-semibold" style="font-family: 'Times New Roman', serif;">
                <div class="grid grid-cols-2">
                    <div></div>
                    <div class="text-center space-y-16 ml-auto w-64">
                        <p>Kota Pengeluaran, <?= $tgl_sekarang ?><br>Kepala Laboratorium,</p>
                        <div style="height: 60px;"></div>
                        <p class="underline font-bold text-slate-900"><?= esc(Setting_model::getVal('lab_head', 'Dr. Ir. Budi Santoso, M.T.')) ?></p>
                    </div>
                </div>
            </div>
            
        <?php endif; ?>
    </div>
</div>

<!-- Print utility overrides and dynamic toggles -->
<style>
    @media print {
        /* Force display of elements designed for printing */
        .hidden.print\:block {
            display: block !important;
        }
        
        /* Force hide elements designed to be hidden */
        .print\:hidden,
        #sidebarPanel,
        header,
        footer,
        main > div.mb-5, /* Flash alerts */
        main > div.mb-6, /* Page title header */
        #report-form {
            display: none !important;
        }
        
        /* Reset layouts from Flexbox to normal block flow for printing */
        body {
            background-color: #fff !important;
            color: #000 !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .flex.h-screen,
        .flex-1,
        main {
            display: block !important;
            overflow: visible !important;
            position: static !important;
            width: 100% !important;
            height: auto !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }
        
        /* Clean up table margins and borders for print */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
    }
</style>

<script>
    // Cached selectors
    const reportType = document.getElementById('report_type');
    const statusSelect = document.getElementById('status');
    
    // PHP variables mapped to JSON objects
    const currentFilters = <?= json_encode($filters) ?>;

    function toggleFilterFields() {
        const itemFields = document.querySelectorAll('.filter-item-only');
        const dateFields = document.querySelectorAll('.filter-date-only');
        const statusLabel = document.getElementById('status-label');
        
        // Clear options first
        statusSelect.innerHTML = '';

        if (reportType.value === 'items') {
            // Show item filters, hide date filters
            itemFields.forEach(f => f.style.display = 'block');
            dateFields.forEach(f => f.style.display = 'none');
            
            // Build Condition Options for Items
            statusLabel.textContent = "Kondisi Barang";
            injectStatusOptions([
                {value: '', label: 'Semua Kondisi'},
                {value: 'tersedia', label: 'Baik (Tersedia)'},
                {value: 'rusak', label: 'Rusak'},
                {value: 'maintenance', label: 'Servis / Perawatan'},
                {value: 'habis', label: 'Habis'}
            ]);
            
        } else if (reportType.value === 'borrowings') {
            // Hide item filters, show date filters
            itemFields.forEach(f => f.style.display = 'none');
            dateFields.forEach(f => f.style.display = 'block');
            
            // Build Status Options for Borrowings
            statusLabel.textContent = "Status Peminjaman";
            injectStatusOptions([
                {value: '', label: 'Semua Status'},
                {value: 'menunggu', label: 'Menunggu Persetujuan'},
                {value: 'dipinjam', label: 'Sedang Dipinjam'},
                {value: 'dikembalikan', label: 'Selesai Dikembalikan'},
                {value: 'ditolak', label: 'Ditolak'},
                {value: 'terlambat', label: 'Terlambat Kembali'}
            ]);
            
        } else if (reportType.value === 'returns') {
            // Hide item filters, show date filters
            itemFields.forEach(f => f.style.display = 'none');
            dateFields.forEach(f => f.style.display = 'block');
            
            // Build Condition Options for Returns
            statusLabel.textContent = "Kondisi Kembali";
            injectStatusOptions([
                {value: '', label: 'Semua Kondisi'},
                {value: 'baik', label: 'Baik'},
                {value: 'rusak', label: 'Rusak'},
                {value: 'hilang', label: 'Hilang'}
            ]);
        }
    }

    function injectStatusOptions(options) {
        options.forEach(opt => {
            const el = document.createElement('option');
            el.value = opt.value;
            el.textContent = opt.label;
            
            if (currentFilters.status === opt.value) {
                el.selected = true;
            }
            
            statusSelect.appendChild(el);
        });
    }

    // Run once on load
    document.addEventListener('DOMContentLoaded', toggleFilterFields);
</script>
