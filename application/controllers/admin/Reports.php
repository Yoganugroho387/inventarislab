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

class Reports extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('item_model');
        $this->load->model('category_model');
        $this->load->model('location_model');
        $this->load->model('borrowing_model');
    }

    public function index() {
        $reportType = $this->input->get('report_type') ?: 'items';
        $categoryId = $this->input->get('category_id');
        $locationId = $this->input->get('location_id');
        $itemType = $this->input->get('item_type');
        $startDate = $this->input->get('start_date');
        $endDate = $this->input->get('end_date');
        $status = $this->input->get('status');
        
        $results = [];
        
        if ($reportType === 'items') {
            $this->db->select('i.*, c.name as category_name, l.name as location_name, u.name as unit_name');
            $this->db->from('items i');
            $this->db->join('categories c', 'i.category_id = c.id');
            $this->db->join('locations l', 'i.location_id = l.id');
            $this->db->join('units u', 'i.unit_id = u.id');
            
            if ($categoryId) $this->db->where('i.category_id', $categoryId);
            if ($locationId) $this->db->where('i.location_id', $locationId);
            if ($itemType) $this->db->where('i.item_type', $itemType);
            if ($status) $this->db->where('i.condition_status', $status);
            
            $this->db->order_by('i.code', 'ASC');
            $results = $this->db->get()->result_array();
            
        } elseif ($reportType === 'borrowings') {
            $this->db->select('b.*, uc.name as creator_name, ua.name as approver_name');
            $this->db->from('borrowings b');
            $this->db->join('users uc', 'b.created_by = uc.id');
            $this->db->join('users ua', 'b.approved_by = ua.id', 'left');
            
            if ($startDate) $this->db->where('b.loan_date >=', $startDate);
            if ($endDate) $this->db->where('b.loan_date <=', $endDate);
            if ($status) $this->db->where('b.status', $status);
            
            $this->db->order_by('b.id', 'DESC');
            $results = $this->db->get()->result_array();
            
            foreach ($results as &$row) {
                $row['items'] = $this->borrowing_model->get_items_by_borrowing_id($row['id']);
            }
            
        } elseif ($reportType === 'returns') {
            $this->db->select('bd.*, b.borrower_name, b.borrower_identity, b.loan_date, b.due_date, i.name as item_name, i.code as item_code, u.name as unit_name');
            $this->db->from('borrowing_details bd');
            $this->db->join('borrowings b', 'bd.borrowing_id = b.id');
            $this->db->join('items i', 'bd.item_id = i.id');
            $this->db->join('units u', 'i.unit_id = u.id');
            $this->db->where('bd.return_date IS NOT NULL');
            
            if ($startDate) $this->db->where('bd.return_date >=', $startDate);
            if ($endDate) $this->db->where('bd.return_date <=', $endDate);
            if ($status) $this->db->where('bd.return_condition', $status);
            
            $this->db->order_by('bd.return_date', 'DESC');
            $results = $this->db->get()->result_array();
        }
        
        $data = [
            'title' => 'Laporan Sistem',
            'pageTitle' => 'Laporan & Ekspor Data',
            'pageSubtitle' => 'Buat laporan periodik dan ekspor data inventaris atau sirkulasi ke file CSV/Excel.',
            'categories' => $this->category_model->get_all(),
            'locations' => $this->location_model->get_all(),
            'reportType' => $reportType,
            'results' => $results,
            'filters' => [
                'category_id' => $categoryId,
                'location_id' => $locationId,
                'item_type' => $itemType,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status
            ],
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('reports/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function export() {
        $reportType = $this->input->get('report_type') ?: 'items';
        $categoryId = $this->input->get('category_id');
        $locationId = $this->input->get('location_id');
        $itemType = $this->input->get('item_type');
        $startDate = $this->input->get('start_date');
        $endDate = $this->input->get('end_date');
        $status = $this->input->get('status');
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=laporan_' . $reportType . '_' . date('Ymd_His') . '.csv');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        if ($reportType === 'items') {
            fputcsv($output, ['No', 'Kode Barang', 'Nama Barang', 'Kategori', 'Lokasi', 'Jenis', 'Stok', 'Satuan', 'Kondisi', 'Status', 'Tahun Pengadaan', 'Sumber Dana', 'Harga Perolehan']);
            
            $this->db->select('i.*, c.name as category_name, l.name as location_name, u.name as unit_name');
            $this->db->from('items i');
            $this->db->join('categories c', 'i.category_id = c.id');
            $this->db->join('locations l', 'i.location_id = l.id');
            $this->db->join('units u', 'i.unit_id = u.id');
            
            if ($categoryId) $this->db->where('i.category_id', $categoryId);
            if ($locationId) $this->db->where('i.location_id', $locationId);
            if ($itemType) $this->db->where('i.item_type', $itemType);
            if ($status) $this->db->where('i.condition_status', $status);
            
            $this->db->order_by('i.code', 'ASC');
            $items = $this->db->get()->result_array();
            
            $no = 1;
            foreach ($items as $item) {
                fputcsv($output, [
                    $no++,
                    $item['code'],
                    $item['name'],
                    $item['category_name'],
                    $item['location_name'],
                    $item['item_type'] === 'inventaris' ? 'Inventaris Tetap' : 'Habis Pakai',
                    $item['stock'],
                    $item['unit_name'],
                    ucfirst($item['condition_status']),
                    ucfirst($item['item_status']),
                    $item['procurement_year'],
                    $item['funding_source'],
                    $item['acquisition_price']
                ]);
            }
            
        } elseif ($reportType === 'borrowings') {
            fputcsv($output, ['No', 'ID Peminjaman', 'Nama Peminjam', 'Identitas (NIM/NIP)', 'Status Peminjam', 'Tgl Pinjam', 'Batas Kembali', 'Barang Dipinjam', 'Status Peminjaman', 'Diinput Oleh', 'Disetujui Oleh']);
            
            $this->db->select('b.*, uc.name as creator_name, ua.name as approver_name');
            $this->db->from('borrowings b');
            $this->db->join('users uc', 'b.created_by = uc.id');
            $this->db->join('users ua', 'b.approved_by = ua.id', 'left');
            
            if ($startDate) $this->db->where('b.loan_date >=', $startDate);
            if ($endDate) $this->db->where('b.loan_date <=', $endDate);
            if ($status) $this->db->where('b.status', $status);
            
            $this->db->order_by('b.id', 'DESC');
            $borrowings = $this->db->get()->result_array();
            
            $no = 1;
            foreach ($borrowings as $b) {
                $details = $this->borrowing_model->get_items_by_borrowing_id($b['id']);
                $itemNames = array_map(function($i) { return $i['item_name'] . ' (x' . $i['quantity'] . ' ' . $i['unit_name'] . ')'; }, $details);
                $itemsStr = implode(', ', $itemNames);
                
                fputcsv($output, [
                    $no++,
                    '#' . $b['id'],
                    $b['borrower_name'],
                    $b['borrower_identity'],
                    ucfirst($b['borrower_type']),
                    $b['loan_date'],
                    $b['due_date'],
                    $itemsStr,
                    ucfirst($b['status']),
                    $b['creator_name'],
                    $b['approver_name'] ?: '-'
                ]);
            }
            
        } elseif ($reportType === 'returns') {
            fputcsv($output, ['No', 'ID Peminjaman', 'Nama Peminjam', 'Identitas (NIM/NIP)', 'Kode Barang', 'Nama Barang', 'Jumlah', 'Satuan', 'Tgl Pinjam', 'Tgl Kembali Aktual', 'Kondisi Kembali', 'Catatan']);
            
            $this->db->select('bd.*, b.borrower_name, b.borrower_identity, b.loan_date, b.due_date, i.name as item_name, i.code as item_code, u.name as unit_name');
            $this->db->from('borrowing_details bd');
            $this->db->join('borrowings b', 'bd.borrowing_id = b.id');
            $this->db->join('items i', 'bd.item_id = i.id');
            $this->db->join('units u', 'i.unit_id = u.id');
            $this->db->where('bd.return_date IS NOT NULL');
            
            if ($startDate) $this->db->where('bd.return_date >=', $startDate);
            if ($endDate) $this->db->where('bd.return_date <=', $endDate);
            if ($status) $this->db->where('bd.return_condition', $status);
            
            $this->db->order_by('bd.return_date', 'DESC');
            $returns = $this->db->get()->result_array();
            
            $no = 1;
            foreach ($returns as $r) {
                fputcsv($output, [
                    $no++,
                    '#' . $r['borrowing_id'],
                    $r['borrower_name'],
                    $r['borrower_identity'],
                    $r['item_code'],
                    $r['item_name'],
                    $r['quantity'],
                    $r['unit_name'],
                    $r['loan_date'],
                    $r['return_date'],
                    ucfirst($r['return_condition']),
                    $r['return_notes'] ?: '-'
                ]);
            }
        }
        
        fclose($output);
        exit;
    }
}
