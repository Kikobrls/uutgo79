<?php
/**
 * Input Pembayaran SPP
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$page_title = 'Input Pembayaran';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$errors = [];
$success = false;
$last_payment = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_santri = sanitize($_POST['id_santri']);
    $bulan_dibayar = sanitize($_POST['bulan_dibayar']);
    $tahun_dibayar = sanitize($_POST['tahun_dibayar']);
    $jumlah_bayar = str_replace(['.', ','], '', $_POST['jumlah_bayar']); // Remove dot/comma
    $jumlah_bayar = (int) $jumlah_bayar; // Ensure integer
    $metode_bayar = sanitize($_POST['metode_bayar']);
    $keterangan = sanitize($_POST['keterangan']);

    // Validation
    if (empty($id_santri))
        $errors[] = "Santri harus dipilih!";
    if (empty($bulan_dibayar))
        $errors[] = "Bulan harus dipilih!";
    if (empty($tahun_dibayar))
        $errors[] = "Tahun harus diisi!";
    if (empty($jumlah_bayar))
        $errors[] = "Jumlah bayar harus diisi/valid!";

    // Check duplicate payment
    if (empty($errors)) {
        $check = mysqli_fetch_assoc(mysqli_query($conn, "
            SELECT id_pembayaran FROM pembayaran
            WHERE id_santri = '$id_santri' AND bulan_dibayar = '$bulan_dibayar' AND tahun_dibayar = '$tahun_dibayar'
        "));
        if ($check) {
            $errors[] = "Pembayaran untuk bulan $bulan_dibayar tahun $tahun_dibayar sudah ada!";
        }
    }

    if (empty($errors)) {
        // Get student's SPP ID
        $siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_spp FROM santri WHERE id = '$id_santri'"));

        if ($siswa) {
            $id_spp = $siswa['id_spp'];
            $id_guru = $_SESSION['id_guru'];

            $query = "INSERT INTO pembayaran (id_guru, id_santri, tgl_bayar, bulan_dibayar, tahun_dibayar, id_spp, jumlah_bayar, metode_bayar, keterangan)
                      VALUES ('$id_guru', '$id_santri', CURDATE(), '$bulan_dibayar', '$tahun_dibayar', '$id_spp', '$jumlah_bayar', '$metode_bayar', '$keterangan')";

            if (mysqli_query($conn, $query)) {
                $last_id = mysqli_insert_id($conn);
                logActivity('Input pembayaran SPP', 'pembayaran', $last_id);

                // Get payment details for receipt
                $last_payment = mysqli_fetch_assoc(mysqli_query($conn, "
                    SELECT p.*, s.nama, s.id as id_santri, k.nama_kelas
                    FROM pembayaran p
                    JOIN santri s ON p.id_santri = s.id
                    JOIN kelas k ON s.id_kelas = k.id_kelas
                    WHERE p.id_pembayaran = '$last_id'
                "));

                $success = true;
                setFlash('success', 'Pembayaran berhasil disimpan!');
            } else {
                $errors[] = "Gagal menyimpan pembayaran: " . mysqli_error($conn);
            }
        } else {
            $errors[] = "Data santri tidak ditemukan/valid.";
        }
    }
}

// Get students list
$siswa_list = mysqli_query($conn, "
    SELECT s.id, s.nama, k.nama_kelas, sp.nominal
    FROM santri s
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN spp sp ON s.id_spp = sp.id_spp
    WHERE s.status = 'active'
    ORDER BY k.nama_kelas ASC, s.nama ASC
");

// Pre-select student if passed via GET
$selected_id = isset($_GET['id']) ? sanitize($_GET['id']) : '';

$bulan_list = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Transaksi /</span> Input Pembayaran
        </h4>
        <a href="index.php" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Form Column -->
        <div class="col-md-8">
            <div class="card mb-4">
                <h5 class="card-header">Form Pembayaran SPP</h5>
                <div class="card-body">

                    <?php if ($success && $last_payment): ?>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            Pembayaran berhasil disimpan!
                            <a href="print.php?id=<?php echo $last_payment['id_pembayaran']; ?>" target="_blank"
                                class="fw-bold text-success text-decoration-underline">Cetak Kwitansi</a>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

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
                            <label class="form-label">Pilih Siswa</label>
                            <select class="form-select select2" name="id_santri" id="siswaSelect" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php
                                if (mysqli_num_rows($siswa_list) > 0) {
                                    mysqli_data_seek($siswa_list, 0);
                                    while ($s = mysqli_fetch_assoc($siswa_list)) {
                                        $selected = ($selected_id == $s['id']) ? 'selected' : '';
                                        echo "<option value='{$s['id']}' data-nominal='{$s['nominal']}' data-kelas='{$s['nama_kelas']}' $selected>{$s['nama']} (Kelas {$s['nama_kelas']})</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Bulan</label>
                                <select class="form-select" name="bulan_dibayar" required>
                                    <option value="">-- Pilih Bulan --</option>
                                    <?php
                                    $current_month_idx = date('n') - 1;
                                    foreach ($bulan_list as $idx => $bulan) {
                                        $selected = ($idx == $current_month_idx) ? 'selected' : '';
                                        echo "<option value='$bulan' $selected>$bulan</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tahun</label>
                                <input type="number" class="form-control" name="tahun_dibayar"
                                    value="<?php echo date('Y'); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Bayar (Rp)</label>
                            <input type="number" class="form-control" name="jumlah_bayar" id="jumlahBayar"
                                placeholder="0" required>
                            <div class="form-text">Nominal akan terisi otomatis sesuai SPP siswa.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select class="form-select" name="metode_bayar" required>
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control" name="keterangan" rows="2"
                                placeholder="Catatan tambahan..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Pembayaran</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Column -->
        <div class="col-md-4">
            <div class="card mb-4 bg-label-secondary">
                <div class="card-body">
                    <h5 class="card-title text-primary"><i class="bx bx-info-circle me-2"></i>Informasi Siswa</h5>
                    <div id="infoSiswa">
                        <p class="text-muted text-center my-4">Pilih siswa untuk melihat detail informasi pembayaran.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$extra_js = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    var siswaSelect = document.getElementById("siswaSelect");

    function updateInfo() {
        var option = siswaSelect.options[siswaSelect.selectedIndex];
        if (option.value) {
            var nominal = option.getAttribute("data-nominal");
            var kelas = option.getAttribute("data-kelas");
            var nama = option.text.split(" (")[0];

            // Format Rupiah
            var formattedNominal = new Intl.NumberFormat("id-ID").format(nominal);

            // Update Amount Input
            document.getElementById("jumlahBayar").value = nominal;

            // Update Info Card
            document.getElementById("infoSiswa").innerHTML = `
                <ul class="list-group list-group-flush bg-transparent">
                    <li class="list-group-item bg-transparent px-0">
                        <span class="fw-bold d-block">Nama Lengkap:</span>
                        ${nama}
                    </li>
                    <li class="list-group-item bg-transparent px-0">
                        <span class="fw-bold d-block">Kelas:</span>
                        ${kelas}
                    </li>
                    <li class="list-group-item bg-transparent px-0">
                        <span class="fw-bold d-block">Tagihan SPP:</span>
                        <span class="text-success fw-bold">Rp ${formattedNominal}</span>
                    </li>
                </ul>
            `;
        } else {
             document.getElementById("jumlahBayar").value = "";
             document.getElementById("infoSiswa").innerHTML = `<p class="text-muted text-center my-4">Pilih siswa untuk melihat detail informasi pembayaran.</p>`;
        }
    }

    siswaSelect.addEventListener("change", updateInfo);

    // Trigger on load if selected
    if (siswaSelect.value) {
        updateInfo();
    }
});
</script>
';
require_once '../../includes/footer.php';
?>
