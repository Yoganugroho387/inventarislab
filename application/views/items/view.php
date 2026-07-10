<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Detailed Item View
 */
$role = Auth::role();
$prefix = $role === 'admin' ? '/admin' : '/staff';
?>
<!-- Navigation Back Header -->
<div class="mb-5 flex items-center justify-between">
    <a href="<?= url($prefix . '/items') ?>" class="text-xs text-teal-600 hover:text-teal-800 font-semibold flex items-center">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1.5"></i>
        Kembali ke Katalog
    </a>
    
    <?php if ($role === 'admin'): ?>
        <a href="<?= url('/admin/items/edit/' . $item['id']) ?>" 
           class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-350 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition duration-150">
            <i data-lucide="edit-3" class="w-3.5 h-3.5 mr-1.5 text-slate-500"></i>
            Edit Data Aset
        </a>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Area: Details Sheet (2/3 Width) -->
    <div class="lg:col-span-2 bg-white rounded border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Lembar Spesifikasi Aset</h2>
            <span class="text-xs font-semibold text-slate-400">ID: #<?= $item['id'] ?></span>
        </div>

        <div class="p-6">
            <div class="flex flex-col sm:flex-row gap-6">
                <!-- Large Image Preview -->
                <div class="w-full sm:w-44 flex-shrink-0">
                    <?php if (!empty($item['image']) && file_exists('./uploads/' . $item['image'])): ?>
                        <div class="border border-slate-200 rounded overflow-hidden shadow-sm bg-slate-50">
                            <img src="<?= base_url('uploads/' . $item['image']) ?>" alt="Aset" class="w-full h-auto object-cover max-h-56">
                        </div>
                    <?php else: ?>
                        <div class="w-full h-44 rounded bg-slate-100 text-slate-400 flex flex-col items-center justify-center border border-slate-200 shadow-sm">
                            <i data-lucide="image" class="w-10 h-10 mb-2"></i>
                            <span class="text-[10px] text-slate-500">Tidak ada foto</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Attributes Table -->
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-bold text-slate-900 leading-tight"><?= esc($item['name']) ?></h3>
                    <p class="text-xs font-semibold text-teal-600 mt-1"><?= esc($item['category_name']) ?></p>

                    <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-3.5 border-t border-slate-100 pt-4 text-xs">
                        <div>
                            <span class="block text-slate-400 font-semibold uppercase text-[9px]">Kode Aset</span>
                            <span class="font-bold text-slate-800 tracking-wider"><?= esc($item['code']) ?></span>
                        </div>

                        <div>
                            <span class="block text-slate-400 font-semibold uppercase text-[9px]">Jenis Barang</span>
                            <span class="font-medium text-slate-800 capitalize"><?= $item['item_type'] === 'inventaris' ? 'Inventaris Tetap' : 'Bahan Habis Pakai' ?></span>
                        </div>

                        <div>
                            <span class="block text-slate-400 font-semibold uppercase text-[9px]">Lokasi Penyimpanan</span>
                            <span class="font-semibold text-slate-700"><?= esc($item['location_name']) ?></span>
                        </div>

                        <div>
                            <span class="block text-slate-400 font-semibold uppercase text-[9px]">Jumlah Stok Tersedia</span>
                            <span class="font-bold text-slate-900"><?= $item['stock'] ?> <span class="text-[10px] text-slate-400 font-normal"><?= esc($item['unit_name']) ?></span></span>
                        </div>

                        <div>
                            <span class="block text-slate-400 font-semibold uppercase text-[9px]">Status Ketersediaan</span>
                            <div class="mt-0.5">
                                <?php if ($item['item_status'] === 'tersedia'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-250 uppercase">Tersedia</span>
                                <?php elseif ($item['item_status'] === 'dipinjam'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-150 text-slate-700 border border-slate-250 uppercase">Sedang Dipinjam</span>
                                <?php elseif ($item['item_status'] === 'rusak'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 uppercase">Rusak</span>
                                <?php elseif ($item['item_status'] === 'maintenance'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-250 uppercase">Servis</span>
                                <?php elseif ($item['item_status'] === 'habis'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-400 border border-slate-200 uppercase">Habis</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <span class="block text-slate-400 font-semibold uppercase text-[9px]">Kondisi Fisik</span>
                            <span class="font-medium text-slate-800 capitalize"><?= esc($item['condition_status']) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- More specific items meta -->
            <div class="mt-6 border-t border-slate-100 pt-5">
                <h4 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-3">Detail Informasi Pengadaan</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="bg-slate-50 p-3 rounded border border-slate-150">
                        <span class="block text-slate-400 uppercase text-[9px] font-semibold">Tahun Pengadaan</span>
                        <span class="font-bold text-slate-800 mt-0.5 block"><?= esc($item['procurement_year'] ?: '-') ?></span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded border border-slate-150">
                        <span class="block text-slate-400 uppercase text-[9px] font-semibold">Sumber Anggaran</span>
                        <span class="font-bold text-slate-800 mt-0.5 block truncate" title="<?= esc($item['funding_source']) ?>"><?= esc($item['funding_source'] ?: '-') ?></span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded border border-slate-150">
                        <span class="block text-slate-400 uppercase text-[9px] font-semibold">Harga Perolehan</span>
                        <span class="font-bold text-slate-900 mt-0.5 block"><?= formatRupiah($item['acquisition_price']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6 border-t border-slate-100 pt-5">
                <h4 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Spesifikasi / Deskripsi Barang</h4>
                <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded border border-slate-200 whitespace-pre-line"><?= esc($item['description'] ?: 'Tidak ada deskripsi spesifikasi untuk barang ini.') ?></p>
            </div>
        </div>
    </div>

    <!-- Right Area: QR & circulation history (1/3 Width) -->
    <div class="space-y-6">
        <!-- QR Code Block -->
        <div class="bg-white p-5 rounded border border-slate-200 shadow-sm text-center">
            <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-4">Label Identifikasi QR</h3>
            
            <!-- Dynamic QR Code generator from secure API -->
            <div class="inline-block p-3 border border-slate-200 rounded-lg bg-white shadow-sm mb-3">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=130x130&data=<?= urlencode($item['code']) ?>" 
                     alt="QR Code" class="w-32 h-32">
            </div>
            
            <p class="text-xs font-semibold text-slate-800 tracking-widest uppercase mb-1"><?= esc($item['code']) ?></p>
            <p class="text-[10px] text-slate-400 font-medium">Scan QR code untuk mengidentifikasi barang ini pada sistem fisik.</p>
            
            <button onclick="printQrLabel()" 
                    class="mt-4 w-full inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition duration-150">
                <i data-lucide="printer" class="w-4 h-4 mr-1.5 text-slate-500"></i>
                Cetak Label QR
            </button>
        </div>

        <!-- Loan History logs -->
        <div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Riwayat Sirkulasi (Terbaru)</h3>
            </div>
            
            <div class="p-1">
                <?php if (empty($history)): ?>
                    <p class="text-xs text-slate-500 p-4 text-center font-medium">Belum ada riwayat peminjaman barang ini.</p>
                <?php else: ?>
                    <div class="divide-y divide-slate-100">
                        <?php foreach ($history as $h): ?>
                            <div class="p-3.5 text-xs hover:bg-slate-50/50 transition">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-800 truncate pr-2 max-w-[130px]" title="<?= esc($h['borrower_name']) ?>">
                                        <?= esc($h['borrower_name']) ?>
                                    </span>
                                    <span class="text-[10px] font-medium bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded border border-slate-200">
                                        Qty: <?= $h['quantity'] ?>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between mt-1 text-[10px] text-slate-400 font-medium">
                                    <span>Pinjam: <?= date('d/m/y', strtotime($h['loan_date'])) ?></span>
                                    <span>
                                        <?php if ($h['status'] === 'dikembalikan'): ?>
                                            Kembali: <?= date('d/m/y', strtotime($h['return_date'])) ?>
                                        <?php else: ?>
                                            Batas: <?= date('d/m/y', strtotime($h['due_date'])) ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="mt-1.5 flex items-center">
                                    <?php if ($h['status'] === 'dikembalikan'): ?>
                                        <span class="text-[9px] font-bold uppercase text-emerald-600">Selesai dikembalikan</span>
                                    <?php elseif ($h['status'] === 'dipinjam'): ?>
                                        <span class="text-[9px] font-bold uppercase text-blue-600">Sedang dipinjam</span>
                                    <?php elseif ($h['status'] === 'terlambat'): ?>
                                        <span class="text-[9px] font-bold uppercase text-rose-600">Terlambat kembali</span>
                                    <?php else: ?>
                                        <span class="text-[9px] font-bold uppercase text-amber-500 capitalize">Status: <?= esc($h['status']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Print Window script helper -->
<script>
    function printQrLabel() {
        const qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=<?= urlencode($item['code']) ?>";
        const code = "<?= esc($item['code']) ?>";
        const name = "<?= esc($item['name']) ?>";
        const appName = "<?= esc(Setting_model::getVal('app_name', 'Sistem Inventaris Lab')) ?>";
        
        const printWindow = window.open('', '_blank', 'width=350,height=450');
        printWindow.document.write(`
            <html>
            <head>
                <title>Cetak Label QR - ${code}</title>
                <style>
                    body {
                        font-family: 'Helvetica Neue', Arial, sans-serif;
                        text-align: center;
                        margin: 0;
                        padding: 30px 20px;
                        color: #1e293b;
                    }
                    .label-card {
                        border: 2px dashed #94a3b8;
                        border-radius: 12px;
                        padding: 25px 15px;
                        display: inline-block;
                        background: #fff;
                    }
                    .app-title {
                        font-size: 10px;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                        color: #64748b;
                        margin-bottom: 12px;
                        font-weight: bold;
                    }
                    .qr-image {
                        width: 140px;
                        height: 140px;
                        margin: 10px 0;
                    }
                    .item-code {
                        font-size: 14px;
                        font-weight: bold;
                        letter-spacing: 2px;
                        margin: 8px 0 2px 0;
                    }
                    .item-name {
                        font-size: 11px;
                        color: #475569;
                        max-width: 200px;
                        margin: 0 auto;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    }
                </style>
            </head>
            <body>
                <div class="label-card">
                    <div class="app-title">${appName}</div>
                    <img class="qr-image" src="${qrUrl}" alt="QR">
                    <div class="item-code">${code}</div>
                    <div class="item-name">${name}</div>
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() { window.close(); }, 500);
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
