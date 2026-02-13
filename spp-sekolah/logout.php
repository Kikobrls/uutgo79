<?php
/**
 * Logout
 * sistem keuangan Sekolah
 */

session_start();
require_once 'config/database.php';

// Log activity
if (isset($_SESSION['id_guru'])) {
    logActivity('Logout dari sistem', 'guru', $_SESSION['id_guru']);
}

// Destroy session
session_unset();
session_destroy();

// Redirect to login
header("Location: login.php");
exit;
?>
