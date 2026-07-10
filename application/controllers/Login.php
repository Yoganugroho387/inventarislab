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

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function login_form() {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            return $this->do_login();
        }

        if ($this->auth_lib->is_logged_in()) {
            $role = $this->auth_lib->role();
            redirect($role === 'admin' ? 'admin/dashboard' : 'staff/dashboard');
        }

        $data['title'] = 'Login';
        $data['content'] = $this->load->view('auth/login', $data, TRUE);
        $this->load->view('layouts/auth', $data);
    }

    public function do_login() {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        if ($this->auth_lib->login($username, $password)) {
            $this->load->model('activity_log_model');
            $this->activity_log_model->log($this->auth_lib->id(), 'Login', 'Berhasil masuk ke dalam sistem.');

            $role = $this->auth_lib->role();
            redirect($role === 'admin' ? 'admin/dashboard' : 'staff/dashboard');
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah atau akun dinonaktifkan.');
            $this->session->set_flashdata('old_input', $_POST);
            redirect('login');
        }
    }

    public function logout() {
        if ($this->auth_lib->is_logged_in()) {
            $this->load->model('activity_log_model');
            $this->activity_log_model->log($this->auth_lib->id(), 'Logout', 'Keluar dari sistem.');
        }
        $this->auth_lib->logout();
        redirect('login');
    }
}
