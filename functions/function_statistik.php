<?php
// ===================================
// FUNCTION STATISTIK DASHBOARD (FINAL)
// ADMIN & MITRA DALAM 1 FILE
// ===================================

require_once '../config/config.php';

$sesi_role = $_SESSION['sesi_role'] ?? '';
$sesi_id   = $_SESSION['sesi_id'] ?? null;

// ===================================
// FILTER PESANAN (DEFAULT)
// ===================================
$where = "WHERE p.status_pesanan != 'batal'";

// ===================================
// FILTER MITRA (BERDASARKAN id_user)
// ===================================
if ($sesi_role === 'mitra' && $sesi_id) {
    $where .= " AND u.id_user = '$sesi_id'";
}

// ===================================
// CARD: TOTAL PESANAN
// ===================================
$total_pesanan = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COUNT(*) total
    FROM pesanan p
    JOIN mitra m ON p.id_mitra = m.id_mitra
    JOIN users u ON m.id_user = u.id_user
    $where
"))['total'] ?? 0;

// ===================================
// CARD: PESANAN WAKTU
// ===================================
$pesanan_hari_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COUNT(*) total
    FROM pesanan p
    JOIN mitra m ON p.id_mitra = m.id_mitra
    JOIN users u ON m.id_user = u.id_user
    $where AND DATE(p.tanggal_pesanan) = CURDATE()
"))['total'] ?? 0;

$pesanan_bulan_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COUNT(*) total
    FROM pesanan p
    JOIN mitra m ON p.id_mitra = m.id_mitra
    JOIN users u ON m.id_user = u.id_user
    $where
    AND MONTH(p.tanggal_pesanan) = MONTH(CURDATE())
    AND YEAR(p.tanggal_pesanan) = YEAR(CURDATE())
"))['total'] ?? 0;

$pesanan_tahun_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COUNT(*) total
    FROM pesanan p
    JOIN mitra m ON p.id_mitra = m.id_mitra
    JOIN users u ON m.id_user = u.id_user
    $where AND YEAR(p.tanggal_pesanan) = YEAR(CURDATE())
"))['total'] ?? 0;

// ===================================
// CARD: PENDAPATAN (SELESAI)
// ===================================
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT IFNULL(SUM(p.total_harga),0) total
    FROM pesanan p
    JOIN mitra m ON p.id_mitra = m.id_mitra
    JOIN users u ON m.id_user = u.id_user
    $where AND p.status_pesanan = 'selesai'
"))['total'] ?? 0;

$pendapatan_bulan_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT IFNULL(SUM(p.total_harga),0) total
    FROM pesanan p
    JOIN mitra m ON p.id_mitra = m.id_mitra
    JOIN users u ON m.id_user = u.id_user
    $where
    AND p.status_pesanan = 'selesai'
    AND MONTH(p.tanggal_pesanan) = MONTH(CURDATE())
    AND YEAR(p.tanggal_pesanan) = YEAR(CURDATE())
"))['total'] ?? 0;

$pendapatan_tahun_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT IFNULL(SUM(p.total_harga),0) total
    FROM pesanan p
    JOIN mitra m ON p.id_mitra = m.id_mitra
    JOIN users u ON m.id_user = u.id_user
    $where
    AND p.status_pesanan = 'selesai'
    AND YEAR(p.tanggal_pesanan) = YEAR(CURDATE())
"))['total'] ?? 0;

// ===================================
// MASTER DATA (ADMIN)
// ===================================
$total_produk   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) total FROM produk"))['total'] ?? 0;
$total_mitra    = 0;
$total_supplier = 0;

if ($sesi_role === 'admin') {
    $total_mitra = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) total FROM mitra"))['total'] ?? 0;
    $total_supplier = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) total FROM supplier"))['total'] ?? 0;
}

// ===================================
// CHART: PESANAN HARIAN (14 HARI)
// ===================================
$chart_pesanan_harian = ['tanggal' => [], 'jumlah' => []];

$q = mysqli_query($koneksi, "
    SELECT DATE(p.tanggal_pesanan) tgl, COUNT(*) jml
    FROM pesanan p
    JOIN mitra m ON p.id_mitra = m.id_mitra
    JOIN users u ON m.id_user = u.id_user
    $where
    GROUP BY DATE(p.tanggal_pesanan)
    ORDER BY tgl DESC
    LIMIT 14
");

$tmp_tgl = [];
$tmp_jml = [];
while ($r = mysqli_fetch_assoc($q)) {
    $tmp_tgl[] = $r['tgl'];
    $tmp_jml[] = (int)$r['jml'];
}

$chart_pesanan_harian['tanggal'] = array_reverse($tmp_tgl);
$chart_pesanan_harian['jumlah']  = array_reverse($tmp_jml);

// ===================================
// CHART: STATUS PESANAN
// ===================================
$chart_status = ['status' => [], 'jumlah' => []];

$q = mysqli_query($koneksi, "
    SELECT p.status_pesanan, COUNT(*) jml
    FROM pesanan p
    JOIN mitra m ON p.id_mitra = m.id_mitra
    JOIN users u ON m.id_user = u.id_user
    $where
    GROUP BY p.status_pesanan
");

while ($r = mysqli_fetch_assoc($q)) {
    $chart_status['status'][] = $r['status_pesanan'];
    $chart_status['jumlah'][] = (int)$r['jml'];
}

// ===================================
// CHART: TOP PRODUK
// ===================================
$chart_produk = ['produk' => [], 'total' => []];

$q = mysqli_query($koneksi, "
    SELECT pr.nama_produk, COUNT(p.id_pesanan) total
    FROM pesanan p
    JOIN produk pr ON p.id_produk = pr.id_produk
    JOIN mitra m ON p.id_mitra = m.id_mitra
    JOIN users u ON m.id_user = u.id_user
    $where
    GROUP BY pr.id_produk
    ORDER BY total DESC
    LIMIT 5
");

while ($r = mysqli_fetch_assoc($q)) {
    $chart_produk['produk'][] = $r['nama_produk'];
    $chart_produk['total'][]  = (int)$r['total'];
}

// ===================================
// CHART: TOP MITRA (ADMIN ONLY)
// ===================================
$chart_mitra = ['mitra' => [], 'total' => []];

if ($sesi_role === 'admin') {
    $q = mysqli_query($koneksi, "
        SELECT u.nama, SUM(p.total_harga) total
        FROM pesanan p
        JOIN mitra m ON p.id_mitra = m.id_mitra
        JOIN users u ON m.id_user = u.id_user
        WHERE p.status_pesanan = 'selesai'
        GROUP BY m.id_mitra
        ORDER BY total DESC
        LIMIT 5
    ");

    while ($r = mysqli_fetch_assoc($q)) {
        $chart_mitra['mitra'][] = $r['nama'];
        $chart_mitra['total'][] = (int)$r['total'];
    }
}
