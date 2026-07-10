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

class Returns extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('borrowing_model');
        $this->load->model('activity_log_model');
    }

    public function index() {
        $status = $this->input->get('status') ?: 'dipinjam';
        $filters = [
            'search' => $this->input->get('search', TRUE),
            'status' => $status,
        ];

        $this->db->select('b.*, uc.name as creator_name');
        $this->db->from('borrowings b');
        $this->db->join('users uc', 'b.created_by = uc.id');

        if ($status === 'dikembalikan') {
            $this->db->where('b.status', 'dikembalikan');
        } else {
            $this->db->group_start()
                ->where('b.status', 'dipinjam')
                ->or_where('b.status', 'terlambat')
            ->group_end();
        }

        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('b.borrower_name', $filters['search']);
            $this->db->or_like('b.borrower_identity', $filters['search']);
            $this->db->group_end();
        }

        $this->db->order_by('b.id', 'DESC');
        $borrowings = $this->db->get()->result_array();

        foreach ($borrowings as &$b) {
            $b['items'] = $this->borrowing_model->get_items_by_borrowing_id($b['id']);
        }

        $data = [
            'title' => 'Pengembalian Barang',
            'pageTitle' => 'Sirkulasi Pengembalian Barang',
            'pageSubtitle' => 'Verifikasi pengembalian alat, kelayakan kondisi fisik, dan input catatan audit kerusakan.',
            'borrowings' => $borrowings,
            'filters' => $filters,
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('returns/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function create($id) {
        $borrowing = $this->borrowing_model->find_with_relations($id);
        if (!$borrowing || ($borrowing['status'] !== 'dipinjam' && $borrowing['status'] !== 'terlambat')) {
            $this->session->set_flashdata('error', 'Transaksi peminjaman tidak aktif atau tidak ditemukan.');
            redirect('admin/returns');
        }

        $data = [
            'title' => 'Proses Pengembalian',
            'pageTitle' => 'Pemeriksaan Pengembalian Barang',
            'pageSubtitle' => 'Cek kelayakan fisik barang yang dikembalikan sebelum dicatat masuk ke gudang.',
            'borrowing' => $borrowing,
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('returns/create', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function store($id) {
        $borrowing = $this->borrowing_model->find_with_relations($id);
        if (!$borrowing || ($borrowing['status'] !== 'dipinjam' && $borrowing['status'] !== 'terlambat')) {
            $this->session->set_flashdata('error', 'Transaksi peminjaman tidak aktif.');
            redirect('admin/returns');
        }

        $items_input = $this->input->post('items');
        if (empty($items_input)) {
            $this->session->set_flashdata('error', 'Data barang pengembalian tidak valid.');
            redirect('admin/returns/create/' . $id);
        }

        $returns = [];
        foreach ($items_input as $item_id => $details) {
            $returns[] = [
                'item_id' => (int)$item_id,
                'return_condition' => $details['return_condition'],
                'return_notes' => $details['return_notes'] ?: NULL
            ];
        }

        try {
            $this->borrowing_model->return_items($id, $returns, $this->auth_lib->id());
            $this->activity_log_model->log($this->auth_lib->id(), 'Input Pengembalian', 'Mencatat pengembalian barang untuk peminjaman ID: ' . $id);

            // WhatsApp Notification
            if (!empty($borrowing['borrower_phone'])) {
                $this->load->helper('whatsapp');
                $msg = "Halo *" . $borrowing['borrower_name'] . "*,\n\nPengembalian barang untuk peminjaman dengan ID *#" . $borrowing['id'] . "* telah BERHASIL diverifikasi dan dicatat oleh petugas lab.\n\nTerima kasih atas tertib administrasi Anda!";
                send_whatsapp($borrowing['borrower_phone'], $msg);
            }

            $this->session->set_flashdata('success', 'Catatan pengembalian barang berhasil disimpan.');
            redirect('admin/returns');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
            redirect('admin/returns/create/' . $id);
        }
    }

    public function find_by_item_code() {
        $code = $this->input->get('code', TRUE);
        if (empty($code)) {
            return $this->output->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'error', 'message' => 'Kode barang kosong']));
        }

        // Find the item first
        $item = $this->db->where('code', $code)->get('items')->row_array();
        if (!$item) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Barang tidak ditemukan']));
        }

        // Query active borrowings that contain this item
        $this->db->select('b.id, b.borrower_name, b.borrower_identity, b.loan_date');
        $this->db->from('borrowings b');
        $this->db->join('borrowing_details bd', 'b.id = bd.borrowing_id');
        $this->db->where('bd.item_id', $item['id']);
        $this->db->where('bd.return_date', NULL);
        $this->db->group_start()
            ->where('b.status', 'dipinjam')
            ->or_where('b.status', 'terlambat')
        ->group_end();

        $borrowings = $this->db->get()->result_array();

        if (empty($borrowings)) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Barang ini tidak sedang dipinjam']));
        }

        return $this->output->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'success',
                'item_name' => $item['name'],
                'item_code' => $item['code'],
                'borrowings' => $borrowings
            ]));
    }
}
