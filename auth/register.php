<?php
require_once '../config/config.php';

if (@$_SESSION['sesi_id']) {
    header('Location: ../index');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="robots" content="noindex, nofollow">
    <title>Registrasi Mitra | <?php echo NAMA_WEB ?></title>

    <link rel="shortcut icon" href="../assets/logo.png">
    <link rel="stylesheet" href="../assets/compiled/css/app.css">
    <link rel="stylesheet" href="../assets/compiled/css/auth.css">

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
                <div class="sub">Registrasi Mitra</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="auth-title">Registrasi Akun</h2>
                <p class="auth-subtitle">
                    Buat akun mitra untuk bergabung dengan Matcha Time
                </p>
            </div>

            <div class="card-body">
                <form action="../functions/function_auth.php" method="post" autocomplete="off">

                    <input type="hidden" name="role" value="mitra">

                    <div class="form-group position-relative has-icon-left mb-3">
                        <label>Nama Lengkap</label>
                        <div class="position-relative">
                            <input type="text" name="nama" class="form-control"
                                placeholder="Nama lengkap mitra" required>
                            <div class="form-control-icon"><i class="bi bi-person"></i></div>
                        </div>
                    </div>

                    <div class="form-group position-relative has-icon-left mb-3">
                        <label>Username</label>
                        <div class="position-relative">
                            <input type="text" name="username" class="form-control"
                                placeholder="Username mitra" required>
                            <div class="form-control-icon"><i class="bi bi-person-badge"></i></div>
                        </div>
                    </div>

                    <div class="form-group position-relative has-icon-left mb-3">
                        <label>Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" class="form-control"
                                placeholder="Password akun" required>
                            <div class="form-control-icon"><i class="bi bi-shield-lock"></i></div>
                        </div>
                    </div>

                    <div class="form-group position-relative has-icon-left mb-3">
                        <label>Ulangi Password</label>
                        <div class="position-relative">
                            <input type="password" name="konfirmasi_password" class="form-control"
                                placeholder="Ulangi password" required>
                            <div class="form-control-icon"><i class="bi bi-shield-check"></i></div>
                        </div>
                    </div>

                    <div class="form-group position-relative has-icon-left mb-3">
                        <label>Nomor HP</label>
                        <div class="position-relative">
                            <input type="text" name="no_hp" class="form-control"
                                placeholder="Nomor HP aktif" required>
                            <div class="form-control-icon"><i class="bi bi-telephone"></i></div>
                        </div>
                    </div>

                    <button type="submit" name="btn_register" class="btn btn-primary w-100">
                        Daftar
                    </button>
                </form>

                <div class="text-center mt-3">
                    <p>
                        Sudah punya akun?
                        <a href="login" class="fw-bold" style="color:var(--primary2)">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>