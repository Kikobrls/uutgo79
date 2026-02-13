<?php
include 'config/database.php';
$output = "";
$result = mysqli_query($conn, "SHOW TABLES");
while ($row = mysqli_fetch_array($result)) {
    $output .= "Table: " . $row[0] . "\n";
    $cols = mysqli_query($conn, "DESCRIBE " . $row[0]);
    while ($col = mysqli_fetch_array($cols)) {
        $output .= "  - " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }
    $output .= "\n";
}
file_put_contents('db_schema.txt', $output);
echo "Schema saved to db_schema.txt";
?>