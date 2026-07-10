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

class Item_model extends CI_Model {
    protected $table = 'items';

    public function get_all() {
        return $this->db->get($this->table)->result_array();
    }

    public function find($id) {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function all_with_relations($filters = []) {
        $this->db->select('i.*, c.name as category_name, l.name as location_name, u.name as unit_name');
        $this->db->from('items i');
        $this->db->join('categories c', 'i.category_id = c.id');
        $this->db->join('locations l', 'i.location_id = l.id');
        $this->db->join('units u', 'i.unit_id = u.id');

        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('i.name', $filters['search']);
            $this->db->or_like('i.code', $filters['search']);
            $this->db->group_end();
        }

        if (!empty($filters['category_id'])) {
            $this->db->where('i.category_id', $filters['category_id']);
        }

        if (!empty($filters['location_id'])) {
            $this->db->where('i.location_id', $filters['location_id']);
        }

        if (!empty($filters['item_type'])) {
            $this->db->where('i.item_type', $filters['item_type']);
        }

        if (!empty($filters['condition_status'])) {
            $this->db->where('i.condition_status', $filters['condition_status']);
        }

        if (isset($filters['low_stock']) && $filters['low_stock'] === true) {
            $this->db->where('i.item_type', 'habis_pakai');
            $this->db->where('i.stock <= i.minimum_stock', NULL, FALSE);
        }

        $this->db->order_by('i.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function find_with_relations($id) {
        $this->db->select('i.*, c.name as category_name, l.name as location_name, u.name as unit_name');
        $this->db->from('items i');
        $this->db->join('categories c', 'i.category_id = c.id');
        $this->db->join('locations l', 'i.location_id = l.id');
        $this->db->join('units u', 'i.unit_id = u.id');
        $this->db->where('i.id', $id);
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function get_low_stock_count() {
        $this->db->where('item_type', 'habis_pakai');
        $this->db->where('stock <= minimum_stock', NULL, FALSE);
        return $this->db->count_all_results($this->table);
    }

    public function get_low_stock_items() {
        $this->db->select('i.*, u.name as unit_name, l.name as location_name');
        $this->db->from('items i');
        $this->db->join('units u', 'i.unit_id = u.id');
        $this->db->join('locations l', 'i.location_id = l.id');
        $this->db->where('i.item_type', 'habis_pakai');
        $this->db->where('i.stock <= i.minimum_stock', NULL, FALSE);
        $this->db->order_by('i.stock', 'ASC');
        return $this->db->get()->result_array();
    }
}
