<?php
require_once 'config/database.php';

echo "Current DB: " . mysqli_fetch_row(mysqli_query($conn, "SELECT DATABASE()"))[0] . "\n";

$sql = "SELECT * FROM kategori_keuangan ORDER BY jenis, nama_kategori ASC";
echo "Executing: $sql\n";

if ($result = mysqli_query($conn, $sql)) {
    echo "Success! Rows: " . mysqli_num_rows($result) . "\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['nama_kategori'] . " (" . $row['jenis'] . ")\n";
    }
} else {
    echo "Error: " . mysqli_error($conn) . "\n";
}
?>