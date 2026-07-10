<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * User Profile View
 */
?>
<div class="max-w-xl bg-white p-6 rounded border border-slate-200 shadow-sm">
    <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Perbarui Profil Anda</h2>
    </div>

    <form action="<?= url($prefix . '/profile/update') ?>" method="POST" autocomplete="off">
        <?= csrf_field() ?>

        <div class="space-y-4">
            <!-- Full Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700">Nama Lengkap</label>
                <input type="text" name="name" id="name" required
                       value="<?= esc(old('name', $user['name'])) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Masukkan nama lengkap Anda">
                <?php if (error('name')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('name')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-xs font-semibold text-slate-700">Username</label>
                <input type="text" name="username" id="username" required
                       value="<?= esc(old('username', $user['username'])) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Masukkan username login Anda">
                <?php if (error('username')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('username')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center">
                    <label for="password" class="block text-xs font-semibold text-slate-700">Ganti Password Baru</label>
                    <span class="text-[10px] text-slate-400">Kosongkan jika tidak ingin diganti</span>
                </div>
                <input type="password" name="password" id="password"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Minimal 6 karakter">
                <?php if (error('password')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('password')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Role View-only badge -->
            <div>
                <span class="block text-[10px] text-slate-400 font-semibold uppercase tracking-wider mb-1">Hak Akses Anda</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 capitalize">
                    <?= esc($user['role_name']) ?>
                </span>
            </div>

            <!-- Submit trigger -->
            <div class="flex items-center space-x-2 pt-4 border-t border-slate-100">
                <button type="submit" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
                    <i data-lucide="save" class="w-4 h-4 mr-1.5"></i>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
