<?php
/**
 * Edit Transaksi
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$page_title = 'Edit Transaksi';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Get ID
$id_transaksi = isset($_GET['id']) ? sanitize($_GET['id']) : '';

if (empty($id_transaksi)) {
    redirect('index.php', 'danger', 'ID Transaksi tidak ditemukan!');
}

// Get Transaksi Data
$query = "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    redirect('index.php', 'danger', 'Data transaksi tidak ditemukan!');
}
$transaksi = mysqli_fetch_assoc($result);

// Determine jenis
$jenis = $transaksi['debit'] > 0 ? 'pemasukkan' : 'pengeluaran';
$nominal = $jenis == 'pemasukkan' ? $transaksi['debit'] : $transaksi['kredit'];

// Get Categories
$kategori_list = mysqli_query($conn, "SELECT * FROM kategori_keuangan ORDER BY nama_kategori ASC");

$page_title = 'Edit Transaksi';
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Transaksi /</span> Edit Data
        </h4>
        <a href="index.php" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Edit Transaksi #<?php echo htmlspecialchars($transaksi['id_transaksi']); ?></h5>
                <div class="card-body">
                    <form action="process.php?action=edit" method="POST">
                        <input type="hidden" name="id_transaksi" value="<?php echo htmlspecialchars($transaksi['id_transaksi']); ?>">

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Tanggal Transaksi</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" name="tanggal" value="<?php echo $transaksi['tanggal']; ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Jenis Transaksi</label>
                            <div class="col-sm-10">
                                <div class="btn-group" role="group" aria-label="Jenis Transaksi">
                                    <input type="radio" class="btn-check" name="jenis" id="pemasukkan" value="pemasukkan"
                                           <?php echo $jenis == 'pemasukkan' ? 'checked' : ''; ?> required>
                                    <label class="btn btn-outline-success" for="pemasukkan">Pemasukkan (Debit)</label>

                                    <input type="radio" class="btn-check" name="jenis" id="pengeluaran" value="pengeluaran"
                                           <?php echo $jenis == 'pengeluaran' ? 'checked' : ''; ?> required>
                                    <label class="btn btn-outline-danger" for="pengeluaran">Pengeluaran (Kredit)</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="id_kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php
                                    mysqli_data_seek($kategori_list, 0);
                                    while ($row = mysqli_fetch_assoc($kategori_list)):
                                    ?>
                                        <option value="<?php echo $row['id_kategori']; ?>"
                                            <?php echo $transaksi['id_kategori'] == $row['id_kategori'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($row['nama_kategori']); ?>
                                            
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Uraian / Keterangan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="uraian" rows="3" required><?php echo htmlspecialchars($transaksi['uraian']); ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Nominal (Rp)</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" name="nominal" min="0" value="<?php echo $nominal; ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Metode Pembayaran</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="metode_bayar" required>
                                    <option value="tunai" <?php echo $transaksi['metode_bayar'] == 'tunai' ? 'selected' : ''; ?>>Tunai</option>
                                    <option value="transfer" <?php echo $transaksi['metode_bayar'] == 'transfer' ? 'selected' : ''; ?>>Transfer Bank</option>
                                    <option value="lainnya" <?php echo $transaksi['metode_bayar'] == 'lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Update Transaksi</button>
                                <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
