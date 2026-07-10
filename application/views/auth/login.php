<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Login View
 */
?>
<div class="sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
    <div class="bg-white py-8 px-6 shadow sm:rounded-lg sm:px-10 border border-slate-200">
        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-teal-50 text-teal-600 mb-2.5">
                <i data-lucide="box" class="w-6 h-6"></i>
            </div>
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Sistem Inventaris Lab</h2>
            <p class="text-xs text-slate-500 mt-1">Gunakan akun Anda untuk masuk ke dashboard</p>
        </div>

        <!-- Form -->
        <form class="space-y-4" action="<?= url('/login') ?>" method="POST" autocomplete="off">
            <?= csrf_field() ?>

            <!-- Username Field -->
            <div>
                <label for="username" class="block text-xs font-semibold text-slate-700">Username</label>
                <div class="mt-1 relative rounded shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="user" class="h-4 w-4 text-slate-400"></i>
                    </div>
                    <input type="text" name="username" id="username" value="<?= esc(old('username')) ?>" required
                           class="block w-full pl-9 pr-3 py-2 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                           placeholder="Masukkan username">
                </div>
                <?php if (error('username')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('username')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700">Password</label>
                <div class="mt-1 relative rounded shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="h-4 w-4 text-slate-400"></i>
                    </div>
                    <input type="password" name="password" id="password" required
                           class="block w-full pl-9 pr-3 py-2 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                           placeholder="Masukkan password">
                </div>
                <?php if (error('password')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('password')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Submit Button -->
            <div class="pt-1">
                <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-xs font-semibold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-teal-500 transition duration-150">
                    Masuk ke Sistem
                </button>
            </div>
        </form>
    </div>
</div>
