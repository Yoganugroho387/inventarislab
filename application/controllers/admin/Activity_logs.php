<?php
/**
 * ==========================================================================
 * Sistem Inventaris Laboratorium
 * ==========================================================================
 * 
 * Developed by : Yoga Nugroho
 * WhatsApp     : 089685027530
 * Support      : https://tako.id/YNGRHO
 * 
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * 
 * NOTICE: Seluruh kode sumber pada sistem ini dilindungi hak cipta.
 * Dilarang keras menyalin, mendistribusikan, atau memodifikasi
 * tanpa izin tertulis dari pengembang.
 * ==========================================================================
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_logs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('activity_log_model');
    }

    public function index() {
        $data = [
            'title' => 'Log Aktivitas',
            'pageTitle' => 'Audit Log Aktivitas Sistem',
            'pageSubtitle' => 'Pantau riwayat operasi sistem, penambahan barang, sirkulasi, log masuk, dan pelacakan IP.',
            'logs' => $this->activity_log_model->get_logs(150),
            'prefix' => '/admin'
        ];
        $data['content'] = $this->load->view('activity-logs/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }
}
