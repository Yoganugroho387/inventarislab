<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Activity Logs Index View
 */
?>
<div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col">
    <div class="px-6 py-4 border-b border-slate-150 bg-slate-50 flex flex-row items-center justify-between flex-shrink-0">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Log Riwayat Aktivitas Sistem</h2>
        <span class="text-[10px] font-semibold bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full uppercase">
            Terbaru (Maks 150)
        </span>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <?php if (empty($logs)): ?>
            <div class="p-12 text-center bg-white">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-800">Belum ada rekaman audit log</h3>
                <p class="text-xs text-slate-500 mt-1">Audit log kosong atau belum ada log yang tercatat.</p>
            </div>
        <?php else: ?>
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-[10px] font-semibold uppercase bg-slate-100 text-slate-500">
                        <th class="px-6 py-3 w-16">No</th>
                        <th class="px-6 py-3 w-44">Waktu Kejadian</th>
                        <th class="px-6 py-3 w-48">Pelaku (User)</th>
                        <th class="px-6 py-3 w-40">Tindakan</th>
                        <th class="px-6 py-3">Rincian Deskripsi</th>
                        <th class="px-6 py-3 w-36">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $no = 1; foreach ($logs as $log): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-3 text-slate-500 font-medium"><?= $no++ ?></td>
                            <td class="px-6 py-3 text-slate-500 font-medium whitespace-nowrap">
                                <?= date('d M Y, H:i:s', strtotime($log['created_at'])) ?>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-full bg-teal-100 text-teal-850 flex items-center justify-center font-bold text-[9px] uppercase">
                                        <?= substr(esc($log['user_name'] ?: 'G'), 0, 2) ?>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="font-semibold text-slate-850 truncate max-w-[120px]" title="<?= esc($log['user_name']) ?>"><?= esc($log['user_name'] ?: 'Guest / Sistem') ?></span>
                                        <span class="text-[9px] text-slate-400 capitalize"><?= esc($log['role_name'] ?: 'public') ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200 capitalize">
                                    <?= esc($log['action']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-3 text-slate-655 font-medium leading-relaxed"><?= esc($log['details']) ?></td>
                            <td class="px-6 py-3 text-slate-450 font-mono font-semibold whitespace-nowrap"><?= esc($log['ip_address']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
