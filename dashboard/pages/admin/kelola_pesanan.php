<?php
// ================================
// PROTEKSI ROLE
// ================================
if (!isset($_SESSION['sesi_role']) || !in_array($_SESSION['sesi_role'], ['admin', 'mitra'])) {
    return;
}

$sesi_role = $_SESSION['sesi_role'];
$sesi_id   = $_SESSION['sesi_id'] ?? null;

// ================================
// FILTER SEARCH & TANGGAL
// ================================
$where = [];
if ($sesi_role === 'mitra') {
    $where[] = "u.id_user = '$sesi_id'";
}

if (!empty($_GET['q'])) {
    $q = mysqli_real_escape_string($koneksi, $_GET['q']);
    $where[] = "(u.nama LIKE '$q%' OR m.id_mitra LIKE '%$q%')";
}

if (!empty($_GET['tgl_mulai']) && !empty($_GET['tgl_selesai'])) {
    $mulai  = $_GET['tgl_mulai'];
    $selesai = $_GET['tgl_selesai'];
    $where[] = "DATE(p.tanggal_pesanan) BETWEEN '$mulai' AND '$selesai'";
}

$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// ================================
// QUERY PESANAN
// ================================
$sql = mysqli_query($koneksi, "
    SELECT 
        p.*,
        pr.nama_produk,
        pr.harga,
        u.nama AS nama_mitra,
        m.id_user,
        b.kode_bank,
        k.kode_kurir
    FROM pesanan p
    LEFT JOIN mitra m ON p.id_mitra = m.id_mitra
    LEFT JOIN users u ON m.id_user = u.id_user
    LEFT JOIN produk pr ON p.id_produk = pr.id_produk
    LEFT JOIN bank b ON p.id_bank = b.id_bank
    LEFT JOIN kurir k ON p.id_kurir = k.id_kurir
    $where_sql
    ORDER BY p.tanggal_pesanan DESC
");

// ================================
// DATA MASTER
// ================================
$sql_produk = mysqli_query($koneksi, "
    SELECT id_produk, nama_produk, harga, stok
    FROM produk
    WHERE stok > 0
    ORDER BY nama_produk ASC
");
$sql_bank  = mysqli_query($koneksi, "SELECT * FROM bank");
$sql_kurir = mysqli_query($koneksi, "SELECT * FROM kurir");
$sql_mitra = mysqli_query($koneksi, "
    SELECT m.id_mitra, u.nama 
    FROM mitra m 
    JOIN users u ON m.id_user = u.id_user
");

?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pesanan</h3>
                <p class="text-subtitle text-muted">
                    Pantau dan kelola seluruh pesanan mitra.
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= $page; ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- FILTER -->
    <form method="get" class="row g-2 mb-3">
        <input type="hidden" name="page" value="Pesanan">

        <div class="col-md-4">
            <input type="text" name="q" class="form-control"
                placeholder="Cari nama / ID mitra"
                value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        </div>

        <div class="col-md-3">
            <input type="date" name="tgl_mulai" class="form-control"
                value="<?= $_GET['tgl_mulai'] ?? '' ?>">
        </div>

        <div class="col-md-3">
            <input type="date" name="tgl_selesai" class="form-control"
                value="<?= $_GET['tgl_selesai'] ?? '' ?>">
        </div>

        <div class="col-md-1">
            <button class="btn btn-primary w-100">
                <i class="bi bi-search"></i>
            </button>
        </div>

        <div class="col-md-1">
            <a href="?page=Pesanan" class="btn btn-secondary w-100"
                title="Reset Filter">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>
    </form>


    <?php if ($sesi_role === 'admin'): ?>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahPesanan">
            <i class="bi bi-plus-circle"></i> Buat Pesanan
        </button>
    <?php endif; ?>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle" id="lengkap1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No Resi</th>
                        <th>Waktu Pesan</th>
                        <th>Mitra</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($p = mysqli_fetch_assoc($sql)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($p['no_resi']); ?></td>
                            <td><?= htmlspecialchars($p['tanggal_pesanan']); ?></td>
                            <td><?= htmlspecialchars($p['nama_mitra']); ?></td>
                            <td><?= htmlspecialchars($p['nama_produk']); ?></td>
                            <td><?= $p['jumlah']; ?></td>
                            <td>Rp <?= number_format($p['total_harga'], 0, ',', '.'); ?></td>
                            <td>
                                <?php
                                $badge = 'bg-secondary';
                                if ($p['status_pesanan'] === 'diproses') $badge = 'bg-warning';
                                if ($p['status_pesanan'] === 'dikirim')  $badge = 'bg-info';
                                if ($p['status_pesanan'] === 'selesai')  $badge = 'bg-success';
                                if ($p['status_pesanan'] === 'batal')    $badge = 'bg-danger';
                                ?>
                                <span class="badge <?= $badge; ?>">
                                    <?= ucfirst($p['status_pesanan']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($p['status_pesanan'] !== 'selesai'): ?>
                                    <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalStatus<?= $p['id_pesanan']; ?>">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($p['status_pesanan'] === 'batal'): ?>
                                    <button class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDelete<?= $p['id_pesanan']; ?>">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================= MODAL TAMBAH PESANAN ================= -->
    <div class="modal fade" id="modalTambahPesanan">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="post" action="../functions/function_pesanan.php" class="modal-content">
                <div class="modal-header">
                    <h5>Buat Pesanan</h5>
                </div>
                <div class="modal-body row g-3">

                    <div class="col-md-6">
                        <label>Mitra</label>
                        <select name="id_mitra" class="form-select" required>
                            <?php while ($m = mysqli_fetch_assoc($sql_mitra)): ?>
                                <option value="<?= $m['id_mitra']; ?>"><?= $m['nama']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Produk</label>
                        <select name="id_produk" id="produk" class="form-select" required>
                            <?php while ($pr = mysqli_fetch_assoc($sql_produk)): ?>
                                <option value="<?= $pr['id_produk']; ?>"
                                    data-harga="<?= $pr['harga']; ?>"
                                    data-stok="<?= $pr['stok']; ?>">
                                    <?= $pr['nama_produk']; ?> (Stok: <?= $pr['stok']; ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" required>
                    </div>

                    <div class="col-md-4">
                        <label>Total</label>
                        <input type="text" id="total_view" class="form-control" readonly>
                        <input type="hidden" name="total_harga" id="total">
                    </div>

                    <div class="col-md-4">
                        <label>Bank</label>
                        <select name="id_bank" class="form-select">
                            <?php while ($b = mysqli_fetch_assoc($sql_bank)): ?>
                                <option value="<?= $b['id_bank']; ?>"><?= $b['nama_bank']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Kurir</label>
                        <select name="id_kurir" class="form-select">
                            <?php while ($k = mysqli_fetch_assoc($sql_kurir)): ?>
                                <option value="<?= $k['id_kurir']; ?>"><?= $k['nama_kurir']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button name="btn_add_pesanan" class="btn btn-primary">Buat Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL UPDATE STATUS PESANAN -->
<?php
mysqli_data_seek($sql, 0);
while ($p = mysqli_fetch_assoc($sql)):
?>
    <div class="modal fade" id="modalStatus<?= $p['id_pesanan']; ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="../functions/function_pesanan.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Status Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>
                        Pesanan:
                        <strong><?= htmlspecialchars($p['no_resi']); ?></strong>
                    </p>

                    <input type="hidden" name="id_pesanan" value="<?= $p['id_pesanan']; ?>">
                    <input type="hidden" name="status_lama" value="<?= $p['status_pesanan']; ?>">

                    <label>Status Baru</label>
                    <select name="status_baru" class="form-select" required>

                        <?php if ($p['status_pesanan'] === 'diproses'): ?>
                            <option value="diproses" selected>Diproses</option>
                            <option value="batal">Batalkan</option>

                            <?php if ($sesi_role === 'admin'): ?>
                                <option value="dikirim">Kirimkan</option>
                            <?php endif; ?>
                        <?php endif; ?>

                    </select>

                    <small class="text-muted d-block mt-2">
                        * Pesanan dikirim tidak dapat dibatalkan
                    </small>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="btn_update_status" class="btn btn-primary">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endwhile; ?>
<!-- MODAL HAPUS PESANAN -->
<?php
mysqli_data_seek($sql, 0);
while ($p = mysqli_fetch_assoc($sql)):
    if ($p['status_pesanan'] !== 'batal') continue;
?>
    <div class="modal fade" id="modalDelete<?= $p['id_pesanan']; ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="../functions/function_pesanan.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Hapus Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>Yakin ingin menghapus pesanan:</p>
                    <strong><?= htmlspecialchars($p['no_resi']); ?></strong>

                    <input type="hidden" name="id_pesanan" value="<?= $p['id_pesanan']; ?>">
                </div>

                <div class="modal-footer">
                    <button type="submit" name="btn_delete_pesanan" class="btn btn-danger">
                        Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endwhile; ?>

<script>
    const produk = document.getElementById('produk');
    const jumlah = document.getElementById('jumlah');
    const total = document.getElementById('total');
    const totalView = document.getElementById('total_view');

    function hitung() {
        let h = produk.options[produk.selectedIndex].dataset.harga;
        let s = produk.options[produk.selectedIndex].dataset.stok;
        if (jumlah.value > s) {
            alert('Jumlah melebihi stok');
            jumlah.value = s;
        }
        let t = h * jumlah.value;
        total.value = t;
        totalView.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(t);
    }
    produk.onchange = hitung;
    jumlah.onkeyup = hitung;
</script>