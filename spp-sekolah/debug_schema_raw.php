<?php
header('Content-Type: text/plain');
require_once 'config/database.php';

echo "Tables:\n";
$tables = mysqli_query($conn, "SHOW TABLES");
while ($row = mysqli_fetch_row($tables)) {
    echo "- " . $row[0] . "\n";
}

echo "\nSchema of `kategori_keuangan`:\n";
$result = mysqli_query($conn, "DESCRIBE kategori_keuangan");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} else {
    echo "Error: " . mysqli_error($conn) . "\n";
}
?>