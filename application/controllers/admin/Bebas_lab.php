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

class Bebas_lab extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('setting_model');
        $this->load->model('borrowing_model');
    }

    public function index() {
        $identity = $this->input->get('identity', TRUE);
        $student_info = null;
        $active_loans = [];
        $is_searched = FALSE;
        $is_free = FALSE;

        if ($identity !== NULL && trim($identity) !== '') {
            $identity = trim($identity);
            $is_searched = TRUE;

            // Query active borrowings
            $active_loans = $this->db->select('b.*')
                ->from('borrowings b')
                ->where('b.borrower_identity', $identity)
                ->group_start()
                    ->where('b.status', 'dipinjam')
                    ->or_where('b.status', 'terlambat')
                ->group_end()
                ->get()
                ->result_array();

            $is_free = empty($active_loans);

            // Fetch borrower details from most recent loan
            $student_info = $this->db->select('borrower_name, borrower_type, borrower_phone')
                ->from('borrowings')
                ->where('borrower_identity', $identity)
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get()
                ->row_array();
        }

        $data = [
            'title' => 'Surat Bebas Laboratorium',
            'pageTitle' => 'Surat Keterangan Bebas Lab',
            'pageSubtitle' => 'Cari identitas mahasiswa/staf untuk memverifikasi tanggungan pinjaman alat lab.',
            'identity' => $identity,
            'student_info' => $student_info,
            'active_loans' => $active_loans,
            'is_searched' => $is_searched,
            'is_free' => $is_free,
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('bebas_lab/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function print_letter($identity) {
        $identity = trim(urldecode($identity));

        // Re-verify no active loans
        $active_loans = $this->db->select('b.*')
            ->from('borrowings b')
            ->where('b.borrower_identity', $identity)
            ->group_start()
                ->where('b.status', 'dipinjam')
                ->or_where('b.status', 'terlambat')
            ->group_end()
            ->get()
            ->result_array();

        if (!empty($active_loans)) {
            $this->session->set_flashdata('error', 'Mahasiswa belum bebas dari tanggungan pinjaman.');
            redirect('admin/bebas-lab?identity=' . urlencode($identity));
        }

        $student_info = $this->db->select('borrower_name, borrower_type')
            ->from('borrowings')
            ->where('borrower_identity', $identity)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        if (!$student_info) {
            $this->session->set_flashdata('error', 'Data mahasiswa tidak ditemukan.');
            redirect('admin/bebas-lab');
        }

        $settings = $this->setting_model->all_settings();

        $data = [
            'identity' => $identity,
            'student' => $student_info,
            'settings' => $settings,
            'verification_url' => base_url('verify/document/' . $identity)
        ];

        $this->load->view('bebas_lab/print', $data);
    }
}
