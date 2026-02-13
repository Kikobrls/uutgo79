<?php
/**
 * Laporan Terpadu
 * sistem keuangan Sekolah
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$page_title = 'Laporan Keuangan';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// --- FILTERS ---
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'spp';

// Filter SPP
$filter_bulan = isset($_GET['bulan']) ? sanitize($_GET['bulan']) : date('m');
$filter_tahun = isset($_GET['tahun']) ? sanitize($_GET['tahun']) : date('Y');
$filter_kelas = isset($_GET['kelas']) ? sanitize($_GET['kelas']) : '';

// Filter Umum / Rekap
$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');
$filter_kategori_umum = isset($_GET['kategori_id']) ? sanitize($_GET['kategori_id']) : '';

// --- DATA LOGIC ---

// 1. DATA SPP
$where_spp = "WHERE MONTH(p.tgl_bayar) = '$filter_bulan' AND YEAR(p.tgl_bayar) = '$filter_tahun'";
if (!empty($filter_kelas)) {
    $where_spp .= " AND s.id_kelas = '$filter_kelas'";
}

$query_spp = "SELECT p.*, s.nama as nama_santri, k.nama_kelas, g.nama_guru
          FROM pembayaran p
          JOIN santri s ON p.id_santri = s.id
          JOIN kelas k ON s.id_kelas = k.id_kelas
          JOIN guru g ON p.id_guru = g.id_guru
          $where_spp
          ORDER BY p.tgl_bayar DESC, s.nama";
$result_spp = mysqli_query($conn, $query_spp);

// Total SPP
$total_spp_data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(p.jumlah_bayar), 0) as total, COUNT(*) as jumlah
    FROM pembayaran p
    JOIN santri s ON p.id_santri = s.id
    $where_spp
"));

// 2. DATA KEUANGAN UMUM (TRANSAKSI)
$where_umum = "WHERE t.tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'";
if (!empty($filter_kategori_umum)) {
    $where_umum .= " AND t.id_kategori = '$filter_kategori_umum'";
}
$query_umum = "SELECT t.*, k.nama_kategori, g.nama_guru
               FROM transaksi t
               LEFT JOIN kategori_keuangan k ON t.id_kategori = k.id_kategori
               LEFT JOIN guru g ON t.id_guru = g.id_guru
               $where_umum
               ORDER BY t.tanggal DESC, t.created_at DESC";
$result_umum = mysqli_query($conn, $query_umum);

// Total Umum
$total_umum_data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COALESCE(SUM(debit), 0) as total_debit,
        COALESCE(SUM(kredit), 0) as total_kredit,
        COUNT(*) as jumlah
    FROM transaksi t
    $where_umum
"));


// 3. REKAPITULASI (SPP + TRANSAKSI)
$total_spp_recap = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_bayar), 0) as total
    FROM pembayaran
    WHERE tgl_bayar BETWEEN '$tgl_awal' AND '$tgl_akhir'
"));

$total_transaksi_recap = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COALESCE(SUM(debit), 0) as total_debit,
        COALESCE(SUM(kredit), 0) as total_kredit
    FROM transaksi
    WHERE tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'
"));

$recap_income = $total_spp_recap['total'] + $total_transaksi_recap['total_debit'];
$recap_expense = $total_transaksi_recap['total_kredit'];
$recap_balance = $recap_income - $recap_expense;

// Options List
$kelas_list = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas");
$kategori_list = mysqli_query($conn, "SELECT * FROM kategori_keuangan ORDER BY nama_kategori");
$bulan_list = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
$nama_sekolah = getSetting('nama_sekolah', 'SMK Negeri 1 Contoh');
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Keuangan /</span> Laporan
        </h4>
        <button onclick="window.print()" class="btn btn-primary btn-print">
            <i class="bx bx-printer me-1"></i> Cetak Laporan
        </button>
    </div>

    <!-- Tabs Navigation -->
    <div class="nav-align-top mb-4">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?php echo $active_tab == 'spp' ? 'active' : ''; ?>" id="spp-tab" href="?tab=spp">
                    <i class="bx bx-user me-1"></i> Pembayaran SPP
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_tab == 'umum' ? 'active' : ''; ?>" id="umum-tab" href="?tab=umum">
                    <i class="bx bx-transfer me-1"></i> Keuangan Umum
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_tab == 'rekap' ? 'active' : ''; ?>" id="rekap-tab" href="?tab=rekap">
                    <i class="bx bx-pie-chart-alt me-1"></i> Rekapitulasi
                </a>
            </li>
        </ul>

        <div class="tab-content">

            <!-- TAB 1: SPP -->
            <div class="tab-pane fade <?php echo $active_tab == 'spp' ? 'show active' : ''; ?>" id="spp" role="tabpanel">

                <!-- Filters SPP -->
                <div class="card mb-4 no-print">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <input type="hidden" name="tab" value="spp">
                            <div class="col-md-3">
                                <label class="form-label">Bulan</label>
                                <select name="bulan" class="form-select">
                                    <?php foreach ($bulan_list as $key => $bulan): ?>
                                        <option value="<?php echo $key; ?>" <?php echo $filter_bulan == $key ? 'selected' : ''; ?>>
                                            <?php echo $bulan; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tahun</label>
                                <select name="tahun" class="form-select">
                                    <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                                        <option value="<?php echo $y; ?>" <?php echo $filter_tahun == $y ? 'selected' : ''; ?>>
                                            <?php echo $y; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kelas</label>
                                <select name="kelas" class="form-select">
                                    <option value="">Semua Kelas</option>
                                    <?php
                                    mysqli_data_seek($kelas_list, 0);
                                    while ($kelas = mysqli_fetch_assoc($kelas_list)):
                                    ?>
                                        <option value="<?php echo $kelas['id_kelas']; ?>" <?php echo $filter_kelas == $kelas['id_kelas'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($kelas['nama_kelas']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-filter"></i> Tampilkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- SPP Summary -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-label-success h-100">
                            <div class="card-body">
                                <span class="fw-bold d-block mb-1">Total Pemasukan SPP</span>
                                <h3 class="card-title mb-0 text-success"><?php echo formatRupiah($total_spp_data['total']); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-label-info h-100">
                            <div class="card-body">
                                <span class="fw-bold d-block mb-1">Jumlah Transaksi</span>
                                <h3 class="card-title mb-0 text-info"><?php echo number_format($total_spp_data['jumlah']); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SPP Table -->
                <div class="card report-card">
                    <h5 class="card-header bg-primary text-white">Laporan Pembayaran SPP - <?php echo $bulan_list[$filter_bulan] . ' ' . $filter_tahun; ?></h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Santri</th>
                                    <th>Kelas</th>
                                    <th>Bayar Untuk</th>
                                    <th>Metode</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($result_spp)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tgl_bayar'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_santri']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_kelas']); ?></td>
                                    <td><?php echo htmlspecialchars($row['bulan_dibayar']) . ' ' . $row['tahun_dibayar']; ?></td>
                                    <td><?php echo ucfirst($row['metode_bayar']); ?></td>
                                    <td class="text-end"><?php echo formatRupiah($row['jumlah_bayar']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot class="fw-bold">
                                <tr>
                                    <td colspan="6" class="text-end">TOTAL</td>
                                    <td class="text-end"><?php echo formatRupiah($total_spp_data['total']); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: KEUANGAN UMUM -->
            <div class="tab-pane fade <?php echo $active_tab == 'umum' ? 'show active' : ''; ?>" id="umum" role="tabpanel">

                <!-- Filters Umum -->
                <div class="card mb-4 no-print">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <input type="hidden" name="tab" value="umum">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Awal</label>
                                <input type="date" class="form-control" name="tgl_awal" value="<?php echo $tgl_awal; ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" name="tgl_akhir" value="<?php echo $tgl_akhir; ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori_id" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    <?php
                                    mysqli_data_seek($kategori_list, 0);
                                    while ($kat = mysqli_fetch_assoc($kategori_list)):
                                    ?>
                                        <option value="<?php echo $kat['id_kategori']; ?>" <?php echo $filter_kategori_umum == $kat['id_kategori'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bx bx-filter"></i> Tampilkan
                                </button>
                                <a href="export_transaksi.php?tgl_awal=<?php echo $tgl_awal; ?>&tgl_akhir=<?php echo $tgl_akhir; ?>&kategori_id=<?php echo $filter_kategori_umum; ?>" class="btn btn-success">
                                    <i class="bx bx-spreadsheet"></i> Export
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Summary Cards Umum -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-label-success h-100">
                            <div class="card-body">
                                <span class="fw-bold d-block mb-1">Total Pemasukan</span>
                                <h3 class="card-title mb-0 text-success"><?php echo formatRupiah($total_umum_data['total_debit']); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-label-danger h-100">
                            <div class="card-body">
                                <span class="fw-bold d-block mb-1">Total Pengeluaran</span>
                                <h3 class="card-title mb-0 text-danger"><?php echo formatRupiah($total_umum_data['total_kredit']); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-label-primary h-100">
                            <div class="card-body">
                                <span class="fw-bold d-block mb-1">Surplus / Defisit</span>
                                <h3 class="card-title mb-0 text-primary"><?php echo formatRupiah($total_umum_data['total_debit'] - $total_umum_data['total_kredit']); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Umum Table -->
                 <div class="card report-card">
                    <h5 class="card-header bg-info text-white">Laporan Keuangan Umum (<?php
                        if (!empty($filter_kategori_umum)) {
                            mysqli_data_seek($kategori_list, 0);
                            $found_cat = "Kategori Tidak Ditemukan";
                            while ($k = mysqli_fetch_assoc($kategori_list)) {
                                if ($k['id_kategori'] == $filter_kategori_umum) {
                                    $found_cat = $k['nama_kategori'];
                                    break;
                                }
                            }
                            echo htmlspecialchars($found_cat);
                        } else {
                            echo "Semua Kategori";
                        }
                    ?>) <small class="text-white opacity-75 d-block mt-1"><?php echo date('d F Y', strtotime($tgl_awal)) . ' - ' . date('d F Y', strtotime($tgl_akhir)); ?></small></h5>

                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Uraian</th>
                                    <th>Kategori</th>
                                    <th class="text-end text-success">Pemasukan</th>
                                    <th class="text-end text-danger">Pengeluaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($result_umum)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['uraian']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_kategori'] ?? '-'); ?></td>
                                    <td class="text-end text-success"><?php echo $row['debit'] > 0 ? formatRupiah($row['debit']) : '-'; ?></td>
                                    <td class="text-end text-danger"><?php echo $row['kredit'] > 0 ? formatRupiah($row['kredit']) : '-'; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot class="fw-bold">
                                <tr>
                                    <td colspan="4" class="text-end">TOTAL</td>
                                    <td class="text-end text-success"><?php echo formatRupiah($total_umum_data['total_debit']); ?></td>
                                    <td class="text-end text-danger"><?php echo formatRupiah($total_umum_data['total_kredit']); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: REKAPITULASI -->
            <div class="tab-pane fade <?php echo $active_tab == 'rekap' ? 'show active' : ''; ?>" id="rekap" role="tabpanel">

                <!-- Filters Rekap -->
                <div class="card mb-4 no-print">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <input type="hidden" name="tab" value="rekap">
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Awal</label>
                                <input type="date" class="form-control" name="tgl_awal" value="<?php echo $tgl_awal; ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" name="tgl_akhir" value="<?php echo $tgl_akhir; ?>">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-filter"></i> Hitung Rekap
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Rekap Data -->
                <div class="card report-card">
                    <h5 class="card-header bg-dark text-white">Rekapitulasi Keuangan Terpadu <small class="text-white opacity-75 d-block mt-1"><?php echo date('d F Y', strtotime($tgl_awal)) . ' - ' . date('d F Y', strtotime($tgl_akhir)); ?></small></h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Komponen</th>
                                                <th class="text-end">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Pemasukan SPP</strong></td>
                                                <td class="text-end text-success"><?php echo formatRupiah($total_spp_recap['total']); ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pemasukan Lainnya (Umum)</strong></td>
                                                <td class="text-end text-success"><?php echo formatRupiah($total_transaksi_recap['total_debit']); ?></td>
                                            </tr>
                                            <tr class="table-success">
                                                <td><strong>TOTAL PEMASUKAN</strong></td>
                                                <td class="text-end"><strong><?php echo formatRupiah($recap_income); ?></strong></td>
                                            </tr>
                                            <tr><td><br></td><td></td></tr>
                                            <tr>
                                                <td><strong>Pengeluaran Operasional (Umum)</strong></td>
                                                <td class="text-end text-danger"><?php echo formatRupiah($total_transaksi_recap['total_kredit']); ?></td>
                                            </tr>
                                            <tr class="table-danger">
                                                <td><strong>TOTAL PENGELUARAN</strong></td>
                                                <td class="text-end"><strong><?php echo formatRupiah($recap_expense); ?></strong></td>
                                            </tr>
                                            <tr><td><br></td><td></td></tr>
                                            <tr class="table-secondary" style="font-size: 1.2em;">
                                                <td><strong>SALDO AKHIR</strong></td>
                                                <td class="text-end"><strong><?php echo formatRupiah($recap_balance); ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Visualization -->
                            <div class="col-lg-6">
                                <div class="card shadow-none border h-100">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h5 class="fw-bold mb-4">Visualisasi Arus Kas</h5>

                                        <div style="width: 100%; max-width: 400px;">
                                            <?php
                                            $max_val = max($recap_income, $recap_expense, 1);
                                            $income_pct = ($recap_income / $max_val) * 100;
                                            $expense_pct = ($recap_expense / $max_val) * 100;
                                            ?>

                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="fw-bold text-success">Pemasukan</span>
                                                    <span class="fw-bold text-success"><?php echo formatRupiah($recap_income); ?></span>
                                                </div>
                                                <div class="progress" style="height: 25px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $income_pct; ?>%"></div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="fw-bold text-danger">Pengeluaran</span>
                                                    <span class="fw-bold text-danger"><?php echo formatRupiah($recap_expense); ?></span>
                                                </div>
                                                <div class="progress" style="height: 25px;">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $expense_pct; ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Signature Section (Print Only) -->
    <div class="print-only mt-5" style="display: none;">
        <div class="row">
            <div class="col-4 text-center">
                <br>
                <p>Mengetahui,<br>Kepala Sekolah</p>
                <br><br><br>
                <p>_______________________</p>
            </div>
            <div class="col-4"></div>
            <div class="col-4 text-center">
                <p><?php echo date('d F Y'); ?><br>Bendahara</p>
                <br><br><br>
                <p>_______________________</p>
                <p><b><?php echo $_SESSION['nama_guru'] ?? 'Admin'; ?></b></p>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for Printing -->
<style>
    @media print {
        @page { size: landscape; margin: 10mm; }
        body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

        .no-print, .btn, .layout-menu, .layout-navbar, footer { display: none !important; }
        .print-only { display: block !important; }

        .container-xxl { max-width: 100% !important; padding: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }

        /* Only print active tab content */
        .tab-pane { display: none !important; }
        .tab-pane.show.active { display: block !important; }

        .bg-success { background-color: #71dd37 !important; color: white !important; }
        .bg-danger { background-color: #ff3e1d !important; color: white !important; }
        .bg-primary { background-color: #696cff !important; color: white !important; }
        .bg-info { background-color: #03c3ec !important; color: white !important; }
        .bg-dark { background-color: #233446 !important; color: white !important; }
    }
</style>

<?php require_once '../../includes/footer.php'; ?>
