<?php
// Verify files exist
$files = [
    'pages/transaksi/index.php',
    'pages/transaksi/add.php',
    'pages/transaksi/edit.php',
    'pages/transaksi/process.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "[OK] $file exists.\n";
        // Check for syntax errors
        exec("php -l $file", $output, $return_var);
        if ($return_var === 0) {
            echo "[OK] $file syntax is valid.\n";
        } else {
            echo "[ERROR] $file syntax is invalid!\n";
            print_r($output);
        }
    } else {
        echo "[ERROR] $file does not exist!\n";
    }
}

// Check if old directories exist
if (is_dir('pages/pemasukkan')) {
    echo "[WARNING] pages/pemasukkan still exists.\n";
} else {
    echo "[OK] pages/pemasukkan removed.\n";
}

if (is_dir('pages/pengeluaran')) {
    echo "[WARNING] pages/pengeluaran still exists.\n";
} else {
    echo "[OK] pages/pengeluaran removed.\n";
}
?>