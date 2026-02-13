<?php
/**
 * Cetak Laporan Keuangan Umum
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
$kategori_id = isset($_GET['kategori_id']) ? sanitize($_GET['kategori_id']) : '';

$where = "WHERE t.tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'";
if (!empty($kategori_id)) {
    $where .= " AND t.id_kategori = '$kategori_id'";
}

$query = "SELECT t.*, k.nama_kategori, g.nama_guru 
          FROM transaksi t 
          LEFT JOIN kategori_keuangan k ON t.id_kategori = k.id_kategori 
          LEFT JOIN guru g ON t.id_guru = g.id_guru 
          $where 
          ORDER BY t.tanggal DESC, t.created_at DESC";
$result = mysqli_query($conn, $query);

$nama_sekolah = getSetting('nama_sekolah', 'SMK Negeri 1 Contoh');
$alamat_sekolah = getSetting('alamat_sekolah', 'Jl. Pendidikan No. 1');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Umum</title>
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
        <h3>LAPORAN KEUANGAN UMUM</h3>
        <p>Periode:
            <?php echo date('d/m/Y', strtotime($tgl_awal)); ?> -
            <?php echo date('d/m/Y', strtotime($tgl_akhir)); ?>
        </p>
        <?php if (!empty($kategori_id)) {
            $k = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_kategori FROM kategori_keuangan WHERE id_kategori='$kategori_id'"));
            echo "<p>Kategori: " . htmlspecialchars($k['nama_kategori']) . "</p>";
        } ?>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Kategori</th>
                <th>Masuk (Debit)</th>
                <th>Keluar (Kredit)</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_d = 0;
            $total_k = 0;
            if (mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)):
                    $total_d += $row['debit'];
                    $total_k += $row['kredit'];
                    ?>
                    <tr>
                        <td class="text-center">
                            <?php echo $no++; ?>
                        </td>
                        <td class="text-center">
                            <?php echo date('d/m/Y', strtotime($row['tanggal'])); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['uraian']); ?>
                        </td>
                        <td class="text-center">
                            <?php echo htmlspecialchars($row['nama_kategori'] ?? '-'); ?>
                        </td>
                        <td class="text-right">
                            <?php echo $row['debit'] > 0 ? formatRupiah($row['debit']) : '-'; ?>
                        </td>
                        <td class="text-right">
                            <?php echo $row['kredit'] > 0 ? formatRupiah($row['kredit']) : '-'; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['nama_guru']); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td colspan="4" class="text-right"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>Rp
                            <?php echo number_format($total_d, 0, ',', '.'); ?>
                        </strong></td>
                    <td class="text-right"><strong>Rp
                            <?php echo number_format($total_k, 0, ',', '.'); ?>
                        </strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>SALDO BERSIH</strong></td>
                    <td colspan="2" class="text-center"><strong>Rp
                            <?php echo number_format($total_d - $total_k, 0, ',', '.'); ?>
                        </strong></td>
                    <td></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data.</td>
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