<?php
/**
 * Backup & Restore Database
 * sistem keuangan Sekolah
 */

session_start();

// Define base path for config
require_once '../../config/app.php';
require_once '../../config/database.php';

// Check admin level
if (!isset($_SESSION['login']) || $_SESSION['level'] != 'admin') {
    header("Location: ../../index.php");
    exit;
}

$page_title = 'Backup Database';
$backup_dir = '../../backups/';

// Ensure backup directory exists
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

// Handle backup
if (isset($_POST['backup'])) {
    $tables = [];
    $result = mysqli_query($conn, "SHOW TABLES");

    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }

    $sqlScript = "-- Database Backup\n";
    $sqlScript .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $sqlScript .= "-- Database: " . DB_NAME . "\n\n";
    $sqlScript .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

    foreach ($tables as $table) {
        // Get create table statement
        $result = mysqli_query($conn, "SHOW CREATE TABLE `$table`");
        $row = mysqli_fetch_row($result);

        $sqlScript .= "-- Table: $table\n";
        $sqlScript .= "DROP TABLE IF EXISTS `$table`;\n";
        $sqlScript .= $row[1] . ";\n\n";

        // Get table data
        $result = mysqli_query($conn, "SELECT * FROM `$table`");
        $columnCount = mysqli_num_fields($result);

        while ($row = mysqli_fetch_row($result)) {
            $sqlScript .= "INSERT INTO `$table` VALUES(";
            for ($j = 0; $j < $columnCount; $j++) {
                if (isset($row[$j])) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n", "\\n", $row[$j]);
                    $sqlScript .= '"' . $row[$j] . '"';
                } else {
                    $sqlScript .= 'NULL';
                }
                if ($j < ($columnCount - 1)) {
                    $sqlScript .= ',';
                }
            }
            $sqlScript .= ");\n";
        }
        $sqlScript .= "\n";
    }

    $sqlScript .= "SET FOREIGN_KEY_CHECKS = 1;\n";

    $filename = 'backup_' . date('Y-m-d_His') . '.sql';
    $filepath = $backup_dir . $filename;

    if (file_put_contents($filepath, $sqlScript)) {
        logActivity('Backup database', 'backup', $filename);
        setFlash('success', 'Backup database berhasil! File: ' . $filename);
    } else {
        setFlash('danger', 'Gagal membuat backup!');
    }

    header("Location: backup.php");
    exit;
}

