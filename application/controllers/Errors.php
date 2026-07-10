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

class Errors extends CI_Controller {

    public function page_404() {
        $this->output->set_status_header('404');
        $data['title'] = 'Halaman Tidak Ditemukan';
        $data['content'] = $this->load->view('errors/404', $data, TRUE);
        $this->load->view('layouts/auth', $data);
    }
}
