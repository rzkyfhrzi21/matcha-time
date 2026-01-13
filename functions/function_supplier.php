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

$redirect = "../dashboard/admin?page=Supplier";

/* ===============================
   TAMBAH SUPPLIER
=============================== */
if (isset($_POST['btn_add_supplier'])) {

    $nama_supplier = trim($_POST['nama_supplier']);
    $kontak        = trim($_POST['kontak']);
    $alamat        = trim($_POST['alamat']);

    if ($nama_supplier === '' || $kontak === '' || $alamat === '') {
        echo "<script>
                alert('Semua field wajib diisi');
                location.replace('$redirect');
              </script>";
        exit;
    }

    $insert = mysqli_query($koneksi, "
        INSERT INTO supplier (nama_supplier, kontak, alamat)
        VALUES ('$nama_supplier', '$kontak', '$alamat')
    ");

    if ($insert) {
        echo "<script>
                alert('Supplier berhasil ditambahkan');
                location.replace('$redirect');
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan supplier');
                location.replace('$redirect');
              </script>";
    }
    exit;
}

/* ===============================
   EDIT SUPPLIER
=============================== */
if (isset($_POST['btn_edit_supplier'])) {

    $id_supplier   = $_POST['id_supplier'];
    $nama_supplier = trim($_POST['nama_supplier']);
    $kontak        = trim($_POST['kontak']);
    $alamat        = trim($_POST['alamat']);

    if (empty($id_supplier)) {
        echo "<script>
                alert('Data tidak valid');
                location.replace('$redirect');
              </script>";
        exit;
    }

    $update = mysqli_query($koneksi, "
        UPDATE supplier SET
            nama_supplier='$nama_supplier',
            kontak='$kontak',
            alamat='$alamat'
        WHERE id_supplier='$id_supplier'
    ");

    if ($update) {
        echo "<script>
                alert('Supplier berhasil diperbarui');
                location.replace('$redirect');
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui supplier');
                location.replace('$redirect');
              </script>";
    }
    exit;
}

/* ===============================
   HAPUS SUPPLIER
=============================== */
if (isset($_POST['btn_delete_supplier'])) {

    $id_supplier = $_POST['id_supplier'];

    if (empty($id_supplier)) {
        echo "<script>
                alert('Data tidak valid');
                location.replace('$redirect');
              </script>";
        exit;
    }

    $delete = mysqli_query(
        $koneksi,
        "DELETE FROM supplier WHERE id_supplier='$id_supplier'"
    );

    if ($delete) {
        echo "<script>
                alert('Supplier berhasil dihapus');
                location.replace('$redirect');
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus supplier');
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
