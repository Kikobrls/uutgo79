<?php
/**
 * Edit Santri
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$page_title = 'Edit Santri';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Get Data
$nisn = isset($_GET['nisn']) ? sanitize($_GET['nisn']) : '';
if (empty($nisn)) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$query = "SELECT * FROM santri WHERE nisn = '$nisn'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    setFlash('danger', 'Data santri tidak ditemukan!');
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_nisn = sanitize($_POST['nisn']);
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
    $status = sanitize($_POST['status']);

    // Validation
    if (empty($new_nisn))
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
        // Check duplicate if NISN changed
        if ($new_nisn != $nisn) {
            $check = mysqli_query($conn, "SELECT nisn FROM santri WHERE nisn = '$new_nisn'");
            if (mysqli_num_rows($check) > 0) {
                $errors[] = "NISN sudah terdaftar!";
            }
        }

        if (empty($errors)) {
            $query = "UPDATE santri SET
                      nisn = '$new_nisn',
                      nik = '$nik',
                      nama = '$nama',
                      tempat_lahir = '$tempat_lahir',
                      tanggal_lahir = '$tanggal_lahir',
                      jenis_kelamin = '$jenis_kelamin',
                      nspp = '$nspp',
                      satuan_pendidikan = '$satuan_pendidikan',
                      id_kelas = '$id_kelas',
                      id_spp = '$id_spp',
                      alamat = '$alamat',
                      nama_wali = '$nama_wali',
                      telp_wali = '$telp_wali',
                      status = '$status'
                      WHERE nisn = '$nisn'";

            if (mysqli_query($conn, $query)) {
                logActivity('Mengubah data santri', 'santri', $new_nisn);
                setFlash('success', 'Data santri berhasil diubah!');
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
            <span class="text-muted fw-light">Data Santri /</span> Edit Santri
        </h4>
        <a href="index.php" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Form Edit Santri</h5>
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
                                    value="<?php echo htmlspecialchars($data['nisn']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIK <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="nik" required
                                    value="<?php echo htmlspecialchars($data['nik']); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama" required
                                    value="<?php echo htmlspecialchars($data['nama']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l"
                                            value="L" <?php echo $data['jenis_kelamin'] == 'L' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="jk_l">Laki-laki</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p"
                                            value="P" <?php echo $data['jenis_kelamin'] == 'P' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="jk_p">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" name="tempat_lahir"
                                    value="<?php echo htmlspecialchars($data['tempat_lahir']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tanggal_lahir"
                                    value="<?php echo htmlspecialchars($data['tanggal_lahir']); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NSPP</label>
                                <input type="text" class="form-control" name="nspp"
                                    value="<?php echo htmlspecialchars($data['nspp']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Satuan Pendidikan</label>
                                <input type="text" class="form-control" name="satuan_pendidikan"
                                    value="<?php echo htmlspecialchars($data['satuan_pendidikan']); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_kelas" required>
                                    <option value="">Pilih Kelas</option>
                                    <?php
                                    mysqli_data_seek($kelas_list, 0);
                                    while ($k = mysqli_fetch_assoc($kelas_list)):
                                        ?>
                                        <option value="<?php echo $k['id_kelas']; ?>" <?php echo $k['id_kelas'] == $data['id_kelas'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($k['nama_kelas']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Masuk / SPP <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_spp" required>
                                    <option value="">Pilih SPP</option>
                                    <?php
                                    mysqli_data_seek($spp_list, 0);
                                    while ($s = mysqli_fetch_assoc($spp_list)):
                                        ?>
                                        <option value="<?php echo $s['id_spp']; ?>" <?php echo $s['id_spp'] == $data['id_spp'] ? 'selected' : ''; ?>>
                                            <?php echo $s['tahun'] . ' - Rp ' . number_format($s['nominal'], 0, ',', '.'); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat"
                                rows="2"><?php echo htmlspecialchars($data['alamat']); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Wali</label>
                                <input type="text" class="form-control" name="nama_wali"
                                    value="<?php echo htmlspecialchars($data['nama_wali']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon Wali</label>
                                <input type="text" class="form-control" name="telp_wali"
                                    value="<?php echo htmlspecialchars($data['telp_wali']); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active" <?php echo $data['status'] == 'active' ? 'selected' : ''; ?>>Aktif
                                </option>
                                <option value="alumni" <?php echo $data['status'] == 'alumni' ? 'selected' : ''; ?>>Alumni
                                </option>
                                <option value="inactive" <?php echo $data['status'] == 'inactive' ? 'selected' : ''; ?>>
                                    Tidak Aktif</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
