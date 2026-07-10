<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Settings Index View
 */
?>
<div class="max-w-2xl bg-white p-6 rounded border border-slate-200 shadow-sm">
    <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Pengaturan Profil Laboratorium</h2>
    </div>

    <form action="<?= url('/admin/settings/update') ?>" method="POST" autocomplete="off" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- App Name -->
                <div>
                    <label for="app_name" class="block text-xs font-semibold text-slate-700">Nama Aplikasi</label>
                    <input type="text" name="app_name" id="app_name" required
                           value="<?= esc(old('app_name', $settings['app_name'] ?? '')) ?>"
                           class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                           placeholder="Nama Sistem Inventaris">
                </div>

                <!-- QR Prefix -->
                <div>
                    <label for="qr_prefix" class="block text-xs font-semibold text-slate-700">Awalan Kode QR / Barcode</label>
                    <input type="text" name="qr_prefix" id="qr_prefix" required
                           value="<?= esc(old('qr_prefix', $settings['qr_prefix'] ?? '')) ?>"
                           class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                           placeholder="Misal: LAB-INF-">
                </div>
            </div>

            <!-- Kop Surat & Instansi -->
            <div class="border-t border-slate-100 pt-4">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-3">Identitas Instansi & Kop Surat</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Institution Name -->
                    <div>
                        <label for="institution_name" class="block text-xs font-semibold text-slate-700">Nama Universitas / Instansi</label>
                        <input type="text" name="institution_name" id="institution_name" required
                               value="<?= esc(old('institution_name', $settings['institution_name'] ?? '')) ?>"
                               class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                               placeholder="Misal: Universitas Indonesia">
                    </div>
                    
                    <!-- Institution Logo -->
                    <div>
                        <label for="institution_logo" class="block text-xs font-semibold text-slate-700">Logo Universitas / Instansi</label>
                        <div class="mt-1 flex items-center space-x-3">
                            <?php if (!empty($settings['institution_logo']) && file_exists('./uploads/' . $settings['institution_logo'])): ?>
                                <img src="<?= base_url('uploads/' . $settings['institution_logo']) ?>" alt="Logo Instansi" class="w-10 h-10 object-contain rounded border border-slate-200 bg-slate-50">
                            <?php else: ?>
                                <div class="w-10 h-10 rounded border border-dashed border-slate-300 flex items-center justify-center text-slate-450 bg-slate-50">
                                    <i data-lucide="image" class="w-5 h-5"></i>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="institution_logo" id="institution_logo" accept="image/*"
                                   class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lab Name -->
            <div>
                <label for="lab_name" class="block text-xs font-semibold text-slate-700">Nama Laboratorium / Jurusan</label>
                <input type="text" name="lab_name" id="lab_name" required
                       value="<?= esc(old('lab_name', $settings['lab_name'] ?? '')) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Nama instansi laboratorium">
            </div>

            <!-- Lab Head -->
            <div>
                <label for="lab_head" class="block text-xs font-semibold text-slate-700">Nama Kepala Laboratorium</label>
                <input type="text" name="lab_head" id="lab_head" required
                       value="<?= esc(old('lab_head', $settings['lab_head'] ?? '')) ?>"
                       class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                       placeholder="Nama lengkap pimpinan lab beserta gelar">
            </div>

            <!-- Lab Address -->
            <div>
                <label for="lab_address" class="block text-xs font-semibold text-slate-700">Alamat / Lokasi Fisik Laboratorium</label>
                <textarea name="lab_address" id="lab_address" required rows="3"
                          class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                          placeholder="Masukkan alamat fisik lengkap laboratorium..."><?= esc(old('lab_address', $settings['lab_address'] ?? '')) ?></textarea>
            </div>

            <!-- WhatsApp Gateway (Fonnte) -->
            <div class="border-t border-slate-100 pt-4">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-3">Integrasi WhatsApp Gateway (Fonnte)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="whatsapp_enabled" class="block text-xs font-semibold text-slate-700">Status WhatsApp Gateway</label>
                        <select name="whatsapp_enabled" id="whatsapp_enabled"
                                class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150">
                            <option value="0" <?= ($settings['whatsapp_enabled'] ?? '0') === '0' ? 'selected' : '' ?>>Nonaktif</option>
                            <option value="1" <?= ($settings['whatsapp_enabled'] ?? '0') === '1' ? 'selected' : '' ?>>Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label for="whatsapp_token" class="block text-xs font-semibold text-slate-700">Fonnte API Token</label>
                        <input type="text" name="whatsapp_token" id="whatsapp_token"
                               value="<?= esc(old('whatsapp_token', $settings['whatsapp_token'] ?? '')) ?>"
                               class="mt-1 block w-full px-3 py-1.5 text-xs border border-slate-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 bg-slate-50 focus:bg-white text-slate-800 transition duration-150"
                               placeholder="Token API dari Fonnte">
                        <span class="text-[10px] text-slate-400 mt-1 block">Dapatkan token di <a href="https://docs.fonnte.com" target="_blank" class="text-teal-600 hover:underline">docs.fonnte.com</a></span>
                    </div>
                </div>
            </div>

            <!-- Submit trigger -->
            <div class="flex items-center space-x-2 pt-4 border-t border-slate-100">
                <button type="submit" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-semibold rounded text-white bg-teal-600 hover:bg-teal-700 shadow-sm focus:outline-none transition duration-150">
                    <i data-lucide="save" class="w-4 h-4 mr-1.5"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>
