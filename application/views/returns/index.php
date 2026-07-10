<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Returns Index Listing View
 */
?>
<!-- Top Tabs Toolbar -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <!-- Filter status tabs -->
    <div class="flex flex-wrap gap-1.5 p-1 bg-slate-200/60 rounded border border-slate-200 w-fit text-xs">
        <a href="<?= url($prefix . '/returns?status=dipinjam') ?>" 
           class="px-3 py-1.5 rounded transition font-medium <?= $filters['status'] === 'dipinjam' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-600 hover:text-slate-800' ?>">
            Belum Kembali (Aktif)
        </a>
        <a href="<?= url($prefix . '/returns?status=dikembalikan') ?>" 
           class="px-3 py-1.5 rounded transition font-medium <?= $filters['status'] === 'dikembalikan' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-600 hover:text-slate-800' ?>">
            Sudah Kembali (Selesai)
        </a>
    </div>
</div>

<!-- Search Panel with scan button -->
<div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm mb-6 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
    <form action="<?= url($prefix . '/returns') ?>" method="GET" class="flex flex-wrap items-center gap-2 flex-1">
        <input type="hidden" name="status" value="<?= esc($filters['status']) ?>">
        
        <div class="relative rounded shadow-sm flex-1 max-w-sm">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                <i data-lucide="search" class="h-3.5 w-3.5 text-slate-400"></i>
            </div>
            <input type="text" name="search" id="search" value="<?= esc($filters['search']) ?>"
                   class="block w-full pl-8 pr-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                   placeholder="Cari nama peminjam...">
        </div>
        <button type="submit" 
                class="inline-flex items-center justify-center px-4 py-1.5 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
            Cari
        </button>
        <a href="<?= url($prefix . '/returns?status=' . $filters['status']) ?>" 
           class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-300 text-xs font-medium rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition duration-150">
            Reset
        </a>
    </form>

    <!-- Scan QR Code Button -->
    <?php if ($filters['status'] === 'dipinjam'): ?>
        <div class="flex items-center gap-2 flex-shrink-0">
            <button type="button" onclick="openCameraScanner()" 
                    class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-bold rounded-lg text-white bg-teal-600 hover:bg-teal-700 shadow-sm transition duration-150">
                <i data-lucide="scan-line" class="w-4 h-4 mr-1.5 animate-pulse"></i>
                Scan QR Kamera
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- List of Borrowings Table -->
<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
    <div class="px-6 py-4 border-b border-slate-150 bg-slate-50 flex-shrink-0">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">
            <?= $filters['status'] === 'dikembalikan' ? 'Riwayat Pengembalian Selesai' : 'Daftar Pinjaman Aktif (Siap Dikembalikan)' ?>
        </h2>
    </div>

    <div class="overflow-x-auto">
        <?php if (empty($borrowings)): ?>
            <!-- Empty state -->
            <div class="p-12 text-center bg-white">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                    <i data-lucide="check-square" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-800">Tidak ada data ditemukan</h3>
                <p class="text-xs text-slate-500 mt-1">Tidak ada transaksi peminjaman yang sesuai dengan filter di atas.</p>
            </div>
        <?php else: ?>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase bg-slate-100 text-slate-500">
                        <th class="px-6 py-3 w-20">ID</th>
                        <th class="px-6 py-3">Nama Peminjam</th>
                        <th class="px-6 py-3 w-32">Identitas</th>
                        <th class="px-6 py-3 w-40">Tanggal Pinjam</th>
                        <th class="px-6 py-3 w-40">Batas Kembali</th>
                        <th class="px-6 py-3">Rincian Barang</th>
                        <th class="px-6 py-3 w-28">Status</th>
                        <th class="px-6 py-3 w-28 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($borrowings as $b): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-3.5 font-bold text-slate-700">#<?= $b['id'] ?></td>
                            <td class="px-6 py-3.5">
                                <div class="flex flex-col">
                                    <span class="font-medium text-slate-800"><?= esc($b['borrower_name']) ?></span>
                                    <span class="text-[10px] text-slate-400 capitalize"><?= esc($b['borrower_type']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-slate-600 font-semibold"><?= esc($b['borrower_identity']) ?></td>
                            <td class="px-6 py-3.5 text-xs text-slate-500 font-medium"><?= date('d M Y', strtotime($b['loan_date'])) ?></td>
                            <td class="px-6 py-3.5 text-xs font-medium <?= ($b['status'] === 'dipinjam' && strtotime($b['due_date']) < time()) ? 'text-rose-600 font-bold' : 'text-slate-500' ?>">
                                <?= date('d M Y', strtotime($b['due_date'])) ?>
                                <?php if ($b['status'] === 'dipinjam' && strtotime($b['due_date']) < time()): ?>
                                    <span class="block text-[9px] text-rose-500 uppercase tracking-tighter mt-0.5">Terlambat</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-slate-600">
                                <div class="max-w-[220px] truncate" title="<?php 
                                    $itemNames = array_map(function($i) { return $i['item_name'] . ' (' . $i['quantity'] . ')'; }, $b['items']);
                                    echo esc(implode(', ', $itemNames));
                                 ?>">
                                    <?= esc($b['items'][0]['item_name']) ?>
                                    <span class="text-[10px] text-slate-400 font-medium">(x<?= $b['items'][0]['quantity'] ?>)</span>
                                    <?php if (count($b['items']) > 1): ?>
                                        <span class="block text-[10px] text-teal-600 font-semibold">+<?= count($b['items']) - 1 ?> barang lainnya</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <?php if ($b['status'] === 'dipinjam'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-250 uppercase">
                                        Dipinjam
                                    </span>
                                <?php elseif ($b['status'] === 'dikembalikan'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-500 border border-slate-200 uppercase">
                                        Kembali
                                    </span>
                                <?php elseif ($b['status'] === 'terlambat' || (strtotime($b['due_date']) < time() && $b['status'] === 'dipinjam')): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-100 text-rose-800 border border-rose-200 uppercase">
                                        Terlambat
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3.5 text-right whitespace-nowrap space-x-2">
                                <?php if ($b['status'] === 'dipinjam' || $b['status'] === 'terlambat'): ?>
                                    <a href="<?= url($prefix . '/returns/create/' . $b['id']) ?>" 
                                       class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-semibold rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition duration-150">
                                        <i data-lucide="check-square" class="w-3.5 h-3.5 mr-1"></i>
                                        Kembalikan
                                    </a>
                                <?php else: ?>
                                    <a href="<?= url($prefix . '/borrowings/view/' . $b['id']) ?>" 
                                       class="inline-flex items-center text-slate-500 hover:text-teal-600 transition" 
                                       title="Lihat Detail">
                                        <i data-lucide="eye" class="w-3.5 h-3.5 mr-1"></i>
                                        Detail
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Camera QR Scan Modal -->
<div id="cameraScannerModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-xl border border-slate-200 shadow-2xl overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="scan-line" class="w-5 h-5 text-teal-600"></i>
                <h3 class="text-sm font-bold text-slate-800">Scan QR Kamera Realtime</h3>
            </div>
            <button onclick="closeCameraScanner()" class="text-slate-400 hover:text-slate-600 transition">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <!-- Scanner Area -->
        <div class="p-6 flex flex-col items-center justify-center">
            <!-- Reader container -->
            <div id="qrReader" class="w-full overflow-hidden rounded-lg bg-black border border-slate-200 aspect-square flex items-center justify-center text-white relative">
                <div class="text-xs text-slate-400 text-center p-6" id="qrReaderPlaceholder">
                    <i data-lucide="video" class="w-8 h-8 text-slate-500 mx-auto mb-2 animate-bounce"></i>
                    Menginisialisasi kamera...
                </div>
            </div>
            
            <p class="text-[10px] text-slate-500 mt-4 text-center">Arahkan kamera ke QR Code label barang fisik untuk melacak peminjaman secara otomatis.</p>
            
            <!-- Borrowings selector container (hidden by default) -->
            <div id="borrowingsListContainer" class="w-full mt-4 border-t border-slate-100 pt-4 hidden">
                <h4 class="text-xs font-bold text-slate-700 mb-2">Pilih Peminjam yang Mengembalikan:</h4>
                <div id="borrowingsList" class="space-y-2 max-h-48 overflow-y-auto pr-1"></div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50 flex justify-end">
            <button onclick="closeCameraScanner()" class="px-4 py-2 border border-slate-300 text-xs font-semibold rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- html5-qrcode library from CDN -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let html5QrcodeScanner = null;

    function playBeep(success) {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            if (success) {
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(880, audioCtx.currentTime);
                gainNode.gain.setValueAtTime(0.05, audioCtx.currentTime);
                oscillator.start();
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.15);
                oscillator.stop(audioCtx.currentTime + 0.15);
            } else {
                oscillator.type = 'sawtooth';
                oscillator.frequency.setValueAtTime(150, audioCtx.currentTime);
                gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
                oscillator.start();
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
                oscillator.stop(audioCtx.currentTime + 0.3);
            }
        } catch (e) {}
    }

    function openCameraScanner() {
        document.getElementById('cameraScannerModal').classList.remove('hidden');
        document.getElementById('cameraScannerModal').classList.add('flex');
        document.getElementById('borrowingsListContainer').classList.add('hidden');
        document.getElementById('borrowingsList').innerHTML = '';
        document.getElementById('qrReaderPlaceholder').style.display = 'block';
        
        // Initialize HTML5 QR Code
        setTimeout(() => {
            html5QrcodeScanner = new Html5Qrcode("qrReader");
            html5QrcodeScanner.start(
                { facingMode: "environment" }, 
                {
                    fps: 10,
                    qrbox: function(width, height) {
                        return { width: Math.min(width * 0.7, 250), height: Math.min(height * 0.7, 250) };
                    }
                },
                onScanSuccess,
                onScanFailure
            ).then(() => {
                document.getElementById('qrReaderPlaceholder').style.display = 'none';
            }).catch(err => {
                console.error("Gagal inisialisasi kamera:", err);
                document.getElementById('qrReaderPlaceholder').innerHTML = `
                    <i data-lucide="video-off" class="w-8 h-8 text-rose-500 mx-auto mb-2"></i>
                    Gagal mengakses kamera.<br>Pastikan izin kamera aktif & menggunakan HTTPS.
                `;
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        }, 300);
    }

    function closeCameraScanner() {
        document.getElementById('cameraScannerModal').classList.remove('flex');
        document.getElementById('cameraScannerModal').classList.add('hidden');
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then(() => {
                html5QrcodeScanner = null;
            }).catch(err => console.error("Gagal menghentikan scanner:", err));
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Pause scanner
        if (html5QrcodeScanner) {
            html5QrcodeScanner.pause(true);
        }

        const itemCode = decodedText.trim();
        playBeep(true);

        const ajaxUrl = `<?= url($prefix . '/returns/find_by_item_code') ?>?code=${encodeURIComponent(itemCode)}`;
        
        fetch(ajaxUrl)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.borrowings.length === 1) {
                        const borrowingId = data.borrowings[0].id;
                        window.location.href = `<?= url($prefix . '/returns/create') ?>/${borrowingId}?highlight=${encodeURIComponent(itemCode)}`;
                    } else {
                        // Multiple active borrowings for this item
                        document.getElementById('borrowingsListContainer').classList.remove('hidden');
                        const listDiv = document.getElementById('borrowingsList');
                        listDiv.innerHTML = '';
                        
                        data.borrowings.forEach(b => {
                            const btn = document.createElement('a');
                            btn.href = `<?= url($prefix . '/returns/create') ?>/${b.id}?highlight=${encodeURIComponent(itemCode)}`;
                            btn.className = "flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-lg hover:bg-teal-50 hover:border-teal-300 transition text-xs font-semibold text-slate-800";
                            btn.innerHTML = `
                                <div>
                                    <div class="font-bold text-slate-900">${b.borrower_name} (#${b.id})</div>
                                    <div class="text-[10px] text-slate-500 font-medium">NIM/NIP: ${b.borrower_identity} | Pinjam: ${b.loan_date}</div>
                                </div>
                                <i data-lucide="arrow-right" class="w-4 h-4 text-teal-600"></i>
                            `;
                            listDiv.appendChild(btn);
                        });
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }
                } else {
                    playBeep(false);
                    alert(data.message || 'Barang tidak terdeteksi dalam sirkulasi aktif');
                    if (html5QrcodeScanner) {
                        html5QrcodeScanner.resume();
                    }
                }
            })
            .catch(err => {
                console.error("AJAX Error:", err);
                playBeep(false);
                alert("Koneksi gagal untuk melacak QR.");
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.resume();
                }
            });
    }

    function onScanFailure(error) {
        // Silent
    }
</script>
