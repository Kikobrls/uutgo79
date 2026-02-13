<?php
/**
 * Cetak Bukti Pembayaran
 * sistem keuangan Sekolah
 */

session_start();
require_once '../../config/database.php';
require_once '../../config/app.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

$id = isset($_GET['id']) ? sanitize($_GET['id']) : '';

if (empty($id)) {
    die("ID pembayaran tidak valid!");
}

// Get payment details
$query = "SELECT p.*, s.nama, s.alamat,
                 k.nama_kelas,
                 sp.tahun as tahun_spp, sp.nominal,
                 g.nama_guru
          FROM pembayaran p
          JOIN santri s ON p.id_santri = s.id
          JOIN kelas k ON s.id_kelas = k.id_kelas
          JOIN spp sp ON p.id_spp = sp.id_spp
          JOIN guru g ON p.id_guru = g.id_guru
          WHERE p.id_pembayaran = '$id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("Data pembayaran tidak ditemukan!");
}

$data = mysqli_fetch_assoc($result);

// Get school info
$nama_sekolah = getSetting('nama_sekolah', 'SMK Negeri 1 Contoh');
$alamat_sekolah = getSetting('alamat_sekolah', 'Jl. Pendidikan No. 1');
$telp_sekolah = getSetting('telp_sekolah', '021-12345678');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Bukti Pembayaran</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid #4e73df;
            border-radius: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #ccc;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 16px;
            color: #4e73df;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11px;
            color: #666;
        }

        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            background: #4e73df;
            color: white;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            width: 120px;
            color: #666;
        }

        .info-value {
            flex: 1;
            font-weight: 500;
        }

        .divider {
            border-top: 1px dashed #ccc;
            margin: 15px 0;
        }

        .total {
            background: #f8f9fc;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin: 15px 0;
        }

        .total-label {
            font-size: 12px;
            color: #666;
        }

        .total-value {
            font-size: 24px;
            font-weight: bold;
            color: #1cc88a;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 2px dashed #ccc;
            padding-top: 15px;
            margin-top: 15px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            border-radius: 3px;
            background: #4e73df;
            color: white;
        }

        .badge-success {
            background: #1cc88a;
        }

        .badge-info {
            background: #36b9cc;
        }

        .print-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #4e73df;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 15px;
        }

        .print-btn:hover {
            background: #2e59d9;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                margin: 0;
            }

            .container {
                border: 1px solid #000;
                margin: 0;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><?php echo htmlspecialchars($nama_sekolah); ?></h1>
            <p><?php echo htmlspecialchars($alamat_sekolah); ?></p>
            <p>Telp: <?php echo htmlspecialchars($telp_sekolah); ?></p>
        </div>

        <div class="title">BUKTI PEMBAYARAN SPP</div>


        <div class="info-row">
            <span class="info-label">Tanggal</span>
            <span class="info-value"><?php echo tanggalIndo($data['tgl_bayar']); ?></span>
        </div>

        <div class="divider"></div>

        <div class="info-row">
            <span class="info-label">Nama Santri</span>
            <span class="info-value"><?php echo htmlspecialchars($data['nama']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Kelas</span>
            <span class="info-value"><?php echo htmlspecialchars($data['nama_kelas']); ?></span>
        </div>

        <div class="divider"></div>

        <div class="info-row">
            <span class="info-label">Bulan Bayar</span>
            <span
                class="info-value"><?php echo htmlspecialchars($data['bulan_dibayar']) . ' ' . $data['tahun_dibayar']; ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Metode</span>
            <span class="info-value">
                <span class="badge badge-<?php echo $data['metode_bayar'] == 'tunai' ? 'success' : 'info'; ?>">
                    <?php echo ucfirst($data['metode_bayar']); ?>
                </span>
            </span>
        </div>

        <div class="total">
            <div class="total-label">JUMLAH DIBAYAR</div>
            <div class="total-value"><?php echo formatRupiah($data['jumlah_bayar']); ?></div>
        </div>

        <?php if ($data['keterangan']): ?>
            <div class="info-row">
                <span class="info-label">Keterangan</span>
                <span class="info-value"><?php echo htmlspecialchars($data['keterangan']); ?></span>
            </div>
        <?php endif; ?>

        <div class="footer">
            <p>Guru: <?php echo htmlspecialchars($data['nama_guru']); ?></p>
            <p>Dicetak: <?php echo date('d/m/Y H:i:s'); ?></p>
            <p style="margin-top: 10px;">Terima kasih atas pembayaran Anda.<br>Simpan bukti ini sebagai arsip.</p>
        </div>

        <button class="print-btn" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Bukti Pembayaran
        </button>
    </div>
</body>

</html>
