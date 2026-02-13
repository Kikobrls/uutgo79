<?php
/**
 * Data Kelas
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
    $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM santri WHERE id_kelas = '$id'"));
    if ($check['total'] > 0) {
        setFlash('danger', 'Kelas tidak dapat dihapus karena memiliki data santri!');
    } else {
        if (mysqli_query($conn, "DELETE FROM kelas WHERE id_kelas = '$id'")) {
            logActivity('Menghapus data kelas', 'kelas', $id);
            setFlash('success', 'Data kelas berhasil dihapus!');
        } else {
            setFlash('danger', 'Gagal menghapus data kelas!');
        }
    }
    header("Location: index.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kelas = isset($_POST['id_kelas']) ? sanitize($_POST['id_kelas']) : '';
    $nama_kelas = sanitize($_POST['nama_kelas']);

    if (empty($nama_kelas)) {
        setFlash('danger', 'Nama kelas harus diisi!');
    } else {
        if (empty($id_kelas)) {
            // Add
            $query = "INSERT INTO kelas (nama_kelas) VALUES ('$nama_kelas')";
            $msg = 'ditambahkan';
        } else {
            // Edit
            $query = "UPDATE kelas SET nama_kelas = '$nama_kelas' WHERE id_kelas = '$id_kelas'";
            $msg = 'diubah';
        }

        if (mysqli_query($conn, $query)) {
            logActivity("Menyimpan data kelas ($msg)", 'kelas', $id_kelas ?: mysqli_insert_id($conn));
            setFlash('success', "Data kelas berhasil $msg!");
        } else {
            setFlash('danger', 'Gagal menyimpan data kelas!');
        }
    }
    header("Location: index.php");
    exit;
}

$page_title = 'Data Kelas';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Get data
$query = "SELECT k.*, COUNT(s.id) as jumlah_santri
          FROM kelas k
          LEFT JOIN santri s ON k.id_kelas = s.id_kelas
          GROUP BY k.id_kelas
          ORDER BY k.nama_kelas";
$result = mysqli_query($conn, $query);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Data Master /</span> Kelas
        </h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kelasModal"
            onclick="resetForm()">
            <i class="bx bx-plus me-1"></i> Tambah Kelas
        </button>
    </div>

    <!-- Table -->
    <div class="card">
        <h5 class="card-header">Daftar Kelas</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Kelas</th>
                        <th>Jumlah Santri</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nama_kelas']); ?></strong></td>
                            <td>
                                <span class="badge bg-label-info"><?php echo $row['jumlah_santri']; ?> Santri</span>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <button type="button" class="btn btn-icon btn-sm btn-outline-warning me-1"
                                        onclick='editKelas(<?php echo json_encode($row); ?>)' title="Edit">
                                        <span class="bx bx-edit-alt"></span>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-sm btn-outline-danger"
                                        onclick="confirmDelete('index.php?delete=<?php echo $row['id_kelas']; ?>')"
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
<div class="modal fade" id="kelasModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_kelas" id="id_kelas">
                    <div class="mb-3">
                        <label for="nama_kelas" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" id="nama_kelas" name="nama_kelas" class="form-control"
                            placeholder="Contoh: X RPL 1" required>
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
function resetForm() {
    document.getElementById("modalTitle").innerText = "Tambah Kelas";
    document.getElementById("id_kelas").value = "";
    document.getElementById("nama_kelas").value = "";
}

function editKelas(data) {
    document.getElementById("modalTitle").innerText = "Edit Kelas";
    document.getElementById("id_kelas").value = data.id_kelas;
    document.getElementById("nama_kelas").value = data.nama_kelas;

    var myModal = new bootstrap.Modal(document.getElementById("kelasModal"));
    myModal.show();
}
</script>
';
require_once '../../includes/footer.php';
?>
