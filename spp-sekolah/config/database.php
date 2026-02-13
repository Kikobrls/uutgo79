<?php
/**
 * Database Configuration
 * sistem keuangan Sekolah
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'keuangan_tpq');

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset to utf8mb4
mysqli_set_charset($conn, "utf8mb4");

/**
 * Get setting value from database
 */
function getSetting($key, $default = '')
{
    global $conn;
    $key = mysqli_real_escape_string($conn, $key);
    $query = "SELECT setting_value FROM settings WHERE setting_key = '$key'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['setting_value'];
    }
    return $default;
}

/**
 * Update setting value
 */
function updateSetting($key, $value)
{
    global $conn;
    $key = mysqli_real_escape_string($conn, $key);
    $value = mysqli_real_escape_string($conn, $value);

    $query = "UPDATE settings SET setting_value = '$value' WHERE setting_key = '$key'";
    return mysqli_query($conn, $query);
}

/**
 * Get all settings by group
 */
function getSettingsByGroup($group)
{
    global $conn;
    $group = mysqli_real_escape_string($conn, $group);
    $query = "SELECT * FROM settings WHERE setting_group = '$group' ORDER BY id";
    $result = mysqli_query($conn, $query);

    $settings = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $settings[$row['setting_key']] = $row;
        }
    }
    return $settings;
}

/**
 * Log activity
 */
function logActivity($aktivitas, $tabel = 'NULL', $data_id = 'NULL')
{
    global $conn;

    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $aktivitas = mysqli_real_escape_string($conn, $aktivitas);
    $tabel = $tabel === 'NULL' ? 'NULL' : "'" . mysqli_real_escape_string($conn, $tabel) . "'";
    $data_id = $data_id === 'NULL' ? 'NULL' : "'" . mysqli_real_escape_string($conn, $data_id) . "'";
    $id_guru = isset($_SESSION['id_guru']) ? $_SESSION['id_guru'] : 'NULL';

    // Convert to null string if empty
    if ($id_guru === '')
        $id_guru = 'NULL';

    $query = "INSERT INTO log_aktivitas (id_guru, aktivitas, tabel, data_id, ip_address, user_agent)
              VALUES ($id_guru, '$aktivitas', $tabel, $data_id, '$ip_address', '$user_agent')";

    mysqli_query($conn, $query);
}

/**
 * Format currency
 */
function formatRupiah($number)
{
    return 'Rp ' . number_format($number, 0, ',', '.');
}

/**
 * Sanitize input
 */
function sanitize($data)
{
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}
?>
