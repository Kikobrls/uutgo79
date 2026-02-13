<?php
require_once 'config/database.php';

echo "<h2>Database Migration</h2>";

// 1. Check and Add 'jenis' column to 'kategori_keuangan'
$check = mysqli_query($conn, "SHOW COLUMNS FROM kategori_keuangan LIKE 'jenis'");
if (mysqli_num_rows($check) == 0) {
    $sql = "ALTER TABLE kategori_keuangan ADD COLUMN jenis ENUM('masuk', 'keluar', 'keduanya') NOT NULL DEFAULT 'masuk' AFTER nama_kategori";
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green'>[SUCCESS] Added column 'jenis' to table 'kategori_keuangan'.</p>";
    } else {
        echo "<p style='color:red'>[ERROR] Failed to add column 'jenis': " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color:orange'>[INFO] Column 'jenis' already exists in 'kategori_keuangan'.</p>";
}

// 2. Check and Add 'id_kategori' column to 'transaksi' if missing (just in case)
$check_trx = mysqli_query($conn, "SHOW COLUMNS FROM transaksi LIKE 'id_kategori'");
if (mysqli_num_rows($check_trx) == 0) {
    $sql = "ALTER TABLE transaksi ADD COLUMN id_kategori INT(11) NULL AFTER tanggal";
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green'>[SUCCESS] Added column 'id_kategori' to table 'transaksi'.</p>";
    } else {
        echo "<p style='color:red'>[ERROR] Failed to add column 'id_kategori': " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color:orange'>[INFO] Column 'id_kategori' already exists in 'transaksi'.</p>";
}

echo "<p>Done.</p>";
echo "<a href='index.php'>Go to Dashboard</a>";
?>