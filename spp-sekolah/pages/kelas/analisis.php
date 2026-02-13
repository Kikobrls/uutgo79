<?php
/**
 * Analisis Data Kelas
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$page_title = 'Analisis Data Kelas';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// ==========================================
// QUERY: Statistics
// ==========================================
$stats_query = "
    SELECT
        COUNT(DISTINCT k.id_kelas) as total_kelas,
        COUNT(DISTINCT s.id) as total_santri,
        ROUND(COUNT(DISTINCT s.id) / NULLIF(COUNT(DISTINCT k.id_kelas), 0), 2) as rata_rata_santri
    FROM kelas k
    LEFT JOIN santri s ON k.id_kelas = s.id_kelas
";
$stats_result = mysqli_fetch_assoc(mysqli_query($conn, $stats_query));

// ==========================================
// QUERY: Students per Class for Chart
// ==========================================
$students_per_class = "
    SELECT
        k.nama_kelas,
        COUNT(s.id) as jumlah_santri
    FROM kelas k
    LEFT JOIN santri s ON k.id_kelas = s.id_kelas
    GROUP BY k.id_kelas
    ORDER BY k.nama_kelas
";
$class_result = mysqli_query($conn, $students_per_class);
$class_data = [];
while ($row = mysqli_fetch_assoc($class_result)) {
    $class_data[] = $row;
}

// ==========================================
// QUERY: Payment per Class Table
// ==========================================
$payment_per_class = "
    SELECT
        k.nama_kelas,
        COUNT(DISTINCT s.id) as total_santri,
        COUNT(DISTINCT p.id_santri) as santri_bayar,
        ROUND((COUNT(DISTINCT p.id_santri) / NULLIF(COUNT(DISTINCT s.id), 0)) * 100, 2) as persentase_bayar
    FROM kelas k
    LEFT JOIN santri s ON k.id_kelas = s.id_kelas
    LEFT JOIN pembayaran p ON s.id = p.id_santri
    GROUP BY k.id_kelas
    ORDER BY persentase_bayar DESC
";
$payment_class_result = mysqli_query($conn, $payment_per_class);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Kelas /</span> Analisis Data
        </h4>
        <a href="index.php" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-lg-4 col-md-12 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                             <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-buildings"></i></span>
                        </div>
                    </div>
                    <span>Total Kelas</span>
                    <h3 class="card-title text-nowrap mb-1"><?php echo $stats_result['total_kelas']; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-user"></i></span>
                        </div>
                    </div>
                    <span>Total Santri</span>
                    <h3 class="card-title text-nowrap mb-1"><?php echo $stats_result['total_santri']; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-group"></i></span>
                        </div>
                    </div>
                    <span>Rata-rata Santri/Kelas</span>
                    <h3 class="card-title text-nowrap mb-1"><?php echo $stats_result['rata_rata_santri']; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Jumlah Santri per Kelas</h5>
                </div>
                <div class="card-body">
                    <canvas id="studentsPerClassChart" style="min-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title m-0">Statistik Pembayaran per Kelas</h5>
                </div>
                <div class="table-responsive text-nowrap h-100">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Santri</th>
                                <th>Sudah Bayar</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($payment_class_result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($payment_class_result)): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row['nama_kelas']); ?></strong></td>
                                    <td><?php echo $row['total_santri']; ?></td>
                                    <td><?php echo $row['santri_bayar']; ?></td>
                                    <td>
                                        <?php
                                        $percent = $row['persentase_bayar'];
                                        $cls = $percent > 75 ? 'bg-success' : ($percent > 40 ? 'bg-warning' : 'bg-danger');
                                        ?>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar <?php echo $cls; ?>" role="progressbar" style="width: <?php echo $percent; ?>%" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="fw-semibold"><?php echo $percent; ?>%</small>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Prepare data for charts
$class_labels = json_encode(array_column($class_data, 'nama_kelas'));
$class_values = json_encode(array_column($class_data, 'jumlah_santri'));

$extra_js = "
<script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentsCtx = document.getElementById('studentsPerClassChart').getContext('2d');
    new Chart(studentsCtx, {
        type: 'bar',
        data: {
            labels: $class_labels,
            datasets: [{
                label: 'Jumlah Santri',
                data: $class_values,
                backgroundColor: 'rgba(105, 108, 255, 0.7)',
                borderColor: 'rgba(105, 108, 255, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
";

require_once '../../includes/footer.php';
?>
