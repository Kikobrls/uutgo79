<?php
/**
 * Profil Petugas
 * sistem keuangan Sekolah
 */

$page_title = 'Profil Saya';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$id_guru = $_SESSION['id_guru'] ?? null;

if (!$id_guru) {
    header("Location: ../../login.php");
    exit;
}

// Get current user data
$query = "SELECT * FROM guru WHERE id_guru = '$id_guru'";
$result = mysqli_query($conn, $query);
$guru = mysqli_fetch_assoc($result);

if (!$guru) {
    session_destroy();
    header("Location: ../../login.php");
    exit;
}

$errors = [];
$success = false;

// Handle profile update
if (isset($_POST['update_profile'])) {
    $nama_guru = sanitize($_POST['nama_guru']);

    if (empty($nama_guru)) {
        $errors[] = "Nama harus diisi!";
    }

    if (empty($errors)) {
        $query = "UPDATE guru SET
                    nama_guru = '$nama_guru'
                  WHERE id_guru = '$id_guru'";

        if (mysqli_query($conn, $query)) {
            $_SESSION['nama_guru'] = $nama_guru;
            logActivity('Mengubah profil', 'guru', $id_guru);
            setFlash('success', 'Profil berhasil diperbarui!');
            header("Location: profile.php");
            exit;
        } else {
            $errors[] = "Gagal memperbarui profil!";
        }
    }

    $guru['nama_guru'] = $nama_guru;
}

// Handle password change
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $errors[] = "Semua field password harus diisi!";
    } else if (!password_verify($current_password, $guru['password'])) {
        $errors[] = "Password saat ini tidak valid!";
    } else if (strlen($new_password) < 6) {
        $errors[] = "Password baru minimal 6 karakter!";
    } else if ($new_password !== $confirm_password) {
        $errors[] = "Konfirmasi password tidak cocok!";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE guru SET password = '$hashed_password' WHERE id_guru = '$id_guru'";

        if (mysqli_query($conn, $query)) {
            logActivity('Mengubah password', 'guru', $id_guru);
            setFlash('success', 'Password berhasil diubah!');
            header("Location: profile.php");
            exit;
        } else {
            $errors[] = "Gagal mengubah password!";
        }
    }
}

// Get activity statistics
$stats = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total_transaksi, COALESCE(SUM(jumlah_bayar), 0) as total_pembayaran
    FROM pembayaran WHERE id_guru = '$id_guru'
"));
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Guru /</span> Profil Saya</h4>

    <div class="row">
        <div class="col-md-12">

            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="card mb-4">
                <h5 class="card-header">Detail Profil</h5>
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <div class="avatar avatar-xl">
                            <span class="avatar-initial rounded bg-label-primary display-4">
                                <?php echo strtoupper(substr($guru['nama_guru'], 0, 1)); ?>
                            </span>
                        </div>
                        <div class="button-wrapper">
                            <h4 class="mb-1"><?php echo htmlspecialchars($guru['nama_guru']); ?></h4>
                            <p class="text-muted mb-0"><?php echo ucfirst($guru['level']); ?></p>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="update_profile" value="1">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="nama_guru" class="form-label">Nama Lengkap</label>
                                <input class="form-control" type="text" id="nama_guru" name="nama_guru"
                                    value="<?php echo htmlspecialchars($guru['nama_guru']); ?>" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username"
                                    value="<?php echo htmlspecialchars($guru['username']); ?>" readonly disabled />
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>

            <div class="card mb-4">
                <h5 class="card-header">Ubah Password</h5>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="change_password" value="1">
                        <div class="row">
                            <div class="mb-3 col-md-4 form-password-toggle">
                                <label class="form-label">Password Saat Ini</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" name="current_password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-3 col-md-4 form-password-toggle">
                                <label class="form-label">Password Baru</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" name="new_password" minlength="6"
                                        required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-3 col-md-4 form-password-toggle">
                                <label class="form-label">Konfirmasi Password</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" name="confirm_password" minlength="6"
                                        required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-warning me-2">Ubah Password</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <h5 class="card-header">Statistik Aktivitas</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex mb-3">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-success"><i
                                            class="bx bx-wallet"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Total Transaksi Dibuat</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small
                                            class="fw-semibold"><?php echo number_format($stats['total_transaksi']); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex mb-3">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                            class="bx bx-dollar"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Total Pembayaran Diterima</h6>
                                    </div>
                                    <div class="user-progress">
                                        <small
                                            class="fw-semibold"><?php echo formatRupiah($stats['total_pembayaran']); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