// Handle download
if (isset($_GET['download'])) {
    $filename = basename(sanitize($_GET['download']));
    $filepath = $backup_dir . $filename;

    if (file_exists($filepath) && pathinfo($filepath, PATHINFO_EXTENSION) == 'sql') {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $filename = basename(sanitize($_GET['delete']));
    $filepath = $backup_dir . $filename;

    if (file_exists($filepath) && pathinfo($filepath, PATHINFO_EXTENSION) == 'sql') {
        if (unlink($filepath)) {
            logActivity('Menghapus file backup', 'backup', $filename);
            setFlash('success', 'File backup berhasil dihapus!');
        } else {
            setFlash('danger', 'Gagal menghapus file backup!');
        }
    }

    header("Location: backup.php");
    exit;
}

// Handle restore
if (isset($_POST['restore'])) {
    if (isset($_FILES['backup_file']) && $_FILES['backup_file']['error'] == 0) {
        $file_ext = pathinfo($_FILES['backup_file']['name'], PATHINFO_EXTENSION);

        if ($file_ext == 'sql') {
            $sql = file_get_contents($_FILES['backup_file']['tmp_name']);

            // Execute SQL
            mysqli_multi_query($conn, $sql);

            // Wait for all queries to complete
            do {
                if ($result = mysqli_store_result($conn)) {
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($conn));

            logActivity('Restore database dari file backup', 'backup', $_FILES['backup_file']['name']);
            setFlash('success', 'Database berhasil di-restore!');
        } else {
            setFlash('danger', 'Format file tidak valid! Hanya file .sql yang diperbolehkan.');
        }
    } else {
        setFlash('danger', 'Gagal upload file backup!');
    }

    header("Location: backup.php");
    exit;
}

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Get backup files
$backup_files = [];
if (is_dir($backup_dir)) {
    $files = glob($backup_dir . '*.sql');
    foreach ($files as $file) {
        $backup_files[] = [
            'name' => basename($file),
            'size' => filesize($file),
            'date' => filemtime($file)
        ];
    }
    // Sort by date descending
    usort($backup_files, function ($a, $b) {
        return $b['date'] - $a['date'];
    });
}

// Database stats
$db_size = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
    FROM information_schema.tables
    WHERE table_schema = '" . DB_NAME . "'
"));

$table_count = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM information_schema.tables
    WHERE table_schema = '" . DB_NAME . "'
"));
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Backup & Restore</h4>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">

            <!-- Backup Section -->
            <div class="card mb-4">
                <h5 class="card-header"><i class="bx bx-download me-2"></i>Backup Database</h5>
                <div class="card-body">
                    <p>Backup database akan menyimpan semua data dalam format SQL yang dapat digunakan untuk restore.
                    </p>

                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bx bx-info-circle me-2"></i>
                        <div>
                            <strong>Info Database:</strong><br>
                            Ukuran: <?php echo $db_size['size_mb']; ?> MB |
                            Jumlah Tabel: <?php echo $table_count['total']; ?>
                        </div>
                    </div>

                    <form method="POST" action="">
                        <button type="submit" name="backup" class="btn btn-primary">
                            <i class="bx bx-data me-1"></i> Backup Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <!-- Restore Section -->
            <div class="card mb-4">
                <h5 class="card-header text-warning"><i class="bx bx-upload me-2"></i>Restore Database</h5>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        <i class="bx bx-error me-2"></i>
                        <strong>Peringatan!</strong> Restore database akan menghapus semua data yang ada dan
                        menggantinya dengan data dari file backup. Pastikan Anda sudah membuat backup sebelum melakukan
                        restore.
                    </div>

                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Pilih File Backup (.sql)</label>
                            <input type="file" name="backup_file" class="form-control" accept=".sql" required>
                        </div>
                        <button type="submit" name="restore" class="btn btn-warning"
                            onclick="return confirm('Apakah Anda yakin ingin me-restore database? Semua data saat ini akan dihapus!')">
                            <i class="bx bx-history me-1"></i> Restore Database
                        </button>
                    </form>
                </div>
            </div>

            <!-- Backup Files List -->
            <div class="card mb-4">
                <h5 class="card-header"><i class="bx bx-folder me-2"></i>Daftar File Backup</h5>
                <div class="card-body">
                    <?php if (count($backup_files) > 0): ?>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama File</th>
                                        <th>Ukuran</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($backup_files as $file): ?>
                                        <tr>
                                            <td><code><?php echo htmlspecialchars($file['name']); ?></code></td>
                                            <td><?php echo number_format($file['size'] / 1024, 2); ?> KB</td>
                                            <td><?php echo date('d/m/Y H:i', $file['date']); ?></td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="?download=<?php echo urlencode($file['name']); ?>"
                                                        class="btn btn-sm btn-icon btn-outline-info me-1" title="Download">
                                                        <i class="bx bx-download"></i>
                                                    </a>
                                                    <a href="javascript:void(0);"
                                                        onclick="confirmDelete('?delete=<?php echo urlencode($file['name']); ?>')"
                                                        class="btn btn-sm btn-icon btn-outline-danger" title="Hapus">
                                                        <i class="bx bx-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <p class="text-muted mb-0">Belum ada file backup.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Menu Pengaturan</div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="general.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bx bx-buildings me-2"></i> Umum
                        </a>
                        <a href="email.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bx bx-envelope me-2"></i> Email SMTP
                        </a>
                        <a href="whatsapp.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bx bxl-whatsapp me-2"></i> WhatsApp API
                        </a>
                        <a href="payment.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bx bx-credit-card me-2"></i> Payment Gateway
                        </a>
                        <a href="backup.php"
                            class="list-group-item list-group-item-action active d-flex align-items-center">
                            <i class="bx bx-data me-2"></i> Backup Database
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><i class="bx bx-bulb me-2"></i>Tips</div>
                <div class="card-body">
                    <ul class="mb-0 ps-3">
                        <li class="mb-2">Lakukan backup secara rutin (minimal seminggu sekali).</li>
                        <li class="mb-2">Simpan file backup di tempat yang aman.</li>
                        <li class="mb-2">Test restore di lingkungan testing sebelum production.</li>
                        <li>Jangan hapus backup yang masih diperlukan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
