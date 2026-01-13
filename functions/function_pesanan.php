<?php
require_once '../config/config.php';

$sesi_role = $_SESSION['sesi_role'] ?? '';
$sesi_id   = $_SESSION['sesi_id'] ?? null;

/*
|--------------------------------------------------------------------------
| HELPER REDIRECT
|--------------------------------------------------------------------------
*/
function redirectPesanan($role)
{
    echo "<script>
        location.replace('../dashboard/{$role}?page=Pesanan');
    </script>";
    exit;
}

/*
|--------------------------------------------------------------------------
| ADMIN - BUAT PESANAN
|--------------------------------------------------------------------------
*/
if (isset($_POST['btn_add_pesanan'])) {

    if ($sesi_role !== 'admin') {
        redirectPesanan($sesi_role);
    }

    $id_mitra   = $_POST['id_mitra'];
    $id_produk  = $_POST['id_produk'];
    $id_bank    = $_POST['id_bank'];
    $id_kurir   = $_POST['id_kurir'];
    $jumlah     = (int) $_POST['jumlah'];
    $total      = (int) $_POST['total_harga'];

    // cek stok
    $produk = mysqli_fetch_assoc(mysqli_query(
        $koneksi,
        "SELECT stok FROM produk WHERE id_produk='$id_produk'"
    ));

    if (!$produk || $jumlah > $produk['stok']) {
        echo "<script>alert('Jumlah melebihi stok');</script>";
        redirectPesanan('admin');
    }

    // insert pesanan
    mysqli_query($koneksi, "
        INSERT INTO pesanan
        (id_mitra,id_produk,id_bank,id_kurir,jumlah,total_harga,status_pesanan)
        VALUES
        ('$id_mitra','$id_produk','$id_bank','$id_kurir','$jumlah','$total','diproses')
    ");

    $id_pesanan = mysqli_insert_id($koneksi);

    // generate resi
    $kode = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT k.kode_kurir, b.kode_bank
        FROM kurir k, bank b
        WHERE k.id_kurir='$id_kurir'
          AND b.id_bank='$id_bank'
    "));

    $no_resi = $kode['kode_kurir'] . $kode['kode_bank'] . date('Y') . $id_pesanan;

    mysqli_query($koneksi, "
        UPDATE pesanan SET no_resi='$no_resi'
        WHERE id_pesanan='$id_pesanan'
    ");

    // kurangi stok
    mysqli_query($koneksi, "
        UPDATE produk SET stok = stok - $jumlah
        WHERE id_produk='$id_produk'
    ");

    echo "<script>alert('Pesanan berhasil dibuat');</script>";
    redirectPesanan('admin');
}

// ===================================
// MITRA - BUAT PESANAN
// ===================================
if (isset($_POST['btn_pesan_mitra'])) {

    if ($sesi_role !== 'mitra') {
        redirectPesanan($sesi_role);
    }

    $id_produk = $_POST['id_produk'];
    $id_bank   = $_POST['id_bank'];
    $id_kurir  = $_POST['id_kurir'];
    $jumlah    = (int) $_POST['jumlah'];
    $total     = (int) $_POST['total_harga'];

    // ambil id_mitra
    $mitra = mysqli_fetch_assoc(mysqli_query(
        $koneksi,
        "SELECT id_mitra FROM mitra WHERE id_user='$sesi_id'"
    ));
    $id_mitra = $mitra['id_mitra'] ?? null;

    if (!$id_mitra) {
        echo "<script>alert('Data mitra belum lengkap');</script>";
        redirectPesanan('mitra');
    }

    // cek stok
    $produk = mysqli_fetch_assoc(mysqli_query(
        $koneksi,
        "SELECT stok FROM produk WHERE id_produk='$id_produk'"
    ));

    if ($jumlah > $produk['stok']) {
        echo "<script>alert('Jumlah melebihi stok');</script>";
        redirectPesanan('mitra');
    }

    // insert pesanan
    mysqli_query($koneksi, "
        INSERT INTO pesanan
        (id_mitra,id_produk,id_bank,id_kurir,jumlah,total_harga,status_pesanan)
        VALUES
        ('$id_mitra','$id_produk','$id_bank','$id_kurir','$jumlah','$total','diproses')
    ");

    $id_pesanan = mysqli_insert_id($koneksi);

    // generate resi
    $kode = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT 
            (SELECT kode_kurir FROM kurir WHERE id_kurir='$id_kurir') AS kode_kurir,
            (SELECT kode_bank FROM bank WHERE id_bank='$id_bank') AS kode_bank
    "));

    $no_resi = $kode['kode_kurir'] . $kode['kode_bank'] . date('Y') . $id_pesanan;

    mysqli_query($koneksi, "
        UPDATE pesanan SET no_resi='$no_resi'
        WHERE id_pesanan='$id_pesanan'
    ");

    // kurangi stok
    mysqli_query($koneksi, "
        UPDATE produk SET stok = stok - $jumlah
        WHERE id_produk='$id_produk'
    ");

    echo "<script>alert('Pesanan berhasil dibuat');</script>";
    redirectPesanan('mitra');
}


/*
|--------------------------------------------------------------------------
| UPDATE STATUS (ADMIN & MITRA)
|--------------------------------------------------------------------------
*/
if (isset($_POST['btn_update_status'])) {

    $id_pesanan  = $_POST['id_pesanan'];
    $status_lama = $_POST['status_lama'];
    $status_baru = $_POST['status_baru'];

    // aturan
    if ($status_lama === 'dikirim' && $status_baru === 'batal') {
        echo "<script>alert('Pesanan dikirim tidak bisa dibatalkan');</script>";
        redirectPesanan($sesi_role);
    }

    // kembalikan stok jika batal
    if ($status_lama !== 'batal' && $status_baru === 'batal') {
        $q = mysqli_fetch_assoc(mysqli_query(
            $koneksi,
            "SELECT id_produk,jumlah FROM pesanan WHERE id_pesanan='$id_pesanan'"
        ));

        mysqli_query($koneksi, "
            UPDATE produk
            SET stok = stok + {$q['jumlah']}
            WHERE id_produk='{$q['id_produk']}'
        ");
    }

    mysqli_query($koneksi, "
        UPDATE pesanan
        SET status_pesanan='$status_baru'
        WHERE id_pesanan='$id_pesanan'
    ");

    echo "<script>alert('Status pesanan diperbarui');</script>";
    redirectPesanan($sesi_role);
}

/*
|--------------------------------------------------------------------------
| ADMIN - HAPUS PESANAN (HANYA BATAL)
|--------------------------------------------------------------------------
*/
if (isset($_POST['btn_delete_pesanan'])) {

    if ($sesi_role !== 'admin') {
        redirectPesanan($sesi_role);
    }

    $id_pesanan = $_POST['id_pesanan'];

    $cek = mysqli_fetch_assoc(mysqli_query(
        $koneksi,
        "SELECT status_pesanan FROM pesanan WHERE id_pesanan='$id_pesanan'"
    ));

    if ($cek['status_pesanan'] !== 'batal') {
        echo "<script>alert('Hanya pesanan batal yang bisa dihapus');</script>";
        redirectPesanan('admin');
    }

    mysqli_query($koneksi, "
        DELETE FROM pesanan WHERE id_pesanan='$id_pesanan'
    ");

    echo "<script>alert('Pesanan dihapus');</script>";
    redirectPesanan('admin');
}
