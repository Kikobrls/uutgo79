<?php
/**
 * Pengaturan WhatsApp API
 * sistem keuangan Sekolah
 */

$page_title = 'Pengaturan WhatsApp';
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

// Handle test message
if (isset($_POST['test_wa'])) {
    $test_to = sanitize($_POST['test_to']);
    $test_message = sanitize($_POST['test_message']);

    // Get API settings
    $api_url = getSetting('wa_api_url');
    $api_token = getSetting('wa_api_token');

    if (empty($api_token)) {
        $test_result = [
            'type' => 'danger',
            'message' => 'Token API WhatsApp belum dikonfigurasi!'
        ];
    } else {
        // Send test message using cURL
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target' => $test_to,
                'message' => $test_message
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $api_token
            ]
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $test_result = [
                'type' => 'danger',
                'message' => 'Error: ' . $err
            ];
        } else {
            $result = json_decode($response, true);
            if (isset($result['status']) && $result['status'] == true) {
                $test_result = [
                    'type' => 'success',
                    'message' => 'Pesan WhatsApp berhasil dikirim!'
                ];
            } else {
                $test_result = [
                    'type' => 'warning',
                    'message' => 'Response: ' . htmlspecialchars($response)
                ];
            }
        }
    }
}

// Handle form submission
if (isset($_POST['save_settings'])) {
    updateSetting('wa_api_provider', sanitize($_POST['wa_api_provider']));
    updateSetting('wa_api_url', sanitize($_POST['wa_api_url']));

    // Only update token if not empty
    if (!empty($_POST['wa_api_token'])) {
        updateSetting('wa_api_token', $_POST['wa_api_token']);
    }

    updateSetting('wa_sender', sanitize($_POST['wa_sender']));
    updateSetting('notif_wa_enabled', isset($_POST['notif_wa_enabled']) ? '1' : '0');
    updateSetting('notif_payment_template', sanitize($_POST['notif_payment_template']));

    logActivity('Mengubah pengaturan WhatsApp API', 'settings', 'whatsapp');
    setFlash('success', 'Pengaturan WhatsApp berhasil disimpan!');
    header("Location: whatsapp.php");
    exit;
}

