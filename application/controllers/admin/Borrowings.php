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

class Borrowings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('borrowing_model');
        $this->load->model('item_model');
        $this->load->model('activity_log_model');
    }

    public function index() {
        $filters = [
            'search' => $this->input->get('search', TRUE),
            'status' => $this->input->get('status')
        ];

        $data = [
            'title' => 'Daftar Peminjaman',
            'pageTitle' => 'Transaksi Peminjaman Alat & Bahan',
            'pageSubtitle' => 'Pantau alur sirkulasi pengajuan peminjaman barang dan status persetujuannya.',
            'borrowings' => $this->borrowing_model->all_with_relations($filters),
            'filters' => $filters,
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('borrowings/index', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function create() {
        $items = $this->db->select('items.*, units.name as unit_name, locations.name as location_name')
            ->from('items')
            ->join('units', 'items.unit_id = units.id')
            ->join('locations', 'items.location_id = locations.id')
            ->where('items.stock >', 0)
            ->where_in('items.condition_status', ['tersedia'])
            ->get()->result_array();

        $data = [
            'title' => 'Input Peminjaman',
            'pageTitle' => 'Formulir Peminjaman Baru',
            'pageSubtitle' => 'Catat transaksi peminjaman alat inventaris tetap atau pemakaian bahan habis pakai.',
            'items' => $items,
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('borrowings/create', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function store() {
        $this->form_validation->set_rules('borrower_name', 'Nama Peminjam', 'required|min_length[3]');
        $this->form_validation->set_rules('borrower_identity', 'Identitas Peminjam', 'required');
        $this->form_validation->set_rules('borrower_phone', 'Nomor WhatsApp', 'trim');
        $this->form_validation->set_rules('borrower_type', 'Tipe Peminjam', 'required');
        $this->form_validation->set_rules('loan_date', 'Tanggal Pinjam', 'required');
        $this->form_validation->set_rules('due_date', 'Tanggal Batas Kembali', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('form_errors', $this->form_validation->error_array());
            $this->session->set_flashdata('old_input', $_POST);
            redirect('admin/borrowings/create');
        } else {
            $borrow_items = $this->input->post('items');
            if (empty($borrow_items)) {
                $this->session->set_flashdata('error', 'Silakan pilih minimal satu barang yang akan dipinjam.');
                $this->session->set_flashdata('old_input', $_POST);
                redirect('admin/borrowings/create');
            }

            $parsed_items = [];
            foreach ($borrow_items as $item_row) {
                if (isset($item_row['item_id']) && isset($item_row['quantity'])) {
                    $item_id = (int)$item_row['item_id'];
                    $qty = (int)$item_row['quantity'];
                    if ($item_id > 0 && $qty > 0) {
                        $parsed_items[] = [
                            'item_id' => $item_id,
                            'quantity' => $qty
                        ];
                    }
                }
            }

            if (empty($parsed_items)) {
                $this->session->set_flashdata('error', 'Kuantitas barang yang dipinjam harus lebih dari 0.');
                $this->session->set_flashdata('old_input', $_POST);
                redirect('admin/borrowings/create');
            }

            $loan_date = $this->input->post('loan_date') ?: date('Y-m-d');
            $status = ($loan_date === date('Y-m-d')) ? 'dipinjam' : 'disetujui';

            $borrowing_data = [
                'borrower_name' => $this->input->post('borrower_name', TRUE),
                'borrower_identity' => $this->input->post('borrower_identity', TRUE),
                'borrower_phone' => $this->input->post('borrower_phone', TRUE) ?: NULL,
                'borrower_type' => $this->input->post('borrower_type', TRUE),
                'loan_date' => $loan_date,
                'due_date' => $this->input->post('due_date'),
                'status' => $status,
                'notes' => $this->input->post('notes', TRUE) ?: NULL,
                'created_by' => $this->auth_lib->id(),
                'approved_by' => $this->auth_lib->id()
            ];

            try {
                $borrowing_id = $this->borrowing_model->create_borrowing($borrowing_data, $parsed_items);
                $this->activity_log_model->log($this->auth_lib->id(), 'Input Peminjaman', 'Mencatat peminjaman langsung untuk: ' . $borrowing_data['borrower_name']);

                // WhatsApp Notification
                if (!empty($borrowing_data['borrower_phone'])) {
                    $this->load->helper('whatsapp');
                    $status_label = ($status === 'dipinjam') ? 'Sedang Dipinjam' : 'Disetujui (Reservasi)';
                    $msg = "Halo *" . $borrowing_data['borrower_name'] . "*,\n\nTransaksi peminjaman Anda dengan ID *#" . $borrowing_id . "* telah dicatat oleh Admin.\n\nDetail:\n- Status: " . $status_label . "\n- Tanggal Pinjam: " . date('d M Y', strtotime($loan_date)) . "\n- Batas Pengembalian: " . date('d M Y', strtotime($borrowing_data['due_date'])) . "\n\nTerima kasih!";
                    send_whatsapp($borrowing_data['borrower_phone'], $msg);
                }

                $this->session->set_flashdata('success', 'Transaksi peminjaman berhasil dicatat.');
                redirect('admin/borrowings');
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                $this->session->set_flashdata('old_input', $_POST);
                redirect('admin/borrowings/create');
            }
        }
    }

    public function view($id) {
        $borrowing = $this->borrowing_model->find_with_relations($id);
        if (!$borrowing) {
            $this->session->set_flashdata('error', 'Transaksi peminjaman tidak ditemukan.');
            redirect('admin/borrowings');
        }

        $data = [
            'title' => 'Detail Peminjaman',
            'pageTitle' => 'Lembar Transaksi Peminjaman',
            'pageSubtitle' => 'Detail berkas peminjaman, data peminjam, item barang, dan status pengembalian.',
            'borrowing' => $borrowing,
            'prefix' => '/admin'
        ];

        $data['content'] = $this->load->view('borrowings/view', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function approve($id) {
        try {
            $this->borrowing_model->approve($id, $this->auth_lib->id());
            $this->activity_log_model->log($this->auth_lib->id(), 'Persetujuan Peminjaman', 'Menyetujui permohonan peminjaman ID: ' . $id);

            // WhatsApp Notification
            $borrowing = $this->borrowing_model->find_with_relations($id);
            if ($borrowing && !empty($borrowing['borrower_phone'])) {
                $this->load->helper('whatsapp');
                $msg = "Halo *" . $borrowing['borrower_name'] . "*,\n\nPengajuan peminjaman barang Anda dengan ID *#" . $borrowing['id'] . "* telah *DISETUJUI* oleh petugas lab.\n\nSilakan ambil barang di laboratorium.\n- Batas Pengembalian: " . date('d M Y', strtotime($borrowing['due_date'])) . "\n\nTerima kasih!";
                send_whatsapp($borrowing['borrower_phone'], $msg);
            }

            $this->session->set_flashdata('success', 'Permohonan peminjaman berhasil disetujui.');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
        redirect('admin/borrowings/view/' . $id);
    }

    public function reject($id) {
        $this->borrowing_model->reject($id, $this->auth_lib->id());
        $this->activity_log_model->log($this->auth_lib->id(), 'Penolakan Peminjaman', 'Menolak permohonan peminjaman ID: ' . $id);

        // WhatsApp Notification
        $borrowing = $this->borrowing_model->find_with_relations($id);
        if ($borrowing && !empty($borrowing['borrower_phone'])) {
            $this->load->helper('whatsapp');
            $msg = "Halo *" . $borrowing['borrower_name'] . "*,\n\nMohon maaf, pengajuan peminjaman barang Anda dengan ID *#" . $borrowing['id'] . "* telah *DITOLAK* oleh petugas lab.\n\nSilakan hubungi laboran untuk informasi lebih lanjut.";
            send_whatsapp($borrowing['borrower_phone'], $msg);
        }

        $this->session->set_flashdata('success', 'Permohonan peminjaman berhasil ditolak.');
        redirect('admin/borrowings/view/' . $id);
    }

    public function calendar() {
        $data = [
            'title' => 'Kalender Sirkulasi',
            'pageTitle' => 'Kalender Peminjaman & Reservasi',
            'pageSubtitle' => 'Visualisasi linimasa jadwal peminjaman aktif dan rencana reservasi alat lab.',
            'prefix' => '/admin'
        ];
        $data['content'] = $this->load->view('borrowings/calendar', $data, TRUE);
        $this->load->view('layouts/app', $data);
    }

    public function calendar_events() {
        $this->db->select('b.id, b.borrower_name, b.loan_date, b.due_date, b.status');
        $this->db->from('borrowings b');
        $this->db->where_in('b.status', ['menunggu', 'disetujui', 'dipinjam', 'dikembalikan', 'terlambat']);
        $borrowings = $this->db->get()->result_array();

        $events = [];
        foreach ($borrowings as $b) {
            // Get items summary
            $items = $this->borrowing_model->get_items_by_borrowing_id($b['id']);
            $itemNames = array_map(function($i) { return $i['item_name'] . ' (x' . $i['quantity'] . ')'; }, $items);
            $summary = implode(', ', $itemNames);

            // Determine color
            $color = '#64748b'; // slate default
            if ($b['status'] === 'menunggu') $color = '#d97706'; // amber
            elseif ($b['status'] === 'disetujui') $color = '#2563eb'; // blue
            elseif ($b['status'] === 'dipinjam') $color = '#0d9488'; // teal
            elseif ($b['status'] === 'terlambat') $color = '#dc2626'; // red

            $events[] = [
                'id' => $b['id'],
                'title' => esc($b['borrower_name'] . ': ' . $summary),
                'start' => $b['loan_date'],
                'end' => date('Y-m-d', strtotime($b['due_date'] . ' +1 day')),
                'url' => url('/admin/borrowings/view/' . $b['id']),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'allDay' => true
            ];
        }

        return $this->output->set_content_type('application/json')
            ->set_output(json_encode($events));
    }

    public function print_letter($id) {
        $id = (int)$id;
        $borrowing = $this->borrowing_model->find_with_relations($id);
        if (!$borrowing) {
            $this->session->set_flashdata('error', 'Transaksi peminjaman tidak ditemukan.');
            redirect('admin/borrowings');
        }

        $this->load->model('setting_model');
        $settings = $this->setting_model->all_settings();

        $data = [
            'borrowing' => $borrowing,
            'settings' => $settings,
            'verification_url' => base_url('verify/borrowing/' . $id)
        ];

        $this->load->view('borrowings/print_letter', $data);
    }
}
