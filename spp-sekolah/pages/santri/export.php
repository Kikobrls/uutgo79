<?php
/**
 * Export Santri Data to CSV
 * sistem keuangan Sekolah
 */

require_once '../../config/database.php';

// Check if user is logged in
session_start();
if (!isset($_SESSION['level'])) {
    header("Location: ../../login.php");
    exit;
}

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="data_santri_' . date('Y-m-d') . '.csv"');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, array('No', 'Nama Santri', 'Tempat Lahir', 'Alamat', 'No Telp Wali', 'Kelas', 'Tahun SPP', 'Nominal SPP', 'Status'));

// Fetch student data joined with class and SPP details
$query = "SELECT s.*, k.nama_kelas, sp.tahun as tahun_spp, sp.nominal
          FROM santri s
          JOIN kelas k ON s.id_kelas = k.id_kelas
          LEFT JOIN spp sp ON s.id_spp = sp.id_spp
          ORDER BY k.nama_kelas, s.nama";
$result = mysqli_query($conn, $query);

// Loop over the rows, outputting them
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $lineData = array(
        $no++,
        $row['nama'],
        $row['tempat_lahir'] ?? '-',
        $row['alamat'] ?? '-',
        "'" . ($row['telp_wali'] ?? '-'),
        $row['nama_kelas'],
        $row['tahun_spp'] ?? '-',
        $row['nominal'] ?? 0,
        ucfirst($row['status'])
    );
    fputcsv($output, $lineData);
}

fclose($output);
exit;
