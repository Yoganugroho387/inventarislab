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
    <title>Surat Bebas Lab - <?= esc($identity) ?></title>
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
            font-size: 12pt;
            line-height: 1.6;
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
            font-size: 13pt;
            text-transform: uppercase;
            margin: 0;
            font-weight: normal;
            letter-spacing: 0.5px;
        }
        .kop-text h3 {
            font-size: 14pt;
            text-transform: uppercase;
            margin: 2px 0 0 0;
            font-weight: bold;
        }
        .kop-text p {
            font-size: 9pt;
            margin: 5px 0 0 0;
            line-height: 1.3;
        }
        .judul-surat {
            text-align: center;
            margin-top: 25px;
            margin-bottom: 25px;
        }
        .judul-surat h2 {
            font-size: 14pt;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .judul-surat p {
            font-size: 11pt;
            margin: 5px 0 0 0;
        }
        .paragraf {
            text-indent: 40px;
            text-align: justify;
            margin-bottom: 15px;
        }
        .tabel-data {
            width: 85%;
            margin: 15px auto;
            border-collapse: collapse;
        }
        .tabel-data td {
            padding: 5px 10px;
            vertical-align: top;
        }
        .tabel-data td.label {
            width: 35%;
        }
        .tabel-data td.titik-dua {
            width: 5%;
            text-align: center;
        }
        .pernyataan-bebas {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            margin: 25px 0;
            text-transform: uppercase;
            border: 1px solid #000;
            padding: 8px;
            background-color: #fcfcfc;
        }
        .penutup {
            text-indent: 40px;
            text-align: justify;
            margin-bottom: 30px;
        }
        .tabel-ttd {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }
        .tabel-ttd td {
            vertical-align: top;
        }
        .qr-box {
            width: 40%;
            text-align: left;
            padding-left: 10px;
        }
        .qr-box img {
            border: 1px solid #000;
            padding: 3px;
        }
        .qr-box span {
            display: block;
            font-size: 8pt;
            color: #444;
            margin-top: 5px;
            font-style: italic;
            line-height: 1.3;
        }
        .ttd-box {
            width: 60%;
            text-align: right;
            padding-right: 10px;
        }
        .ttd-space {
            height: 80px;
        }
        @media print {
            body {
                background-color: #white;
            }
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
        <h2>Surat Keterangan Bebas Laboratorium</h2>
        <p>Nomor: SKB/<?= date('Y/m') ?>/<?= str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT) ?></p>
    </div>

    <!-- Isi Surat -->
    <p class="paragraf">
        Kepala Laboratorium <?= esc($settings['lab_name']) ?> pada <?= esc($settings['institution_name']) ?> dengan ini menerangkan bahwa mahasiswa/pengguna laboratorium di bawah ini:
    </p>

    <table class="tabel-data">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="titik-dua">:</td>
            <td><strong><?= esc($student['borrower_name']) ?></strong></td>
        </tr>
        <tr>
            <td class="label">Nomor Induk Mahasiswa (NIM)</td>
            <td class="titik-dua">:</td>
            <td><?= esc($identity) ?></td>
        </tr>
        <tr>
            <td class="label">Peran / Status</td>
            <td class="titik-dua">:</td>
            <td style="text-transform: capitalize;"><?= esc($student['borrower_type']) ?></td>
        </tr>
    </table>

    <p class="paragraf">
        Berdasarkan pengecekan data sirkulasi inventaris, yang bersangkutan dinyatakan telah mengembalikan seluruh alat/barang laboratorium yang dipinjam, menyelesaikan administrasi pemakaian bahan praktikum habis pakai, dan dinyatakan:
    </p>

    <div class="pernyataan-bebas">
        BEBAS DARI TANGGUNGAN LABORATORIUM
    </div>

    <p class="penutup">
        Demikian surat keterangan bebas laboratorium ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagai salah satu kelengkapan persyaratan akademik, yudisium, maupun keperluan lainnya.
    </p>

    <!-- Tanda Tangan & QR Verification -->
    <table class="tabel-ttd">
        <tr>
            <td class="qr-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=85x85&data=<?= urlencode($verification_url) ?>" alt="Verification QR Code">
                <span>Dokumen Sah Digital.<br>Scan QR Code untuk verifikasi status bebas lab.</span>
            </td>
            <td class="ttd-box">
                Jakarta, <?= tgl_indo(date('Y-m-d')) ?><br>
                Kepala Laboratorium,
                <div class="ttd-space"></div>
                <strong><u><?= esc($settings['lab_head'] ?? 'NAMA KEPALA LAB') ?></u></strong><br>
                NIP. ........................................
            </td>
        </tr>
    </table>

    <!-- Auto Print Dialog -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
