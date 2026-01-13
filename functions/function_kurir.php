<?php
require_once '../config/config.php';

// Proteksi role admin
if (!isset($_SESSION['sesi_role']) || $_SESSION['sesi_role'] !== 'admin') {
    echo "<script>
            alert('Akses ditolak!');
            location.replace('../auth/logout.php');
          </script>";
    exit;
}

$redirect = "../dashboard/admin?page=Kurir";

/* ===============================
   TAMBAH KURIR
=============================== */
if (isset($_POST['btn_add_kurir'])) {

    $nama_kurir = trim($_POST['nama_kurir']);
    $kode_kurir = trim($_POST['kode_kurir']);
    $kontak     = trim($_POST['kontak']);

    if ($nama_kurir === '' || $kode_kurir === '' || $kontak === '') {
        echo "<script>
                alert('Semua field wajib diisi');
                location.replace('$redirect');
              </script>";
        exit;
    }

    $insert = mysqli_query($koneksi, "
        INSERT INTO kurir (nama_kurir, kode_kurir, kontak)
        VALUES ('$nama_kurir', '$kode_kurir', '$kontak')
    ");

    if ($insert) {
        echo "<script>
                alert('Kurir berhasil ditambahkan');
                location.replace('$redirect');
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan kurir');
                location.replace('$redirect');
              </script>";
    }
    exit;
}

/* ===============================
   EDIT KURIR
=============================== */
if (isset($_POST['btn_edit_kurir'])) {

    $id_kurir   = $_POST['id_kurir'];
    $nama_kurir = trim($_POST['nama_kurir']);
    $kode_kurir = trim($_POST['kode_kurir']);
    $kontak     = trim($_POST['kontak']);

    if (empty($id_kurir)) {
        echo "<script>
                alert('Data tidak valid');
                location.replace('$redirect');
              </script>";
        exit;
    }

    $update = mysqli_query($koneksi, "
        UPDATE kurir SET
            nama_kurir='$nama_kurir',
            kode_kurir='$kode_kurir',
            kontak='$kontak'
        WHERE id_kurir='$id_kurir'
    ");

    if ($update) {
        echo "<script>
                alert('Kurir berhasil diperbarui');
                location.replace('$redirect');
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui kurir');
                location.replace('$redirect');
              </script>";
    }
    exit;
}

/* ===============================
   HAPUS KURIR
=============================== */
if (isset($_POST['btn_delete_kurir'])) {

    $id_kurir = $_POST['id_kurir'];

    if (empty($id_kurir)) {
        echo "<script>
                alert('Data tidak valid');
                location.replace('$redirect');
              </script>";
        exit;
    }

    $delete = mysqli_query(
        $koneksi,
        "DELETE FROM kurir WHERE id_kurir='$id_kurir'"
    );

    if ($delete) {
        echo "<script>
                alert('Kurir berhasil dihapus');
                location.replace('$redirect');
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus kurir');
                location.replace('$redirect');
              </script>";
    }
    exit;
}

/* ===============================
   FALLBACK
=============================== */
echo "<script>
        alert('Aksi tidak dikenali');
        location.replace('$redirect');
      </script>";
exit;
