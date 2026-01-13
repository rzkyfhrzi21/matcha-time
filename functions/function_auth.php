<?php
session_start();
require_once '../config/config.php';

/* =====================================================
   LOGIN
===================================================== */
if (isset($_POST['btn_login'])) {

	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);

	$password_md5 = md5($password);

	$query = mysqli_query(
		$koneksi,
		"SELECT * FROM users 
         WHERE username = '$username'
         AND password = '$password_md5'
         LIMIT 1"
	);

	$jumlah_user = mysqli_num_rows($query);
	$data_user   = mysqli_fetch_assoc($query);

	if ($jumlah_user > 0) {
		$_SESSION['sesi_id']   		= $data_user['id_user'];
		$_SESSION['sesi_nama'] 		= $data_user['nama'];
		$_SESSION['sesi_username'] 	= $data_user['username'];
		$_SESSION['sesi_role'] 		= $data_user['role'];
		$_SESSION['sesi_nohp'] 		= $data_user['no_hp'];

		if ($_SESSION['sesi_role'] == 'admin') {
			echo "<script>
                alert('Login berhasil. Selamat datang Admin di Matcha Time!');
                location.replace('../dashboard/admin');
              </script>";
		} else if ($_SESSION['sesi_role'] == 'mitra') {
			echo "<script>
                alert('Login berhasil. Selamat datang di Matcha Time!');
                location.replace('../dashboard/mitra');
              </script>";
		} else {
			echo "<script>
                alert('Akun tidak ditemukan. Silakan login ulang!');
                location.replace('../auth/logout');
              </script>";
		}
		exit;
	} else {
		echo "<script>
                alert('Login gagal! Username atau password salah.');
                location.replace('../auth/login?action=login&status=error');
              </script>";
		exit;
	}
}


/* =====================================================
   REGISTER (HANYA USERS)
===================================================== */
if (isset($_POST['btn_register'])) {

	$nama                = htmlspecialchars($_POST['nama']);
	$username            = htmlspecialchars($_POST['username']);
	$password            = htmlspecialchars($_POST['password']);
	$konfirmasi_password = htmlspecialchars($_POST['konfirmasi_password']);
	$no_hp               = htmlspecialchars($_POST['no_hp']);
	$role                = htmlspecialchars($_POST['role']); // mitra

	// validasi password sama
	if ($password !== $konfirmasi_password) {
		echo "<script>
                alert('Password dan Ulangi Password tidak sama!');
                location.replace('../auth/register?action=passwordnotsame&status=warning');
              </script>";
		exit;
	}

	// cek username sudah dipakai
	$cek_user = mysqli_query(
		$koneksi,
		"SELECT id_user FROM users WHERE username = '$username' LIMIT 1"
	);

	if (mysqli_num_rows($cek_user) > 0) {
		echo "<script>
                alert('Username sudah digunakan. Silakan gunakan username lain.');
                location.replace('../auth/register?action=userexist&status=warning');
              </script>";
		exit;
	}

	$password_md5 = md5($password);

	$query_daftar = mysqli_query(
		$koneksi,
		"INSERT INTO users (nama, username, password, no_hp, role)
         VALUES ('$nama', '$username', '$password_md5', '$no_hp', '$role')"
	);

	if ($query_daftar) {
		echo "<script>
                alert('Registrasi berhasil! Silakan login untuk melanjutkan.');
                location.replace('../auth/login?action=registered&status=success');
              </script>";
		exit;
	} else {
		echo "<script>
                alert('Registrasi gagal! Silakan coba lagi.');
                location.replace('../auth/register?status=error');
              </script>";
		exit;
	}
}
