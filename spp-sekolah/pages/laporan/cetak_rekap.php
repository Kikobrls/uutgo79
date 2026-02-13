<?php
/**
 * Cetak Laporan Rekapitulasi
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

if (!isset($_SESSION['login'])) {
    die("Akses ditolak");
}

$tgl_awal = isset($_GET['tgl_awal']) ? sanitize($_GET['tgl_awal']) : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? sanitize($_GET['tgl_akhir']) : date('Y-m-d');

$nama_sekolah = getSetting('nama_sekolah', 'SMK Negeri 1 Contoh');
$alamat_sekolah = getSetting('alamat_sekolah', 'Jl. Pendidikan No. 1');

// Queries
$total_spp = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_bayar), 0) as total 
    FROM pembayaran 
    WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'
"));

$total_umum = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        COALESCE(SUM(debit), 0) as total_debit, 
        COALESCE(SUM(kredit), 0) as total_kredit
    FROM transaksi 
    WHERE tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'
"));

$income = $total_spp['total'] + $total_umum['total_debit'];
$expense = $total_umum['total_kredit'];
$balance = $income - $expense;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
        }

        .header p {
            margin: 5px 0;
        }

        .box {
            width: 60%;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 20px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 5px;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <h2>
            <?php echo htmlspecialchars($nama_sekolah); ?>
        </h2>
        <p>
            <?php echo htmlspecialchars($alamat_sekolah); ?>
        </p>
        <h3>REKAPITULASI KEUANGAN</h3>
        <p>Periode:
            <?php echo date('d/m/Y', strtotime($tgl_awal)); ?> -
            <?php echo date('d/m/Y', strtotime($tgl_akhir)); ?>
        </p>
    </div>

    <div class="box">
        <h4 style="text-align: center; margin-top: 0;">RINGKASAN KEUANGAN</h4>

        <div class="row">
            <span>Pemasukkan SPP</span>
            <span class="fw-bold">Rp
                <?php echo number_format($total_spp['total'], 0, ',', '.'); ?>
            </span>
        </div>
        <div class="row">
            <span>Pemasukkan Lain (Umum)</span>
            <span class="fw-bold">Rp
                <?php echo number_format($total_umum['total_debit'], 0, ',', '.'); ?>
            </span>
        </div>
        <div class="row" style="border-bottom: 2px solid #000;">
            <span class="fw-bold">TOTAL PEMASUKKAN</span>
            <span class="fw-bold">Rp
                <?php echo number_format($income, 0, ',', '.'); ?>
            </span>
        </div>

        <br>

        <div class="row" style="color: red;">
            <span class="fw-bold">TOTAL PENGELUARAN</span>
            <span class="fw-bold">Rp
                <?php echo number_format($expense, 0, ',', '.'); ?>
            </span>
        </div>

        <br><br>

        <div class="row" style="border: 2px solid #000; padding: 10px; font-size: 16px; background-color: #f9f9f9;">
            <span class="fw-bold">SISA SALDO BERSIH</span>
            <span class="fw-bold">Rp
                <?php echo number_format($balance, 0, ',', '.'); ?>
            </span>
        </div>
    </div>

    <div style="margin-top: 30px; text-align: center;">
        <p>Dicetak pada:
            <?php echo date('d/m/Y H:i'); ?>
        </p>
        <br><br><br>
        <p>( Kepala Sekolah / Bendahara )</p>
    </div>
</body>

</html>