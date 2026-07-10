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

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('role_model');
        $this->load->model('activity_log_model');
    }

    public function index() {
        $data = [
            'title' => 'Manajemen User',
            'pageTitle' => 'User Pengguna',
            'pageSubtitle' => 'Kelola akun laboran/petugas dan administrator sistem.',
            'users' => $this->user_model->all_with_role(),
            'prefix' => '/admin'
        ];
        $data['content'] = $this->load->view('users/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function create() {
        $data = [
            'title' => 'Tambah User',
            'pageTitle' => 'Tambah User Baru',
            'pageSubtitle' => 'Buat akun pengguna baru dengan hak akses tertentu.',
            'roles' => $this->role_model->get_all(),
            'prefix' => '/admin'
        ];
        $data['content'] = $this->load->view('users/create', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function store() {
        $this->form_validation->set_rules('name', 'Nama Lengkap', 'required|min_length[3]');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('role_id', 'Role', 'required|integer');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('form_errors', $this->form_validation->error_array());
            $this->session->set_flashdata('old_input', $_POST);
            redirect('admin/users/create');
        } else {
            $data = [
                'name' => $this->input->post('name', TRUE),
                'username' => $this->input->post('username', TRUE),
                'role_id' => (int)$this->input->post('role_id'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'is_active' => (int)($this->input->post('is_active') !== null)
            ];

            $this->user_model->insert($data);
            $this->activity_log_model->log($this->auth_lib->id(), 'Tambah User', 'Membuat user baru: ' . $data['username']);
            $this->session->set_flashdata('success', 'User baru berhasil dibuat.');
            redirect('admin/users');
        }
    }

    public function edit($id) {
        $user = $this->user_model->find($id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('admin/users');
        }

        $data = [
            'title' => 'Ubah User',
            'pageTitle' => 'Ubah Data User',
            'pageSubtitle' => 'Edit informasi akun, hak akses, atau ubah status aktif user.',
            'user' => $user,
            'roles' => $this->role_model->get_all(),
            'prefix' => '/admin'
        ];
        $data['content'] = $this->load->view('users/edit', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function edit_username_check($username) {
        $id = $this->uri->segment(4);
        $this->db->where('id !=', $id);
        $this->db->where('username', $username);
        $exists = $this->db->count_all_results('users') > 0;
        if ($exists) {
            $this->form_validation->set_message('edit_username_check', 'Username sudah digunakan.');
            return FALSE;
        }
        return TRUE;
    }

    public function update($id) {
        $user = $this->user_model->find($id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('admin/users');
        }

        $this->form_validation->set_rules('name', 'Nama Lengkap', 'required|min_length[3]');
        $this->form_validation->set_rules('username', 'Username', 'required|callback_edit_username_check');
        $this->form_validation->set_rules('role_id', 'Role', 'required|integer');
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('form_errors', $this->form_validation->error_array());
            $this->session->set_flashdata('old_input', $_POST);
            redirect('admin/users/edit/' . $id);
        } else {
            $is_active = (int)($this->input->post('is_active') !== null);
            if ($id == $this->auth_lib->id() && $is_active == 0) {
                $this->session->set_flashdata('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
                redirect('admin/users/edit/' . $id);
            }

            $data = [
                'name' => $this->input->post('name', TRUE),
                'username' => $this->input->post('username', TRUE),
                'role_id' => (int)$this->input->post('role_id'),
                'is_active' => $is_active
            ];

            if ($this->input->post('password')) {
                $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            }

            $this->user_model->update($id, $data);
            $this->activity_log_model->log($this->auth_lib->id(), 'Ubah User', 'Mengubah data user: ' . $data['username']);
            $this->session->set_flashdata('success', 'Data user berhasil diperbarui.');
            redirect('admin/users');
        }
    }

    public function delete($id) {
        $user = $this->user_model->find($id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('admin/users');
        }

        if ($id == $this->auth_lib->id()) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun sendiri.');
            redirect('admin/users');
        }

        $has_borrowings = $this->db->where('created_by', $id)->count_all_results('borrowings') > 0;
        if ($has_borrowings) {
            $this->session->set_flashdata('error', 'User tidak dapat dihapus karena sudah memiliki riwayat pencatatan transaksi.');
            redirect('admin/users');
        }

        $this->user_model->delete($id);
        $this->activity_log_model->log($this->auth_lib->id(), 'Hapus User', 'Menghapus user: ' . $user['username']);
        $this->session->set_flashdata('success', 'User berhasil dihapus.');
        redirect('admin/users');
    }
}
