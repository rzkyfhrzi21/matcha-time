<?php
require_once '../config/config.php';

// ================================
// AMBIL DATA SESI
// ================================
$sesi_id        = $_SESSION['sesi_id'] ?? null;
$sesi_nama      = $_SESSION['sesi_nama'] ?? '';
$sesi_username  = $_SESSION['sesi_username'] ?? '';
$sesi_no_hp     = $_SESSION['sesi_nohp'] ?? '';
$sesi_role      = $_SESSION['sesi_role'] ?? null;

// ================================
// PROTEKSI LOGIN & ROLE
// ================================
if (!$sesi_id || $sesi_role !== 'mitra') {
    echo "<script>
        alert('Akses ditolak');
        location.replace('../auth/logout');
    </script>";
    exit;
}

// ================================
// PAGE
// ================================
$page = $_GET['page'] ?? 'Dashboard';

// ================================
// GUARD MITRA (WAJIB LENGKAP DATA)
// ================================
require_once '../config/guard_mitra.php';

if (!profilMitraLengkap($koneksi, $sesi_id) && $page !== 'Profil') {
    echo "<script>
        alert('Lengkapi profil akun dan usaha Anda terlebih dahulu');
        location.replace('?page=Profil');
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">

    <title><?= ucfirst($page); ?> - Panel Mitra <?= NAMA_WEB ?></title>

    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">
    <?php include '../assets/css.php'; ?>
    <style>
        .img-responsive {
            width: 100px;
            height: auto;
            max-width: 600px;
            /* opsional, biar tidak kebesaran */
            object-fit: contain;
        }
    </style>
</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>

    <div id="app">

        <!-- ================= SIDEBAR ================= -->
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <img src="../assets/logo.png" alt="<?= NAMA_WEB; ?>" style="width:120px;">
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block">
                                <i class="bi bi-x bi-middle"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="sidebar-menu">
                    <ul class="menu">

                        <li class="sidebar-title">Menu Mitra</li>

                        <li class="sidebar-item <?= ($page === 'Dashboard') ? 'active' : ''; ?>">
                            <a href="?page=Dashboard" class="sidebar-link">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= ($page === 'Pesanan') ? 'active' : ''; ?>">
                            <a href="?page=Pesanan" class="sidebar-link">
                                <i class="bi bi-receipt"></i>
                                <span>Pesanan</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= ($page === 'Produk') ? 'active' : ''; ?>">
                            <a href="?page=Produk" class="sidebar-link">
                                <i class="bi bi-cup-hot"></i>
                                <span>Produk</span>
                            </a>
                        </li>

                        <li class="sidebar-item <?= ($page === 'Profil') ? 'active' : ''; ?>">
                            <a href="?page=Profil" class="sidebar-link">
                                <i class="bi bi-person-circle"></i>
                                <span>Profil</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="../auth/logout.php" class="sidebar-link text-danger">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <!-- ================= MAIN ================= -->
        <div id="main">

            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <?php
            switch ($page) {

                // =====================
                // DASHBOARD MITRA
                // =====================
                case 'Dashboard':
                    include 'pages/mitra/dashboard.php';
                    break;

                // =====================
                // PESANAN MITRA
                // =====================
                case 'Pesanan':
                    include 'pages/mitra/kelola_pesanan.php';
                    break;

                // =====================
                // PROFIL MITRA
                // =====================
                case 'Profil':
                    include 'pages/mitra/kelola_profil.php';
                    break;

                // =====================
                // TAMPIL PRODUK YANG TERSEDIA
                // =====================
                case 'Produk':
                    include 'pages/mitra/kelola_produk.php';
                    break;

                // =====================
                // DEFAULT
                // =====================
                default:
                    include 'pages/mitra/dashboard.php';
                    break;
            }
            ?>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                            &copy; <?= NAMA_WEB; ?>
                        </p>
                    </div>
                    <div class="float-end">
                        <p>
                            Dikembangkan oleh
                            <strong><?= NAMA_LENGKAP; ?></strong>
                        </p>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <?php include '../assets/js.php'; ?>
</body>

</html>