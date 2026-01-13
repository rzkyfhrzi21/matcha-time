<?php
require_once '../config/config.php';

$sesi_role = $_SESSION['sesi_role'] ?? 'admin'; // fallback aman

$redirectProfil = "../dashboard/{$sesi_role}?page=Profil";

/* ======================================================
   UPDATE DATA PROFIL (NAMA & USERNAME)
====================================================== */
if (isset($_POST['btn_editdatapribadi'])) {

	$id_user  = $_POST['id_user'];
	$nama     = trim(htmlspecialchars($_POST['nama']));
	$username = trim(htmlspecialchars($_POST['username']));

	$update = mysqli_query($koneksi, "
        UPDATE users SET
            nama = '$nama',
            username = '$username'
        WHERE id_user = '$id_user'
    ");

	if ($update) {
		echo "<script>
                alert('Data profil berhasil diperbarui');
                location.replace('$redirectProfil');
              </script>";
	} else {
		echo "<script>
                alert('Gagal memperbarui data profil');
                location.replace('$redirectProfil');
              </script>";
	}
	exit;
}


/* ======================================================
   UPDATE PASSWORD
====================================================== */
if (isset($_POST['btn_editdataakun'])) {

	$id_user  = $_POST['id_user'];
	$password = trim($_POST['password']);
	$confirm  = trim($_POST['konfirmasi_password']);

	if (empty($password)) {
		echo "<script>
                alert('Password baru tidak boleh kosong');
                location.replace('$redirectProfil');
              </script>";
		exit;
	}

	if ($password !== $confirm) {
		echo "<script>
                alert('Password dan konfirmasi password tidak sama');
                location.replace('$redirectProfil');
              </script>";
		exit;
	}

	$password_md5 = md5($password);

	$update = mysqli_query($koneksi, "
        UPDATE users SET
            password = '$password_md5'
        WHERE id_user = '$id_user'
    ");

	if ($update) {
		echo "<script>
                alert('Password berhasil diperbarui');
                location.replace('$redirectProfil');
              </script>";
	} else {
		echo "<script>
                alert('Gagal memperbarui password');
                location.replace('$redirectProfil');
              </script>";
	}
	exit;
}


/* ======================================================
   HAPUS AKUN
====================================================== */
if (isset($_POST['btn_deleteakun'])) {

	$id_user = $_POST['id_user'];
	$sesi_id = $_SESSION['sesi_id'] ?? null;

	mysqli_query($koneksi, "DELETE FROM users WHERE id_user = '$id_user'");

	if ($id_user == $sesi_id) {
		echo "<script>
                alert('Akun anda berhasil dihapus');
                location.replace('../auth/logout.php');
              </script>";
	} else {
		echo "<script>
                alert('Akun berhasil dihapus');
                location.replace('$redirectProfil');
              </script>";
	}
	exit;
}

/* ======================================================
   TAMBAH DATA USAHA (MITRA)
====================================================== */
if (isset($_POST['btn_add_mitra'])) {

	$id_user         = $_POST['id_user'];
	$alamat_tinggal  = trim($_POST['alamat_tinggal']);
	$lokasi_stand    = trim($_POST['lokasi_stand']);
	$link_gmaps      = trim($_POST['link_gmaps_stand']);

	// Pastikan belum ada mitra
	$cek = mysqli_num_rows(mysqli_query($koneksi, "
        SELECT id_mitra FROM mitra WHERE id_user = '$id_user'
    "));

	if ($cek > 0) {
		echo "<script>
                alert('Data usaha sudah ada');
                location.replace('$redirectProfil');
              </script>";
		exit;
	}

	$insert = mysqli_query($koneksi, "
        INSERT INTO mitra (id_user, alamat_tinggal, lokasi_stand, link_gmaps_stand)
        VALUES ('$id_user', '$alamat_tinggal', '$lokasi_stand', '$link_gmaps')
    ");

	if ($insert) {
		echo "<script>
                alert('Data usaha berhasil disimpan');
                location.replace('$redirectProfil');
              </script>";
	} else {
		echo "<script>
                alert('Gagal menyimpan data usaha');
                location.replace('$redirectProfil');
              </script>";
	}
	exit;
}


/* ======================================================
   UPDATE DATA USAHA (MITRA)
====================================================== */
if (isset($_POST['btn_edit_mitra'])) {

	$id_user         = $_POST['id_user'];
	$id_mitra        = $_POST['id_mitra'];
	$alamat_tinggal  = trim($_POST['alamat_tinggal']);
	$lokasi_stand    = trim($_POST['lokasi_stand']);
	$link_gmaps      = trim($_POST['link_gmaps_stand']);

	// Keamanan: mitra hanya boleh edit usahanya sendiri
	$cek = mysqli_num_rows(mysqli_query($koneksi, "
        SELECT id_mitra FROM mitra 
        WHERE id_mitra = '$id_mitra' AND id_user = '$id_user'
    "));

	if ($cek == 0) {
		echo "<script>
                alert('Akses tidak valid');
                location.replace('$redirectProfil');
              </script>";
		exit;
	}

	$update = mysqli_query($koneksi, "
        UPDATE mitra SET
            alamat_tinggal = '$alamat_tinggal',
            lokasi_stand = '$lokasi_stand',
            link_gmaps_stand = '$link_gmaps'
        WHERE id_mitra = '$id_mitra'
    ");

	if ($update) {
		echo "<script>
                alert('Data usaha berhasil diperbarui');
                location.replace('$redirectProfil');
              </script>";
	} else {
		echo "<script>
                alert('Gagal memperbarui data usaha');
                location.replace('$redirectProfil');
              </script>";
	}
	exit;
}
