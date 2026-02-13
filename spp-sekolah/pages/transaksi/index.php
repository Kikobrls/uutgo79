<?php
/**
 * Data Transaksi
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$page_title = 'Data Transaksi';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Filter variables
$tgl_awal = $_GET['tgl_awal'] ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$kategori_id = $_GET['kategori_id'] ?? '';

// Build query
$where_clauses = ["t.tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'"];
if (!empty($kategori_id)) {
    $where_clauses[] = "t.id_kategori = '$kategori_id'";
}
$where_sql = implode(' AND ', $where_clauses);

$query = "SELECT t.*, k.nama_kategori, g.nama_guru
          FROM transaksi t
          LEFT JOIN kategori_keuangan k ON t.id_kategori = k.id_kategori
          LEFT JOIN guru g ON t.id_guru = g.id_guru
          WHERE $where_sql
          ORDER BY t.tanggal DESC, t.created_at DESC";
$result = mysqli_query($conn, $query);

// Get categories for filter
$query_kategori = "SELECT * FROM kategori_keuangan ORDER BY nama_kategori ASC";
$result_kategori = mysqli_query($conn, $query_kategori);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Keuangan /</span> Data Transaksi
        </h4>
        <a href="add.php" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Tambah Transaksi
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold">Filter Data</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="tgl_awal" class="form-label">Tanggal Awal</label>
                    <input type="date" class="form-control" name="tgl_awal" value="<?php echo $tgl_awal; ?>">
                </div>
                <div class="col-md-3">
                    <label for="tgl_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="tgl_akhir" value="<?php echo $tgl_akhir; ?>">
                </div>
                <div class="col-md-3">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        <?php while ($kat = mysqli_fetch_assoc($result_kategori)): ?>
                            <option value="<?php echo $kat['id_kategori']; ?>" <?php echo $kategori_id == $kat['id_kategori'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary me-2"><i class="bx bx-filter"></i> Tampilkan</button>
                    <a href="index.php" class="btn btn-outline-secondary"><i class="bx bx-refresh"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <h5 class="card-header">Daftar Transaksi</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-hover" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Uraian</th>
                        <th>Kategori</th>
                        <th class="text-end">Masuk (Debit)</th>
                        <th class="text-end">Keluar (Kredit)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $total_debit = 0;
                    $total_kredit = 0;
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)):
                            $total_debit += $row['debit'];
                            $total_kredit += $row['kredit'];
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td><?php echo htmlspecialchars($row['uraian']); ?></td>
                                <td>
                                    <?php
                                    if ($row['nama_kategori']) {
                                        echo htmlspecialchars($row['nama_kategori']);
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td class="text-end text-success fw-bold">
                                    <?php echo $row['debit'] > 0 ? formatRupiah($row['debit']) : '-'; ?>
                                </td>
                                <td class="text-end text-danger fw-bold">
                                    <?php echo $row['kredit'] > 0 ? formatRupiah($row['kredit']) : '-'; ?>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="edit.php?id=<?php echo $row['id_transaksi']; ?>"
                                            class="btn btn-icon btn-sm btn-outline-warning me-1" title="Edit">
                                            <span class="bx bx-edit-alt"></span>
                                        </a>
                                        <button type="button"
                                            onclick="confirmDelete('process.php?action=delete&id=<?php echo $row['id_transaksi']; ?>')"
                                            class="btn btn-icon btn-sm btn-outline-danger" title="Hapus">
                                            <span class="bx bx-trash-alt"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile;
                    } else { ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data transaksi.</td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td colspan="4" class="text-end">Total</td>
                        <td class="text-end text-success">
                            <?php echo formatRupiah($total_debit); ?>
                        </td>
                        <td class="text-end text-danger">
                            <?php echo formatRupiah($total_kredit); ?>
                        </td>
                        <td></td>
                    </tr>
                    <tr class="table-secondary fw-bold">
                        <td colspan="4" class="text-end">Saldo Akhir</td>
                        <td colspan="2" class="text-center">
                            <?php echo formatRupiah($total_debit - $total_kredit); ?>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
