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

class Welcome extends CI_Controller {

    public function index()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->userdata('user_id')) {
            $role = $this->session->userdata('user_role');
            if ($role === 'admin') {
                redirect('admin/dashboard');
            } else {
                redirect('staff/dashboard');
            }
        }

        $data = [
            'title' => 'Selamat Datang'
        ];
        $this->load->view('landing', $data);
    }
}
