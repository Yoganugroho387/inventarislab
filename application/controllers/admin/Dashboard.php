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
        $this->load->model('activity_log_model');
    }

    public function index() {
        $totalItems = $this->db->count_all('items');
        $totalCategories = $this->db->count_all('categories');
        $totalLocations = $this->db->count_all('locations');

        $totalBorrowed = $this->db->select_sum('quantity')
            ->join('borrowings b', 'borrowing_details.borrowing_id = b.id')
            ->where('borrowing_details.return_date', NULL)
            ->group_start()
                ->where('b.status', 'dipinjam')
                ->or_where('b.status', 'terlambat')
            ->group_end()
            ->get('borrowing_details')
            ->row()->quantity ?: 0;

        $totalDamaged = $this->db->where('condition_status', 'rusak')->count_all_results('items');
        
        $lowStockCount = $this->item_model->get_low_stock_count();
        $lowStockItems = $this->item_model->get_low_stock_items();

        $pendingBorrowings = $this->borrowing_model->all_with_relations(['status' => 'menunggu']);
        $recentLogs = $this->activity_log_model->get_logs(7);

        // Chart 1: Monthly Loan Trends (last 6 months)
        $chart_months = [];
        $chart_monthly_loans = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-$i months"));
            $month_name = date('M Y', strtotime("-$i months"));
            $chart_months[] = $month_name;
            
            $cnt = $this->db->where("DATE_FORMAT(loan_date, '%Y-%m') =", $m)
                ->count_all_results('borrowings');
            $chart_monthly_loans[] = $cnt;
        }

        // Chart 2: Condition Status Breakdown
        $status_counts = $this->db->select('condition_status, COUNT(id) as count')
            ->from('items')
            ->group_by('condition_status')
            ->get()->result_array();
        $chart_conditions = ['tersedia' => 0, 'rusak' => 0, 'maintenance' => 0, 'habis' => 0];
        foreach ($status_counts as $sc) {
            if (isset($chart_conditions[$sc['condition_status']])) {
                $chart_conditions[$sc['condition_status']] = (int)$sc['count'];
            }
        }

        // Chart 3: Top 5 Categories
        $chart_top_categories = $this->db->select('c.name as category_name, SUM(bd.quantity) as count')
            ->from('borrowing_details bd')
            ->join('items i', 'bd.item_id = i.id')
            ->join('categories c', 'i.category_id = c.id')
            ->group_by('c.name')
            ->order_by('count', 'DESC')
            ->limit(5)
            ->get()->result_array();

        $data = [
            'title' => 'Dashboard Admin',
            'pageTitle' => 'Beranda Administrasi',
            'pageSubtitle' => 'Ikhtisar cepat ketersediaan barang lab, peminjaman tertunda, dan rekaman aktivitas.',
            'totalItems' => $totalItems,
            'totalCategories' => $totalCategories,
            'totalLocations' => $totalLocations,
            'totalBorrowed' => $totalBorrowed,
            'totalDamaged' => $totalDamaged,
            'lowStockCount' => $lowStockCount,
            'lowStockItems' => $lowStockItems,
            'pendingBorrowings' => $pendingBorrowings,
            'recentLogs' => $recentLogs,
            'chartMonths' => $chart_months,
            'chartMonthlyLoans' => $chart_monthly_loans,
            'chartConditions' => $chart_conditions,
            'chartTopCategories' => $chart_top_categories,
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('dashboard/admin', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }
}
