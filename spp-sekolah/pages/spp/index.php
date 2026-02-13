<?php
/**
 * Data SPP
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = sanitize($_GET['delete']);
    // Check usage
    $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM santri WHERE id_spp = '$id'"));

    if ($check['total'] > 0) {
        setFlash('danger', 'SPP tidak dapat dihapus karena digunakan oleh santri!');
    } else {
        if (mysqli_query($conn, "DELETE FROM spp WHERE id_spp = '$id'")) {
            logActivity('Menghapus data SPP', 'spp', $id);
            setFlash('success', 'Data SPP berhasil dihapus!');
        } else {
            setFlash('danger', 'Gagal menghapus data SPP!');
        }
    }
    header("Location: index.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_spp = isset($_POST['id_spp']) ? sanitize($_POST['id_spp']) : '';
    $tahun = sanitize($_POST['tahun']);
    $nominal = str_replace(['.', ','], '', $_POST['nominal']); // Remove formatting

    if (empty($tahun) || empty($nominal)) {
        setFlash('danger', 'Tahun dan nominal harus diisi!');
    } else {
        if (empty($id_spp)) {
            // Add
            $query = "INSERT INTO spp (tahun, nominal) VALUES ('$tahun', '$nominal')";
            $msg = 'ditambahkan';
        } else {
            // Edit
            $query = "UPDATE spp SET tahun = '$tahun', nominal = '$nominal' WHERE id_spp = '$id_spp'";
            $msg = 'diubah';
        }

        if (mysqli_query($conn, $query)) {
            logActivity("Menyimpan data SPP ($msg)", 'spp', $id_spp ?: mysqli_insert_id($conn));
            setFlash('success', "Data SPP berhasil $msg!");
        } else {
            setFlash('danger', 'Gagal menyimpan data SPP!');
        }
    }
    header("Location: index.php");
    exit;
}

$page_title = 'Data SPP';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Get data
$query = "SELECT sp.*, COUNT(s.id) as jumlah_santri
          FROM spp sp
          LEFT JOIN santri s ON sp.id_spp = s.id_spp
          GROUP BY sp.id_spp
          ORDER BY sp.tahun DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Data Master /</span> SPP
        </h4>
        <button type="button" class="btn btn-primary" onclick="tambahSpp()">
            <i class="bx bx-plus me-1"></i> Tambah SPP
        </button>
    </div>

    <!-- Table -->
    <div class="card">
        <h5 class="card-header">Daftar Tarif SPP</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tahun Ajaran</th>
                        <th>Nominal</th>
                        <th>Jumlah Santri</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['tahun']); ?></strong></td>
                            <td class="text-end fw-bold text-success">Rp
                                <?php echo number_format($row['nominal'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="badge bg-label-info"><?php echo $row['jumlah_santri']; ?> Santri</span>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <button type="button" class="btn btn-icon btn-sm btn-outline-warning me-1"
                                        onclick='editSpp(<?php echo json_encode($row); ?>)' title="Edit">
                                        <span class="bx bx-edit-alt"></span>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-sm btn-outline-danger"
                                        onclick="confirmDelete('index.php?delete=<?php echo $row['id_spp']; ?>')"
                                        title="Hapus">
                                        <span class="bx bx-trash-alt"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="sppModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_spp" id="id_spp">
                    <div class="mb-3">
                        <label for="tahun" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                        <input type="number" id="tahun" name="tahun" class="form-control" placeholder="YYYY" min="2000"
                            max="2100" required>
                    </div>
                    <div class="mb-3">
                        <label for="nominal" class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
                        <input type="number" id="nominal" name="nominal" class="form-control" placeholder="0" min="0"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$extra_js = '
<script>
function tambahSpp() {
    document.getElementById("modalTitle").innerText = "Tambah SPP";
    document.getElementById("id_spp").value = "";
    document.getElementById("tahun").value = new Date().getFullYear();
    document.getElementById("nominal").value = "";

    var myModal = new bootstrap.Modal(document.getElementById("sppModal"));
    myModal.show();
}

function editSpp(data) {
    document.getElementById("modalTitle").innerText = "Edit SPP";
    document.getElementById("id_spp").value = data.id_spp;
    document.getElementById("tahun").value = data.tahun;
    document.getElementById("nominal").value = data.nominal;

    var myModal = new bootstrap.Modal(document.getElementById("sppModal"));
    myModal.show();
}
</script>
';
require_once '../../includes/footer.php';
?>
