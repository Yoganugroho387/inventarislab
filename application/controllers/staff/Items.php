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

class Items extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('item_model');
        $this->load->model('category_model');
        $this->load->model('location_model');
    }

    public function index() {
        $filters = [
            'search' => $this->input->get('search', TRUE),
            'category_id' => $this->input->get('category_id'),
            'location_id' => $this->input->get('location_id'),
            'item_type' => $this->input->get('item_type'),
            'condition_status' => $this->input->get('condition_status'),
            'low_stock' => $this->input->get('low_stock') === '1'
        ];

        $data = [
            'title' => 'Lihat Inventaris',
            'pageTitle' => 'Katalog Inventaris Barang',
            'pageSubtitle' => 'Cari alat praktikum, periksa status ketersediaan, atau lihat informasi detail barang.',
            'items' => $this->item_model->all_with_relations($filters),
            'categories' => $this->category_model->get_all(),
            'locations' => $this->location_model->get_all(),
            'filters' => $filters,
            'prefix' => '/staff'
        ];

        $data['content'] = $this->load->view('items/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function view($id) {
        $item = $this->item_model->find_with_relations($id);
        if (!$item) {
            $this->session->set_flashdata('error', 'Barang tidak ditemukan.');
            redirect('staff/items');
        }

        $this->load->model('setting_model');
        $qr_prefix = $this->setting_model->get_val('qr_prefix', 'LAB-INF-');

        $data = [
            'title' => 'Detail Barang',
            'pageTitle' => 'Detail Informasi Barang',
            'pageSubtitle' => 'Spesifikasi teknis, data stok, penempatan lokasi, dan label kode QR barang.',
            'item' => $item,
            'qr_prefix' => $qr_prefix,
            'prefix' => '/staff'
        ];
        $data['content'] = $this->load->view('items/view', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }
}
