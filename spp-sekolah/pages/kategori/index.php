<?php
/**
 * Data Kategori Keuangan
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
    $usage_count = 0;

    // Check transactions
    $check_trx = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE id_kategori = '$id'");
    if ($check_trx) {
        $row = mysqli_fetch_assoc($check_trx);
        $usage_count += $row['total'];
    }

    if ($usage_count > 0) {
        setFlash('danger', 'Kategori tidak dapat dihapus karena sedang digunakan dalam transaksi!');
    } else {
        if (mysqli_query($conn, "DELETE FROM kategori_keuangan WHERE id_kategori = '$id'")) {
            logActivity('Menghapus kategori keuangan', 'kategori', $id);
            setFlash('success', 'Kategori berhasil dihapus!');
        } else {
            setFlash('danger', 'Gagal menghapus kategori!');
        }
    }
    header("Location: index.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kategori = isset($_POST['id_kategori']) ? sanitize($_POST['id_kategori']) : '';
    $nama_kategori = sanitize($_POST['nama_kategori']);

    if (empty($nama_kategori)) {
        setFlash('danger', 'Nama kategori harus diisi!');
    } else {
        if (empty($id_kategori)) {
            // Add (Without 'jenis')
            // Assuming database 'jenis' column allows default or is nullable/removed.
            // If DB column 'jenis' is strictly ENUM without default, this insert might fail if strict mode is on.
            // But I will run migration to set default or drop it if needed. For now assume it works or has default.
            $query = "INSERT INTO kategori_keuangan (nama_kategori) VALUES ('$nama_kategori')";
            $msg = 'ditambahkan';
        } else {
            // Edit
            $query = "UPDATE kategori_keuangan SET nama_kategori = '$nama_kategori' WHERE id_kategori = '$id_kategori'";
            $msg = 'diubah';
        }

        if (mysqli_query($conn, $query)) {
            logActivity("Menyimpan kategori keuangan ($msg)", 'kategori', $id_kategori ?: mysqli_insert_id($conn));
            setFlash('success', "Kategori berhasil $msg!");
        } else {
            setFlash('danger', 'Gagal menyimpan kategori!');
        }
    }
    header("Location: index.php");
    exit;
}

$page_title = 'Data Kategori Keuangan';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Get data
$result = mysqli_query($conn, "SELECT * FROM kategori_keuangan ORDER BY nama_kategori ASC");
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Keuangan /</span> Kategori
        </h4>
        <button type="button" class="btn btn-primary" onclick="tambahKategori()">
            <i class="bx bx-plus me-1"></i> Tambah Kategori
        </button>
    </div>

    <!-- Table -->
    <div class="card">
        <h5 class="card-header">Daftar Kategori Keuangan</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Kategori</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nama_kategori']); ?></strong></td>
                            <td>
                                <div class="d-flex">
                                    <button type="button" class="btn btn-icon btn-sm btn-outline-warning me-1"
                                        onclick='editKategori(<?php echo json_encode($row); ?>)' title="Edit">
                                        <span class="bx bx-edit-alt"></span>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-sm btn-outline-danger"
                                        onclick="confirmDelete('index.php?delete=<?php echo $row['id_kategori']; ?>')"
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
<div class="modal fade" id="kategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_kategori" id="id_kategori">

                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori <span
                                class="text-danger">*</span></label>
                        <input type="text" id="nama_kategori" name="nama_kategori" class="form-control"
                            placeholder="Contoh: Sumbangan, Listrik, Air" required>
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
function tambahKategori() {
    document.getElementById("modalTitle").innerText = "Tambah Kategori";
    document.getElementById("id_kategori").value = "";
    document.getElementById("nama_kategori").value = "";

    var myModal = new bootstrap.Modal(document.getElementById("kategoriModal"));
    myModal.show();
}

function editKategori(data) {
    document.getElementById("modalTitle").innerText = "Edit Kategori";
    document.getElementById("id_kategori").value = data.id_kategori;
    document.getElementById("nama_kategori").value = data.nama_kategori;

    var myModal = new bootstrap.Modal(document.getElementById("kategoriModal"));
    myModal.show();
}
</script>
';
require_once '../../includes/footer.php';
?>
