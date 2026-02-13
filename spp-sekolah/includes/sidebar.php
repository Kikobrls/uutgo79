<?php
/**
 * Sidebar Template - Sneat Bootstrap 5
 * sistem keuangan Sekolah
 */

$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="<?php echo $base_url; ?>index.php" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <i class="fas fa-school fa-2x"></i>
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">Sistem Keuangan</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item <?php echo $current_page == 'index.php' && $current_dir != 'pages' ? 'active' : ''; ?>">
            <a href="<?php echo $base_url; ?>index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate">Dashboard</div>
            </a>
        </li>

        <!-- Master Data -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master Data</span>
        </li>

        <!-- Data Santri -->
        <li class="menu-item <?php echo $current_dir == 'santri' ? 'active' : ''; ?>">
            <a href="<?php echo $base_url; ?>pages/santri/index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div class="text-truncate">Data Santri</div>
            </a>
        </li>

        <!-- Data Kelas -->
        <li class="menu-item <?php echo $current_dir == 'kelas' ? 'active' : ''; ?>">
            <a href="<?php echo $base_url; ?>pages/kelas/index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-chalkboard"></i>
                <div class="text-truncate">Data Kelas</div>
            </a>
        </li>

        <!-- Data SPP -->
        <li class="menu-item <?php echo $current_dir == 'spp' ? 'active' : ''; ?>">
            <a href="<?php echo $base_url; ?>pages/spp/index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div class="text-truncate">Data SPP</div>
            </a>
        </li>

        <!-- Transaksi -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Transaksi</span>
        </li>

        <!-- Pembayaran SPP -->
        <li class="menu-item <?php echo $current_dir == 'pembayaran' ? 'active' : ''; ?>">
            <a href="<?php echo $base_url; ?>pages/pembayaran/index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div class="text-truncate">Pembayaran SPP</div>
            </a>
        </li>

        <!-- Laporan -->
        <li class="menu-item <?php echo $current_dir == 'laporan' ? 'active' : ''; ?>">
            <a href="<?php echo $base_url; ?>pages/laporan/index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div class="text-truncate">Laporan</div>
            </a>
        </li>

        <!-- Keuangan -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Keuangan</span>
        </li>

        <!-- Data Kategori -->
        <li class="menu-item <?php echo $current_dir == 'kategori' ? 'active' : ''; ?>">
            <a href="<?php echo $base_url; ?>pages/kategori/index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-purchase-tag"></i>
                <div class="text-truncate">Data Kategori</div>
            </a>
        </li>

        <!-- Transaksi Keuangan -->
        <li class="menu-item <?php echo $current_dir == 'transaksi' ? 'active' : ''; ?>">
            <a href="<?php echo $base_url; ?>pages/transaksi/index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-transfer"></i>
                <div class="text-truncate">Transaksi</div>
            </a>
        </li>

        <?php if ($_SESSION['level'] == 'admin'): ?>
            <!-- Administrasi -->
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Administrasi</span>
            </li>

            <!-- Data Guru -->
            <li class="menu-item <?php echo $current_dir == 'guru' ? 'active' : ''; ?>">
                <a href="<?php echo $base_url; ?>pages/guru/index.php" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-group"></i>
                    <div class="text-truncate">Data Guru</div>
                </a>
            </li>

            <!-- Pengaturan -->
            <li class="menu-item <?php echo $current_dir == 'setting' ? 'active open' : ''; ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div class="text-truncate">Pengaturan</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item <?php echo $current_page == 'general.php' ? 'active' : ''; ?>">
                        <a href="<?php echo $base_url; ?>pages/setting/general.php" class="menu-link">
                            <div class="text-truncate">Umum</div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo $current_page == 'email.php' ? 'active' : ''; ?>">
                        <a href="<?php echo $base_url; ?>pages/setting/email.php" class="menu-link">
                            <div class="text-truncate">Email SMTP</div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo $current_page == 'whatsapp.php' ? 'active' : ''; ?>">
                        <a href="<?php echo $base_url; ?>pages/setting/whatsapp.php" class="menu-link">
                            <div class="text-truncate">WhatsApp API</div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo $current_page == 'payment.php' ? 'active' : ''; ?>">
                        <a href="<?php echo $base_url; ?>pages/setting/payment.php" class="menu-link">
                            <div class="text-truncate">Payment Gateway</div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo $current_page == 'backup.php' ? 'active' : ''; ?>">
                        <a href="<?php echo $base_url; ?>pages/setting/backup.php" class="menu-link">
                            <div class="text-truncate">Backup Database</div>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>
    </ul>
</aside>
<!-- / Menu -->
