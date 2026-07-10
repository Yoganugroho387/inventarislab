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
        $this->load->model('unit_model');
        $this->load->model('activity_log_model');
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
            'title' => 'Inventaris Barang',
            'pageTitle' => 'Katalog Inventaris Barang',
            'pageSubtitle' => 'Kelola persediaan alat praktikum, bahan habis pakai, beserta penempatan lokasinya.',
            'items' => $this->item_model->all_with_relations($filters),
            'categories' => $this->category_model->get_all(),
            'locations' => $this->location_model->get_all(),
            'filters' => $filters,
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('items/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function create() {
        // Generate next item code
        $prefix = Setting_model::getVal('qr_prefix', 'LAB');
        $lastItem = $this->db->select('code')->like('code', $prefix . '-', 'after')
            ->order_by('id', 'DESC')->limit(1)->get('items')->row();
        
        if ($lastItem) {
            // Extract the numeric part and increment
            $parts = explode('-', $lastItem->code);
            $lastNum = intval(end($parts));
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }
        $nextCode = $prefix . '-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        $data = [
            'title' => 'Tambah Barang',
            'pageTitle' => 'Tambah Barang Baru',
            'pageSubtitle' => 'Tambahkan aset tetap (inventaris) atau bahan habis pakai ke database.',
            'categories' => $this->category_model->get_all(),
            'locations' => $this->location_model->get_all(),
            'units' => $this->unit_model->get_all(),
            'nextCode' => $nextCode,
            'prefix' => '/admin'
        ];
        $data['content'] = $this->load->view('items/create', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function item_code_check($code) {
        $id = $this->uri->segment(4);
        if ($id) {
            $this->db->where('id !=', $id);
        }
        $this->db->where('code', $code);
        $exists = $this->db->count_all_results('items') > 0;
        if ($exists) {
            $this->form_validation->set_message('item_code_check', 'Kode barang sudah digunakan.');
            return FALSE;
        }
        return TRUE;
    }

    public function store() {
        $this->form_validation->set_rules('code', 'Kode Barang', 'required|callback_item_code_check');
        $this->form_validation->set_rules('name', 'Nama Barang', 'required|min_length[3]');
        $this->form_validation->set_rules('category_id', 'Kategori', 'required|integer');
        $this->form_validation->set_rules('location_id', 'Lokasi', 'required|integer');
        $this->form_validation->set_rules('unit_id', 'Satuan', 'required|integer');
        $this->form_validation->set_rules('item_type', 'Jenis Barang', 'required');
        $this->form_validation->set_rules('stock', 'Stok Awal', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('minimum_stock', 'Stok Minimum', 'required|integer|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('form_errors', $this->form_validation->error_array());
            $this->session->set_flashdata('old_input', $_POST);
            redirect('admin/items/create');
        } else {
            $image_name = $this->_upload_image();

            $data = [
                'code' => $this->input->post('code', TRUE),
                'name' => $this->input->post('name', TRUE),
                'category_id' => (int)$this->input->post('category_id'),
                'location_id' => (int)$this->input->post('location_id'),
                'unit_id' => (int)$this->input->post('unit_id'),
                'item_type' => $this->input->post('item_type', TRUE),
                'condition_status' => $this->input->post('condition_status', TRUE) ?: 'tersedia',
                'stock' => (int)$this->input->post('stock'),
                'minimum_stock' => (int)$this->input->post('minimum_stock'),
                'procurement_year' => $this->input->post('procurement_year') ? (int)$this->input->post('procurement_year') : NULL,
                'funding_source' => $this->input->post('funding_source', TRUE) ?: NULL,
                'acquisition_price' => $this->input->post('acquisition_price') ? (float)$this->input->post('acquisition_price') : 0.00,
                'item_status' => $this->input->post('condition_status', TRUE) ?: 'tersedia',
                'description' => $this->input->post('description', TRUE) ?: NULL,
                'image' => $image_name
            ];

            if ($data['item_type'] === 'habis_pakai' && $data['stock'] == 0) {
                $data['condition_status'] = 'habis';
                $data['item_status'] = 'habis';
            }

            $this->item_model->insert($data);
            $this->activity_log_model->log($this->auth_lib->id(), 'Tambah Barang', 'Membuat barang baru: ' . $data['code'] . ' - ' . $data['name']);
            $this->session->set_flashdata('success', 'Barang baru berhasil ditambahkan.');
            redirect('admin/items');
        }
    }

    public function edit($id) {
        $item = $this->item_model->find($id);
        if (!$item) {
            $this->session->set_flashdata('error', 'Barang tidak ditemukan.');
            redirect('admin/items');
        }

        $data = [
            'title' => 'Ubah Barang',
            'pageTitle' => 'Ubah Data Barang',
            'pageSubtitle' => 'Edit informasi spesifikasi, kuantitas stok, atau lokasi barang.',
            'item' => $item,
            'categories' => $this->category_model->get_all(),
            'locations' => $this->location_model->get_all(),
            'units' => $this->unit_model->get_all(),
            'prefix' => '/admin'
        ];
        $data['content'] = $this->load->view('items/edit', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function update($id) {
        $item = $this->item_model->find($id);
        if (!$item) {
            $this->session->set_flashdata('error', 'Barang tidak ditemukan.');
            redirect('admin/items');
        }

        $this->form_validation->set_rules('code', 'Kode Barang', 'required|callback_item_code_check');
        $this->form_validation->set_rules('name', 'Nama Barang', 'required|min_length[3]');
        $this->form_validation->set_rules('category_id', 'Kategori', 'required|integer');
        $this->form_validation->set_rules('location_id', 'Lokasi', 'required|integer');
        $this->form_validation->set_rules('unit_id', 'Satuan', 'required|integer');
        $this->form_validation->set_rules('item_type', 'Jenis Barang', 'required');
        $this->form_validation->set_rules('stock', 'Stok', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('minimum_stock', 'Stok Minimum', 'required|integer|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('form_errors', $this->form_validation->error_array());
            $this->session->set_flashdata('old_input', $_POST);
            redirect('admin/items/edit/' . $id);
        } else {
            $data = [
                'code' => $this->input->post('code', TRUE),
                'name' => $this->input->post('name', TRUE),
                'category_id' => (int)$this->input->post('category_id'),
                'location_id' => (int)$this->input->post('location_id'),
                'unit_id' => (int)$this->input->post('unit_id'),
                'item_type' => $this->input->post('item_type', TRUE),
                'condition_status' => $this->input->post('condition_status', TRUE) ?: 'tersedia',
                'stock' => (int)$this->input->post('stock'),
                'minimum_stock' => (int)$this->input->post('minimum_stock'),
                'procurement_year' => $this->input->post('procurement_year') ? (int)$this->input->post('procurement_year') : NULL,
                'funding_source' => $this->input->post('funding_source', TRUE) ?: NULL,
                'acquisition_price' => $this->input->post('acquisition_price') ? (float)$this->input->post('acquisition_price') : 0.00,
                'description' => $this->input->post('description', TRUE) ?: NULL
            ];

            $image_name = $this->_upload_image();
            if ($image_name) {
                if ($item['image'] && file_exists('./uploads/' . $item['image'])) {
                    unlink('./uploads/' . $item['image']);
                }
                $data['image'] = $image_name;
            }

            if ($data['item_type'] === 'habis_pakai' && $data['stock'] == 0) {
                $data['condition_status'] = 'habis';
                $data['item_status'] = 'habis';
            } else {
                if ($item['item_status'] === 'dipinjam' && $data['stock'] > 0) {
                    $data['item_status'] = 'tersedia';
                } else {
                    $data['item_status'] = $data['condition_status'];
                }
            }

            $this->item_model->update($id, $data);
            $this->activity_log_model->log($this->auth_lib->id(), 'Ubah Barang', 'Mengubah data barang: ' . $data['code'] . ' - ' . $data['name']);
            $this->session->set_flashdata('success', 'Data barang berhasil diperbarui.');
            redirect('admin/items');
        }
    }

    public function delete($id) {
        $item = $this->item_model->find($id);
        if (!$item) {
            $this->session->set_flashdata('error', 'Barang tidak ditemukan.');
            redirect('admin/items');
        }

        $has_loans = $this->db->where('item_id', $id)->count_all_results('borrowing_details') > 0;
        if ($has_loans) {
            $this->session->set_flashdata('error', 'Barang tidak dapat dihapus karena memiliki riwayat peminjaman.');
            redirect('admin/items');
        }

        if ($item['image'] && file_exists('./uploads/' . $item['image'])) {
            unlink('./uploads/' . $item['image']);
        }

        $this->item_model->delete($id);
        $this->activity_log_model->log($this->auth_lib->id(), 'Hapus Barang', 'Menghapus barang: ' . $item['code'] . ' - ' . $item['name']);
        $this->session->set_flashdata('success', 'Barang berhasil dihapus.');
        redirect('admin/items');
    }

    public function view($id) {
        $item = $this->item_model->find_with_relations($id);
        if (!$item) {
            $this->session->set_flashdata('error', 'Barang tidak ditemukan.');
            redirect('admin/items');
        }

        $this->load->model('setting_model');
        $qr_prefix = $this->setting_model->get_val('qr_prefix', 'LAB-INF-');

        $data = [
            'title' => 'Detail Barang',
            'pageTitle' => 'Detail Informasi Barang',
            'pageSubtitle' => 'Spesifikasi teknis, data stok, penempatan lokasi, dan label kode QR barang.',
            'item' => $item,
            'qr_prefix' => $qr_prefix,
            'prefix' => '/admin'
        ];
        $data['content'] = $this->load->view('items/view', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    private function _upload_image() {
        if (empty($_FILES['image']['name'])) {
            return NULL;
        }

        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('image')) {
            return $this->upload->data('file_name');
        }
        return NULL;
    }

    public function download_template() {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=template_import_barang.csv');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
        
        $header = ["Kode Barang", "Nama Barang", "Kategori", "Lokasi", "Satuan", "Tipe", "Kondisi", "Stok", "Stok Minimum", "Tahun Pengadaan", "Sumber Dana", "Harga Perolehan", "Deskripsi"];
        $example = ["LAB-INF-999", "Multimeter Digital Sanwa", "Alat Ukur", "Lab Jaringan & Komputer", "Unit / Pcs", "inventaris", "tersedia", "5", "1", "2024", "Operasional Lab", "750000", "Alat ukur tegangan dan hambatan listrik"];
        
        fputcsv($output, $header);
        fputcsv($output, $example);
        fclose($output);
        exit;
    }

    public function import_csv() {
        if (empty($_FILES['userfile']['tmp_name'])) {
            $this->session->set_flashdata('error', 'Silakan pilih file CSV yang akan diunggah.');
            redirect('admin/items');
        }

        $file_path = $_FILES['userfile']['tmp_name'];
        $handle = fopen($file_path, "r");

        if ($handle === FALSE) {
            $this->session->set_flashdata('error', 'Gagal membuka file CSV.');
            redirect('admin/items');
        }

        // Read header
        $header = fgetcsv($handle, 1000, ",");
        if ($header !== FALSE && isset($header[0])) {
            // Remove BOM if exists
            if (substr($header[0], 0, 3) === pack("CCC", 0xef, 0xbb, 0xbf)) {
                $header[0] = substr($header[0], 3);
            }
        }

        $this->db->trans_start();
        $imported_count = 0;

        try {
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Skip empty or mismatching rows
                if (empty($row) || count($row) < 2 || empty($row[0]) || empty($row[1])) {
                    continue;
                }

                $code = trim($row[0]);
                $name = trim($row[1]);
                $category_name = isset($row[2]) && !empty($row[2]) ? trim($row[2]) : 'Umum';
                $location_name = isset($row[3]) && !empty($row[3]) ? trim($row[3]) : 'Ruang Penyimpanan Alat';
                $unit_name = isset($row[4]) && !empty($row[4]) ? trim($row[4]) : 'Unit / Pcs';
                $item_type = isset($row[5]) ? trim(strtolower($row[5])) : 'inventaris';
                $condition_status = isset($row[6]) ? trim(strtolower($row[6])) : 'tersedia';
                $stock = isset($row[7]) ? (int)$row[7] : 0;
                $minimum_stock = isset($row[8]) ? (int)$row[8] : 0;
                $procurement_year = isset($row[9]) && !empty($row[9]) ? (int)$row[9] : null;
                $funding_source = isset($row[10]) && !empty($row[10]) ? trim($row[10]) : null;
                $acquisition_price = isset($row[11]) ? (float)$row[11] : 0.00;
                $description = isset($row[12]) && !empty($row[12]) ? trim($row[12]) : null;

                // Validate code uniqueness
                $existing = $this->db->where('code', $code)->get('items')->row_array();
                if ($existing) {
                    throw new Exception("Kode barang '" . $code . "' sudah digunakan pada barang '" . $existing['name'] . "'.");
                }

                // Check or Create Category
                $category = $this->db->where('name', $category_name)->get('categories')->row_array();
                if ($category) {
                    $category_id = $category['id'];
                } else {
                    $this->db->insert('categories', ['name' => $category_name]);
                    $category_id = $this->db->insert_id();
                }

                // Check or Create Location
                $location = $this->db->where('name', $location_name)->get('locations')->row_array();
                if ($location) {
                    $location_id = $location['id'];
                } else {
                    $this->db->insert('locations', ['name' => $location_name]);
                    $location_id = $this->db->insert_id();
                }

                // Check or Create Unit
                $unit = $this->db->where('name', $unit_name)->get('units')->row_array();
                if ($unit) {
                    $unit_id = $unit['id'];
                } else {
                    $this->db->insert('units', ['name' => $unit_name]);
                    $unit_id = $this->db->insert_id();
                }

                // Adjust status depending on stock for consumables
                $item_status = 'tersedia';
                if ($item_type === 'habis_pakai' && $stock == 0) {
                    $condition_status = 'habis';
                    $item_status = 'habis';
                }

                $insert_data = [
                    'code' => $code,
                    'name' => $name,
                    'category_id' => $category_id,
                    'location_id' => $location_id,
                    'unit_id' => $unit_id,
                    'item_type' => in_array($item_type, ['inventaris', 'habis_pakai']) ? $item_type : 'inventaris',
                    'condition_status' => in_array($condition_status, ['tersedia', 'rusak', 'maintenance', 'habis']) ? $condition_status : 'tersedia',
                    'stock' => $stock,
                    'minimum_stock' => $minimum_stock,
                    'procurement_year' => $procurement_year,
                    'funding_source' => $funding_source,
                    'acquisition_price' => $acquisition_price,
                    'item_status' => $item_status,
                    'description' => $description
                ];

                $this->db->insert('items', $insert_data);
                $imported_count++;
            }

            fclose($handle);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("Transaksi database gagal.");
            }

            $this->activity_log_model->log($this->auth_lib->id(), 'Impor Barang', 'Berhasil mengimpor ' . $imported_count . ' data barang dari file CSV');
            $this->session->set_flashdata('success', 'Berhasil mengimpor ' . $imported_count . ' data barang.');
            redirect('admin/items');

        } catch (Exception $e) {
            fclose($handle);
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal mengimpor data: ' . $e->getMessage());
            redirect('admin/items');
        }
    }
}
