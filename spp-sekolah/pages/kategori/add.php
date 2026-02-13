<?php
/**
 * Tambah Kategori Keuangan
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$page_title = 'Tambah Kategori';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = sanitize($_POST['nama_kategori']);

    // Validation
    if (empty($nama_kategori))
        $errors[] = "Nama kategori harus diisi!";

    if (empty($errors)) {
        // Check duplicate
        $check = mysqli_query($conn, "SELECT id_kategori FROM kategori_keuangan WHERE nama_kategori = '$nama_kategori'");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = "Kategori dengan nama yang sama sudah ada!";
        } else {
            $query = "INSERT INTO kategori_keuangan (nama_kategori) VALUES ('$nama_kategori')";

            if (mysqli_query($conn, $query)) {
                logActivity('Menambah kategori keuangan', 'kategori_keuangan', mysqli_insert_id($conn));
                setFlash('success', 'Kategori berhasil ditambahkan!');
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Gagal menyimpan data: " . mysqli_error($conn);
            }
        }
    }
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Keuangan /</span> Tambah Kategori
        </h4>
        <a href="index.php" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">Form Tambah Kategori</h5>
                <div class="card-body">

                    <?php if (!empty($errors)): ?>
                        <?php foreach ($errors as $error): ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_kategori"
                                placeholder="Contoh: Sumbangan, Listrik, Air" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
