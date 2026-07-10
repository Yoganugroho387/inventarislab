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

class Locations extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('location_model');
        $this->load->model('activity_log_model');
    }

    public function index() {
        $editLocation = null;
        $editId = $this->input->get('edit');
        if ($editId) {
            $editLocation = $this->location_model->find($editId);
        }

        $data = [
            'title' => 'Lokasi Lab',
            'pageTitle' => 'Lokasi Laboratorium',
            'pageSubtitle' => 'Kelola ruangan penempatan aset dan inventaris barang.',
            'locations' => $this->location_model->get_all(),
            'editLocation' => $editLocation,
            'prefix' => '/admin'
        ];
        $data['content'] = $this->load->view('locations/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function store() {
        $this->form_validation->set_rules('name', 'Nama Lokasi', 'required|is_unique[locations.name]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/locations');
        } else {
            $name = $this->input->post('name', TRUE);
            $this->location_model->insert(['name' => $name]);
            $this->activity_log_model->log($this->auth_lib->id(), 'Tambah Lokasi', 'Membuat lokasi: ' . $name);
            $this->session->set_flashdata('success', 'Lokasi baru berhasil dibuat.');
            redirect('admin/locations');
        }
    }

    public function update($id) {
        $location = $this->location_model->find($id);
        if (!$location) {
            $this->session->set_flashdata('error', 'Lokasi tidak ditemukan.');
            redirect('admin/locations');
        }

        $this->form_validation->set_rules('name', 'Nama Lokasi', 'required|callback_edit_name_check');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/locations');
        } else {
            $name = $this->input->post('name', TRUE);
            $this->location_model->update($id, ['name' => $name]);
            $this->activity_log_model->log($this->auth_lib->id(), 'Ubah Lokasi', 'Mengubah lokasi ID: ' . $id . ' menjadi: ' . $name);
            $this->session->set_flashdata('success', 'Lokasi berhasil diperbarui.');
            redirect('admin/locations');
        }
    }

    public function edit_name_check($name) {
        $id = $this->uri->segment(4);
        $this->db->where('id !=', $id);
        $this->db->where('name', $name);
        $exists = $this->db->count_all_results('locations') > 0;
        if ($exists) {
            $this->form_validation->set_message('edit_name_check', 'Nama lokasi sudah digunakan.');
            return FALSE;
        }
        return TRUE;
    }

    public function delete($id) {
        $location = $this->location_model->find($id);
        if (!$location) {
            $this->session->set_flashdata('error', 'Lokasi tidak ditemukan.');
            redirect('admin/locations');
        }

        $has_items = $this->db->where('location_id', $id)->count_all_results('items') > 0;
        if ($has_items) {
            $this->session->set_flashdata('error', 'Lokasi tidak dapat dihapus karena masih digunakan oleh beberapa barang.');
            redirect('admin/locations');
        }

        $this->location_model->delete($id);
        $this->activity_log_model->log($this->auth_lib->id(), 'Hapus Lokasi', 'Menghapus lokasi: ' . $location['name']);
        $this->session->set_flashdata('success', 'Lokasi berhasil dihapus.');
        redirect('admin/locations');
    }
}
