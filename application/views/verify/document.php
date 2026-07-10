<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat Bebas Lab - <?= esc($institution_name) ?></title>
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
                    <p class="text-[10px] text-slate-450 font-medium">Sistem Verifikasi Dokumen Digital</p>
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
            <!-- Alert Badge / Top Section -->
            <?php if ($is_free): ?>
                <!-- Verified Green Section -->
                <div class="bg-emerald-50 border-b border-emerald-100 p-6 text-center">
                    <div class="w-14 h-14 bg-emerald-500 rounded-full flex items-center justify-center text-white mx-auto shadow-md mb-3">
                        <i data-lucide="check-circle-2" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-lg font-bold text-emerald-800">DOKUMEN VALID & TERVERIFIKASI</h2>
                    <p class="text-xs text-emerald-600 font-medium mt-1">Sistem menyatakan bebas tanggungan pinjaman laboratorium</p>
                </div>
            <?php else: ?>
                <!-- Warning Red Section -->
                <div class="bg-rose-50 border-b border-rose-100 p-6 text-center">
                    <div class="w-14 h-14 bg-rose-500 rounded-full flex items-center justify-center text-white mx-auto shadow-md mb-3">
                        <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-lg font-bold text-rose-800">DOKUMEN TANGGUH / BELUM BEBAS</h2>
                    <p class="text-xs text-rose-600 font-medium mt-1">Mahasiswa masih memiliki tanggungan pinjaman alat</p>
                </div>
            <?php endif; ?>

            <!-- Document Details -->
            <div class="p-6 space-y-5">
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Data Mahasiswa / Peminjam</h3>
                    <div class="grid grid-cols-3 gap-y-2 text-xs">
                        <div class="text-slate-400 font-medium">NIM / NIP</div>
                        <div class="col-span-2 font-bold text-slate-800"><?= esc($identity) ?></div>

                        <div class="text-slate-400 font-medium">Nama Lengkap</div>
                        <div class="col-span-2 font-semibold text-slate-700"><?= esc($student_info['borrower_name'] ?? 'Tidak Terdaftar/Baru') ?></div>

                        <div class="text-slate-400 font-medium">Status / Peran</div>
                        <div class="col-span-2"><span class="capitalize text-[10px] px-2 py-0.5 rounded font-bold bg-slate-100 text-slate-600 border border-slate-200"><?= esc($student_info['borrower_type'] ?? '-') ?></span></div>
                    </div>
                </div>

                <hr class="border-slate-150">

                <?php if (!$is_free): ?>
                    <!-- Tanggungan Items List -->
                    <div>
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Barang Yang Belum Dikembalikan</h3>
                        <div class="space-y-2">
                            <?php foreach ($active_loans as $loan): ?>
                                <div class="bg-slate-50 border border-slate-200 rounded p-3 text-xs flex flex-col gap-1">
                                    <div class="flex items-center justify-between font-semibold text-slate-800">
                                        <span>Transaksi Peminjaman #<?= $loan['id'] ?></span>
                                        <span class="text-[10px] text-rose-600 font-bold uppercase">Belum Kembali</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 font-medium mt-0.5">
                                        Tanggal Pinjam: <?= date('d M Y', strtotime($loan['loan_date'])) ?><br>
                                        Batas Kembali: <?= date('d M Y', strtotime($loan['due_date'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Clean State Info -->
                    <div class="flex items-start space-x-2.5 bg-emerald-50/40 border border-emerald-200 p-3 rounded">
                        <i data-lucide="info" class="w-4 h-4 text-emerald-600 mt-0.5 flex-shrink-0"></i>
                        <p class="text-[11px] text-emerald-700 leading-relaxed font-medium">
                            Mahasiswa dengan identitas di atas dinyatakan **bersih** dan **tidak memiliki tunggungan** inventaris apapun di laboratorium. Surat Bebas Lab yang dicetak adalah sah dan terdaftar secara resmi di pangkalan data inventaris laboratorium.
                        </p>
                    </div>
                <?php endif; ?>

                <hr class="border-slate-150">

                <!-- Meta Footer -->
                <div class="flex items-center justify-between text-[10px] text-slate-400 font-semibold uppercase tracking-wide">
                    <span>Verifikator: Pangkalan Data Lab</span>
                    <span>Waktu Cek: <?= date('d M Y H:i') ?></span>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 px-6 text-center text-[10px] text-slate-400 font-medium">
        &copy; <?= date('Y') ?> <?= esc($institution_name) ?>. All rights reserved.
    </footer>

    <script>
        // Init Lucide
        lucide.createIcons();
    </script>
</body>
</html>
