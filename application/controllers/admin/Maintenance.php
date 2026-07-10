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

class Maintenance extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('maintenance_model');
        $this->load->model('item_model');
        $this->load->model('activity_log_model');
    }

    public function index() {
        $filters = [
            'search' => $this->input->get('search', TRUE),
            'status' => $this->input->get('status')
        ];

        $data = [
            'title' => 'Perbaikan Aset',
            'pageTitle' => 'Manajemen Pemeliharaan & Perbaikan Aset',
            'pageSubtitle' => 'Pantau histori pemeliharaan, jadwal servis vendor, dan pencatatan biaya perbaikan aset laboratorium.',
            'logs' => $this->maintenance_model->get_all($filters),
            'filters' => $filters,
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('maintenance/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function create() {
        // Find items that can be maintained: type 'inventaris' and status 'tersedia' or 'rusak' (but NOT currently dipinjam or maintenance)
        $items = $this->db->select('items.*, locations.name as location_name')
            ->from('items')
            ->join('locations', 'items.location_id = locations.id')
            ->where('items.item_type', 'inventaris')
            ->where_in('items.condition_status', ['tersedia', 'rusak'])
            ->where('items.item_status', 'tersedia')
            ->get()->result_array();

        $data = [
            'title' => 'Catat Perbaikan',
            'pageTitle' => 'Ajukan Perbaikan Aset Baru',
            'pageSubtitle' => 'Kirim barang inventaris yang rusak atau perlu servis ke tahap pemeliharaan.',
            'items' => $items,
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('maintenance/create', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function store() {
        $this->form_validation->set_rules('item_id', 'Aset', 'required|integer');
        $this->form_validation->set_rules('start_date', 'Tanggal Mulai', 'required');
        $this->form_validation->set_rules('issue_description', 'Deskripsi Masalah', 'required|min_length[5]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('form_errors', $this->form_validation->error_array());
            $this->session->set_flashdata('old_input', $_POST);
            redirect('admin/maintenance/create');
        } else {
            $data = [
                'item_id' => (int)$this->input->post('item_id'),
                'start_date' => $this->input->post('start_date'),
                'vendor_name' => $this->input->post('vendor_name', TRUE) ?: NULL,
                'issue_description' => $this->input->post('issue_description', TRUE),
                'status' => 'proses',
                'created_by' => $this->auth_lib->id()
            ];

            try {
                $this->maintenance_model->create_log($data);
                
                $item = $this->item_model->find($data['item_id']);
                $itemName = $item ? $item['name'] : 'Aset';

                $this->activity_log_model->log($this->auth_lib->id(), 'Input Perbaikan', 'Memproses perbaikan barang: ' . $itemName);
                $this->session->set_flashdata('success', 'Aset berhasil dipindahkan ke status perbaikan.');
                redirect('admin/maintenance');
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                $this->session->set_flashdata('old_input', $_POST);
                redirect('admin/maintenance/create');
            }
        }
    }

    public function edit($id) {
        $log = $this->maintenance_model->find($id);
        if (!$log || $log['status'] !== 'proses') {
            $this->session->set_flashdata('error', 'Log perbaikan tidak aktif atau tidak ditemukan.');
            redirect('admin/maintenance');
        }

        $data = [
            'title' => 'Penyelesaian Perbaikan',
            'pageTitle' => 'Pemeriksaan Hasil Perbaikan Aset',
            'pageSubtitle' => 'Input hasil tindakan servis, biaya, dan tentukan kondisi kelayakan barang.',
            'log' => $log,
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('maintenance/edit', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function update($id) {
        $this->form_validation->set_rules('end_date', 'Tanggal Selesai', 'required');
        $this->form_validation->set_rules('status', 'Hasil Perbaikan', 'required|in_list[selesai,tidak_bisa_diperbaiki]');
        $this->form_validation->set_rules('cost', 'Biaya Perbaikan', 'required|numeric');
        $this->form_validation->set_rules('repair_action', 'Tindakan Perbaikan', 'required|min_length[5]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('form_errors', $this->form_validation->error_array());
            $this->session->set_flashdata('old_input', $_POST);
            redirect('admin/maintenance/edit/' . $id);
        } else {
            $data = [
                'end_date' => $this->input->post('end_date'),
                'status' => $this->input->post('status'),
                'cost' => (float)$this->input->post('cost'),
                'repair_action' => $this->input->post('repair_action', TRUE),
            ];

            try {
                $this->maintenance_model->update_log($id, $data);
                
                $log = $this->maintenance_model->find($id);
                $itemName = $log ? $log['item_name'] : 'Aset';

                $this->activity_log_model->log($this->auth_lib->id(), 'Penyelesaian Perbaikan', 'Menyelesaikan perbaikan barang: ' . $itemName . ' dengan status ' . $data['status']);
                $this->session->set_flashdata('success', 'Catatan pemeliharaan aset berhasil diperbarui.');
                redirect('admin/maintenance');
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                $this->session->set_flashdata('old_input', $_POST);
                redirect('admin/maintenance/edit/' . $id);
            }
        }
    }
}
