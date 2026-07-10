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

class Verify extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('setting_model');
        $this->load->model('borrowing_model');
    }

    public function document($identity) {
        $identity = trim($identity);
        
        // Find if this student has any active borrowings
        $active_loans = $this->db->select('b.*')
            ->from('borrowings b')
            ->where('b.borrower_identity', $identity)
            ->group_start()
                ->where('b.status', 'dipinjam')
                ->or_where('b.status', 'terlambat')
            ->group_end()
            ->get()
            ->result_array();

        // Get student information from most recent loan to show their name
        $student_info = $this->db->select('borrower_name, borrower_type')
            ->from('borrowings')
            ->where('borrower_identity', $identity)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        $institution_name = $this->setting_model->get_val('institution_name', 'Politeknik Negeri Jakarta');
        $institution_logo = $this->setting_model->get_val('institution_logo', '');

        $data = [
            'title' => 'Verifikasi Bebas Lab',
            'identity' => $identity,
            'student_info' => $student_info,
            'is_free' => empty($active_loans),
            'active_loans' => $active_loans,
            'institution_name' => $institution_name,
            'institution_logo' => $institution_logo
        ];

        $this->load->view('verify/document', $data);
    }

    public function borrowing($id) {
        $id = (int)$id;
        $borrowing = $this->borrowing_model->find_with_relations($id);

        if (!$borrowing) {
            show_404();
        }

        $institution_name = $this->setting_model->get_val('institution_name', 'Politeknik Negeri Jakarta');
        $institution_logo = $this->setting_model->get_val('institution_logo', '');

        $data = [
            'title' => 'Verifikasi Surat Jalan Peminjaman',
            'borrowing' => $borrowing,
            'institution_name' => $institution_name,
            'institution_logo' => $institution_logo
        ];

        $this->load->view('verify/borrowing', $data);
    }
}
