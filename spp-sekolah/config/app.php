<?php
/**
 * Application Configuration
 * sistem keuangan Sekolah
 */

// Application settings
define('APP_NAME', 'sistem keuangan');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/spp-sekolah');

// Time zone
date_default_timezone_set('Asia/Jakarta');

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
}

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Paths
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_PATH', BASE_PATH . '/assets');
define('UPLOADS_PATH', BASE_PATH . '/uploads');

// Months array (Indonesian)
$bulan_indo = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
];

// Alert types
$alert_types = ['success', 'danger', 'warning', 'info'];

/**
 * Convert date to Indonesian format
 */
function tanggalIndo($date)
{
    global $bulan_indo;
    $timestamp = strtotime($date);
    $day = date('d', $timestamp);
    $month = $bulan_indo[date('n', $timestamp) - 1];
    $year = date('Y', $timestamp);
    return "$day $month $year";
}

/**
 * Get Indonesian month name
 */
function bulanIndo($month_number)
{
    global $bulan_indo;
    return $bulan_indo[$month_number - 1];
}

/**
 * Flash message
 */
function setFlash($type, $message)
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Redirect with flash
 */
function redirect($url, $type = null, $message = null)
{
    if ($type && $message) {
        setFlash($type, $message);
    }
    header("Location: $url");
    exit;
}

/**
 * Check if request is AJAX
 */
function isAjax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * JSON response
 */
function jsonResponse($data, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Get base URL
 */
function baseUrl($path = '')
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['SCRIPT_NAME']);

    // Find the root of the application
    $root = '';
    if (strpos($script, '/spp-sekolah') !== false) {
        $pos = strpos($script, '/spp-sekolah');
        $root = substr($script, 0, $pos + strlen('/spp-sekolah'));
    } else {
        $root = $script;
    }

    return $protocol . '://' . $host . $root . '/' . ltrim($path, '/');
}

/**
 * Get assets URL
 */
function assetUrl($path = '')
{
    return baseUrl('assets/' . ltrim($path, '/'));
}


?>
