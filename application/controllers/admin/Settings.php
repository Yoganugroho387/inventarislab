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

class Settings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('setting_model');
        $this->load->model('activity_log_model');
    }

    public function index() {
        $data = [
            'title' => 'Pengaturan Lab',
            'pageTitle' => 'Pengaturan Identitas Laboratorium',
            'pageSubtitle' => 'Konfigurasi nama aplikasi, nama laboratorium, kepala lab, alamat, dan prefiks kode QR.',
            'settings' => $this->setting_model->all_settings(),
            'prefix' => '/admin'
        ];
        $data['content'] = $this->load->view('settings/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function update() {
        $fields = ['app_name', 'lab_name', 'lab_head', 'lab_address', 'qr_prefix', 'whatsapp_token', 'whatsapp_enabled', 'institution_name'];
        
        foreach ($fields as $field) {
            $val = $this->input->post($field, TRUE);
            if ($val !== NULL) {
                $this->setting_model->update_val($field, $val);
            }
        }

        // Upload logo
        $logo = $this->_upload_logo();
        if ($logo !== NULL) {
            // Delete old logo if exists
            $old_logo = $this->setting_model->get_val('institution_logo');
            if ($old_logo && file_exists('./uploads/' . $old_logo)) {
                unlink('./uploads/' . $old_logo);
            }
            $this->setting_model->update_val('institution_logo', $logo);
        }

        $this->activity_log_model->log($this->auth_lib->id(), 'Ubah Pengaturan', 'Memperbarui parameter konfigurasi laboratorium.');
        $this->session->set_flashdata('success', 'Konfigurasi pengaturan lab berhasil disimpan.');
        redirect('admin/settings');
    }

    private function _upload_logo() {
        if (empty($_FILES['institution_logo']['name'])) {
            return NULL;
        }

        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if ($this->upload->do_upload('institution_logo')) {
            return $this->upload->data('file_name');
        }
        return NULL;
    }
}
