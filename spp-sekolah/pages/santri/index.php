<?php
/**
 * Data Santri
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = sanitize($_GET['delete']);

    // Check if student has payments
    $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pembayaran WHERE id_santri = '$id'"));

    if ($check['total'] > 0) {
        setFlash('danger', 'Santri tidak dapat dihapus karena memiliki data pembayaran!');
    } else {
        $query = "DELETE FROM santri WHERE id = '$id'";
        if (mysqli_query($conn, $query)) {
            logActivity('Menghapus data santri', 'santri', $id);
            setFlash('success', 'Data santri berhasil dihapus!');
        } else {
            setFlash('danger', 'Gagal menghapus data santri!');
        }
    }
    header("Location: index.php");
    exit;
}

$page_title = 'Data Santri';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Get data
$filter_kelas = isset($_GET['kelas']) ? sanitize($_GET['kelas']) : '';
$where = '';
if (!empty($filter_kelas)) {
    $where = "WHERE s.id_kelas = '$filter_kelas'";
}

$query = "SELECT s.*, k.nama_kelas, sp.nominal, sp.tahun as tahun_spp
          FROM santri s
          JOIN kelas k ON s.id_kelas = k.id_kelas
          LEFT JOIN spp sp ON s.id_spp = sp.id_spp
          $where
          ORDER BY k.nama_kelas, s.nama";
$result = mysqli_query($conn, $query);

// Get kelas for filter
$kelas_list = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas");
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Data Master /</span> Santri
        </h4>
        <div>
            <a href="import.php" class="btn btn-success me-2">
                <i class="bx bx-spreadsheet me-1"></i> Import Excel
            </a>
            <a href="export.php" class="btn btn-info me-2">
                <i class="bx bx-export me-1"></i> Export CSV
            </a>
            <a href="add.php" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Santri
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Filter Kelas</label>
                    <div class="input-group">
                        <select name="kelas" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Kelas</option>
                            <?php while ($k = mysqli_fetch_assoc($kelas_list)): ?>
                                <option value="<?php echo $k['id_kelas']; ?>" <?php echo $filter_kelas == $k['id_kelas'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($k['nama_kelas']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <button class="btn btn-outline-secondary" type="submit">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <h5 class="card-header">Daftar Santri</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Santri</th>
                        <th>Tempat Lahir</th>
                        <th>Kelas</th>
                        <th>SPP</th>
                        <th>No. Telp Wali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php
                    $no = 1;
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)):
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['tempat_lahir'] ?? '-'); ?></td>
                                <td><span class="badge bg-primary"><?php echo htmlspecialchars($row['nama_kelas']); ?></span>
                                </td>
                                <td>Rp <?php echo number_format($row['nominal'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($row['telp_wali'] ?? '-'); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'active'): ?>
                                        <span class="badge bg-label-success">Active</span>
                                    <?php elseif ($row['status'] == 'alumni'): ?>
                                        <span class="badge bg-label-secondary">Alumni</span>
                                    <?php else: ?>
                                        <span class="badge bg-label-warning"><?php echo ucfirst($row['status']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="view.php?id=<?php echo $row['id']; ?>"
                                            class="btn btn-icon btn-sm btn-outline-info me-1" title="Detail">
                                            <span class="bx bx-show"></span>
                                        </a>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>"
                                            class="btn btn-icon btn-sm btn-outline-warning me-1" title="Edit">
                                            <span class="bx bx-edit-alt"></span>
                                        </a>
                                        <button type="button" class="btn btn-icon btn-sm btn-outline-danger"
                                            onclick="confirmDelete('index.php?delete=<?php echo $row['id']; ?>')"
                                            title="Hapus">
                                            <span class="bx bx-trash-alt"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile;
                    } else { ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data santri found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
