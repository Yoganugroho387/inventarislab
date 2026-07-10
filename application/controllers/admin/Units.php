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

class Units extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('unit_model');
        $this->load->model('activity_log_model');
    }

    public function index() {
        $editUnit = null;
        $editId = $this->input->get('edit');
        if ($editId) {
            $editUnit = $this->unit_model->find($editId);
        }

        $data = [
            'title' => 'Satuan Barang',
            'pageTitle' => 'Satuan Ukuran Barang',
            'pageSubtitle' => 'Kelola standar satuan kuantitas barang lab (pcs, box, liter, dll).',
            'units' => $this->unit_model->get_all(),
            'editUnit' => $editUnit,
            'prefix' => '/admin'
        ];
        $data['content'] = $this->load->view('units/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function store() {
        $this->form_validation->set_rules('name', 'Nama Satuan', 'required|is_unique[units.name]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/units');
        } else {
            $name = $this->input->post('name', TRUE);
            $this->unit_model->insert(['name' => $name]);
            $this->activity_log_model->log($this->auth_lib->id(), 'Tambah Satuan', 'Membuat satuan: ' . $name);
            $this->session->set_flashdata('success', 'Satuan baru berhasil dibuat.');
            redirect('admin/units');
        }
    }

    public function update($id) {
        $unit = $this->unit_model->find($id);
        if (!$unit) {
            $this->session->set_flashdata('error', 'Satuan tidak ditemukan.');
            redirect('admin/units');
        }

        $this->form_validation->set_rules('name', 'Nama Satuan', 'required|callback_edit_name_check');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/units');
        } else {
            $name = $this->input->post('name', TRUE);
            $this->unit_model->update($id, ['name' => $name]);
            $this->activity_log_model->log($this->auth_lib->id(), 'Ubah Satuan', 'Mengubah satuan ID: ' . $id . ' menjadi: ' . $name);
            $this->session->set_flashdata('success', 'Satuan berhasil diperbarui.');
            redirect('admin/units');
        }
    }

    public function edit_name_check($name) {
        $id = $this->uri->segment(4);
        $this->db->where('id !=', $id);
        $this->db->where('name', $name);
        $exists = $this->db->count_all_results('units') > 0;
        if ($exists) {
            $this->form_validation->set_message('edit_name_check', 'Nama satuan sudah digunakan.');
            return FALSE;
        }
        return TRUE;
    }

    public function delete($id) {
        $unit = $this->unit_model->find($id);
        if (!$unit) {
            $this->session->set_flashdata('error', 'Satuan tidak ditemukan.');
            redirect('admin/units');
        }

        $has_items = $this->db->where('unit_id', $id)->count_all_results('items') > 0;
        if ($has_items) {
            $this->session->set_flashdata('error', 'Satuan tidak dapat dihapus karena masih digunakan oleh beberapa barang.');
            redirect('admin/units');
        }

        $this->unit_model->delete($id);
        $this->activity_log_model->log($this->auth_lib->id(), 'Hapus Satuan', 'Menghapus satuan: ' . $unit['name']);
        $this->session->set_flashdata('success', 'Satuan berhasil dihapus.');
        redirect('admin/units');
    }
}
