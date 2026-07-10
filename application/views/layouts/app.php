<?php
/**
 * Main Application Layout Wrapper
 * Developed by: Yoga Nugroho | 089685027530
 * Open Jasa Pembuatan Website & Joki Tugas Website
 */

// Load Header HTML
require APPPATH . 'views/layouts/header.php';
?>

<!-- Mobile Sidebar Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity duration-300" onclick="toggleSidebar()"></div>

<!-- App Core Shell -->
<div class="flex h-screen overflow-hidden">
    <!-- Left Sidebar (hidden on mobile by default) -->
    <div id="sidebarPanel" class="fixed inset-y-0 left-0 z-50 w-64 h-full transform -translate-x-full lg:translate-x-0 lg:static lg:inset-auto lg:h-full transition-transform duration-300 ease-in-out">
        <?php require APPPATH . 'views/layouts/sidebar.php'; ?>
    </div>

    <!-- Right Content Area -->
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50 overflow-hidden w-full">
        <!-- Top Navbar -->
        <header class="flex items-center justify-between px-4 sm:px-6 py-3 bg-white border-b border-slate-200 z-10 flex-shrink-0">
            <!-- Left: Hamburger + Breadcrumb -->
            <div class="flex items-center space-x-3">
                <!-- Hamburger Menu (mobile only) -->
                <button id="hamburgerBtn" onclick="toggleSidebar()" class="lg:hidden p-1.5 -ml-1 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <!-- Breadcrumb Navigation -->
                <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium">
                    <span class="text-slate-400 hidden sm:inline">Sistem Inventaris</span>
                    <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400 hidden sm:block"></i>
                    <span class="text-slate-700 capitalize"><?= esc($title ?? 'Dashboard') ?></span>
                </div>
            </div>
            
            <!-- Quick Date Badge -->
            <div class="flex items-center text-xs text-slate-500 font-medium bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200">
                <i data-lucide="calendar" class="w-3.5 h-3.5 mr-1.5 text-slate-400"></i>
                <span><?= date('d M Y') ?></span>
            </div>
        </header>

        <!-- Main Scrollable Pane -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 min-w-0">
            <!-- Flash Message Alerts -->
            <?php if ($this->session->flashdata('success') !== NULL): ?>
                <div class="mb-5 flex items-center p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded text-xs leading-none shadow-sm transition-all duration-300">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-2 text-emerald-600 flex-shrink-0"></i>
                    <span class="font-medium"><?= esc($this->session->flashdata('success')) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error') !== NULL): ?>
                <div class="mb-5 flex items-center p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded text-xs leading-none shadow-sm transition-all duration-300">
                    <i data-lucide="alert-circle" class="w-4 h-4 mr-2 text-rose-600 flex-shrink-0"></i>
                    <span class="font-medium"><?= esc($this->session->flashdata('error')) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('warning') !== NULL): ?>
                <div class="mb-5 flex items-center p-3.5 bg-amber-50 border border-amber-200 text-amber-800 rounded text-xs leading-none shadow-sm transition-all duration-300">
                    <i data-lucide="alert-triangle" class="w-4 h-4 mr-2 text-amber-600 flex-shrink-0"></i>
                    <span class="font-medium"><?= esc($this->session->flashdata('warning')) ?></span>
                </div>
            <?php endif; ?>

            <!-- Page Title Header -->
            <?php if (isset($pageTitle)): ?>
                <div class="mb-6">
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-900 tracking-tight leading-none"><?= esc($pageTitle) ?></h1>
                    <?php if (isset($pageSubtitle)): ?>
                        <p class="text-xs text-slate-500 mt-1 font-medium"><?= esc($pageSubtitle) ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Injected Inner Content -->
            <?= $content ?>
        </main>

        <!-- Copyright Footer -->
        <footer class="flex-shrink-0 border-t border-slate-200 bg-white px-4 sm:px-6 py-2.5 flex items-center justify-between print:hidden">
            <p class="text-[10px] text-slate-400 font-medium">
                &copy; <?= date('Y') ?> <span class="text-slate-600 font-semibold">Yoga Nugroho</span> &mdash; All Rights Reserved.
                <span class="hidden sm:inline text-slate-400">| 089685027530 | Open Jasa Pembuatan Website &amp; Joki Tugas Website</span>
            </p>
            <a href="<?= url('/support-developer') ?>" class="inline-flex items-center gap-1 text-[10px] font-semibold text-teal-600 hover:text-teal-800 transition">
                <i data-lucide="heart" class="w-3 h-3"></i>
                <span class="hidden sm:inline">Support Developer</span>
            </a>
        </footer>
    </div>
</div>

<!-- Sidebar Toggle Script -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebarPanel');
        const overlay = document.getElementById('sidebarOverlay');
        const isOpen = !sidebar.classList.contains('-translate-x-full');
        
        if (isOpen) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        } else {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    // Close sidebar on resize to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            const sidebar = document.getElementById('sidebarPanel');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
</script>

<?php
// Load Footer HTML (includes script execution)
require APPPATH . 'views/layouts/footer.php';
?>
