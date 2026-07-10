<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Users Listing View
 */
?>
<div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col">
    <!-- Header area -->
    <div class="px-6 py-4 border-b border-slate-150 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50">
        <div>
            <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Daftar Akun Pengguna</h2>
        </div>
        <div>
            <a href="<?= url('/admin/users/create') ?>" 
               class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm transition duration-150">
                <i data-lucide="user-plus" class="w-4 h-4 mr-1.5"></i>
                Tambah User Baru
            </a>
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <?php if (empty($users)): ?>
            <div class="p-8 text-center bg-white">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-slate-100 text-slate-400 mb-2">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <p class="text-xs text-slate-500 font-medium">Belum ada data user.</p>
            </div>
        <?php else: ?>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase bg-slate-100 text-slate-500">
                        <th class="px-6 py-3 w-16">#</th>
                        <th class="px-6 py-3">Nama Lengkap</th>
                        <th class="px-6 py-3">Username</th>
                        <th class="px-6 py-3">Hak Akses / Role</th>
                        <th class="px-6 py-3 w-28">Status</th>
                        <th class="px-6 py-3 w-28 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $no = 1; foreach ($users as $u): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-3.5 text-slate-500 font-medium"><?= $no++ ?></td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center space-x-3">
                                    <!-- Avatar fallback -->
                                    <div class="w-7 h-7 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-[10px] uppercase">
                                        <?= substr(esc($u['name']), 0, 2) ?>
                                    </div>
                                    <span class="font-medium text-slate-800"><?= esc($u['name']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-slate-600 font-medium"><?= esc($u['username']) ?></td>
                            <td class="px-6 py-3.5">
                                <?php if ($u['role_name'] === 'admin'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-teal-50 text-teal-700 border border-teal-200 uppercase">
                                        Admin
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 uppercase">
                                        Staff
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3.5">
                                <?php if ($u['is_active']): ?>
                                    <span class="inline-flex items-center text-[11px] font-medium text-emerald-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center text-[11px] font-medium text-slate-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-300 mr-1.5"></span>
                                        Nonaktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3.5 text-right space-x-2">
                                <!-- Edit -->
                                <a href="<?= url('/admin/users/edit/' . $u['id']) ?>" 
                                   class="inline-flex items-center text-teal-600 hover:text-teal-800 transition" 
                                   title="Edit User">
                                    <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                </a>

                                <!-- Delete -->
                                <?php if ($u['id'] != Auth::id() && $u['id'] != 1): ?>
                                    <form action="<?= url('/admin/users/delete/' . $u['id']) ?>" method="POST" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 transition" title="Hapus User">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <!-- Disabled action for self or primary admin -->
                                    <span class="inline-flex text-slate-300 cursor-not-allowed" title="Tidak dapat menghapus akun ini">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
