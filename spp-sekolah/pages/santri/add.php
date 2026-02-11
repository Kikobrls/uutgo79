<?php
/**
 * Tambah Santri
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$page_title = 'Tambah Santri';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nisn = sanitize($_POST['nisn']);
    $nik = sanitize($_POST['nik']);
    $nama = sanitize($_POST['nama']);
    $tempat_lahir = sanitize($_POST['tempat_lahir']);
    $tanggal_lahir = sanitize($_POST['tanggal_lahir']);
    $jenis_kelamin = sanitize($_POST['jenis_kelamin']);
    $nspp = sanitize($_POST['nspp']);
    $satuan_pendidikan = sanitize($_POST['satuan_pendidikan']);
    $id_kelas = sanitize($_POST['id_kelas']);
    $id_spp = sanitize($_POST['id_spp']);
    $alamat = sanitize($_POST['alamat']);
    $nama_wali = sanitize($_POST['nama_wali']);
    $telp_wali = sanitize($_POST['telp_wali']);
    $status = 'active'; // Default status

    // Validation
    if (empty($nisn))
        $errors[] = "NISN harus diisi!";
    if (empty($nik))
        $errors[] = "NIK harus diisi!";
    if (empty($nama))
        $errors[] = "Nama harus diisi!";
    if (empty($id_kelas))
        $errors[] = "Kelas harus dipilih!";
    if (empty($id_spp))
        $errors[] = "SPP harus dipilih!";

    if (empty($errors)) {
        // Check duplicate
        $check = mysqli_query($conn, "SELECT nisn FROM santri WHERE nisn = '$nisn'");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = "NISN sudah terdaftar!";
        } else {
            $query = "INSERT INTO santri (nisn, nik, nama, tempat_lahir, tanggal_lahir, jenis_kelamin, nspp, satuan_pendidikan, id_kelas, id_spp, alamat, nama_wali, telp_wali, status)
                      VALUES ('$nisn', '$nik', '$nama', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$nspp', '$satuan_pendidikan', '$id_kelas', '$id_spp', '$alamat', '$nama_wali', '$telp_wali', '$status')";

            if (mysqli_query($conn, $query)) {
                logActivity('Menambah data santri', 'santri', $nisn);
                setFlash('success', 'Data santri berhasil ditambahkan!');
                echo "<script>window.location.href='index.php';</script>";
                exit;
            } else {
                $errors[] = "Gagal menyimpan data: " . mysqli_error($conn);
            }
        }
    }
}

// Get dropdown data
$kelas_list = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas");
$spp_list = mysqli_query($conn, "SELECT * FROM spp ORDER BY tahun DESC");
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Data Santri /</span> Tambah Santri
        </h4>
        <a href="index.php" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Form Tambah Santri</h5>
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
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NISN <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="nisn" required
                                    value="<?php echo isset($_POST['nisn']) ? htmlspecialchars($_POST['nisn']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIK <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="nik" required
                                    value="<?php echo isset($_POST['nik']) ? htmlspecialchars($_POST['nik']) : ''; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama" required
                                    value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l"
                                            value="L" checked>
                                        <label class="form-check-label" for="jk_l">Laki-laki</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p"
                                            value="P">
                                        <label class="form-check-label" for="jk_p">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" name="tempat_lahir"
                                    value="<?php echo isset($_POST['tempat_lahir']) ? htmlspecialchars($_POST['tempat_lahir']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tanggal_lahir"
                                    value="<?php echo isset($_POST['tanggal_lahir']) ? htmlspecialchars($_POST['tanggal_lahir']) : ''; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NSPP</label>
                                <input type="text" class="form-control" name="nspp"
                                    value="<?php echo isset($_POST['nspp']) ? htmlspecialchars($_POST['nspp']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Satuan Pendidikan</label>
                                <input type="text" class="form-control" name="satuan_pendidikan"
                                    value="<?php echo isset($_POST['satuan_pendidikan']) ? htmlspecialchars($_POST['satuan_pendidikan']) : ''; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_kelas" required>
                                    <option value="">Pilih Kelas</option>
                                    <?php while ($k = mysqli_fetch_assoc($kelas_list)): ?>
                                        <option value="<?php echo $k['id_kelas']; ?>">
                                            <?php echo htmlspecialchars($k['nama_kelas']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Masuk / SPP <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_spp" required>
                                    <option value="">Pilih SPP</option>
                                    <?php while ($s = mysqli_fetch_assoc($spp_list)): ?>
                                        <option value="<?php echo $s['id_spp']; ?>">
                                            <?php echo $s['tahun'] . ' - Rp ' . number_format($s['nominal'], 0, ',', '.'); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat"
                                rows="2"><?php echo isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : ''; ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Wali</label>
                                <input type="text" class="form-control" name="nama_wali"
                                    value="<?php echo isset($_POST['nama_wali']) ? htmlspecialchars($_POST['nama_wali']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon Wali</label>
                                <input type="text" class="form-control" name="telp_wali"
                                    value="<?php echo isset($_POST['telp_wali']) ? htmlspecialchars($_POST['telp_wali']) : ''; ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Santri</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
