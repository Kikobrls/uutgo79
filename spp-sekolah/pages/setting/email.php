<?php
/**
 * Pengaturan Email SMTP
 * sistem keuangan Sekolah
 */

$page_title = 'Pengaturan Email';
require_once '../../includes/header.php';

// Check admin level
if ($_SESSION['level'] != 'admin') {
    setFlash('danger', 'Anda tidak memiliki akses ke halaman ini!');
    header("Location: ../../index.php");
    exit;
}

require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$test_result = null;

// Handle test email
if (isset($_POST['test_email'])) {
    $test_to = sanitize($_POST['test_to']);

    // Simple test - in real implementation, use PHPMailer
    $test_result = [
        'type' => 'info',
        'message' => "Fitur test email membutuhkan library PHPMailer. Silakan install via Composer: <code>composer require phpmailer/phpmailer</code>"
    ];
}

// Handle form submission
if (isset($_POST['save_settings'])) {
    updateSetting('smtp_host', sanitize($_POST['smtp_host']));
    updateSetting('smtp_port', sanitize($_POST['smtp_port']));
    updateSetting('smtp_username', sanitize($_POST['smtp_username']));

    // Only update password if not empty
    if (!empty($_POST['smtp_password'])) {
        updateSetting('smtp_password', $_POST['smtp_password']);
    }

    updateSetting('smtp_encryption', sanitize($_POST['smtp_encryption']));
    updateSetting('email_from_name', sanitize($_POST['email_from_name']));
    updateSetting('notif_email_enabled', isset($_POST['notif_email_enabled']) ? '1' : '0');

    logActivity('Mengubah pengaturan email SMTP', 'settings', 'email');
    setFlash('success', 'Pengaturan email berhasil disimpan!');
    header("Location: email.php");
    exit;
}

// Get current settings
$settings = getSettingsByGroup('email');
$notif_settings = getSettingsByGroup('notification');
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Email SMTP</h4>

    <?php if ($test_result): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: '<?php echo $test_result['type']; ?>',
                    title: '<?php echo $test_result['type'] == 'success' ? 'Berhasil!' : ($test_result['type'] == 'danger' ? 'Error!' : 'Info'); ?>',
                    html: '<?php echo addslashes($test_result['message']); ?>',
                    showConfirmButton: true
                });
            });
        </script>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <!-- SMTP Settings -->
            <div class="card mb-4">
                <h5 class="card-header"><i class="bx bx-server me-2"></i>Konfigurasi SMTP</h5>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label">SMTP Host <span class="text-danger">*</span></label>
                                <input type="text" name="smtp_host" class="form-control"
                                    value="<?php echo htmlspecialchars($settings['smtp_host']['setting_value'] ?? 'smtp.gmail.com'); ?>"
                                    required>
                                <div class="form-text">Contoh: smtp.gmail.com, smtp.mailtrap.io</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Port <span class="text-danger">*</span></label>
                                <input type="number" name="smtp_port" class="form-control"
                                    value="<?php echo htmlspecialchars($settings['smtp_port']['setting_value'] ?? '587'); ?>"
                                    required>
                                <div class="form-text">587 (TLS) / 465 (SSL)</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">SMTP Username/Email <span class="text-danger">*</span></label>
                            <input type="email" name="smtp_username" class="form-control"
                                value="<?php echo htmlspecialchars($settings['smtp_username']['setting_value'] ?? ''); ?>"
                                required>
                            <div class="form-text">Email yang akan digunakan untuk mengirim</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">SMTP Password/App Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="smtp_password" id="smtp_password" class="form-control"
                                    placeholder="<?php echo !empty($settings['smtp_password']['setting_value']) ? '••••••••' : ''; ?>">
                                <span class="input-group-text cursor-pointer"
                                    onclick="togglePassword('smtp_password')"><i class="bx bx-hide"></i></span>
                            </div>
                            <div class="form-text">Untuk Gmail, gunakan App Password. Kosongkan jika tidak ingin
                                mengubah.</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Encryption</label>
                                <select name="smtp_encryption" class="form-select">
                                    <option value="tls" <?php echo ($settings['smtp_encryption']['setting_value'] ?? '') == 'tls' ? 'selected' : ''; ?>>TLS</option>
                                    <option value="ssl" <?php echo ($settings['smtp_encryption']['setting_value'] ?? '') == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Pengirim</label>
                                <input type="text" name="email_from_name" class="form-control"
                                    value="<?php echo htmlspecialchars($settings['email_from_name']['setting_value'] ?? 'Sistem SPP Sekolah'); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="notif_email_enabled"
                                    name="notif_email_enabled" <?php echo ($notif_settings['notif_email_enabled']['setting_value'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="notif_email_enabled">Aktifkan Notifikasi Email
                                    Pembayaran</label>
                            </div>
                            <div class="form-text">Kirim email otomatis setiap ada pembayaran SPP</div>
                        </div>

                        <button type="submit" name="save_settings" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Simpan Pengaturan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Test Email -->
            <div class="card mb-4">
                <h5 class="card-header text-info"><i class="bx bx-paper-plane me-2"></i>Test Kirim Email</h5>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Email Tujuan</label>
                            <input type="email" name="test_to" class="form-control" placeholder="test@example.com"
                                required>
                        </div>
                        <button type="submit" name="test_email" class="btn btn-info">
                            <i class="bx bx-send me-1"></i> Kirim Test Email
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Quick Guide -->
            <div class="card mb-4">
                <h5 class="card-header"><i class="bx bx-help-circle me-2"></i>Panduan</h5>
                <div class="card-body">
                    <h6>Gmail SMTP:</h6>
                    <ul class="ps-3 mb-3">
                        <li>Host: smtp.gmail.com</li>
                        <li>Port: 587 (TLS) / 465 (SSL)</li>
                        <li>Gunakan App Password dari Google Account</li>
                    </ul>
                    <hr>
                    <h6>Cara Mendapatkan App Password Gmail:</h6>
                    <ol class="ps-3 mb-0">
                        <li>Buka Google Account</li>
                        <li>Aktifkan 2-Factor Authentication</li>
                        <li>Buka Security > App Passwords</li>
                        <li>Generate password untuk aplikasi</li>
                    </ol>
                </div>
            </div>

            <!-- Menu Links -->
            <div class="card mb-4">
                <div class="card-header">Menu Pengaturan</div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="general.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bx bx-buildings me-2"></i> Umum
                        </a>
                        <a href="email.php"
                            class="list-group-item list-group-item-action active d-flex align-items-center">
                            <i class="bx bx-envelope me-2"></i> Email SMTP
                        </a>
                        <a href="whatsapp.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bx bxl-whatsapp me-2"></i> WhatsApp API
                        </a>
                        <a href="payment.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bx bx-credit-card me-2"></i> Payment Gateway
                        </a>
                        <a href="backup.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bx bx-data me-2"></i> Backup Database
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$extra_js = '
<script>
function togglePassword(id) {
    var input = document.getElementById(id);
    var icon = input.nextElementSibling.querySelector("i");
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bx-hide");
        icon.classList.add("bx-show");
    } else {
        input.type = "password";
        icon.classList.remove("bx-show");
        icon.classList.add("bx-hide");
    }
}
</script>
';
require_once '../../includes/footer.php';
?>
