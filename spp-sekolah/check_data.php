<?php
include 'config/database.php';
$result = mysqli_query($conn, "SELECT count(*) as count FROM transaksi");
$row = mysqli_fetch_assoc($result);
echo "Transaksi count: " . $row['count'] . "\n";

if ($row['count'] > 0) {
    $result = mysqli_query($conn, "SELECT * FROM transaksi LIMIT 5");
    while ($r = mysqli_fetch_assoc($result)) {
        print_r($r);
    }
}
?>