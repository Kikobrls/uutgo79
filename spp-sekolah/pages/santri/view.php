<?php
/**
 * Detail Santri
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$page_title = 'Detail Santri';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Get Data
$id = isset($_GET['id']) ? sanitize($_GET['id']) : '';
if (empty($id)) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$query = "SELECT s.*, k.nama_kelas, sp.nominal, sp.tahun as tahun_spp
          FROM santri s
          JOIN kelas k ON s.id_kelas = k.id_kelas
          LEFT JOIN spp sp ON s.id_spp = sp.id_spp
          WHERE s.id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    setFlash('danger', 'Data santri tidak ditemukan!');
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

// Payment History
$payments = mysqli_query($conn, "SELECT * FROM pembayaran WHERE id_santri = '$id' ORDER BY tgl_bayar DESC");
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Data Santri /</span> Detail Santri
        </h4>
        <a href="index.php" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Profile -->
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="mx-auto mb-3"
                        style="width: 100px; height: 100px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bx bx-user" style="font-size: 50px; color: #aaa;"></i>
                    </div>
                    <h5 class="card-title"><?php echo htmlspecialchars($data['nama']); ?></h5>
                    <span class="badge bg-primary"><?php echo htmlspecialchars($data['nama_kelas']); ?></span>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Tempat Lahir</div>
                        <div class="col-8">
                            <?php echo htmlspecialchars($data['tempat_lahir'] ?? '-'); ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Alamat</div>
                        <div class="col-8"><?php echo htmlspecialchars($data['alamat'] ?? '-'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Telp Wali</div>
                        <div class="col-8">
                            <?php echo htmlspecialchars($data['telp_wali'] ?? '-'); ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">SPP</div>
                        <div class="col-8">Rp <?php echo number_format($data['nominal'], 0, ',', '.'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 fw-bold">Status</div>
                        <div class="col-8">
                            <?php if ($data['status'] == 'active'): ?>
                                <span class="badge bg-label-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-label-secondary"><?php echo ucfirst($data['status']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments -->
        <div class="col-md-7">
            <div class="card mb-4">
                <h5 class="card-header">Riwayat Pembayaran SPP</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Bulan/Tahun</th>
                                <th>Jumlah</th>
                                <th>Via</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($payments) > 0): ?>
                                <?php while ($p = mysqli_fetch_assoc($payments)): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($p['tgl_bayar'])); ?></td>
                                        <td><?php echo $p['bulan_dibayar'] . ' ' . $p['tahun_dibayar']; ?></td>
                                        <td class="text-success fw-bold">Rp
                                            <?php echo number_format($p['jumlah_bayar'], 0, ',', '.'); ?></td>
                                        <td><span class="badge bg-label-info"><?php echo ucfirst($p['metode_bayar']); ?></span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada pembayaran.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
