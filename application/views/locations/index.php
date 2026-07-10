<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Locations Index View
 */
?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Form Panel (1/3 Width) -->
    <div class="bg-white p-5 rounded border border-slate-200 shadow-sm h-fit">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-4">
            <?= $editLocation ? 'Edit Lokasi' : 'Tambah Lokasi Baru' ?>
        </h2>
        
        <form action="<?= $editLocation ? url('/admin/locations/update/' . $editLocation['id']) : url('/admin/locations/store') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="space-y-4">
                <!-- Location Name Input -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700">Nama Lokasi / Ruang Lab</label>
                    <input type="text" name="name" id="name" required 
                           value="<?= esc(old('name', $editLocation['name'] ?? '')) ?>"
                           class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                           placeholder="Misal: Lab Jaringan, Lemari A">
                    <?php if (error('name')): ?>
                        <p class="mt-1 text-[10px] text-rose-600 font-medium"><?= esc(error('name')) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-2 pt-2 border-t border-slate-100">
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
                        <i data-lucide="save" class="w-3.5 h-3.5 mr-1"></i>
                        Simpan
                    </button>
                    
                    <?php if ($editLocation): ?>
                        <a href="<?= url('/admin/locations') ?>" 
                           class="inline-flex items-center justify-center px-3 py-1.5 border border-slate-300 text-xs font-medium rounded text-slate-700 bg-white hover:bg-slate-50 shadow-sm focus:outline-none transition duration-150">
                            Batal
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Panel (2/3 Width) -->
    <div class="md:col-span-2 bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Daftar Lokasi Lab</h2>
            <span class="text-[10px] font-semibold bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full uppercase">
                Total: <?= count($locations) ?>
            </span>
        </div>

        <div class="overflow-x-auto">
            <?php if (empty($locations)): ?>
                <!-- Empty State -->
                <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-slate-100 text-slate-400 mb-2">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                    </div>
                    <p class="text-xs text-slate-500 font-medium">Belum ada data lokasi.</p>
                </div>
            <?php else: ?>
                <!-- Data Table -->
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-[11px] font-semibold uppercase bg-slate-100 text-slate-500">
                            <th class="px-5 py-3 w-16">#</th>
                            <th class="px-5 py-3">Nama Lokasi / Ruang Lab</th>
                            <th class="px-5 py-3 w-28 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $no = 1; foreach ($locations as $l): ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-3 text-slate-500 font-medium"><?= $no++ ?></td>
                                <td class="px-5 py-3 font-medium text-slate-800"><?= esc($l['name']) ?></td>
                                <td class="px-5 py-3 text-right space-x-2">
                                    <!-- Edit Link -->
                                    <a href="<?= url('/admin/locations?edit=' . $l['id']) ?>" 
                                       class="inline-flex items-center text-teal-600 hover:text-teal-800 transition" 
                                       title="Edit Lokasi">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    </a>
                                    
                                    <!-- Delete Form -->
                                    <form action="<?= url('/admin/locations/delete/' . $l['id']) ?>" method="POST" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 transition" title="Hapus Lokasi">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
