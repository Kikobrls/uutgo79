<?php
/**
 * Export Laporan Transaksi ke Excel
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
$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');
$kategori_id = isset($_GET['kategori_id']) ? sanitize($_GET['kategori_id']) : '';

// Build Query
$where = "WHERE t.tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$category_name = "Semua Kategori";

if (!empty($kategori_id)) {
    $where .= " AND t.id_kategori = '$kategori_id'";

    // Get Category Name
    $cat_query = mysqli_query($conn, "SELECT nama_kategori FROM kategori_keuangan WHERE id_kategori = '$kategori_id'");
    if ($row = mysqli_fetch_assoc($cat_query)) {
        $category_name = $row['nama_kategori'];
    }
}

$query = "SELECT t.*, k.nama_kategori, g.nama_guru
          FROM transaksi t
          LEFT JOIN kategori_keuangan k ON t.id_kategori = k.id_kategori
          LEFT JOIN guru g ON t.id_guru = g.id_guru
          $where
          ORDER BY t.tanggal DESC, t.created_at DESC";
$result = mysqli_query($conn, $query);

$nama_sekolah = getSetting('nama_sekolah', 'SMK Negeri 1 Contoh');
$filename = "Laporan_Keuangan_" . str_replace(' ', '_', $category_name) . "_" . date('Ymd', strtotime($tgl_awal)) . "-" . date('Ymd', strtotime($tgl_akhir));

// Export Headers
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
header('Cache-Control: max-age=0');
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

        .success {
            color: #1cc88a;
        }

        .danger {
            color: #e74a3b;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>
            <?php echo htmlspecialchars($nama_sekolah); ?>
        </h2>
        <h3>Laporan Keuangan Umum (
            <?php echo htmlspecialchars($category_name); ?>)
        </h3>
        <p>Periode:
            <?php echo date('d F Y', strtotime($tgl_awal)) . ' - ' . date('d F Y', strtotime($tgl_akhir)); ?>
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Uraian</th>
                <th>Kategori</th>
                <th>Pemasukan</th>
                <th>Pengeluaran</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_debit = 0;
            $total_kredit = 0;
            while ($row = mysqli_fetch_assoc($result)):
                $total_debit += $row['debit'];
                $total_kredit += $row['kredit'];
                ?>
                <tr>
                    <td>
                        <?php echo $no++; ?>
                    </td>
                    <td>
                        <?php echo date('d/m/Y', strtotime($row['tanggal'])); ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($row['uraian']); ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($row['nama_kategori'] ?? '-'); ?>
                    </td>
                    <td class="text-right success">
                        <?php echo $row['debit'] > 0 ? number_format($row['debit'], 0, ',', '.') : '-'; ?>
                    </td>
                    <td class="text-right danger">
                        <?php echo $row['kredit'] > 0 ? number_format($row['kredit'], 0, ',', '.') : '-'; ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($row['nama_guru']); ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right font-bold">TOTAL</td>
                <td class="text-right font-bold success">
                    <?php echo number_format($total_debit, 0, ',', '.'); ?>
                </td>
                <td class="text-right font-bold danger">
                    <?php echo number_format($total_kredit, 0, ',', '.'); ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right font-bold">SURPLUS / DEFISIT</td>
                <td colspan="3" class="text-right font-bold">
                    <?php echo number_format($total_debit - $total_kredit, 0, ',', '.'); ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <p style="margin-top: 30px;">Dicetak pada:
        <?php echo date('d/m/Y H:i:s'); ?>
    </p>
</body>

</html>
<?php
logActivity('Export laporan keuangan umum', 'transaksi', $category_name);
?>
