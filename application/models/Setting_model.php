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

class Setting_model extends CI_Model {

    public function get_val($key, $default = '') {
        $row = $this->db->where('setting_key', $key)->get('settings')->row_array();
        return $row ? $row['setting_value'] : $default;
    }

    public function update_val($key, $value) {
        $this->db->where('setting_key', $key)->update('settings', ['setting_value' => $value]);
    }

    public function all_settings() {
        $rows = $this->db->get('settings')->result_array();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    // Static-like accessor for views
    public static function getVal($key, $default = '') {
        $CI =& get_instance();
        $CI->load->model('setting_model');
        return $CI->setting_model->get_val($key, $default);
    }
}
