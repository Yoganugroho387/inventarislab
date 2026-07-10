<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat Jalan Peminjaman - <?= esc($institution_name) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col justify-between text-slate-800">

    <!-- Header / Navbar -->
    <header class="bg-white border-b border-slate-200 py-4 px-6 shadow-sm">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <?php if ($institution_logo && file_exists('./uploads/' . $institution_logo)): ?>
                    <img src="<?= base_url('uploads/' . $institution_logo) ?>" alt="Logo Instansi" class="w-9 h-9 object-contain">
                <?php else: ?>
                    <div class="w-9 h-9 rounded bg-teal-50 border border-teal-200 flex items-center justify-center text-teal-600">
                        <i data-lucide="school" class="w-5 h-5"></i>
                    </div>
                <?php endif; ?>
                <div>
                    <h1 class="text-xs font-bold text-slate-700 uppercase tracking-wide"><?= esc($institution_name) ?></h1>
                    <p class="text-[10px] text-slate-455 font-medium">Sistem Verifikasi Dokumen Digital</p>
                </div>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-teal-50 text-teal-700 border border-teal-200 uppercase">
                <i data-lucide="shield-check" class="w-3.5 h-3.5 mr-1 flex-shrink-0"></i>
                Official Check
            </span>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 py-10 px-4 flex items-center justify-center">
        <div class="w-full max-w-lg bg-white rounded-xl border border-slate-200 shadow-lg overflow-hidden">
            
            <!-- Dynamic Verification Banner based on Status -->
            <?php if ($borrowing['status'] === 'dipinjam'): ?>
                <div class="bg-teal-50 border-b border-teal-100 p-6 text-center">
                    <div class="w-14 h-14 bg-teal-650 rounded-full flex items-center justify-center text-white mx-auto shadow-md mb-3">
                        <i data-lucide="activity" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-lg font-bold text-teal-800">PEMINJAMAN AKTIF</h2>
                    <p class="text-xs text-teal-600 font-medium mt-1">Barang berada di tangan peminjam secara resmi</p>
                </div>
            <?php elseif ($borrowing['status'] === 'dikembalikan'): ?>
                <div class="bg-emerald-50 border-b border-emerald-100 p-6 text-center">
                    <div class="w-14 h-14 bg-emerald-500 rounded-full flex items-center justify-center text-white mx-auto shadow-md mb-3">
                        <i data-lucide="check-circle-2" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-lg font-bold text-emerald-800">PEMINJAMAN SELESAI</h2>
                    <p class="text-xs text-emerald-600 font-medium mt-1">Seluruh barang telah dikembalikan dengan aman</p>
                </div>
            <?php elseif ($borrowing['status'] === 'terlambat'): ?>
                <div class="bg-rose-50 border-b border-rose-100 p-6 text-center animate-pulse">
                    <div class="w-14 h-14 bg-rose-500 rounded-full flex items-center justify-center text-white mx-auto shadow-md mb-3">
                        <i data-lucide="alert-octagon" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-lg font-bold text-rose-800">PEMINJAMAN TERLAMBAT</h2>
                    <p class="text-xs text-rose-600 font-medium mt-1">Batas waktu pengembalian telah terlampaui!</p>
                </div>
            <?php elseif ($borrowing['status'] === 'disetujui'): ?>
                <div class="bg-blue-50 border-b border-blue-100 p-6 text-center">
                    <div class="w-14 h-14 bg-blue-500 rounded-full flex items-center justify-center text-white mx-auto shadow-md mb-3">
                        <i data-lucide="calendar-check" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-lg font-bold text-blue-800">RESERVASI DISETUJUI</h2>
                    <p class="text-xs text-blue-600 font-medium mt-1">Status disetujui, siap diserahterimakan</p>
                </div>
            <?php elseif ($borrowing['status'] === 'menunggu'): ?>
                <div class="bg-amber-50 border-b border-amber-100 p-6 text-center">
                    <div class="w-14 h-14 bg-amber-500 rounded-full flex items-center justify-center text-white mx-auto shadow-md mb-3">
                        <i data-lucide="clock" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-lg font-bold text-amber-800">MENUNGGU PERSETUJUAN</h2>
                    <p class="text-xs text-amber-600 font-medium mt-1">Menunggu validasi & persetujuan dari laboran</p>
                </div>
            <?php else: ?>
                <div class="bg-slate-100 border-b border-slate-200 p-6 text-center">
                    <div class="w-14 h-14 bg-slate-500 rounded-full flex items-center justify-center text-white mx-auto shadow-md mb-3">
                        <i data-lucide="x-circle" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800">PEMINJAMAN DITOLAK</h2>
                    <p class="text-xs text-slate-500 font-medium mt-1">Pengajuan peminjaman ditolak</p>
                </div>
            <?php endif; ?>

            <!-- Details Panel -->
            <div class="p-6 space-y-5">
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Identitas Peminjam</h3>
                    <div class="grid grid-cols-3 gap-y-2 text-xs">
                        <div class="text-slate-400 font-medium">Transaksi</div>
                        <div class="col-span-2 font-bold text-slate-800">#<?= $borrowing['id'] ?></div>

                        <div class="text-slate-400 font-medium">Nama Peminjam</div>
                        <div class="col-span-2 font-bold text-slate-800"><?= esc($borrowing['borrower_name']) ?></div>

                        <div class="text-slate-400 font-medium">NIM / NIP</div>
                        <div class="col-span-2 font-semibold text-slate-700"><?= esc($borrowing['borrower_identity']) ?></div>

                        <div class="text-slate-400 font-medium">No. WhatsApp</div>
                        <div class="col-span-2 font-semibold text-slate-700"><?= esc($borrowing['borrower_phone'] ?: '-') ?></div>

                        <div class="text-slate-400 font-medium">Keperluan</div>
                        <div class="col-span-2 text-slate-650 leading-relaxed font-semibold italic"><?= esc($borrowing['purpose'] ?: '-') ?></div>
                    </div>
                </div>

                <hr class="border-slate-150">

                <!-- Timeline / Dates -->
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Agenda Peminjaman</h3>
                    <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-3 rounded border border-slate-200">
                        <div>
                            <span class="block text-[10px] text-slate-400 font-bold uppercase">Tanggal Pinjam</span>
                            <span class="font-bold text-slate-750 mt-1 block"><?= date('d M Y', strtotime($borrowing['loan_date'])) ?></span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-slate-400 font-bold uppercase">Batas Pengembalian</span>
                            <span class="font-bold text-slate-750 mt-1 block"><?= date('d M Y', strtotime($borrowing['due_date'])) ?></span>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-150">

                <!-- Items Table -->
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Rincian Barang Peminjaman</h3>
                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold uppercase text-slate-500">
                                    <th class="px-4 py-2">Kode</th>
                                    <th class="px-4 py-2">Nama Barang</th>
                                    <th class="px-4 py-2 text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach ($borrowing['items'] as $item): ?>
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-2 font-semibold text-slate-700"><?= esc($item['item_code']) ?></td>
                                        <td class="px-4 py-2 text-slate-600 font-medium"><?= esc($item['item_name']) ?></td>
                                        <td class="px-4 py-2 text-center font-bold text-slate-800"><?= $item['quantity'] ?> <span class="text-[9px] text-slate-400 font-normal"><?= esc($item['unit_name']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="border-slate-150">

                <!-- Approval Metadata -->
                <div class="grid grid-cols-2 gap-4 text-[10px] text-slate-400 font-semibold uppercase tracking-wide">
                    <div>Input Oleh: <strong class="text-slate-655 font-bold"><?= esc($borrowing['creator_name']) ?></strong></div>
                    <div class="text-right">Persetujuan: <strong class="text-slate-655 font-bold"><?= esc($borrowing['approver_name'] ?: '-') ?></strong></div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 px-6 text-center text-[10px] text-slate-450 font-medium">
        &copy; <?= date('Y') ?> <?= esc($institution_name) ?>. All rights reserved.
    </footer>

    <script>
        // Init Lucide
        lucide.createIcons();
    </script>
</body>
</html>
