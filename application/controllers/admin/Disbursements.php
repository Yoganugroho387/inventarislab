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

class Disbursements extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('disbursement_model');
        $this->load->model('item_model');
        $this->load->model('activity_log_model');
    }

    public function index() {
        $filters = [
            'search' => $this->input->get('search', TRUE)
        ];

        $data = [
            'title' => 'Pengeluaran Bahan',
            'pageTitle' => 'Sirkulasi Pengeluaran Bahan Habis Pakai',
            'pageSubtitle' => 'Mencatat sirkulasi pengeluaran bahan habis pakai langsung untuk mahasiswa/dosen.',
            'disbursements' => $this->disbursement_model->get_all($filters),
            'filters' => $filters,
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('disbursements/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function create() {
        // Only get consumable items (item_type = 'habis_pakai') that have stock > 0
        $items = $this->db->select('items.*, units.name as unit_name, locations.name as location_name')
            ->from('items')
            ->join('units', 'items.unit_id = units.id')
            ->join('locations', 'items.location_id = locations.id')
            ->where('items.item_type', 'habis_pakai')
            ->where('items.stock >', 0)
            ->get()->result_array();

        $data = [
            'title' => 'Catat Pengeluaran',
            'pageTitle' => 'Formulir Pengeluaran Bahan Habis Pakai',
            'pageSubtitle' => 'Catat pengeluaran bahan secara langsung (permanen). Stok akan otomatis dipotong.',
            'items' => $items,
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('disbursements/create', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function store() {
        $this->form_validation->set_rules('item_id', 'Barang', 'required|integer');
        $this->form_validation->set_rules('quantity', 'Jumlah Pengeluaran', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('receiver_name', 'Nama Penerima', 'required|min_length[3]');
        $this->form_validation->set_rules('receiver_identity', 'Identitas Penerima', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('form_errors', $this->form_validation->error_array());
            $this->session->set_flashdata('old_input', $_POST);
            redirect('admin/disbursements/create');
        } else {
            $data = [
                'item_id' => (int)$this->input->post('item_id'),
                'quantity' => (int)$this->input->post('quantity'),
                'receiver_name' => $this->input->post('receiver_name', TRUE),
                'receiver_identity' => $this->input->post('receiver_identity', TRUE),
                'purpose' => $this->input->post('purpose', TRUE) ?: NULL,
                'given_by' => $this->auth_lib->id()
            ];

            try {
                $this->disbursement_model->create_disbursement($data);
                
                $item = $this->item_model->find($data['item_id']);
                $itemName = $item ? $item['name'] : 'Barang';
                
                $this->activity_log_model->log($this->auth_lib->id(), 'Input Pengeluaran', 'Mengeluarkan bahan habis pakai: ' . $itemName . ' (x' . $data['quantity'] . ') untuk ' . $data['receiver_name']);
                $this->session->set_flashdata('success', 'Catatan pengeluaran bahan berhasil disimpan.');
                redirect('admin/disbursements');
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                $this->session->set_flashdata('old_input', $_POST);
                redirect('admin/disbursements/create');
            }
        }
    }
}
