<?php
/**
 * Dashboard
 * sistem keuangan Sekolah
 */

$page_title = 'Dashboard';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/topbar.php';

// Statistics queries
$total_santri = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM santri WHERE status = 'active'"));
$total_kelas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kelas"));
$total_guru = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM guru WHERE status = 'active'"));

// Payment statistics
$pembayaran_bulan_ini = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_bayar), 0) as total, COUNT(*) as jumlah
    FROM pembayaran
    WHERE MONTH(tgl_bayar) = MONTH(CURRENT_DATE())
    AND YEAR(tgl_bayar) = YEAR(CURRENT_DATE())
"));

$pembayaran_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_bayar), 0) as total, COUNT(*) as jumlah
    FROM pembayaran
    WHERE DATE(tgl_bayar) = CURDATE()
"));

$pembayaran_tahun_ini = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_bayar), 0) as total, COUNT(*) as jumlah
    FROM pembayaran
    WHERE YEAR(tgl_bayar) = YEAR(CURRENT_DATE())
"));

// Monthly payment data for chart
$data_bulanan = [];
for ($i = 1; $i <= 12; $i++) {
    $result = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT COALESCE(SUM(jumlah_bayar), 0) as total
        FROM pembayaran
        WHERE MONTH(tgl_bayar) = $i AND YEAR(tgl_bayar) = YEAR(CURRENT_DATE())
    "));
    $data_bulanan[] = $result['total'];
}

// Recent payments
$recent_payments = mysqli_query($conn, "
    SELECT p.*, s.nama as nama_santri, k.nama_kelas
    FROM pembayaran p
    JOIN santri s ON p.id_santri = s.id
    JOIN kelas k ON s.id_kelas = k.id_kelas
    ORDER BY p.created_at DESC
    LIMIT 10
");

// Payment by class
$payment_by_class = mysqli_query($conn, "
    SELECT k.nama_kelas, COALESCE(SUM(p.jumlah_bayar), 0) as total
    FROM kelas k
    LEFT JOIN santri s ON k.id_kelas = s.id_kelas
    LEFT JOIN pembayaran p ON s.id = p.id_santri AND YEAR(p.tgl_bayar) = YEAR(CURRENT_DATE())
    GROUP BY k.id_kelas
    ORDER BY total DESC
    LIMIT 5
");

$class_labels = [];
$class_data = [];
while ($row = mysqli_fetch_assoc($payment_by_class)) {
    $class_labels[] = $row['nama_kelas'];
    $class_data[] = $row['total'];
}
?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Beranda /</span> Dashboard
        </h4>
        <a href="pages/laporan/index.php" class="btn btn-primary btn-sm">
            <i class="bx bx-download me-1"></i> Generate Laporan
        </a>
    </div>

    <!-- Stats Cards Row -->
    <div class="row">
        <!-- Total Santri -->
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Total Santri</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2"><?php echo number_format($total_santri['total']); ?></h4>
                            </div>
                            <small class="text-body-secondary">Santri aktif</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-user bx-md"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembayaran Bulan Ini -->
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Bulan Ini</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2"><?php echo formatRupiah($pembayaran_bulan_ini['total']); ?></h4>
                            </div>
                            <small class="text-body-secondary"><?php echo $pembayaran_bulan_ini['jumlah']; ?>
                                transaksi</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-wallet bx-md"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembayaran Hari Ini -->
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Hari Ini</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2"><?php echo formatRupiah($pembayaran_hari_ini['total']); ?></h4>
                            </div>
                            <small class="text-body-secondary"><?php echo $pembayaran_hari_ini['jumlah']; ?>
                                transaksi</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-calendar bx-md"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Kelas -->
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Total Kelas</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2"><?php echo number_format($total_kelas['total']); ?></h4>
                            </div>
                            <small class="text-body-secondary">Kelas tersedia</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-chalkboard bx-md"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">Grafik Pembayaran Bulanan <?php echo date('Y'); ?></h5>
                </div>
                <div class="card-body">
                    <canvas id="myAreaChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0">Pembayaran per Kelas</h5>
                </div>
                <div class="card-body">
                    <canvas id="myPieChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title m-0">Pembayaran Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Santri</th>
                                    <th>Kelas</th>
                                    <th>Bulan</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <?php while ($row = mysqli_fetch_assoc($recent_payments)): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($row['tgl_bayar'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_santri']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_kelas']); ?></td>
                                        <td><?php echo htmlspecialchars($row['bulan_dibayar']) . ' ' . $row['tahun_dibayar']; ?>
                                        </td>
                                        <td><?php echo formatRupiah($row['jumlah_bayar']); ?></td>
                                        <td>
                                            <span
                                                class="badge bg-label-<?php echo $row['metode_bayar'] == 'tunai' ? 'success' : ($row['metode_bayar'] == 'transfer' ? 'info' : 'warning'); ?>">
                                                <?php echo ucfirst($row['metode_bayar']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="pages/pembayaran/index.php" class="btn btn-primary btn-sm mt-3">Lihat Semua &rarr;</a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$extra_js = '
<script>
// Area Chart
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
    type: "line",
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
        datasets: [{
            label: "Pembayaran",
            tension: 0.3,
            backgroundColor: "rgba(105, 108, 255, 0.1)",
            borderColor: "rgba(105, 108, 255, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(105, 108, 255, 1)",
            pointBorderColor: "rgba(105, 108, 255, 1)",
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(105, 108, 255, 1)",
            pointHoverBorderColor: "rgba(105, 108, 255, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            fill: true,
            data: [' . implode(',', $data_bulanan) . '],
        }],
    },
    options: {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return "Pembayaran: Rp " + context.parsed.y.toLocaleString("id-ID");
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                ticks: {
                    callback: function(value) {
                        return "Rp " + value.toLocaleString("id-ID");
                    }
                },
                grid: {
                    color: "rgba(0,0,0,0.05)"
                }
            }
        }
    }
});

// Pie Chart
var ctx2 = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx2, {
    type: "doughnut",
    data: {
        labels: ' . json_encode($class_labels) . ',
        datasets: [{
            data: ' . json_encode($class_data) . ',
            backgroundColor: ["#696cff", "#71dd37", "#03c3ec", "#ffab00", "#ff3e1d"],
            hoverBackgroundColor: ["#5f61e6", "#64c732", "#03aed4", "#e69c00", "#e6381a"],
            borderWidth: 0,
        }],
    },
    options: {
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ": Rp " + context.parsed.toLocaleString("id-ID");
                    }
                }
            },
            legend: {
                display: true,
                position: "bottom"
            }
        },
        cutout: "60%",
    }
});
</script>
';

require_once 'includes/footer.php';
?>
