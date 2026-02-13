<?php
/**
 * Pengaturan Payment Gateway
 * sistem keuangan Sekolah
 */

$page_title = 'Pengaturan Payment Gateway';
require_once '../../includes/header.php';

// Check admin level
if ($_SESSION['level'] != 'admin') {
    setFlash('danger', 'Anda tidak memiliki akses ke halaman ini!');
    header("Location: ../../index.php");
    exit;
}

require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    updateSetting('payment_enabled', isset($_POST['payment_enabled']) ? '1' : '0');

    // Only update keys if not empty
    if (!empty($_POST['midtrans_server_key'])) {
        updateSetting('midtrans_server_key', $_POST['midtrans_server_key']);
    }
    if (!empty($_POST['midtrans_client_key'])) {
        updateSetting('midtrans_client_key', $_POST['midtrans_client_key']);
    }

    updateSetting('midtrans_is_production', isset($_POST['midtrans_is_production']) ? '1' : '0');

    logActivity('Mengubah pengaturan payment gateway', 'settings', 'payment');
    setFlash('success', 'Pengaturan payment gateway berhasil disimpan!');
    header("Location: payment.php");
    exit;
}

// Get current settings
$settings = getSettingsByGroup('payment');
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Payment Gateway</h4>

    <div class="row">
        <div class="col-md-8">
            <!-- Midtrans Settings -->
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><i class="bx bx-credit-card me-2"></i>Konfigurasi Midtrans</h5>
                    <?php if (($settings['payment_enabled']['setting_value'] ?? '0') == '1'): ?>
                        <span class="badge bg-success">Aktif</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Tidak Aktif</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bx bx-info-circle me-2"></i>
                        <div>
                            <strong>Midtrans</strong> adalah payment gateway terpopuler di Indonesia yang mendukung
                            berbagai metode pembayaran.
                        </div>
                    </div>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="payment_enabled"
                                    name="payment_enabled" <?php echo ($settings['payment_enabled']['setting_value'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-bold" for="payment_enabled">Aktifkan Pembayaran
                                    Online</label>
                            </div>
                            <div class="form-text">Aktifkan fitur pembayaran online via Midtrans</div>
                        </div>

                        <hr class="my-3">

                        <div class="mb-3">
                            <label class="form-label">Server Key <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="midtrans_server_key" id="midtrans_server_key"
                                    class="form-control"
                                    placeholder="<?php echo !empty($settings['midtrans_server_key']['setting_value']) ? '••••••••••••••••' : 'SB-Mid-server-xxxxx'; ?>">
                                <span class="input-group-text cursor-pointer"
                                    onclick="togglePassword('midtrans_server_key')"><i class="bx bx-hide"></i></span>
                            </div>
                            <div class="form-text">Server Key dari dashboard Midtrans. Kosongkan jika tidak ingin
                                mengubah.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Client Key <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="midtrans_client_key" id="midtrans_client_key"
                                    class="form-control"
                                    placeholder="<?php echo !empty($settings['midtrans_client_key']['setting_value']) ? '••••••••••••••••' : 'SB-Mid-client-xxxxx'; ?>">
                                <span class="input-group-text cursor-pointer"
                                    onclick="togglePassword('midtrans_client_key')"><i class="bx bx-hide"></i></span>
                            </div>
                            <div class="form-text">Client Key dari dashboard Midtrans. Kosongkan jika tidak ingin
                                mengubah.</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="midtrans_is_production"
                                    name="midtrans_is_production" <?php echo ($settings['midtrans_is_production']['setting_value'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-bold" for="midtrans_is_production">Mode
                                    Production</label>
                            </div>
                            <div class="form-text">Aktifkan jika sudah siap untuk pembayaran real. Jika tidak dicentang,
                                akan menggunakan mode Sandbox (testing).</div>
                        </div>

                        <div class="alert alert-warning" role="alert">
                            <i class="bx bx-error me-2"></i>
                            <strong>Perhatian:</strong> Pastikan Anda menggunakan Server Key dan Client Key yang sesuai
                            dengan mode yang dipilih (Sandbox atau Production).
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Simpan Pengaturan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Payment Methods Info -->
            <div class="card mb-4">
                <h5 class="card-header"><i class="bx bx-wallet me-2"></i>Metode Pembayaran yang Didukung</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="text-primary"><i class="bx bx-buildings me-1"></i> Bank Transfer</h6>
                            <ul class="ps-3 mb-0 small">
                                <li>BCA Virtual Account</li>
                                <li>BNI Virtual Account</li>
                                <li>BRI Virtual Account</li>
                                <li>Mandiri Bill</li>
                                <li>Permata VA</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-success"><i class="bx bx-mobile-alt me-1"></i> E-Wallet</h6>
                            <ul class="ps-3 mb-0 small">
                                <li>GoPay</li>
                                <li>OVO</li>
                                <li>Dana</li>
                                <li>ShopeePay</li>
                                <li>LinkAja</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-info"><i class="bx bx-credit-card me-1"></i> Lainnya</h6>
                            <ul class="ps-3 mb-0 small">
                                <li>Kartu Kredit/Debit</li>
                                <li>Indomaret</li>
                                <li>Alfamart</li>
                                <li>Akulaku</li>
                                <li>Kredivo</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Sidebar Navigation -->
            <div class="card mb-4">
                <div class="card-header">Menu Pengaturan</div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="general.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bx bx-buildings me-2"></i> Umum
                        </a>
                        <a href="email.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bx bx-envelope me-2"></i> Email SMTP
                        </a>
                        <a href="whatsapp.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bx bxl-whatsapp me-2"></i> WhatsApp API
                        </a>
                        <a href="payment.php"
                            class="list-group-item list-group-item-action active d-flex align-items-center">
                            <i class="bx bx-credit-card me-2"></i> Payment Gateway
                        </a>
                        <a href="backup.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bx bx-data me-2"></i> Backup Database
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Guide -->
            <div class="card mb-4">
                <h5 class="card-header"><i class="bx bx-help-circle me-2"></i>Panduan</h5>
                <div class="card-body">
                    <h6>Cara Mendapatkan API Key Midtrans:</h6>
                    <ol class="ps-3 mb-3 small">
                        <li>Daftar di <a href="https://midtrans.com" target="_blank">midtrans.com</a></li>
                        <li>Login ke Dashboard Midtrans</li>
                        <li>Pilih Environment (Sandbox/Production)</li>
                        <li>Buka Settings > Access Keys</li>
                        <li>Copy Server Key dan Client Key</li>
                    </ol>
                    <hr>
                    <h6>Mode Testing (Sandbox):</h6>
                    <p class="small text-muted mb-2">Gunakan mode Sandbox untuk testing. Tidak ada uang yang akan
                        dipotong.</p>
                    <h6>Mode Production:</h6>
                    <p class="small text-muted mb-0">Gunakan mode Production untuk pembayaran real. Pastikan sudah
                        verifikasi akun di Midtrans.</p>
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
