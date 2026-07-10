<?php
function tgl_indo($date_str) {
    $months = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];
    $eng_date = date('d F Y', strtotime($date_str));
    foreach ($months as $eng => $indo) {
        $eng_date = str_replace($eng, $indo, $eng_date);
    }
    return $eng_date;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Peminjaman Alat - #<?= $borrowing['id'] ?></title>
    <style>
        @page {
            size: A4;
            margin: 2.5cm 2cm 2.5cm 2.5cm;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
            color: #000;
            background-color: #fff;
            font-size: 11pt;
            line-height: 1.5;
        }
        .kop-surat {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000;
            margin-bottom: 20px;
        }
        .kop-logo {
            width: 80px;
            text-align: left;
            vertical-align: middle;
            padding-bottom: 10px;
        }
        .kop-logo img {
            max-height: 80px;
            max-width: 80px;
            object-fit: contain;
        }
        .kop-text {
            text-align: center;
            vertical-align: middle;
            padding-right: 80px; /* Offset logo to center title text */
            padding-bottom: 10px;
        }
        .kop-text h4 {
            font-size: 12pt;
            text-transform: uppercase;
            margin: 0;
            font-weight: normal;
            letter-spacing: 0.5px;
        }
        .kop-text h3 {
            font-size: 13pt;
            text-transform: uppercase;
            margin: 2px 0 0 0;
            font-weight: bold;
        }
        .kop-text p {
            font-size: 8.5pt;
            margin: 5px 0 0 0;
            line-height: 1.3;
        }
        .judul-surat {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .judul-surat h2 {
            font-size: 13pt;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .judul-surat p {
            font-size: 10.5pt;
            margin: 5px 0 0 0;
        }
        .paragraf {
            text-align: justify;
            margin-bottom: 15px;
        }
        .tabel-data {
            width: 90%;
            margin: 15px auto;
            border-collapse: collapse;
        }
        .tabel-data td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .tabel-data td.label {
            width: 30%;
            font-weight: bold;
        }
        .tabel-data td.titik-dua {
            width: 5%;
            text-align: center;
        }
        .tabel-barang {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        .tabel-barang th, .tabel-barang td {
            border: 1px solid #000;
            padding: 6px 10px;
            text-align: left;
        }
        .tabel-barang th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10pt;
        }
        .tabel-barang td.center {
            text-align: center;
        }
        .notes {
            font-size: 9.5pt;
            font-style: italic;
            margin-bottom: 25px;
            line-height: 1.4;
        }
        .tabel-ttd {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .tabel-ttd td {
            vertical-align: top;
            text-align: center;
            width: 33.33%;
        }
        .ttd-title {
            margin-bottom: 60px;
        }
        .qr-section {
            margin-top: 25px;
            font-size: 8.5pt;
            color: #333;
            border-top: 1px dashed #aaa;
            padding-top: 15px;
        }
        .qr-section table {
            border-collapse: collapse;
        }
        .qr-section td {
            padding: 0;
            vertical-align: middle;
        }
        .qr-section img {
            border: 1px solid #000;
            padding: 3px;
            background: #fff;
            margin-right: 15px;
        }
    </style>
</head>
<body>

    <!-- Kop Surat Kampus -->
    <table class="kop-surat">
        <tr>
            <td class="kop-logo">
                <?php if (!empty($settings['institution_logo']) && file_exists('./uploads/' . $settings['institution_logo'])): ?>
                    <img src="<?= base_url('uploads/' . $settings['institution_logo']) ?>" alt="Logo Instansi">
                <?php endif; ?>
            </td>
            <td class="kop-text">
                <h4>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h4>
                <h3><?= esc($settings['institution_name'] ?? 'UNIVERSITAS') ?></h3>
                <h3><?= esc($settings['lab_name'] ?? 'LABORATORIUM') ?></h3>
                <p><?= esc($settings['lab_address'] ?? '') ?></p>
            </td>
        </tr>
    </table>

    <!-- Judul Dokumen -->
    <div class="judul-surat">
        <h2>Surat Jalan & Izin Peminjaman Alat</h2>
        <p>Nomor Dokumen: SJP/<?= date('Y/m') ?>/<?= str_pad($borrowing['id'], 4, '0', STR_PAD_LEFT) ?></p>
    </div>

    <!-- Isi Surat -->
    <p class="paragraf">
        Kepala Laboratorium <?= esc($settings['lab_name']) ?> dengan ini menerangkan bahwa telah diberikan izin peminjaman dan otorisasi membawa barang inventaris laboratorium keluar dari lingkungan kampus kepada pengguna berikut:
    </p>

    <table class="tabel-data">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="titik-dua">:</td>
            <td><strong><?= esc($borrowing['borrower_name']) ?></strong></td>
        </tr>
        <tr>
            <td class="label">Nomor Induk / NIM</td>
            <td class="titik-dua">:</td>
            <td><?= esc($borrowing['borrower_identity']) ?></td>
        </tr>
        <tr>
            <td class="label">Status / Jabatan</td>
            <td class="titik-dua">:</td>
            <td style="text-transform: capitalize;"><?= esc($borrowing['borrower_type']) ?></td>
        </tr>
        <tr>
            <td class="label">Tanggal Peminjaman</td>
            <td class="titik-dua">:</td>
            <td><?= tgl_indo($borrowing['loan_date']) ?> s.d. <strong><?= tgl_indo($borrowing['due_date']) ?></strong> (Wajib dikembalikan)</td>
        </tr>
        <tr>
            <td class="label">Keperluan Acara</td>
            <td class="titik-dua">:</td>
            <td><?= esc($borrowing['purpose'] ?: '-') ?></td>
        </tr>
    </table>

    <p class="paragraf" style="margin-top: 15px; margin-bottom: 5px;">
        Adapun rincian barang inventaris laboratorium yang diizinkan untuk dibawa adalah sebagai berikut:
    </p>

    <!-- Tabel Rincian Barang -->
    <table class="tabel-barang">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 25%;">Kode Barang</th>
                <th>Nama Barang / Model</th>
                <th style="width: 15%; text-align: center;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($borrowing['items'] as $item): ?>
                <tr>
                    <td class="center"><?= $no++ ?></td>
                    <td><?= esc($item['item_code']) ?></td>
                    <td><?= esc($item['item_name']) ?></td>
                    <td class="center"><strong><?= $item['quantity'] ?></strong> <?= esc($item['unit_name']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Keterangan Tanggung Jawab -->
    <div class="notes">
        * Catatan: Peminjam bertanggung jawab secara penuh terhadap keamanan fisik, kebersihan, dan pemeliharaan alat selama masa peminjaman. Segala bentuk kerusakan, cacat fisik baru, atau kehilangan barang wajib diganti sesuai dengan peraturan tata tertib laboratorium.
    </div>

    <!-- Kolom Tanda Tangan -->
    <table class="tabel-ttd">
        <tr>
            <td>
                <div class="ttd-title">Peminjam / Pemohon,</div>
                <div style="height: 65px;"></div>
                <strong><u><?= esc($borrowing['borrower_name']) ?></u></strong><br>
                NIM/NIP. <?= esc($borrowing['borrower_identity']) ?>
            </td>
            <td>
                <div class="ttd-title">Laboran / Petugas Lab,</div>
                <div style="height: 65px;"></div>
                <strong><u><?= esc($borrowing['creator_name']) ?></u></strong><br>
                NIP/NIK. ...........................
            </td>
            <td>
                <div class="ttd-title">Kepala Laboratorium,</div>
                <div style="height: 65px;"></div>
                <strong><u><?= esc($settings['lab_head'] ?? 'NAMA KEPALA LAB') ?></u></strong><br>
                NIP. ........................................
            </td>
        </tr>
    </table>

    <!-- QR Validation Section -->
    <div class="qr-section">
        <table>
            <tr>
                <td>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=75x75&data=<?= urlencode($verification_url) ?>" alt="Verification QR Code">
                </td>
                <td style="font-style: italic; line-height: 1.4;">
                    Dokumen ini sah dan terdaftar resmi di basis data sirkulasi laboratorium.<br>
                    Scan QR Code disamping untuk memverifikasi keaslian surat jalan dan rincian barang peminjaman.
                </td>
            </tr>
        </table>
    </div>

    <!-- Auto Print Dialog -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
