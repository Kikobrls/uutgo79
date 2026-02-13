<?php
// Test Transaction Flow
include 'config/database.php';

echo "Starting Transaction Flow Test...\n";

// 1. Create Dummy Transaction (Income)

$tanggal = date('Y-m-d');
$id_kategori = 1; // Assuming category 1 exists, otherwise need to fetch one
$uraian = "Test Income Transaction";
$debit = 100000;
$kredit = 0;
$metode_bayar = 'tunai';
$id_guru = 1; // Assuming guru 1 exists (admin)

// Get a valid category id
$res = mysqli_query($conn, "SELECT id_kategori FROM kategori_keuangan LIMIT 1");
if ($row = mysqli_fetch_assoc($res)) {
    $id_kategori = $row['id_kategori'];
} else {
    die("[ERROR] No category found to test with.\n");
}

// Get a valid guru id
if (!isset($_SESSION['id_guru'])) {
    // Mock session for logActivity
    $_SESSION['id_guru'] = 1;
}
$res = mysqli_query($conn, "SELECT id_guru FROM guru LIMIT 1");
if ($row = mysqli_fetch_assoc($res)) {
    $id_guru = $row['id_guru'];
    $_SESSION['id_guru'] = $id_guru;
}

$query = "INSERT INTO transaksi (tanggal, id_kategori, uraian, debit, kredit, metode_bayar, id_guru) 
          VALUES ('$tanggal', '$id_kategori', '$uraian', '$debit', '$kredit', '$metode_bayar', '$id_guru')";

if (mysqli_query($conn, $query)) {
    echo "[OK] Created transaction\n";
    $id_transaksi = mysqli_insert_id($conn);
} else {
    die("[ERROR] Failed to create transaction: " . mysqli_error($conn) . "\n");
}

// 2. Read Transaction
$result = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = $id_transaksi");
$row = mysqli_fetch_assoc($result);
if ($row && $row['debit'] == 100000 && $row['kredit'] == 0) {
    echo "[OK] Read transaction verified.\n";
} else {
    echo "[ERROR] Read transaction failed or data mismatch.\n";
    print_r($row);
}

// 3. Update Transaction (Change to Expense)
$new_uraian = "Test Expense Transaction Updated";
$debit = 0;
$kredit = 50000;

$query = "UPDATE transaksi SET uraian = '$new_uraian', debit = $debit, kredit = $kredit WHERE id_transaksi = $id_transaksi";
if (mysqli_query($conn, $query)) {
    echo "[OK] Updated transaction to expense.\n";
} else {
    echo "[ERROR] Failed to update transaction: " . mysqli_error($conn) . "\n";
}

// 4. Verify Update
$result = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = $id_transaksi");
$row = mysqli_fetch_assoc($result);
if ($row && $row['kredit'] == 50000 && $row['debit'] == 0) {
    echo "[OK] Update verified.\n";
} else {
    echo "[ERROR] Update verification failed.\n";
    print_r($row);
}

// 5. Delete Transaction
$query = "DELETE FROM transaksi WHERE id_transaksi = $id_transaksi";
if (mysqli_query($conn, $query)) {
    echo "[OK] Deleted transaction.\n";
} else {
    echo "[ERROR] Failed to delete transaction: " . mysqli_error($conn) . "\n";
}

// 6. Verify Deletion
$result = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = $id_transaksi");
if (mysqli_num_rows($result) == 0) {
    echo "[OK] Deletion verified.\n";
} else {
    echo "[ERROR] Deletion verification failed. Record still exists.\n";
}

echo "Test Completed.\n";
?>