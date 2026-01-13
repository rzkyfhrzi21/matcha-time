<?php
require_once '../config/config.php';

// Proteksi ADMIN
if (!isset($_SESSION['sesi_role']) || $_SESSION['sesi_role'] !== 'admin') {
    echo "<script>
            alert('Akses ditolak!');
            location.replace('../auth/logout.php');
          </script>";
    exit;
}

$redirect = "../dashboard/admin?page=Mitra";

/* ===============================
   TAMBAH MITRA
=============================== */
if (isset($_POST['btn_add_mitra'])) {

    $id_user          = $_POST['id_user'];
    $alamat_tinggal   = trim($_POST['alamat_tinggal']);
    $lokasi_stand     = trim($_POST['lokasi_stand']);
    $link_gmaps_stand = trim($_POST['link_gmaps_stand']);

    if (empty($id_user)) {
        echo "<script>alert('User mitra wajib dipilih');location.replace('$redirect');</script>";
        exit;
    }

    $insert = mysqli_query($koneksi, "
        INSERT INTO mitra (id_user, alamat_tinggal, lokasi_stand, link_gmaps_stand)
        VALUES ('$id_user', '$alamat_tinggal', '$lokasi_stand', '$link_gmaps_stand')
    ");

    echo "<script>
            alert('Mitra berhasil ditambahkan');
            location.replace('$redirect');
          </script>";
    exit;
}

/* ===============================
   EDIT MITRA
=============================== */
if (isset($_POST['btn_edit_mitra'])) {

    $id_mitra         = $_POST['id_mitra'];
    $alamat_tinggal   = trim($_POST['alamat_tinggal']);
    $lokasi_stand     = trim($_POST['lokasi_stand']);
    $link_gmaps_stand = trim($_POST['link_gmaps_stand']);

    $update = mysqli_query($koneksi, "
        UPDATE mitra SET
            alamat_tinggal='$alamat_tinggal',
            lokasi_stand='$lokasi_stand',
            link_gmaps_stand='$link_gmaps_stand'
        WHERE id_mitra='$id_mitra'
    ");

    echo "<script>
            alert('Data mitra berhasil diperbarui');
            location.replace('$redirect');
          </script>";
    exit;
}

/* ===============================
   HAPUS MITRA
=============================== */
if (isset($_POST['btn_delete_mitra'])) {

    $id_mitra = $_POST['id_mitra'];

    mysqli_query($koneksi, "DELETE FROM mitra WHERE id_mitra='$id_mitra'");

    echo "<script>
            alert('Mitra berhasil dihapus');
            location.replace('$redirect');
          </script>";
    exit;
}

/* ===============================
   FALLBACK
=============================== */
echo "<script>alert('Aksi tidak dikenali');location.replace('$redirect');</script>";
exit;
