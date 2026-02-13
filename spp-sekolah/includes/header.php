<?php
/**
 * Header Template - Sneat Bootstrap 5
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check login
if (!isset($_SESSION['login'])) {
    header("Location: " . (strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? '../../' : '') . "login.php");
    exit;
}

// Determine base path
$depth = substr_count(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']), '/');
$in_pages = strpos($_SERVER['PHP_SELF'], '/pages/') !== false;
$base_url = $in_pages ? '../../' : '';

require_once ($in_pages ? '../../' : '') . 'config/app.php';
require_once ($in_pages ? '../../' : '') . 'config/database.php';

// Sneat assets path (relative from sneat folder)
$sneat_path = $base_url . 'sneat-bootstrap-html-admin-template-free/';

// Get school info
$nama_sekolah = getSetting('nama_sekolah', 'SPP Sekolah');
?>
<!doctype html>

<html lang="id" class="layout-menu-fixed layout-compact" data-assets-path="<?php echo $sneat_path; ?>assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo APP_NAME; ?></title>

    <meta name="description" content="sistem keuangan Sekolah" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $sneat_path; ?>assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="<?php echo $sneat_path; ?>assets/vendor/fonts/iconify-icons.css" />

    <!-- FontAwesome for backward compatibility -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo $sneat_path; ?>assets/vendor/css/core.css" />
    <link rel="stylesheet" href="<?php echo $sneat_path; ?>assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="<?php echo $sneat_path; ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- DataTables CSS (Bootstrap 5) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />

    <!-- Helpers -->
    <script src="<?php echo $sneat_path; ?>assets/vendor/js/helpers.js"></script>

    <!-- Config -->
    <script src="<?php echo $sneat_path; ?>assets/js/config.js"></script>

    <style>
        /* Custom overrides for SPP system */
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .05);
        }

        .btn-icon-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .badge-status {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }

        .table th {
            white-space: nowrap;
            font-weight: 600;
        }

        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
        }

        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading" id="loading">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
