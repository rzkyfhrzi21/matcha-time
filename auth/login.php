<?php
require_once '../config/config.php';

if (!empty($_SESSION['sesi_role'])) {
    switch (strtolower($_SESSION['sesi_role'])) {

        case 'admin':
            header('Location: ../dashboard/admin?page=dashboard');
            exit;

        case 'mitra':
            header('Location: ../dashboard/mitra?page=dashboard');
            exit;

        default:
            header('Location: ../logout.php');
            exit;
    }
}


$usernameLogin = isset($_GET['username']) ? $_GET['username'] : '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <meta name="description" content="Login Mitra Matcha Time untuk mengelola pesanan dan kemitraan">
    <meta name="robots" content="noindex, nofollow">

    <title>Login Mitra | <?php echo NAMA_WEB ?></title>

    <link rel="shortcut icon" href="../assets/logo.png">
    <link rel="stylesheet" href="../assets/compiled/css/app.css">
    <link rel="stylesheet" href="../assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="../assets/compiled/css/auth.css">
    <link rel="stylesheet" href="../assets/extensions/sweetalert2/sweetalert2.min.css">

    <style>
        :root {
            --bg1: #ecf8f3;
            --bg2: #ffffff;
            --primary: #6FAF8A;
            --primary2: #3F7F5A;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
        }

        body {
            background: radial-gradient(900px 600px at 12% 18%, rgba(14, 165, 164, .14), transparent 60%),
                radial-gradient(800px 520px at 88% 22%, rgba(11, 59, 74, .10), transparent 55%),
                linear-gradient(135deg, var(--bg1), var(--bg2));
            min-height: 100vh;
            margin: 0;
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 26px 14px;
        }

        .auth-shell {
            width: 100%;
            max-width: 560px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .brand-top {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .brand-top img {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #fff;
            padding: 6px;
        }

        .brand-top .title {
            font-weight: 900;
            color: var(--primary2);
            font-size: 15px;
        }

        .brand-top .sub {
            color: var(--muted);
            font-size: 12.5px;
            font-weight: 600;
        }

        .card {
            max-width: 420px;
            width: 100%;
            margin: auto;
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 14px 40px rgba(15, 23, 42, .10);
            background: rgba(255, 255, 255, .96);
        }

        .card-header {
            padding: 18px 18px 12px;
            border-bottom: 1px solid var(--border);
        }

        .card-body {
            padding: 14px 18px 18px;
        }

        .auth-title {
            font-size: 24px;
            font-weight: 900;
            color: var(--primary2);
        }

        .auth-subtitle {
            font-size: 14px;
            color: var(--muted);
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            border-radius: 12px;
            font-weight: 800;
        }
    </style>
</head>

<body>
    <div class="auth-shell">

        <div class="brand-top">
            <img src="../assets/logo.png">
            <div>
                <div class="title">Matcha Time</div>
                <div class="sub">Pusat Kemitraan</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="auth-title">Login Mitra</h2>
                <p class="auth-subtitle">
                    Masuk untuk mengelola pesanan dan aktivitas kemitraan Matcha Time
                </p>
            </div>

            <div class="card-body">
                <form action="../functions/function_auth.php" method="post" autocomplete="off">

                    <div class="form-group position-relative has-icon-left mb-3">
                        <label>Username</label>
                        <div class="position-relative">
                            <input type="text" name="username" class="form-control"
                                placeholder="Username mitra"
                                value="<?= $usernameLogin ?>" required>
                            <div class="form-control-icon"><i class="bi bi-person"></i></div>
                        </div>
                    </div>

                    <div class="form-group position-relative has-icon-left mb-3">
                        <label>Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" class="form-control"
                                placeholder="Password akun"
                                required>
                            <div class="form-control-icon"><i class="bi bi-shield-lock"></i></div>
                        </div>
                    </div>

                    <input type="hidden" name="role" value="mitra">

                    <button type="submit" name="btn_login" class="btn btn-primary w-100">
                        Masuk
                    </button>
                </form>

                <div class="text-center mt-3">
                    <p>
                        Belum menjadi mitra?
                        <a href="register" class="fw-bold" style="color:var(--primary2)">Daftar Sekarang</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>