<?php
require_once 'config/database.php';

echo "<h2>Database Migration: Remove 'jenis'</h2>";

// Remove 'jenis' column from 'kategori_keuangan'
$check = mysqli_query($conn, "SHOW COLUMNS FROM kategori_keuangan LIKE 'jenis'");
if (mysqli_num_rows($check) > 0) {
    // Drop column
    // Note: If you want to keep the data but just not use it, we could skip this.
    // But user said "saya tidak mau ada jenis nya" implies removing it.
    // I will DROP it to be clean.
    $sql = "ALTER TABLE kategori_keuangan DROP COLUMN jenis";
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green'>[SUCCESS] Dropped column 'jenis' from table 'kategori_keuangan'.</p>";
    } else {
        echo "<p style='color:red'>[ERROR] Failed to drop column 'jenis': " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color:orange'>[INFO] Column 'jenis' already removed from 'kategori_keuangan'.</p>";
}

echo "<p>Done.</p>";
echo "<a href='index.php'>Go to Dashboard</a>";
?>