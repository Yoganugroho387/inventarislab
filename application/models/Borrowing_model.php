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

class Borrowing_model extends CI_Model {
    protected $table = 'borrowings';

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
        $this->db->select('b.*, uc.name as creator_name, ua.name as approver_name');
        $this->db->from('borrowings b');
        $this->db->join('users uc', 'b.created_by = uc.id');
        $this->db->join('users ua', 'b.approved_by = ua.id', 'left');

        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('b.borrower_name', $filters['search']);
            $this->db->or_like('b.borrower_identity', $filters['search']);
            $this->db->group_end();
        }

        if (!empty($filters['status'])) {
            $this->db->where('b.status', $filters['status']);
        }

        if (!empty($filters['created_by'])) {
            $this->db->where('b.created_by', $filters['created_by']);
        }

        $this->db->order_by('b.id', 'DESC');
        $borrowings = $this->db->get()->result_array();

        foreach ($borrowings as &$b) {
            $b['items'] = $this->get_items_by_borrowing_id($b['id']);
        }

        return $borrowings;
    }

    public function find_with_relations($id) {
        $this->db->select('b.*, uc.name as creator_name, uc.username as creator_username, ua.name as approver_name, ua.username as approver_username');
        $this->db->from('borrowings b');
        $this->db->join('users uc', 'b.created_by = uc.id');
        $this->db->join('users ua', 'b.approved_by = ua.id', 'left');
        $this->db->where('b.id', $id);
        $this->db->limit(1);
        $borrowing = $this->db->get()->row_array();

        if ($borrowing) {
            $borrowing['items'] = $this->get_items_by_borrowing_id($id);
        }

        return $borrowing;
    }

    public function get_items_by_borrowing_id($borrowing_id) {
        $this->db->select('bd.*, i.name as item_name, i.code as item_code, i.item_type, u.name as unit_name');
        $this->db->from('borrowing_details bd');
        $this->db->join('items i', 'bd.item_id = i.id');
        $this->db->join('units u', 'i.unit_id = u.id');
        $this->db->where('bd.borrowing_id', $borrowing_id);
        return $this->db->get()->result_array();
    }

    public function create_borrowing($borrowing_data, $items) {
        $this->db->trans_start();

        // Insert primary borrowing entry
        $this->db->insert($this->table, $borrowing_data);
        $borrowing_id = $this->db->insert_id();

        $this->load->model('item_model');

        foreach ($items as $item) {
            // If it is immediately active ('dipinjam'), validate and deduct stock
            if ($borrowing_data['status'] === 'dipinjam') {
                $db_item = $this->item_model->find($item['item_id']);

                if ($db_item['stock'] < $item['quantity']) {
                    $this->db->trans_rollback();
                    throw new Exception("Stok barang '" . $db_item['name'] . "' tidak cukup. Tersedia: " . $db_item['stock']);
                }

                $new_stock = $db_item['stock'] - $item['quantity'];
                $this->item_model->update($item['item_id'], ['stock' => $new_stock]);

                // If it is an asset (inventaris) and stock hits 0, toggle state
                if ($db_item['item_type'] === 'inventaris' && $new_stock == 0) {
                    $this->item_model->update($item['item_id'], ['item_status' => 'dipinjam']);
                }
            }

            $this->db->insert('borrowing_details', [
                'borrowing_id' => $borrowing_id,
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity']
            ]);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception("Gagal menyimpan data peminjaman.");
        }

        return $borrowing_id;
    }

    public function approve($id, $approved_by_id) {
        $this->db->trans_start();

        $borrowing = $this->find($id);
        if (!$borrowing || $borrowing['status'] !== 'menunggu') {
            $this->db->trans_rollback();
            throw new Exception("Peminjaman tidak dalam status menunggu persetujuan.");
        }

        $items = $this->get_items_by_borrowing_id($id);
        $this->load->model('item_model');

        foreach ($items as $item) {
            $db_item = $this->item_model->find($item['item_id']);
            if ($db_item['stock'] < $item['quantity']) {
                $this->db->trans_rollback();
                throw new Exception("Stok barang '" . $item['item_name'] . "' tidak cukup. Tersedia: " . $db_item['stock']);
            }

            $new_stock = $db_item['stock'] - $item['quantity'];
            $this->item_model->update($item['item_id'], ['stock' => $new_stock]);

            if ($db_item['item_type'] === 'inventaris' && $new_stock == 0) {
                $this->item_model->update($item['item_id'], ['item_status' => 'dipinjam']);
            }
        }

        $this->update($id, [
            'status' => 'dipinjam',
            'approved_by' => $approved_by_id,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function reject($id, $approved_by_id) {
        return $this->update($id, [
            'status' => 'ditolak',
            'approved_by' => $approved_by_id,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function return_items($borrowing_id, $returns, $received_by_id) {
        $this->db->trans_start();

        $borrowing = $this->find($borrowing_id);
        if (!$borrowing || ($borrowing['status'] !== 'dipinjam' && $borrowing['status'] !== 'terlambat')) {
            $this->db->trans_rollback();
            throw new Exception("Peminjaman tidak dalam status aktif.");
        }

        $this->load->model('item_model');

        foreach ($returns as $ret) {
            $item_id = $ret['item_id'];
            $condition = $ret['return_condition'];
            $notes = $ret['return_notes'] ?? '';

            // Get detail quantity
            $this->db->select('quantity');
            $this->db->where('borrowing_id', $borrowing_id);
            $this->db->where('item_id', $item_id);
            $quantity_row = $this->db->get('borrowing_details')->row();
            $quantity = $quantity_row ? $quantity_row->quantity : 0;

            // Update borrowing_details
            $this->db->where('borrowing_id', $borrowing_id);
            $this->db->where('item_id', $item_id);
            $this->db->update('borrowing_details', [
                'return_date' => date('Y-m-d'),
                'return_condition' => $condition,
                'return_notes' => $notes
            ]);

            $db_item = $this->item_model->find($item_id);
            if ($db_item) {
                if ($db_item['item_type'] === 'inventaris') {
                    if ($condition === 'baik' || $condition === 'rusak') {
                        $new_stock = $db_item['stock'] + $quantity;

                        $update_data = [
                            'stock' => $new_stock,
                            'item_status' => 'tersedia'
                        ];

                        if ($condition === 'rusak') {
                            $update_data['condition_status'] = 'rusak';
                        }

                        $this->item_model->update($item_id, $update_data);
                    } else if ($condition === 'hilang') {
                        $this->item_model->update($item_id, [
                            'condition_status' => 'rusak',
                            'item_status' => 'rusak'
                        ]);
                    }
                } else {
                    // consumables
                    if ($condition === 'baik') {
                        $new_stock = $db_item['stock'] + $quantity;
                        $this->item_model->update($item_id, ['stock' => $new_stock]);
                    }
                }
            }
        }

        // Check if there are any remaining items left to return
        $this->db->where('borrowing_id', $borrowing_id);
        $this->db->where('return_date IS NULL');
        $unreturned_count = $this->db->count_all_results('borrowing_details');

        if ($unreturned_count == 0) {
            $this->update($borrowing_id, [
                'status' => 'dikembalikan',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
