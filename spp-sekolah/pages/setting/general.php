<?php
/**
 * Pengaturan Umum
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update settings using existing helper or direct query if helper doesn't support update
    // Assuming helper function updateSetting($key, $value) exists or we do direct query
    // Let's assume direct queries for safety as I don't see updateSetting in helpers seen so far.
    // Actually, I saw getSetting. Let's assume we need to implement update.

    $settings = [
        'nama_sekolah' => sanitize($_POST['nama_sekolah']),
        'alamat_sekolah' => sanitize($_POST['alamat_sekolah']),
        'email_sekolah' => sanitize($_POST['email_sekolah']),
        'no_telp_sekolah' => sanitize($_POST['no_telp_sekolah']),
        'website_sekolah' => sanitize($_POST['website_sekolah']),
        'kepala_sekolah' => sanitize($_POST['kepala_sekolah']),
        'nip_kepala_sekolah' => sanitize($_POST['nip_kepala_sekolah'])
    ];

    $success = true;
    foreach ($settings as $key => $value) {
        // Check if exists
        $check = mysqli_query($conn, "SELECT setting_key FROM settings WHERE setting_key = '$key'");
        if (mysqli_num_rows($check) > 0) {
            $query = "UPDATE settings SET setting_value = '$value' WHERE setting_key = '$key'";
        } else {
            $query = "INSERT INTO settings (setting_key, setting_value) VALUES ('$key', '$value')";
        }

        if (!mysqli_query($conn, $query)) {
            $success = false;
        }
    }

    // Upload Logo
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $upload_dir = '../../assets/img/';
        $filename = 'logo.png'; // Force name for simplicity
        move_uploaded_file($_FILES['logo']['tmp_name'], $upload_dir . $filename);
    }

    if ($success) {
        logActivity('Mengubah pengaturan umum', 'settings', 0);
        setFlash('success', 'Pengaturan berhasil disimpan!');
    } else {
        setFlash('danger', 'Gagal menyimpan pengaturan!');
    }
    header("Location: general.php");
    exit;
}

$page_title = 'Pengaturan Umum';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Pengaturan /</span> Umum
        </h4>
        <div class="btn-group">
            <button type="button" class="btn btn-primary active">Umum</button>
            <a href="payment.php" class="btn btn-outline-primary">Pembayaran</a>
            <a href="notification.php" class="btn btn-outline-primary">Notifikasi</a>
        </div>
    </div>

    <div class="row">
        <!-- Settings Form -->
        <div class="col-md-8">
            <div class="card mb-4">
                <h5 class="card-header">Identitas Sekolah</h5>
                <hr class="my-0">
                <div class="card-body">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Nama Sekolah</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="nama_sekolah"
                                    value="<?php echo htmlspecialchars(getSetting('nama_sekolah')); ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">NPSN / NSS</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="npsn"
                                    value="<?php echo htmlspecialchars(getSetting('npsn')); ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Alamat Lengkap</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="alamat_sekolah"
                                    rows="3"><?php echo htmlspecialchars(getSetting('alamat_sekolah')); ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email_sekolah"
                                    value="<?php echo htmlspecialchars(getSetting('email_sekolah')); ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">No. Telepon</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="no_telp_sekolah"
                                    value="<?php echo htmlspecialchars(getSetting('no_telp_sekolah')); ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Website</label>
                            <div class="col-sm-9">
                                <input type="url" class="form-control" name="website_sekolah"
                                    value="<?php echo htmlspecialchars(getSetting('website_sekolah')); ?>">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">Kepala Sekolah</h6>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Nama Kepala Sekolah</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kepala_sekolah"
                                    value="<?php echo htmlspecialchars(getSetting('kepala_sekolah')); ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">NIP</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="nip_kepala_sekolah"
                                    value="<?php echo htmlspecialchars(getSetting('nip_kepala_sekolah')); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Logo Sekolah</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="logo" accept="image/png, image/jpeg">
                                <small class="text-muted">Format: PNG/JPG. Max: 2MB.</small>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="col-md-4">
            <div class="card mb-4">
                <h5 class="card-header">Info Sistem</h5>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="fw-bold d-block">Versi Aplikasi</span>
                        <span class="text-muted">v2.0.0 (Sneat Edition)</span>
                    </div>
                    <div class="mb-3">
                        <span class="fw-bold d-block">PHP Version</span>
                        <span class="text-muted"><?php echo phpversion(); ?></span>
                    </div>
                    <div class="mb-3">
                        <span class="fw-bold d-block">Server</span>
                        <span class="text-muted"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></span>
                    </div>
                    <div class="mb-3">
                        <span class="fw-bold d-block">Database</span>
                        <span class="text-muted">MySQL</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
