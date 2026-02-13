<?php
/**
 * Data Pembayaran SPP
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
    if (mysqli_query($conn, "DELETE FROM pembayaran WHERE id_pembayaran = '$id'")) {
        logActivity('Menghapus data pembayaran', 'pembayaran', $id);
        setFlash('success', 'Data pembayaran berhasil dihapus!');
    } else {
        setFlash('danger', 'Gagal menghapus data pembayaran!');
    }
    header("Location: index.php");
    exit;
}

$page_title = 'Data Pembayaran SPP';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Filter
$filter_bulan = isset($_GET['bulan']) ? sanitize($_GET['bulan']) : date('m');
$filter_tahun = isset($_GET['tahun']) ? sanitize($_GET['tahun']) : date('Y');
$filter_kelas = isset($_GET['kelas']) ? sanitize($_GET['kelas']) : '';

$where = "WHERE MONTH(p.tgl_bayar) = '$filter_bulan' AND YEAR(p.tgl_bayar) = '$filter_tahun'";
if (!empty($filter_kelas)) {
    $where .= " AND s.id_kelas = '$filter_kelas'";
}

// Get data
$query = "SELECT p.*, s.nama as nama_santri, k.nama_kelas, g.nama_guru
          FROM pembayaran p
          JOIN santri s ON p.id_santri = s.id
          JOIN kelas k ON s.id_kelas = k.id_kelas
          JOIN guru g ON p.id_guru = g.id_guru
          $where
          ORDER BY p.tgl_bayar DESC, p.id_pembayaran DESC";
$result = mysqli_query($conn, $query);

// Get totals
$total_query = mysqli_query($conn, "
    SELECT COALESCE(SUM(p.jumlah_bayar), 0) as total, COUNT(*) as jumlah
    FROM pembayaran p
    JOIN santri s ON p.id_santri = s.id
    $where
");
$total_data = mysqli_fetch_assoc($total_query);

// Dropdown data
$kelas_list = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas");

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
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Transaksi /</span> Pembayaran SPP
        </h4>
        <a href="add.php" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Transaksi Baru
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header pb-0">
            <h5 class="card-title">Filter Data</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select">
                        <?php foreach ($bulan_list as $key => $val): ?>
                            <option value="<?php echo $key; ?>" <?php echo $filter_bulan == $key ? 'selected' : ''; ?>>
                                <?php echo $val; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="<?php echo $filter_tahun; ?>"
                        min="2020" max="2050">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-select">
                        <option value="">Semua Kelas</option>
                        <?php while ($k = mysqli_fetch_assoc($kelas_list)): ?>
                            <option value="<?php echo $k['id_kelas']; ?>" <?php echo $filter_kelas == $k['id_kelas'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($k['nama_kelas']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="index.php" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-white mb-0">Total Pemasukkan</h5>
                            <small>Periode Terpilih</small>
                        </div>
                        <h3 class="text-white mb-0"><?php echo formatRupiah($total_data['total']); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-white mb-0">Total Transaksi</h5>
                            <small>Periode Terpilih</small>
                        </div>
                        <h3 class="text-white mb-0"><?php echo number_format($total_data['jumlah']); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <h5 class="card-header border-bottom">Riwayat Pembayaran</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Santri</th>
                        <th>Pembayaran Bulan</th>
                        <th>Jumlah</th>
                        <th>Via</th>
                        <th>Petugas</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tgl_bayar'])); ?></td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold"><?php echo htmlspecialchars($row['nama_santri']); ?></span>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['nama_kelas']); ?></small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-primary">
                                    <?php echo htmlspecialchars($row['bulan_dibayar']); ?>
                                    <?php echo htmlspecialchars($row['tahun_dibayar']); ?>
                                </span>
                            </td>
                            <td class="fw-bold text-success"><?php echo formatRupiah($row['jumlah_bayar']); ?></td>
                            <td><?php echo ucfirst($row['metode_bayar']); ?></td>
                            <td><small><?php echo htmlspecialchars($row['nama_guru']); ?></small></td>
                            <td>
                                <div class="d-flex">
                                    <a href="cetak.php?id=<?php echo $row['id_pembayaran']; ?>" target="_blank"
                                        class="btn btn-icon btn-sm btn-outline-secondary me-1" title="Cetak Kwitansi">
                                        <span class="bx bx-printer"></span>
                                    </a>
                                    <a href="javascript:void(0);"
                                        onclick="confirmDelete('index.php?delete=<?php echo $row['id_pembayaran']; ?>')"
                                        class="btn btn-icon btn-sm btn-outline-danger" title="Hapus">
                                        <span class="bx bx-trash-alt"></span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
