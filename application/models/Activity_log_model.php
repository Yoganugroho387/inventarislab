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

class Activity_log_model extends CI_Model {
    protected $table = 'activity_logs';

    public function get_logs($limit = 100) {
        $this->db->select('al.*, u.name as user_name, r.name as role_name');
        $this->db->from('activity_logs al');
        $this->db->join('users u', 'al.user_id = u.id', 'left');
        $this->db->join('roles r', 'u.role_id = r.id', 'left');
        $this->db->order_by('al.id', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function log($user_id, $action, $details = null) {
        $ip_address = $this->input->ip_address();
        return $this->db->insert($this->table, [
            'user_id' => $user_id,
            'action' => $action,
            'details' => $details,
            'ip_address' => $ip_address,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
