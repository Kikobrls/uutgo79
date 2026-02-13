<?php
ob_start();
/**
 * Data Guru
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$page_title = 'Data Guru';
require_once '../../includes/header.php';

// Check admin level
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    setFlash('danger', 'Anda tidak memiliki akses ke halaman ini!');
    header("Location: ../../index.php");
    exit;
}

require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = sanitize($_GET['delete']);

    // Prevent self-delete
    if ($id == $_SESSION['id_guru']) {
        setFlash('danger', 'Anda tidak dapat menghapus akun sendiri!');
    } else {
        // Check if guru has payments
        $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pembayaran WHERE id_guru = '$id'"));

        if ($check['total'] > 0) {
            // Set to inactive instead of delete
            mysqli_query($conn, "UPDATE guru SET status = 'inactive' WHERE id_guru = '$id'");
            logActivity('Menonaktifkan akun guru', 'guru', $id);
            setFlash('warning', 'Guru memiliki data pembayaran. Status diubah menjadi tidak aktif.');
        } else {
            if (mysqli_query($conn, "DELETE FROM guru WHERE id_guru = '$id'")) {
                logActivity('Menghapus data guru', 'guru', $id);
                setFlash('success', 'Data guru berhasil dihapus!');
            } else {
                setFlash('danger', 'Gagal menghapus data guru!');
            }
        }
    }
    header("Location: index.php");
    exit;
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_guru = isset($_POST['id_guru']) ? sanitize($_POST['id_guru']) : '';
    $username = sanitize($_POST['username']);
    $nama_guru = sanitize($_POST['nama_guru']);
    $level = sanitize($_POST['level']);
    $status = sanitize($_POST['status']);
    $password = $_POST['password'];

    $error = '';

    if (empty($username) || empty($nama_guru)) {
        $error = 'Username dan nama harus diisi!';
    }

    // Check duplicate username
    if (empty($id_guru)) {
        $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_guru FROM guru WHERE username = '$username'"));
        if ($check)
            $error = 'Username sudah digunakan!';
        if (empty($password))
            $error = 'Password harus diisi untuk guru baru!';
    } else {
        $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_guru FROM guru WHERE username = '$username' AND id_guru != '$id_guru'"));
        if ($check)
            $error = 'Username sudah digunakan!';
    }

    if (empty($error)) {
        if (empty($id_guru)) {
            // Add new
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO guru (username, password, nama_guru, level, status)
                      VALUES ('$username', '$hashed_password', '$nama_guru', '$level', '$status')";
            $msg = 'ditambahkan';
        } else {
            // Edit
            $password_sql = '';
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $password_sql = ", password = '$hashed_password'";
            }
            $query = "UPDATE guru SET
                        username = '$username',
                        nama_guru = '$nama_guru',
                        level = '$level',
                        status = '$status'
                        $password_sql
                      WHERE id_guru = '$id_guru'";
            $msg = 'diubah';
        }

        if (mysqli_query($conn, $query)) {
            logActivity("Menyimpan data guru ($msg)", 'guru', $id_guru ?: mysqli_insert_id($conn));
            setFlash('success', "Data guru berhasil $msg!");
        } else {
            setFlash('danger', 'Gagal menyimpan data guru!');
        }
    } else {
        setFlash('danger', $error);
    }

    header("Location: index.php");
    exit;
}

// Get data
$query = "SELECT g.*,
          (SELECT COUNT(*) FROM pembayaran WHERE id_guru = g.id_guru) as jumlah_transaksi
          FROM guru g
          ORDER BY g.level, g.nama_guru";
$result = mysqli_query($conn, $query);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Data Master /</span> Data Guru
        </h4>
        <button type="button" class="btn btn-primary" onclick="tambahGuru()">
            <i class="bx bx-plus me-1"></i> Tambah Guru
        </button>
    </div>

    <!-- Table -->
    <div class="card">
        <h5 class="card-header">Daftar Guru / Admin</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Level</th>
                        <th>Transaksi</th>
                        <th>Last Login</th>
                        <th>Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><code><?php echo htmlspecialchars($row['username']); ?></code></td>
                            <td><strong><?php echo htmlspecialchars($row['nama_guru']); ?></strong></td>
                            <td>
                                <?php if ($row['level'] == 'admin'): ?>
                                    <span class="badge bg-label-danger">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-label-primary">Guru</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo number_format($row['jumlah_transaksi']); ?></td>
                            <td>
                                <small><?php echo $row['last_login'] ? date('d/m/Y H:i', strtotime($row['last_login'])) : '-'; ?></small>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'active'): ?>
                                    <span class="badge bg-label-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-label-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <button type="button" class="btn btn-icon btn-sm btn-outline-warning me-1"
                                        onclick='editGuru(<?php echo json_encode($row); ?>)' title="Edit">
                                        <span class="bx bx-edit-alt"></span>
                                    </button>

                                    <?php if ($row['id_guru'] != $_SESSION['id_guru']): ?>
                                        <button type="button" class="btn btn-icon btn-sm btn-outline-danger"
                                            onclick="confirmDelete('index.php?delete=<?php echo $row['id_guru']; ?>')"
                                            title="Hapus">
                                            <span class="bx bx-trash-alt"></span>
                                        </button>
                                    <?php endif; ?>
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
<div class="modal fade" id="guruModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_guru" id="id_guru">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password <span class="text-danger" id="passReq">*</span></label>
                            <input type="password" name="password" id="password" class="form-control">
                            <small class="text-muted" id="passHelp">Minimal 6 karakter</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_guru" id="nama_guru" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Level <span class="text-danger">*</span></label>
                            <select name="level" id="level" class="form-select" required>
                                <option value="guru">Guru</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>
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
var myModal = new bootstrap.Modal(document.getElementById("guruModal"));

function tambahGuru() {
    document.getElementById("modalTitle").innerText = "Tambah Guru";
    document.getElementById("id_guru").value = "";
    document.getElementById("username").value = "";
    document.getElementById("password").value = "";
    document.getElementById("password").required = true;
    document.getElementById("passReq").style.display = "inline";
    document.getElementById("passHelp").innerText = "Minimal 6 karakter";
    document.getElementById("nama_guru").value = "";
    document.getElementById("level").value = "guru";
    document.getElementById("status").value = "active";

    myModal.show();
}

function editGuru(data) {
    document.getElementById("modalTitle").innerText = "Edit Guru";
    document.getElementById("id_guru").value = data.id_guru;
    document.getElementById("username").value = data.username;
    document.getElementById("password").value = "";
    document.getElementById("password").required = false;
    document.getElementById("passReq").style.display = "none";
    document.getElementById("passHelp").innerText = "Kosongkan jika tidak ingin mengubah password";
    document.getElementById("nama_guru").value = data.nama_guru;
    document.getElementById("level").value = data.level;
    document.getElementById("status").value = data.status;

    myModal.show();
}
</script>
';
require_once '../../includes/footer.php';
?>
