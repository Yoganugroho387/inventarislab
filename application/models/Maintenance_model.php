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

class Maintenance_model extends CI_Model {
    protected $table = 'maintenance_logs';

    public function get_all($filters = []) {
        $this->db->select('ml.*, i.name as item_name, i.code as item_code, i.image as item_image, l.name as location_name, usr.name as creator_name');
        $this->db->from('maintenance_logs ml');
        $this->db->join('items i', 'ml.item_id = i.id');
        $this->db->join('locations l', 'i.location_id = l.id');
        $this->db->join('users usr', 'ml.created_by = usr.id');

        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('i.name', $filters['search']);
            $this->db->or_like('i.code', $filters['search']);
            $this->db->or_like('ml.vendor_name', $filters['search']);
            $this->db->group_end();
        }

        if (!empty($filters['status'])) {
            $this->db->where('ml.status', $filters['status']);
        }

        $this->db->order_by('ml.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function find($id) {
        $this->db->select('ml.*, i.name as item_name, i.code as item_code, i.image as item_image, l.name as location_name, usr.name as creator_name');
        $this->db->from('maintenance_logs ml');
        $this->db->join('items i', 'ml.item_id = i.id');
        $this->db->join('locations l', 'i.location_id = l.id');
        $this->db->join('users usr', 'ml.created_by = usr.id');
        $this->db->where('ml.id', $id);
        return $this->db->get()->row_array();
    }

    public function create_log($data) {
        $this->db->trans_start();

        // 1. Insert maintenance log
        $this->db->insert($this->table, $data);
        $log_id = $this->db->insert_id();

        // 2. Set item condition and status to 'maintenance'
        $this->db->where('id', $data['item_id'])->update('items', [
            'condition_status' => 'maintenance',
            'item_status' => 'maintenance'
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception("Gagal menyimpan data perawatan.");
        }

        return $log_id;
    }

    public function update_log($id, $update_data) {
        $this->db->trans_start();

        // Get current log
        $log = $this->find($id);
        if (!$log) {
            $this->db->trans_rollback();
            throw new Exception("Log perawatan tidak ditemukan.");
        }

        // 1. Update log data
        $this->db->where('id', $id)->update($this->table, $update_data);

        // 2. Update item condition depending on repair status
        if ($update_data['status'] === 'selesai') {
            $this->db->where('id', $log['item_id'])->update('items', [
                'condition_status' => 'tersedia',
                'item_status' => 'tersedia'
            ]);
        } else if ($update_data['status'] === 'tidak_bisa_diperbaiki') {
            $this->db->where('id', $log['item_id'])->update('items', [
                'condition_status' => 'rusak',
                'item_status' => 'rusak'
            ]);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
