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

class Categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('category_model');
        $this->load->model('activity_log_model');
    }

    public function index() {
        $editCategory = null;
        $editId = $this->input->get('edit');
        if ($editId) {
            $editCategory = $this->category_model->find($editId);
        }

        $data = [
            'title' => 'Kategori Barang',
            'pageTitle' => 'Kategori Inventaris',
            'pageSubtitle' => 'Kelola klasifikasi kategori aset laboratorium.',
            'categories' => $this->category_model->get_all(),
            'editCategory' => $editCategory,
            'prefix' => '/admin'
        ];
        $data['content'] = $this->load->view('categories/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function store() {
        $this->form_validation->set_rules('name', 'Nama Kategori', 'required|is_unique[categories.name]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/categories');
        } else {
            $name = $this->input->post('name', TRUE);
            $this->category_model->insert(['name' => $name]);
            $this->activity_log_model->log($this->auth_lib->id(), 'Tambah Kategori', 'Membuat kategori: ' . $name);
            $this->session->set_flashdata('success', 'Kategori baru berhasil dibuat.');
            redirect('admin/categories');
        }
    }

    public function update($id) {
        $category = $this->category_model->find($id);
        if (!$category) {
            $this->session->set_flashdata('error', 'Kategori tidak ditemukan.');
            redirect('admin/categories');
        }

        $this->form_validation->set_rules('name', 'Nama Kategori', 'required|callback_edit_name_check');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/categories');
        } else {
            $name = $this->input->post('name', TRUE);
            $this->category_model->update($id, ['name' => $name]);
            $this->activity_log_model->log($this->auth_lib->id(), 'Ubah Kategori', 'Mengubah kategori ID: ' . $id . ' menjadi: ' . $name);
            $this->session->set_flashdata('success', 'Kategori berhasil diperbarui.');
            redirect('admin/categories');
        }
    }

    public function edit_name_check($name) {
        $id = $this->uri->segment(4);
        $this->db->where('id !=', $id);
        $this->db->where('name', $name);
        $exists = $this->db->count_all_results('categories') > 0;
        if ($exists) {
            $this->form_validation->set_message('edit_name_check', 'Nama kategori sudah digunakan.');
            return FALSE;
        }
        return TRUE;
    }

    public function delete($id) {
        $category = $this->category_model->find($id);
        if (!$category) {
            $this->session->set_flashdata('error', 'Kategori tidak ditemukan.');
            redirect('admin/categories');
        }

        $has_items = $this->db->where('category_id', $id)->count_all_results('items') > 0;
        if ($has_items) {
            $this->session->set_flashdata('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa barang.');
            redirect('admin/categories');
        }

        $this->category_model->delete($id);
        $this->activity_log_model->log($this->auth_lib->id(), 'Hapus Kategori', 'Menghapus kategori: ' . $category['name']);
        $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        redirect('admin/categories');
    }
}
