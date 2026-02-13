<?php
/**
 * Export Laporan ke Excel
 * sistem keuangan Sekolah
 */

session_start();
require_once '../../config/database.php';
require_once '../../config/app.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

// Filters
$filter_bulan = isset($_GET['bulan']) ? sanitize($_GET['bulan']) : date('m');
$filter_tahun = isset($_GET['tahun']) ? sanitize($_GET['tahun']) : date('Y');
$filter_kelas = isset($_GET['kelas']) ? sanitize($_GET['kelas']) : '';
$type = isset($_GET['type']) ? $_GET['type'] : 'excel';

$where = "WHERE MONTH(p.tgl_bayar) = '$filter_bulan' AND YEAR(p.tgl_bayar) = '$filter_tahun'";
if (!empty($filter_kelas)) {
    $where .= " AND s.id_kelas = '$filter_kelas'";
}

// Get report data
$query = "SELECT p.tgl_bayar, s.nama as nama_santri,
                 k.nama_kelas, p.bulan_dibayar, p.tahun_dibayar, p.jumlah_bayar,
                 p.metode_bayar, g.nama_guru
          FROM pembayaran p
          JOIN santri s ON p.id_santri = s.id
          JOIN kelas k ON s.id_kelas = k.id_kelas
          JOIN guru g ON p.id_guru = g.id_guru
          $where
          ORDER BY p.tgl_bayar DESC, s.nama";
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

$filename = "Laporan_Pembayaran_" . $bulan_list[$filter_bulan] . "_" . $filter_tahun;

if ($type == 'csv') {
    // Export CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '.csv"');

    $output = fopen('php://output', 'w');

    // UTF-8 BOM for Excel
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Headers
    fputcsv($output, ['No', 'Tanggal', 'Nama Santri', 'Kelas', 'Bulan Bayar', 'Tahun Bayar', 'Jumlah', 'Metode', 'Guru']);

    // Data
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $no++,
            date('d/m/Y', strtotime($row['tgl_bayar'])),
            $row['nama_santri'],
            $row['nama_kelas'],
            $row['bulan_dibayar'],
            $row['tahun_dibayar'],
            $row['jumlah_bayar'],
            ucfirst($row['metode_bayar']),
            $row['nama_guru']
        ]);
    }

    fclose($output);
} else {
    // Export Excel (HTML Table format)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
    header('Cache-Control: max-age=0');

    $nama_sekolah = getSetting('nama_sekolah', 'SMK Negeri 1 Contoh');
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #4e73df;
                color: white;
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
            }

            .total {
                background-color: #f8f9fc;
                font-weight: bold;
            }
        </style>
    </head>

    <body>
        <div class="header">
            <h2><?php echo htmlspecialchars($nama_sekolah); ?></h2>
            <h3>Laporan Pembayaran SPP</h3>
            <p>Periode: <?php echo $bulan_list[$filter_bulan] . ' ' . $filter_tahun; ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Santri</th>
                    <th>Kelas</th>
                    <th>Bulan Bayar</th>
                    <th>Tahun Bayar</th>
                    <th>Jumlah</th>
                    <th>Metode</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $total = 0;
                while ($row = mysqli_fetch_assoc($result)):
                    $total += $row['jumlah_bayar'];
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['tgl_bayar'])); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_santri']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_kelas']); ?></td>
                        <td><?php echo htmlspecialchars($row['bulan_dibayar']); ?></td>
                        <td><?php echo htmlspecialchars($row['tahun_dibayar']); ?></td>
                        <td style="text-align: right;"><?php echo number_format($row['jumlah_bayar'], 0, ',', '.'); ?></td>
                        <td><?php echo ucfirst($row['metode_bayar']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_guru']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr class="total">
                    <td colspan="6" style="text-align: right;">TOTAL</td>
                    <td style="text-align: right;"><?php echo number_format($total, 0, ',', '.'); ?></td>
                    <td colspan="2"><?php echo $no - 1; ?> Transaksi</td>
                </tr>
            </tfoot>
        </table>

        <p style="margin-top: 30px;">Dicetak pada: <?php echo date('d/m/Y H:i:s'); ?></p>
    </body>

    </html>
    <?php
}

logActivity('Export laporan pembayaran', 'laporan', $bulan_list[$filter_bulan] . ' ' . $filter_tahun);
?>
