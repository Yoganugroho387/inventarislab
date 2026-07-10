<?php
/*
 * Developed by: Yoga Nugroho | 089685027530 | https://tako.id/YNGRHO
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * Dilarang menyalin tanpa izin.
 */
/**
 * Borrowings Calendar View
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- FullCalendar JS and styles -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex flex-col">
    <!-- Header with Back Button -->
    <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
        <h2 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Jadwal Kalender Sirkulasi</h2>
        <a href="<?= url($prefix . '/borrowings') ?>" class="text-xs text-teal-600 hover:text-teal-800 flex items-center font-semibold">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1"></i>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Status Legend -->
    <div class="flex flex-wrap gap-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-6 bg-slate-50 p-3 rounded border border-slate-200/60">
        <div class="flex items-center">
            <span class="w-2.5 h-2.5 rounded bg-[#d97706] mr-1.5 block"></span>
            <span>Menunggu</span>
        </div>
        <div class="flex items-center">
            <span class="w-2.5 h-2.5 rounded bg-[#2563eb] mr-1.5 block"></span>
            <span>Disetujui (Reservasi)</span>
        </div>
        <div class="flex items-center">
            <span class="w-2.5 h-2.5 rounded bg-[#0d9488] mr-1.5 block"></span>
            <span>Sedang Dipinjam</span>
        </div>
        <div class="flex items-center">
            <span class="w-2.5 h-2.5 rounded bg-[#dc2626] mr-1.5 block"></span>
            <span>Terlambat</span>
        </div>
        <div class="flex items-center">
            <span class="w-2.5 h-2.5 rounded bg-[#64748b] mr-1.5 block"></span>
            <span>Selesai</span>
        </div>
    </div>

    <!-- Calendar Mount Node -->
    <div class="flex-1 min-h-[600px] overflow-hidden" id="calendar-wrapper">
        <div id="borrow-calendar"></div>
    </div>
</div>

<style>
    /* Premium override styles for FullCalendar */
    .fc {
        font-family: 'Inter', sans-serif;
        font-size: 11px;
    }
    .fc .fc-toolbar-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        text-transform: capitalize;
    }
    .fc .fc-button-primary {
        background-color: #ffffff;
        border-color: #cbd5e1;
        color: #334155;
        font-size: 11px;
        font-weight: 600;
        padding: 5px 10px;
        text-transform: capitalize;
    }
    .fc .fc-button-primary:hover {
        background-color: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }
    .fc .fc-button-primary:not(:disabled).fc-button-active, 
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #0f766e;
        border-color: #0f766e;
        color: #ffffff;
    }
    .fc .fc-button-primary:focus {
        box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.2);
    }
    .fc .fc-daygrid-day-number {
        font-weight: 600;
        color: #475569;
        padding: 6px;
    }
    .fc .fc-col-header-cell-cushion {
        font-weight: 700;
        color: #475569;
        font-size: 10px;
        text-transform: uppercase;
        padding: 8px 4px;
    }
    .fc .fc-event {
        cursor: pointer;
        padding: 2px 4px;
        border-radius: 4px;
        font-weight: 550;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .fc .fc-day-today {
        background-color: #f0fdfa !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('borrow-calendar');
        if (calendarEl) {
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                firstDay: 1, // Start week on Monday
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    list: 'Daftar Agenda'
                },
                events: '<?= url($prefix . "/borrowings/calendar_events") ?>',
                eventClick: function(info) {
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.location.href = info.event.url;
                    }
                }
            });
            calendar.render();
        }
    });
</script>
