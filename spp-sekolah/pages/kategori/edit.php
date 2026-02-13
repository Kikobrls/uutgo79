<?php
/**
 * Edit Kategori Keuangan
 * sistem keuangan Sekolah
 */

$page_title = 'Edit Kategori';
require_once '../../includes/header.php';

$errors = [];
$id = isset($_GET['id']) ? sanitize($_GET['id']) : '';

if (empty($id)) {
    header("Location: index.php");
    exit;
}

// Get data
$query = "SELECT * FROM kategori_keuangan WHERE id_kategori = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    setFlash('danger', 'Data kategori tidak ditemukan!');
    header("Location: index.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = sanitize($_POST['nama_kategori']);

    // Validation
    if (empty($nama_kategori))
        $errors[] = "Nama kategori harus diisi!";

    if (empty($errors)) {
        // Check duplicate
        $check = mysqli_query($conn, "SELECT id_kategori FROM kategori_keuangan WHERE nama_kategori = '$nama_kategori' AND id_kategori != '$id'");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = "Kategori dengan nama yang sama sudah ada!";
        } else {
            $query = "UPDATE kategori_keuangan SET nama_kategori = '$nama_kategori' WHERE id_kategori = '$id'";

            if (mysqli_query($conn, $query)) {
                logActivity('Mengubah kategori keuangan', 'kategori_keuangan', $id);
                setFlash('success', 'Kategori berhasil diperbarui!');
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Gagal menyimpan data: " . mysqli_error($conn);
            }
        }
    }
}

require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';
?>



<?php require_once '../../includes/footer.php'; ?>
