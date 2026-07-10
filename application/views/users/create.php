<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Create User View
 */
?>
<div class="max-w-xl bg-white p-6 rounded border border-slate-200 shadow-sm">
    <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Form Tambah User</h2>
        <a href="<?= url('/admin/users') ?>" class="text-xs text-teal-600 hover:text-teal-800 flex items-center">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1"></i>
            Kembali
        </a>
    </div>

    <form action="<?= url('/admin/users/store') ?>" method="POST" autocomplete="off">
        <?= csrf_field() ?>

        <div class="space-y-4">
            <!-- Full Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700">Nama Lengkap</label>
                <input type="text" name="name" id="name" required
                       value="<?= esc(old('name')) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Masukkan nama lengkap">
                <?php if (error('name')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('name')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-xs font-semibold text-slate-700">Username</label>
                <input type="text" name="username" id="username" required
                       value="<?= esc(old('username')) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Masukkan username unik">
                <?php if (error('username')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('username')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700">Password</label>
                <input type="password" name="password" id="password" required
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Minimal 6 karakter">
                <?php if (error('password')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('password')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Role Selection -->
            <div>
                <label for="role_id" class="block text-xs font-semibold text-slate-700">Hak Akses / Role</label>
                <select name="role_id" id="role_id" required
                        class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                    <option value="">-- Pilih Role --</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= old('role_id') == $r['id'] ? 'selected' : '' ?>>
                            <?= strtoupper(esc($r['name'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (error('role_id')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('role_id')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Status Checkbox -->
            <div class="flex items-center pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked
                       class="w-4 h-4 text-teal-600 border-slate-300 rounded focus:ring-teal-500 focus:outline-none cursor-pointer">
                <label for="is_active" class="ml-2 text-xs font-semibold text-slate-700 cursor-pointer">Akun Aktif (Dapat Login)</label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center space-x-2 pt-4 border-t border-slate-100">
                <button type="submit" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
                    <i data-lucide="save" class="w-4 h-4 mr-1.5"></i>
                    Simpan User
                </button>
                <a href="<?= url('/admin/users') ?>" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm focus:outline-none transition duration-150">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
