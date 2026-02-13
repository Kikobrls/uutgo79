<?php
/**
 * Process Transaksi
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

// Check login
if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

$action = $_GET['action'] ?? '';

// Add Transaksi
if ($action == 'add') {
    $tanggal = sanitize($_POST['tanggal']);
    $jenis = sanitize($_POST['jenis']);
    $id_kategori = sanitize($_POST['id_kategori']);
    $uraian = sanitize($_POST['uraian']);
    $nominal = sanitize($_POST['nominal']);
    $metode_bayar = sanitize($_POST['metode_bayar']);

    // Set Debit/Kredit
    $debit = 0;
    $kredit = 0;

    if ($jenis == 'pemasukkan') {
        $debit = $nominal;
    } else {
        $kredit = $nominal;
    }

    $id_guru = $_SESSION['id_guru'];

    $query = "INSERT INTO transaksi (tanggal, id_kategori, uraian, debit, kredit, metode_bayar, id_guru)
              VALUES ('$tanggal', '$id_kategori', '$uraian', '$debit', '$kredit', '$metode_bayar', '$id_guru')";

    if (mysqli_query($conn, $query)) {
        $new_id = mysqli_insert_id($conn);
        logActivity('Menambah data transaksi', 'transaksi', $new_id);
        setFlash('success', 'Data transaksi berhasil ditambahkan!');
    } else {
        setFlash('danger', 'Gagal menambah data transaksi: ' . mysqli_error($conn));
    }

    header("Location: index.php");
    exit;
}

// Edit Transaksi
elseif ($action == 'edit') {
    $id_transaksi = sanitize($_POST['id_transaksi']);
    $tanggal = sanitize($_POST['tanggal']);
    $jenis = sanitize($_POST['jenis']); // From radio button: pemasukkan or pengeluaran
    $id_kategori = sanitize($_POST['id_kategori']);
    $uraian = sanitize($_POST['uraian']);
    $nominal = sanitize($_POST['nominal']);
    $metode_bayar = sanitize($_POST['metode_bayar']);

    // Set Debit/Kredit
    $debit = 0;
    $kredit = 0;

    if ($jenis == 'pemasukkan') {
        $debit = $nominal;
    } else {
        $kredit = $nominal;
    }

    $query = "UPDATE transaksi SET
              tanggal = '$tanggal',
              id_kategori = '$id_kategori',
              uraian = '$uraian',
              debit = '$debit',
              kredit = '$kredit',
              metode_bayar = '$metode_bayar',
              updated_at = CURRENT_TIMESTAMP
              WHERE id_transaksi = '$id_transaksi'";

    if (mysqli_query($conn, $query)) {
        logActivity('Mengubah data transaksi', 'transaksi', $id_transaksi);
        setFlash('success', 'Data transaksi berhasil diperbarui!');
    } else {
        setFlash('danger', 'Gagal memperbarui data transaksi: ' . mysqli_error($conn));
    }

    header("Location: index.php");
    exit;
}

// Delete Transaksi
elseif ($action == 'delete') {
    $id = sanitize($_GET['id']);

    $query = "DELETE FROM transaksi WHERE id_transaksi = '$id'";

    if (mysqli_query($conn, $query)) {
        logActivity('Menghapus data transaksi', 'transaksi', $id);
        setFlash('success', 'Data transaksi berhasil dihapus!');
    } else {
        setFlash('danger', 'Gagal menghapus data transaksi: ' . mysqli_error($conn));
    }

    header("Location: index.php");
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>
