<!--
============================================================================
Sistem Inventaris Laboratorium - Landing Page
Developed by : Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
Open Jasa Pembuatan Website & Joki Tugas Website
============================================================================
-->
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#0d9488">
    <title>Sistem Inventaris Lab - Manajemen Aset Laboratorium</title>
    <meta name="description" content="Sistem Informasi Inventaris Laboratorium - Kelola aset, peminjaman, dan pengembalian barang lab secara digital.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes float { 0%,100% { transform: translateY(0px); } 50% { transform: translateY(-12px); } }
        .float-anim { animation: float 4s ease-in-out infinite; }
        .float-anim-delay { animation: float 4s ease-in-out 1s infinite; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        .fade-in-up-delay { animation: fadeInUp 0.8s ease-out 0.2s forwards; opacity: 0; }
        .fade-in-up-delay2 { animation: fadeInUp 0.8s ease-out 0.4s forwards; opacity: 0; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center text-white">
                    <i data-lucide="box" class="w-4 h-4"></i>
                </div>
                <span class="font-bold text-slate-900 text-sm tracking-tight">Inventaris Lab</span>
            </div>
            <a href="<?= url('/login') ?>" 
               class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
                <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                Masuk
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-28 pb-20 sm:pt-36 sm:pb-28 overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 -z-10">
            <div class="absolute top-20 -left-20 w-72 h-72 bg-teal-100 rounded-full mix-blend-multiply filter blur-3xl opacity-40 float-anim"></div>
            <div class="absolute top-40 -right-20 w-72 h-72 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-40 float-anim-delay"></div>
            <div class="absolute -bottom-10 left-1/3 w-72 h-72 bg-cyan-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 float-anim"></div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 text-center">
            <!-- Badge -->
            <div class="fade-in-up inline-flex items-center gap-2 px-3 py-1.5 bg-teal-50 border border-teal-200 rounded-full text-[11px] font-semibold text-teal-700 mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span>
                Sistem Informasi Manajemen Laboratorium
            </div>

            <!-- Main Heading -->
            <h1 class="fade-in-up text-3xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight max-w-4xl mx-auto">
                Kelola <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-500">Inventaris Lab</span> dengan Mudah & Terstruktur
            </h1>

            <!-- Subtitle -->
            <p class="fade-in-up-delay max-w-2xl mx-auto mt-5 text-sm sm:text-base text-slate-500 leading-relaxed font-medium">
                Sistem digital untuk manajemen aset laboratorium, pencatatan peminjaman, pengembalian barang, dan pelaporan stok secara real-time.
            </p>

            <!-- CTA Buttons -->
            <div class="fade-in-up-delay2 flex flex-col sm:flex-row items-center justify-center gap-3 mt-8">
                <a href="<?= url('/login') ?>" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    Masuk ke Dashboard
                </a>
                <a href="#features" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl shadow-sm transition-all duration-200">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    Pelajari Fitur
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 sm:py-24 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Fitur Unggulan</h2>
                <p class="text-sm text-slate-500 mt-2 max-w-lg mx-auto font-medium">Solusi lengkap untuk pengelolaan inventaris laboratorium kampus Anda.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Feature 1 -->
                <div class="group p-6 rounded-xl border border-slate-200 bg-white hover:shadow-lg hover:border-teal-200 transition-all duration-300">
                    <div class="w-11 h-11 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mb-4 group-hover:bg-teal-100 transition">
                        <i data-lucide="box" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1.5">Manajemen Barang</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Kelola data inventaris dengan kategori, lokasi, satuan, kode unik, dan pelacakan kondisi fisik barang.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group p-6 rounded-xl border border-slate-200 bg-white hover:shadow-lg hover:border-teal-200 transition-all duration-300">
                    <div class="w-11 h-11 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:bg-emerald-100 transition">
                        <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1.5">Sirkulasi Peminjaman</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Sistem peminjaman dan pengembalian barang dengan alur persetujuan, pencatatan batas waktu, dan verifikasi kondisi.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group p-6 rounded-xl border border-slate-200 bg-white hover:shadow-lg hover:border-teal-200 transition-all duration-300">
                    <div class="w-11 h-11 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center mb-4 group-hover:bg-cyan-100 transition">
                        <i data-lucide="file-bar-chart" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1.5">Laporan & Export</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Generate laporan stok, transaksi, dan kondisi barang dengan filter lengkap serta fitur export dan cetak.</p>
                </div>

                <!-- Feature 4 -->
                <div class="group p-6 rounded-xl border border-slate-200 bg-white hover:shadow-lg hover:border-teal-200 transition-all duration-300">
                    <div class="w-11 h-11 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mb-4 group-hover:bg-amber-100 transition">
                        <i data-lucide="qr-code" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1.5">Cetak Label QR Code</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Generate dan cetak label QR Code untuk ditempel di barang fisik, mempermudah identifikasi dan pendataan aset.</p>
                </div>

                <!-- Feature 5 -->
                <div class="group p-6 rounded-xl border border-slate-200 bg-white hover:shadow-lg hover:border-teal-200 transition-all duration-300">
                    <div class="w-11 h-11 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center mb-4 group-hover:bg-rose-100 transition">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1.5">Multi-Role Akses</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Dua level akses: Admin untuk pengelolaan penuh, dan Laboran/Staff untuk operasional sirkulasi.</p>
                </div>

                <!-- Feature 6 -->
                <div class="group p-6 rounded-xl border border-slate-200 bg-white hover:shadow-lg hover:border-teal-200 transition-all duration-300">
                    <div class="w-11 h-11 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center mb-4 group-hover:bg-violet-100 transition">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1.5">Log Aktivitas</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Rekam jejak seluruh aktivitas pengguna untuk audit trail dan keamanan data laboratorium.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 sm:py-20 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-1/4 w-64 h-64 bg-teal-500 rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-64 h-64 bg-emerald-500 rounded-full filter blur-3xl"></div>
        </div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center relative z-10">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mb-3">Siap Mengelola Inventaris Lab?</h2>
            <p class="text-sm text-slate-400 mb-8 font-medium max-w-lg mx-auto">Masuk ke dashboard untuk mulai mengelola barang, peminjaman, dan laporan laboratorium Anda.</p>
            <a href="<?= url('/login') ?>" 
               class="inline-flex items-center gap-2 px-8 py-3.5 text-sm font-bold text-slate-900 bg-white hover:bg-slate-100 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                <i data-lucide="log-in" class="w-4 h-4"></i>
                Masuk Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-2.5">
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center text-white">
                        <i data-lucide="box" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="font-bold text-slate-800 text-xs">Sistem Inventaris Lab</span>
                </div>
                
                <div class="text-center sm:text-right">
                    <p class="text-[11px] text-slate-400 font-medium">
                        &copy; <?= date('Y') ?> <span class="text-slate-600 font-semibold">Yoga Nugroho</span> &mdash; All Rights Reserved.
                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5">089685027530 | Open Jasa Pembuatan Website &amp; Joki Tugas Website</p>
                    <a href="https://tako.id/YNGRHO" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-semibold text-teal-600 hover:text-teal-800 transition mt-1">
                        ♥ Support Developer
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        if (typeof lucide !== 'undefined') { lucide.createIcons(); }
        // Anti-theft
        document.addEventListener('contextmenu', function(e) { e.preventDefault(); });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && 'IJC'.includes(e.key.toUpperCase())) || (e.ctrlKey && 'US'.includes(e.key.toUpperCase()))) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
