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

class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('activity_log_model');
    }

    public function index() {
        $userId = $this->auth_lib->id();
        $user = $this->user_model->find_with_role($userId);

        $data = [
            'title' => 'Profil Pengguna',
            'pageTitle' => 'Pengaturan Profil Akun',
            'pageSubtitle' => 'Perbarui informasi pribadi nama lengkap, username, dan kata sandi keamanan Anda.',
            'user' => $user,
            'prefix' => '/admin'
        ];
        $data['content'] = $this->load->view('profile/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function profile_username_check($username) {
        $id = $this->auth_lib->id();
        $this->db->where('id !=', $id);
        $this->db->where('username', $username);
        $exists = $this->db->count_all_results('users') > 0;
        if ($exists) {
            $this->form_validation->set_message('profile_username_check', 'Username sudah digunakan.');
            return FALSE;
        }
        return TRUE;
    }

    public function update() {
        $id = $this->auth_lib->id();
        $user = $this->user_model->find($id);

        $this->form_validation->set_rules('name', 'Nama Lengkap', 'required|min_length[3]');
        $this->form_validation->set_rules('username', 'Username', 'required|callback_profile_username_check');
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password Baru', 'min_length[6]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('form_errors', $this->form_validation->error_array());
            $this->session->set_flashdata('old_input', $_POST);
            redirect('admin/profile');
        } else {
            $data = [
                'name' => $this->input->post('name', TRUE),
                'username' => $this->input->post('username', TRUE),
            ];

            if ($this->input->post('password')) {
                $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            }

            $this->user_model->update($id, $data);
            
            $this->session->set_userdata([
                'user_name' => $data['name'],
                'username'  => $data['username'],
            ]);

            $this->activity_log_model->log($id, 'Ubah Profil', 'Memperbarui profil akun sendiri.');
            $this->session->set_flashdata('success', 'Profil Anda berhasil diperbarui.');
            redirect('admin/profile');
        }
    }
}
