<?php
/**
 * Topbar (Navbar) Template - Sneat Bootstrap 5
 * sistem keuangan Sekolah
 */
?>
<!-- Layout container -->
<div class="layout-page">
    <!-- Navbar -->
    <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
        id="layout-navbar">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="icon-base bx bx-menu icon-md"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
            <!-- Search -->
            <div class="navbar-nav align-items-center me-auto">
                <div class="nav-item d-flex align-items-center">
                    <span class="w-px-22 h-px-22"><i class="icon-base bx bx-search icon-md"></i></span>
                    <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2 d-md-block d-none"
                        placeholder="Cari..." aria-label="Cari..." />
                </div>
            </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                <!-- Notifications -->
                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" aria-expanded="false">
                        <span class="position-relative">
                            <i class="icon-base bx bx-bell icon-md"></i>
                            <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-0">
                        <li class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h6 class="mb-0 me-auto">Notifikasi</h6>
                            </div>
                        </li>
                        <li class="dropdown-notifications-list scrollable-container">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    <i class="bx bx-file"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="small mb-0">Sistem SPP Aktif</h6>
                                            <small class="text-body-secondary"><?php echo date('d F Y'); ?></small>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!--/ Notifications -->

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                        data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <span class="avatar-initial rounded-circle bg-label-primary">
                                <?php echo strtoupper(substr($_SESSION['nama_guru'] ?? 'U', 0, 1)); ?>
                            </span>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <?php echo strtoupper(substr($_SESSION['nama_guru'] ?? 'U', 0, 1)); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">
                                            <?php echo htmlspecialchars($_SESSION['nama_guru'] ?? 'User'); ?>
                                        </h6>
                                        <small
                                            class="text-body-secondary"><?php echo ucfirst($_SESSION['level'] ?? 'user'); ?></small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo $base_url; ?>pages/guru/profile.php">
                                <i class="icon-base bx bx-user icon-md me-3"></i><span>Profil Saya</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo $base_url; ?>pages/setting/general.php">
                                <i class="icon-base bx bx-cog icon-md me-3"></i><span>Pengaturan</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                                data-bs-target="#logoutModal">
                                <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>
    </nav>
    <!-- / Navbar -->

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <!-- Content -->