// Get current settings
$settings = getSettingsByGroup('whatsapp');
$notif_settings = getSettingsByGroup('notification');
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> WhatsApp API</h4>

    <?php if ($test_result): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: '<?php echo $test_result['type']; ?>',
                    title: '<?php echo $test_result['type'] == 'success' ? 'Berhasil!' : ($test_result['type'] == 'danger' ? 'Error!' : 'Info'); ?>',
                    text: '<?php echo addslashes($test_result['message']); ?>',
                    showConfirmButton: true
                });
            });
        </script>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <!-- WhatsApp API Settings -->
            <div class="card mb-4">
                <h5 class="card-header text-success"><i class="bx bxl-whatsapp me-2"></i>Konfigurasi WhatsApp API</h5>
                <div class="card-body">
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bx bx-info-circle me-2"></i>
                        <div>
                            Sistem ini mendukung beberapa provider WhatsApp API seperti <strong>Fonnte</strong>,
                            <strong>Wablas</strong>, etc.
                        </div>
                    </div>

                    <form method="POST" action="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Provider API <span class="text-danger">*</span></label>
                                <select name="wa_api_provider" class="form-select" id="providerSelect">
                                    <option value="fonnte" <?php echo ($settings['wa_api_provider']['setting_value'] ?? '') == 'fonnte' ? 'selected' : ''; ?>>Fonnte</option>
                                    <option value="wablas" <?php echo ($settings['wa_api_provider']['setting_value'] ?? '') == 'wablas' ? 'selected' : ''; ?>>Wablas</option>
                                    <option value="woowa" <?php echo ($settings['wa_api_provider']['setting_value'] ?? '') == 'woowa' ? 'selected' : ''; ?>>Woowa</option>
                                    <option value="custom" <?php echo ($settings['wa_api_provider']['setting_value'] ?? '') == 'custom' ? 'selected' : ''; ?>>Custom</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Pengirim</label>
                                <input type="text" name="wa_sender" class="form-control"
                                    value="<?php echo htmlspecialchars($settings['wa_sender']['setting_value'] ?? ''); ?>"
                                    placeholder="628123456789">
                                <div class="form-text">Format: 628xxx (tanpa + atau 0)</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">API URL <span class="text-danger">*</span></label>
                            <input type="url" name="wa_api_url" class="form-control" id="apiUrl"
                                value="<?php echo htmlspecialchars($settings['wa_api_url']['setting_value'] ?? 'https://api.fonnte.com/send'); ?>"
                                required>
                            <div class="form-text">URL endpoint API untuk mengirim pesan</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">API Token/Key <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="wa_api_token" id="wa_api_token" class="form-control"
                                    placeholder="<?php echo !empty($settings['wa_api_token']['setting_value']) ? '••••••••••••••••' : 'Masukkan API Token'; ?>">
                                <span class="input-group-text cursor-pointer"
                                    onclick="togglePassword('wa_api_token')"><i class="bx bx-hide"></i></span>
                            </div>
                            <div class="form-text">Token API dari provider. Kosongkan jika tidak ingin mengubah.</div>
                        </div>

                        <hr class="my-3">

                        <div class="mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="notif_wa_enabled"
                                    name="notif_wa_enabled" <?php echo ($notif_settings['notif_wa_enabled']['setting_value'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-bold" for="notif_wa_enabled">Aktifkan Notifikasi
                                    WhatsApp Pembayaran</label>
                            </div>
                            <div class="form-text">Kirim WhatsApp otomatis setiap ada pembayaran SPP</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Template Pesan Notifikasi</label>
                            <textarea name="notif_payment_template" class="form-control"
                                rows="4"><?php echo htmlspecialchars($notif_settings['notif_payment_template']['setting_value'] ?? 'Pembayaran SPP untuk {nama} bulan {bulan} sebesar Rp {jumlah} telah diterima. Terima kasih.'); ?></textarea>
                            <div class="form-text">Variabel: {nama}, {kelas}, {bulan}, {tahun}, {jumlah},
                                {tanggal}</div>
                        </div>

                        <button type="submit" name="save_settings" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Simpan Pengaturan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Test WhatsApp -->
            <div class="card mb-4">
                <h5 class="card-header text-success"><i class="bx bx-paper-plane me-2"></i>Test Kirim WhatsApp</h5>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nomor Tujuan</label>
                                <input type="text" name="test_to" class="form-control" placeholder="628123456789"
                                    required>
                                <div class="form-text">Format: 628xxx</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pesan</label>
                                <input type="text" name="test_message" class="form-control"
                                    value="Test pesan dari Sistem SPP Sekolah" required>
                            </div>
                        </div>
                        <button type="submit" name="test_wa" class="btn btn-success">
                            <i class="bx bxl-whatsapp me-1"></i> Kirim Test WhatsApp
                        </button>
                    </form>
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
                        <a href="whatsapp.php"
                            class="list-group-item list-group-item-action active d-flex align-items-center">
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

            <!-- Provider Info -->
            <div class="card mb-4">
                <div class="card-header"><i class="bx bx-info-circle me-2"></i>Info Provider</div>
                <div class="card-body">
                    <div id="providerInfo">
                        <h6>Fonnte</h6>
                        <ul class="ps-3 mb-0 small">
                            <li>Website: <a href="https://fonnte.com" target="_blank">fonnte.com</a></li>
                            <li>API URL: https://api.fonnte.com/send</li>
                            <li>Metode: POST dengan Authorization Header</li>
                        </ul>
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

// Update API URL based on provider
document.getElementById("providerSelect").addEventListener("change", function() {
    var provider = this.value;
    var apiUrl = document.getElementById("apiUrl");
    var info = document.getElementById("providerInfo");

    switch(provider) {
        case "fonnte":
            apiUrl.value = "https://api.fonnte.com/send";
            info.innerHTML = "<h6>Fonnte</h6><ul class=\"ps-3 mb-0 small\"><li>Website: <a href=\"https://fonnte.com\" target=\"_blank\">fonnte.com</a></li><li>API URL: https://api.fonnte.com/send</li><li>Metode: POST dengan Authorization Header</li></ul>";
            break;
        case "wablas":
            apiUrl.value = "https://pati.wablas.com/api/send-message";
            info.innerHTML = "<h6>Wablas</h6><ul class=\"ps-3 mb-0 small\"><li>Website: <a href=\"https://wablas.com\" target=\"_blank\">wablas.com</a></li><li>API URL: https://pati.wablas.com/api/send-message</li><li>Metode: POST dengan Authorization Header</li></ul>";
            break;
        case "woowa":
            apiUrl.value = "https://api.woowa.id/api/v1/send";
            info.innerHTML = "<h6>Woowa</h6><ul class=\"ps-3 mb-0 small\"><li>Website: <a href=\"https://woowa.id\" target=\"_blank\">woowa.id</a></li><li>API URL: https://api.woowa.id/api/v1/send</li><li>Metode: POST dengan API Key</li></ul>";
            break;
        default:
            apiUrl.value = "";
            info.innerHTML = "<h6>Custom Provider</h6><p class=\"small mb-0\">Masukkan URL API sesuai dokumentasi provider Anda.</p>";
    }
});
</script>
';
require_once '../../includes/footer.php';
?>
