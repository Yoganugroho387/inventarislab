<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Edit User View
 */
?>
<div class="max-w-xl bg-white p-6 rounded border border-slate-200 shadow-sm">
    <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Form Edit User</h2>
        <a href="<?= url('/admin/users') ?>" class="text-xs text-teal-600 hover:text-teal-800 flex items-center">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1"></i>
            Kembali
        </a>
    </div>

    <form action="<?= url('/admin/users/update/' . $user['id']) ?>" method="POST" autocomplete="off">
        <?= csrf_field() ?>

        <div class="space-y-4">
            <!-- Full Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700">Nama Lengkap</label>
                <input type="text" name="name" id="name" required
                       value="<?= esc(old('name', $user['name'])) ?>"
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
                       value="<?= esc(old('username', $user['username'])) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Masukkan username">
                <?php if (error('username')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('username')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Password (Optional) -->
            <div>
                <div class="flex justify-between items-center">
                    <label for="password" class="block text-xs font-semibold text-slate-700">Password Baru</label>
                    <span class="text-[10px] text-slate-400">Kosongkan jika tidak diganti</span>
                </div>
                <input type="password" name="password" id="password"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Minimal 6 karakter">
                <?php if (error('password')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('password')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Role -->
            <div>
                <label for="role_id" class="block text-xs font-semibold text-slate-700">Hak Akses / Role</label>
                <?php if ($user['id'] == Auth::id()): ?>
                    <!-- Locked selector for current logged in user to prevent self privilege lockouts -->
                    <select name="role_id" id="role_id" disabled
                            class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-200 rounded bg-slate-100 text-slate-500 cursor-not-allowed">
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id'] ?>" <?= $user['role_id'] == $r['id'] ? 'selected' : '' ?>>
                                <?= strtoupper(esc($r['name'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <!-- Hidden field to submit the unchanged role value since disabled field is not sent in POST -->
                    <input type="hidden" name="role_id" value="<?= $user['role_id'] ?>">
                    <span class="text-[9px] text-slate-400 mt-1 block">Anda tidak dapat mengubah hak akses admin Anda sendiri.</span>
                <?php else: ?>
                    <select name="role_id" id="role_id" required
                            class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id'] ?>" <?= old('role_id', $user['role_id']) == $r['id'] ? 'selected' : '' ?>>
                                <?= strtoupper(esc($r['name'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <?php if (error('role_id')): ?>
                    <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('role_id')) ?></p>
                <?php endif; ?>
            </div>

            <!-- Status Checkbox -->
            <div class="flex items-center pt-2">
                <?php if ($user['id'] == Auth::id()): ?>
                    <input type="checkbox" disabled checked
                           class="w-4 h-4 text-teal-600 border-slate-200 rounded cursor-not-allowed">
                    <input type="hidden" name="is_active" value="1">
                    <label class="ml-2 text-xs font-semibold text-slate-400 cursor-not-allowed">Akun Aktif (Anda tidak dapat menonaktifkan akun sendiri)</label>
                <?php else: ?>
                    <input type="checkbox" name="is_active" id="is_active" value="1" <?= old('is_active', $user['is_active']) ? 'checked' : '' ?>
                           class="w-4 h-4 text-teal-600 border-slate-300 rounded focus:ring-teal-500 focus:outline-none cursor-pointer">
                    <label for="is_active" class="ml-2 text-xs font-semibold text-slate-700 cursor-pointer">Akun Aktif (Dapat Login)</label>
                <?php endif; ?>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center space-x-2 pt-4 border-t border-slate-100">
                <button type="submit" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
                    <i data-lucide="save" class="w-4 h-4 mr-1.5"></i>
                    Simpan Perubahan
                </button>
                <a href="<?= url('/admin/users') ?>" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-xs font-semibold rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm focus:outline-none transition duration-150">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
