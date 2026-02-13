<?php
/**
 * Login Page - Sneat Bootstrap 5
 * sistem keuangan Sekolah
 */

session_start();
require_once 'config/database.php';
require_once 'config/app.php';

// Redirect if already logged in
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = '';

// Process login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi!";
    } else {
        $query = "SELECT * FROM guru WHERE username = '$username' AND status = 'active'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['login'] = true;
                $_SESSION['id_guru'] = $user['id_guru'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_guru'] = $user['nama_guru'];
                $_SESSION['level'] = $user['level'];

                // Update last login
                mysqli_query($conn, "UPDATE guru SET last_login = NOW() WHERE id_guru = " . $user['id_guru']);

                // Log activity
                logActivity('Login ke sistem', 'guru', $user['id_guru']);

                header("Location: index.php");
                exit;
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan atau akun tidak aktif!";
        }
    }
}

$nama_sekolah = getSetting('nama_sekolah', 'SMK Negeri 1 Contoh');
$sneat_path = 'sneat-bootstrap-html-admin-template-free/';
?>
<!doctype html>

<html lang="id" class="layout-wide customizer-hide" data-assets-path="<?php echo $sneat_path; ?>assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login - <?php echo APP_NAME; ?></title>

    <meta name="description" content="Login - sistem keuangan" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $sneat_path; ?>assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo $sneat_path; ?>assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo $sneat_path; ?>assets/vendor/css/core.css" />
    <link rel="stylesheet" href="<?php echo $sneat_path; ?>assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="<?php echo $sneat_path; ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="<?php echo $sneat_path; ?>assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="<?php echo $sneat_path; ?>assets/vendor/js/helpers.js"></script>

    <!-- Config -->
    <script src="<?php echo $sneat_path; ?>assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Card -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="index.php" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <span class="text-primary">
                                        <i class="fas fa-school fa-2x"></i>
                                    </span>
                                </span>
                                <span class="app-brand-text demo text-heading fw-bold"><?php echo APP_NAME; ?></span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1 text-center"><?php echo htmlspecialchars($nama_sekolah); ?></h4>
                        <p class="mb-6 text-center">Silakan login untuk melanjutkan</p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible mb-4" role="alert">
                                <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form id="formAuthentication" class="mb-6" method="POST" action="">
                            <div class="mb-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Masukkan username"
                                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                                    required autofocus />
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        required aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-8">
                                <div class="d-flex justify-content-between">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="remember-me" />
                                        <label class="form-check-label" for="remember-me">Ingat Saya</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>
                        </form>

                        <p class="text-center">
                            <small class="text-body-secondary">
                                &copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>
                            </small>
                        </p>
                    </div>
                </div>
                <!-- /Card -->
            </div>
        </div>
    </div>
    <!-- / Content -->

    <!-- Core JS -->
    <script src="<?php echo $sneat_path; ?>assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?php echo $sneat_path; ?>assets/vendor/libs/popper/popper.js"></script>
    <script src="<?php echo $sneat_path; ?>assets/vendor/js/bootstrap.js"></script>
    <script src="<?php echo $sneat_path; ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $sneat_path; ?>assets/vendor/js/menu.js"></script>

    <!-- Main JS -->
    <script src="<?php echo $sneat_path; ?>assets/js/main.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if ($error): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal!',
                text: '<?php echo addslashes($error); ?>',
                showConfirmButton: true
            });
        </script>
    <?php endif; ?>
</body>

</html>
