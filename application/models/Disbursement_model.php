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

class Disbursement_model extends CI_Model {
    protected $table = 'item_disbursements';

    public function get_all($filters = []) {
        $this->db->select('id.*, i.name as item_name, i.code as item_code, u.name as unit_name, l.name as location_name, usr.name as giver_name');
        $this->db->from('item_disbursements id');
        $this->db->join('items i', 'id.item_id = i.id');
        $this->db->join('units u', 'i.unit_id = u.id');
        $this->db->join('locations l', 'i.location_id = l.id');
        $this->db->join('users usr', 'id.given_by = usr.id');

        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('id.receiver_name', $filters['search']);
            $this->db->or_like('id.receiver_identity', $filters['search']);
            $this->db->or_like('i.name', $filters['search']);
            $this->db->or_like('i.code', $filters['search']);
            $this->db->group_end();
        }

        $this->db->order_by('id.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function find($id) {
        $this->db->select('id.*, i.name as item_name, i.code as item_code, u.name as unit_name, l.name as location_name, usr.name as giver_name');
        $this->db->from('item_disbursements id');
        $this->db->join('items i', 'id.item_id = i.id');
        $this->db->join('units u', 'i.unit_id = u.id');
        $this->db->join('locations l', 'i.location_id = l.id');
        $this->db->join('users usr', 'id.given_by = usr.id');
        $this->db->where('id.id', $id);
        return $this->db->get()->row_array();
    }

    public function create_disbursement($data) {
        $this->db->trans_start();

        // 1. Get current stock of the item
        $item = $this->db->where('id', $data['item_id'])->get('items')->row_array();
        if (!$item) {
            $this->db->trans_rollback();
            throw new Exception("Barang tidak ditemukan.");
        }

        if ($item['item_type'] !== 'habis_pakai') {
            $this->db->trans_rollback();
            throw new Exception("Barang ini bukan tipe bahan habis pakai.");
        }

        if ($item['stock'] < $data['quantity']) {
            $this->db->trans_rollback();
            throw new Exception("Stok tidak mencukupi. Tersedia: " . $item['stock'] . " " . $item['unit_id']);
        }

        // 2. Insert into disbursements
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();

        // 3. Deduct stock
        $new_stock = $item['stock'] - $data['quantity'];
        $update_data = ['stock' => $new_stock];
        
        // If stock hits 0, update condition_status to 'habis'
        if ($new_stock <= 0) {
            $update_data['condition_status'] = 'habis';
            $update_data['item_status'] = 'habis';
        }

        $this->db->where('id', $data['item_id'])->update('items', $update_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception("Terjadi kesalahan saat menyimpan data pengeluaran.");
        }

        return $insert_id;
    }
}
