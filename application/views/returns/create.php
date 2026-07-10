<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Return Form View
 */
?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left 1/3: Summary of the active loan -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm h-fit">
        <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-100 pb-2 mb-3">Informasi Peminjaman</h3>
        <div class="space-y-3.5 text-xs text-slate-700">
            <div>
                <span class="block text-slate-400 font-semibold uppercase text-[9px]">ID Peminjaman</span>
                <span class="font-bold text-slate-800">#<?= $borrowing['id'] ?></span>
            </div>
            <div>
                <span class="block text-slate-400 font-semibold uppercase text-[9px]">Nama Peminjam</span>
                <span class="font-bold text-slate-900 text-sm"><?= esc($borrowing['borrower_name']) ?></span>
            </div>
            <div>
                <span class="block text-slate-400 font-semibold uppercase text-[9px]">NIM / NIP Identitas</span>
                <span class="font-semibold text-slate-800"><?= esc($borrowing['borrower_identity']) ?></span>
            </div>
            <div>
                <span class="block text-slate-400 font-semibold uppercase text-[9px]">Tanggal Pinjam</span>
                <span class="font-semibold text-slate-800"><?= date('d F Y', strtotime($borrowing['loan_date'])) ?></span>
            </div>
            <div>
                <span class="block text-slate-400 font-semibold uppercase text-[9px]">Tenggat Batas Waktu</span>
                <span class="font-bold <?= (strtotime($borrowing['due_date']) < time()) ? 'text-rose-600' : 'text-slate-800' ?>">
                    <?= date('d F Y', strtotime($borrowing['due_date'])) ?>
                    <?php if (strtotime($borrowing['due_date']) < time()): ?>
                        <span class="ml-1 text-[9px] bg-rose-50 text-rose-700 border border-rose-200 px-1 py-0.5 rounded font-bold uppercase">Terlambat</span>
                    <?php endif; ?>
                </span>
            </div>
            <?php if (!empty($borrowing['notes'])): ?>
                <div class="pt-2 border-t border-slate-100 mt-2 bg-slate-50 p-2.5 rounded">
                    <span class="block text-slate-400 font-semibold uppercase text-[9px] mb-1">Catatan Peminjaman</span>
                    <p class="text-slate-650 leading-relaxed font-medium"><?= esc($borrowing['notes']) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right 2/3: Return Items Checklist form -->
    <div class="lg:col-span-2 bg-white rounded-lg border border-slate-200 shadow-sm flex flex-col overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Checklist Pengembalian Barang</h2>
            <a href="<?= url($prefix . '/returns') ?>" class="text-xs text-teal-600 hover:text-teal-800 font-semibold flex items-center">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1"></i>
                Batal
            </a>
        </div>

        <form action="<?= url($prefix . '/returns/store/' . $borrowing['id']) ?>" method="POST" class="p-6 space-y-5 flex-1">
            <?= csrf_field() ?>

            <!-- QR Scanner Input Box with Camera Scan -->
            <div class="p-4 bg-teal-50/50 border border-teal-100 rounded-lg flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="scan-line" class="w-5 h-5 animate-pulse"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">Scan QR Code Barang</h4>
                        <p class="text-[10px] text-slate-500 font-medium">Scan menggunakan kamera HP atau gunakan barcode scanner fisik.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="qr-code" class="w-4 h-4 text-slate-400"></i>
                        </div>
                        <input type="text" id="qrScanInput" autofocus autocomplete="off"
                               class="block w-full pl-9 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition placeholder:text-slate-400"
                               placeholder="Ketik/scan kode barang...">
                    </div>
                    <button type="button" onclick="openCameraScanner()"
                            class="inline-flex items-center justify-center p-2 border border-transparent rounded-lg text-white bg-teal-600 hover:bg-teal-700 shadow-sm transition duration-150 flex-shrink-0"
                            title="Scan via Kamera HP">
                        <i data-lucide="camera" class="w-4.5 h-4.5"></i>
                    </button>
                </div>
            </div>

            <!-- Description guidance -->
            <p class="text-xs text-slate-500 bg-slate-50 border border-slate-200 p-3 rounded leading-relaxed">
                <i data-lucide="info" class="w-4 h-4 mr-1.5 inline text-teal-600 align-middle"></i>
                Silakan verifikasi jumlah fisik barang dan pilih kondisi saat barang diterima kembali. Memilih kondisi <strong class="text-rose-700">"Rusak"</strong> atau <strong class="text-rose-700">"Hilang"</strong> akan memicu catatan audit sistem otomatis.
            </p>

            <!-- Table of Items list to check-in -->
            <div class="space-y-4">
                <?php foreach ($borrowing['items'] as $item): ?>
                    <!-- Skip if already returned in a partial return (if any) -->
                    <?php if (!empty($item['return_date'])) continue; ?>

                    <div class="item-return-card p-4 border border-slate-200 rounded-lg bg-slate-50/40 grid grid-cols-1 md:grid-cols-3 gap-4 transition-all duration-300" data-item-code="<?= esc($item['item_code']) ?>">
                        <!-- Item detail -->
                        <div>
                            <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider block">Spesifikasi Barang</span>
                            <span class="font-bold text-slate-800 block text-xs mt-1 leading-tight"><?= esc($item['item_name']) ?></span>
                            <span class="text-[10px] text-slate-500 font-semibold mt-1 block tracking-wider"><?= esc($item['item_code']) ?></span>
                            <span class="inline-flex mt-1 text-[10px] bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded font-bold uppercase">
                                Qty dipinjam: <?= $item['quantity'] ?> <?= esc($item['unit_name']) ?>
                            </span>
                        </div>

                        <!-- Return Condition selector -->
                        <div>
                            <label for="condition_<?= $item['item_id'] ?>" class="block text-[10px] text-slate-400 font-semibold uppercase tracking-wider mb-1">Kondisi Pengembalian</label>
                            <select name="items[<?= $item['item_id'] ?>][return_condition]" id="condition_<?= $item['item_id'] ?>" required
                                    class="block w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150">
                                <option value="baik">Sempurna / Baik</option>
                                <option value="rusak">Rusak (Pecah/Patah/Bermasalah)</option>
                                <option value="hilang">Hilang / Tidak Kembali</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes_<?= $item['item_id'] ?>" class="block text-[10px] text-slate-400 font-semibold uppercase tracking-wider mb-1">Keterangan / Rincian Kerusakan</label>
                            <textarea name="items[<?= $item['item_id'] ?>][return_notes]" id="notes_<?= $item['item_id'] ?>" rows="1.5"
                                      class="block w-full px-2.5 py-1 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-white text-slate-800 transition duration-150"
                                      placeholder="Ketik catatan kondisi jika ada..."></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Submit action -->
            <div class="flex items-center space-x-2 pt-5 border-t border-slate-150 mt-6">
                <button type="submit" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm focus:outline-none transition duration-150">
                    <i data-lucide="check-square" class="w-4 h-4 mr-1.5"></i>
                    Simpan Pengembalian
                </button>
                
                <a href="<?= url($prefix . '/returns') ?>" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm focus:outline-none transition duration-150">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Camera QR Scan Modal -->
