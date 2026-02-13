<?php
/**
 * Cetak Laporan SPP
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

if (!isset($_SESSION['login'])) {
    die("Akses ditolak");
}

$filter_bulan = isset($_GET['bulan']) ? sanitize($_GET['bulan']) : date('m');
$filter_tahun = isset($_GET['tahun']) ? sanitize($_GET['tahun']) : date('Y');
$filter_kelas = isset($_GET['kelas']) ? sanitize($_GET['kelas']) : '';

$where = "WHERE MONTH(p.tgl_bayar) = '$filter_bulan' AND YEAR(p.tgl_bayar) = '$filter_tahun'";
if (!empty($filter_kelas)) {
    $where .= " AND s.id_kelas = '$filter_kelas'";
}

$query = "SELECT p.*, s.nama as nama_santri, k.nama_kelas, g.nama_guru
          FROM pembayaran p
          JOIN santri s ON p.id_santri = s.id
          JOIN kelas k ON s.id_kelas = k.id_kelas
          JOIN guru g ON p.id_guru = g.id_guru
          $where
          ORDER BY p.tgl_bayar ASC, s.nama ASC";
$result = mysqli_query($conn, $query);

$bulan_list = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];

$nama_sekolah = getSetting('nama_sekolah', 'SMK Negeri 1 Contoh');
$alamat_sekolah = getSetting('alamat_sekolah', 'Jl. Pendidikan No. 1');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran SPP</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background-color: #f0f0f0;
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
        <h3>LAPORAN PEMBAYARAN SPP</h3>
        <p>Periode:
            <?php echo $bulan_list[$filter_bulan] . ' ' . $filter_tahun; ?>
        </p>
        <?php if (!empty($filter_kelas)) {
            $k = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE id_kelas='$filter_kelas'"));
            echo "<p>Kelas: " . htmlspecialchars($k['nama_kelas']) . "</p>";
        } ?>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Bulan Bayar</th>
                <th width="15%">Jumlah</th>
                <th>Via</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total = 0;
            if (mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)):
                    $total += $row['jumlah_bayar'];
                    ?>
                    <tr>
                        <td class="text-center">
                            <?php echo $no++; ?>
                        </td>
                        <td class="text-center">
                            <?php echo date('d/m/Y', strtotime($row['tgl_bayar'])); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['nama_santri']); ?>
                        </td>
                        <td class="text-center">
                            <?php echo htmlspecialchars($row['nama_kelas']); ?>
                        </td>
                        <td class="text-center">
                            <?php echo htmlspecialchars($row['bulan_dibayar']); ?>
                        </td>
                        <td class="text-right">Rp
                            <?php echo number_format($row['jumlah_bayar'], 0, ',', '.'); ?>
                        </td>
                        <td class="text-center">
                            <?php echo ucfirst($row['metode_bayar']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['nama_guru']); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td colspan="5" class="text-right"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>Rp
                            <?php echo number_format($total, 0, ',', '.'); ?>
                        </strong></td>
                    <td colspan="2"></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Dicetak pada:
            <?php echo date('d/m/Y H:i'); ?>
        </p>
        <br><br><br>
        <p>(_________________________)</p>
        <p>Petugas</p>
    </div>
</body>

</html>
