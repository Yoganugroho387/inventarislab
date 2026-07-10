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

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('item_model');
        $this->load->model('borrowing_model');
    }

    public function index() {
        $userId = $this->auth_lib->id();
        $totalItems = $this->db->count_all('items');

        $activeLoans = $this->db->where('created_by', $userId)
            ->group_start()
                ->where('status', 'dipinjam')
                ->or_where('status', 'terlambat')
            ->group_end()
            ->count_all_results('borrowings');

        $dueToday = $this->db->where('created_by', $userId)
            ->where('due_date', date('Y-m-d'))
            ->group_start()
                ->where('status', 'dipinjam')
                ->or_where('status', 'terlambat')
            ->group_end()
            ->count_all_results('borrowings');

        $lowStockCount = $this->item_model->get_low_stock_count();
        $lowStockItems = $this->item_model->get_low_stock_items();

        $myActiveLoans = $this->borrowing_model->all_with_relations([
            'status' => 'dipinjam',
            'created_by' => $userId
        ]);

        $this->db->select('al.*, u.name as user_name');
        $this->db->from('activity_logs al');
        $this->db->join('users u', 'al.user_id = u.id');
        $this->db->where('al.user_id', $userId);
        $this->db->order_by('al.id', 'DESC');
        $this->db->limit(5);
        $recentLogs = $this->db->get()->result_array();

        $data = [
            'title' => 'Dashboard Laboran',
            'pageTitle' => 'Beranda Operasional Laboran',
            'pageSubtitle' => 'Akses cepat modul sirkulasi, peminjaman aktif Anda, dan notifikasi stok.',
            'totalItems' => $totalItems,
            'activeLoans' => $activeLoans,
            'dueToday' => $dueToday,
            'lowStockCount' => $lowStockCount,
            'lowStockItems' => $lowStockItems,
            'myActiveLoans' => $myActiveLoans,
            'recentLogs' => $recentLogs,
            'prefix' => '/staff'
        ];

        $data['content'] = $this->load->view('dashboard/staff', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }
}