<div id="cameraScannerModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-xl border border-slate-200 shadow-2xl overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="scan-line" class="w-5 h-5 text-teal-600"></i>
                <h3 class="text-sm font-bold text-slate-800">Scan QR Barang</h3>
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
            <p class="text-[10px] text-slate-500 mt-4 text-center">Arahkan kamera ke QR Code label barang fisik untuk memproses otomatis di daftar checklist.</p>
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

<!-- Auto Scan QR Script -->
<script>
    let html5QrcodeScanner = null;

    // Web Audio API beep sound generator
    function playBeep(success) {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            
            if (success) {
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); // High pitch success beep
                gainNode.gain.setValueAtTime(0.05, audioCtx.currentTime);
                oscillator.start();
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.15);
                oscillator.stop(audioCtx.currentTime + 0.15);
            } else {
                oscillator.type = 'sawtooth';
                oscillator.frequency.setValueAtTime(150, audioCtx.currentTime); // Low pitch error buzz
                gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
                oscillator.start();
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
                oscillator.stop(audioCtx.currentTime + 0.3);
            }
        } catch (e) {
            console.error('Audio beep failed', e);
        }
    }

    function openCameraScanner() {
        document.getElementById('cameraScannerModal').classList.remove('hidden');
        document.getElementById('cameraScannerModal').classList.add('flex');
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
                onCameraScanSuccess,
                onCameraScanFailure
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

    function onCameraScanSuccess(decodedText, decodedResult) {
        const code = decodedText.trim().toUpperCase();
        if (code === '') return;

        // Process item matching
        const isMatched = processItemMatch(code);

        if (isMatched) {
            playBeep(true);
            showToast(`Barang "${code}" ditemukan!`, 'success');
            closeCameraScanner();
        } else {
            playBeep(false);
            showToast(`Barang "${code}" tidak terdaftar di peminjaman ini!`, 'error');
        }
    }

    function onCameraScanFailure(error) {
        // Silent
    }

    // Main matching function
    function processItemMatch(code) {
        const cards = document.querySelectorAll('[data-item-code]');
        let found = false;

        cards.forEach(card => {
            const itemCode = card.getAttribute('data-item-code').toUpperCase();
            if (itemCode === code) {
                found = true;
                // Visual confirmation: green highlight
                card.classList.remove('bg-slate-50/40', 'border-slate-200');
                card.classList.add('bg-emerald-50', 'border-emerald-400', 'ring-2', 'ring-emerald-200');
                
                // Autofocus the return condition select element
                const select = card.querySelector('select');
                if (select) {
                    select.focus();
                }

                // Reset highlight after delay
                setTimeout(() => {
                    card.classList.remove('bg-emerald-50', 'border-emerald-400', 'ring-2', 'ring-emerald-200');
                    card.classList.add('bg-slate-50/40', 'border-slate-200');
                }, 2000);

                // Scroll smoothly to item card
                setTimeout(() => {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
            }
        });

        return found;
    }

    document.getElementById('qrScanInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const code = this.value.trim().toUpperCase();
            if (code === '') return;

            const isMatched = processItemMatch(code);

            if (isMatched) {
                playBeep(true);
                showToast(`Barang "${code}" ditemukan!`, 'success');
            } else {
                playBeep(false);
                showToast(`Barang "${code}" tidak terdaftar di peminjaman ini!`, 'error');
            }

            this.value = '';
        }
    });

    function showToast(message, type) {
        const existing = document.getElementById('scanToast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.id = 'scanToast';
        toast.className = `fixed bottom-10 right-6 z-50 px-4 py-3 rounded-lg shadow-lg border text-xs font-semibold flex items-center gap-2 transition-all duration-300 transform translate-y-10 opacity-0`;
        
        if (type === 'success') {
            toast.className += ' bg-emerald-50 text-emerald-800 border-emerald-200';
            toast.innerHTML = `<i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i> <span>${message}</span>`;
        } else {
            toast.className += ' bg-rose-50 text-rose-800 border-rose-200';
            toast.innerHTML = `<i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i> <span>${message}</span>`;
        }

        document.body.appendChild(toast);
        if (typeof lucide !== 'undefined') lucide.createIcons();

        setTimeout(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
        }, 10);

        setTimeout(() => {
            toast.classList.add('translate-y-10', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Auto highlight from query parameter on redirect
    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const highlightCode = urlParams.get('highlight');
        if (highlightCode) {
            setTimeout(() => {
                processItemMatch(highlightCode);
            }, 300);
        }
    });
</script>
