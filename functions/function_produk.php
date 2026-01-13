<?php
require_once '../config/config.php';

// ================================
// TAMBAH PRODUK
// ================================
if (isset($_POST['btn_add_produk'])) {

    $id_supplier = $_POST['id_supplier'];
    $nama        = trim($_POST['nama_produk']);
    $deskripsi   = trim($_POST['deskripsi']);
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];

    $insert = mysqli_query($koneksi, "
        INSERT INTO produk (
            id_supplier, nama_produk, deskripsi, harga, stok
        ) VALUES (
            '$id_supplier',
            '$nama',
            '$deskripsi',
            '$harga',
            '$stok'
        )
    ");

    if ($insert) {
        echo "<script>
            alert('Produk berhasil ditambahkan');
            location.replace('../dashboard/admin?page=Produk');
        </script>";
    } else {
        echo "<script>
            alert('Gagal menambahkan produk');
            location.replace('../dashboard/admin?page=Produk');
        </script>";
    }
    exit;
}

// ================================
// EDIT PRODUK
// ================================
if (isset($_POST['btn_edit_produk'])) {

    $id_produk  = $_POST['id_produk'];
    $nama       = trim($_POST['nama_produk']);
    $deskripsi  = trim($_POST['deskripsi']);
    $harga      = $_POST['harga'];
    $stok       = $_POST['stok'];

    $update = mysqli_query($koneksi, "
        UPDATE produk SET
            nama_produk = '$nama',
            deskripsi   = '$deskripsi',
            harga       = '$harga',
            stok        = '$stok'
        WHERE id_produk = '$id_produk'
    ");

    if ($update) {
        echo "<script>
            alert('Produk berhasil diperbarui');
            location.replace('../dashboard/admin?page=Produk');
        </script>";
    } else {
        echo "<script>
            alert('Gagal memperbarui produk');
            location.replace('../dashboard/admin?page=Produk');
        </script>";
    }
    exit;
}

// ================================
// HAPUS PRODUK
// ================================
if (isset($_POST['btn_delete_produk'])) {

    $id_produk = $_POST['id_produk'];

    $delete = mysqli_query(
        $koneksi,
        "DELETE FROM produk WHERE id_produk='$id_produk'"
    );

    if ($delete) {
        echo "<script>
            alert('Produk berhasil dihapus');
            location.replace('../dashboard/admin?page=Produk');
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus produk');
            location.replace('../dashboard/admin?page=Produk');
        </script>";
    }
    exit;
}
