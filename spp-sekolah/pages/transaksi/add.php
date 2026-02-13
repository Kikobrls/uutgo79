<?php
/**
 * Tambah Transaksi
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$page_title = 'Tambah Transaksi';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Get categories
$kategori_list = mysqli_query($conn, "SELECT * FROM kategori_keuangan ORDER BY nama_kategori ASC");
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Transaksi /</span> Tambah Data
        </h4>
        <a href="index.php" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Form Transaksi Baru</h5>
                <div class="card-body">
                    <form action="process.php?action=add" method="POST">
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Tanggal Transaksi</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" name="tanggal"
                                    value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Jenis Transaksi</label>
                            <div class="col-sm-10">
                                <div class="btn-group" role="group" aria-label="Jenis Transaksi">
                                    <input type="radio" class="btn-check" name="jenis" id="pemasukkan"
                                        value="pemasukkan" required>
                                    <label class="btn btn-outline-success" for="pemasukkan">Pemasukkan (Debit)</label>

                                    <input type="radio" class="btn-check" name="jenis" id="pengeluaran"
                                        value="pengeluaran" required>
                                    <label class="btn btn-outline-danger" for="pengeluaran">Pengeluaran (Kredit)</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="id_kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php while ($row = mysqli_fetch_assoc($kategori_list)): ?>
                                        <option value="<?php echo $row['id_kategori']; ?>">
                                            <?php echo htmlspecialchars($row['nama_kategori']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Uraian / Keterangan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="uraian" rows="3"
                                    placeholder="Contoh: Pembayaran listrik bulan Januari" required></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Nominal (Rp)</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" name="nominal" min="0" placeholder="0"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Metode Pembayaran</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="metode_bayar" required>
                                    <option value="tunai">Tunai</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
