<?php
require_once '../config/config.php';

if (!isset($_SESSION['sesi_role']) || $_SESSION['sesi_role'] !== 'admin') {
    echo "<script>alert('Akses ditolak');location.replace('../auth/logout.php');</script>";
    exit;
}

$redirect = "../dashboard/admin?page=Bank";

if (isset($_POST['btn_add_bank'])) {
    mysqli_query($koneksi, "
        INSERT INTO bank (nama_bank, kode_bank)
        VALUES ('$_POST[nama_bank]', '$_POST[kode_bank]')
    ");
    echo "<script>alert('Bank ditambahkan');location.replace('$redirect');</script>";
}

if (isset($_POST['btn_edit_bank'])) {
    mysqli_query($koneksi, "
        UPDATE bank SET
            nama_bank='$_POST[nama_bank]',
            kode_bank='$_POST[kode_bank]'
        WHERE id_bank='$_POST[id_bank]'
    ");
    echo "<script>alert('Bank diperbarui');location.replace('$redirect');</script>";
}

if (isset($_POST['btn_delete_bank'])) {
    mysqli_query($koneksi, "
        DELETE FROM bank WHERE id_bank='$_POST[id_bank]'
    ");
    echo "<script>alert('Bank dihapus');location.replace('$redirect');</script>";
}
