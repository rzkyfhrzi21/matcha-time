<?php
// =====================================================
// PROTEKSI ROLE MITRA
// =====================================================
if (!isset($_SESSION['sesi_role']) || $_SESSION['sesi_role'] !== 'mitra') {
    return;
}

$sesi_id = $_SESSION['sesi_id'] ?? null;
if (!$sesi_id) return;

// =====================================================
// AMBIL ID MITRA BERDASARKAN USER LOGIN
// =====================================================
$qMitra = mysqli_query($koneksi, "
    SELECT id_mitra 
    FROM mitra 
    WHERE id_user = '$sesi_id'
");
$mitra = mysqli_fetch_assoc($qMitra);
$id_mitra = $mitra['id_mitra'] ?? null;
if (!$id_mitra) return;

// =====================================================
// FILTER SEARCH & TANGGAL (MITRA)
// =====================================================
$where = [];
$where[] = "p.id_mitra = '$id_mitra'";

// search (no resi / produk)
if (!empty($_GET['q'])) {
    $q = mysqli_real_escape_string($koneksi, $_GET['q']);
    $where[] = "(p.no_resi LIKE '%$q%' OR pr.nama_produk LIKE '%$q%')";
}

// filter tanggal
if (!empty($_GET['tgl_mulai']) && !empty($_GET['tgl_selesai'])) {
    $mulai   = $_GET['tgl_mulai'];
    $selesai = $_GET['tgl_selesai'];
    $where[] = "DATE(p.tanggal_pesanan) BETWEEN '$mulai' AND '$selesai'";
}

$where_sql = 'WHERE ' . implode(' AND ', $where);

// =====================================================
// DATA PESANAN MITRA
// =====================================================
$sql = mysqli_query($koneksi, "
    SELECT 
        p.id_pesanan,
        p.no_resi,
        p.tanggal_pesanan,
        p.jumlah,
        p.total_harga,
        p.status_pesanan,
        pr.nama_produk
    FROM pesanan p
    JOIN produk pr ON p.id_produk = pr.id_produk
    $where_sql
    ORDER BY p.tanggal_pesanan DESC
");

// =====================================================
// DATA PRODUK (STOK > 0)
// =====================================================
$sql_produk = mysqli_query($koneksi, "
    SELECT id_produk, nama_produk, harga, stok
    FROM produk
    WHERE stok > 0
    ORDER BY nama_produk ASC
");
// =====================================================
// DATA BANK & KURIR (UNTUK MITRA)
// =====================================================
$sql_bank  = mysqli_query($koneksi, "SELECT id_bank, nama_bank FROM bank");
$sql_kurir = mysqli_query($koneksi, "SELECT id_kurir, nama_kurir FROM kurir");

?>

<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Pesanan</h3>
            <p class="text-subtitle text-muted">
                kelola pesanan anda.
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
            placeholder="Cari no resi / produk"
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

<!-- TOMBOL BUAT PESANAN -->
<button class="btn btn-primary mb-3"
    data-bs-toggle="modal"
    data-bs-target="#modalTambahPesanan">
    <i class="bi bi-plus-circle"></i> Buat Pesanan
</button>

<!-- TABEL PESANAN -->
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped align-middle" id="lengkap1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No Resi</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($p = mysqli_fetch_assoc($sql)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($p['no_resi']); ?></td>
                        <td><?= htmlspecialchars($p['tanggal_pesanan']); ?></td>
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
                                <?= ucfirst(htmlspecialchars($p['status_pesanan'])); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($p['status_pesanan'] === 'diproses'): ?>
                                <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBatal<?= $p['id_pesanan']; ?>">
                                    Batalkan
                                </button>
                            <?php elseif ($p['status_pesanan'] === 'dikirim'): ?>
                                <button class="btn btn-success btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalSelesai<?= $p['id_pesanan']; ?>">
                                    Selesaikan
                                </button>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
mysqli_data_seek($sql, 0);
while ($p = mysqli_fetch_assoc($sql)):
    if ($p['status_pesanan'] !== 'dikirim') continue;
?>
    <div class="modal fade" id="modalSelesai<?= $p['id_pesanan']; ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="../functions/function_pesanan.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-success">Selesaikan Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>
                        Tandai pesanan dengan resi:
                        <strong><?= htmlspecialchars($p['no_resi']); ?></strong>
                        sebagai <b>selesai</b>?
                    </p>

                    <!-- DATA WAJIB UNTUK FUNCTION -->
                    <input type="hidden" name="id_pesanan" value="<?= $p['id_pesanan']; ?>">
                    <input type="hidden" name="status_lama" value="<?= $p['status_pesanan']; ?>">
                    <input type="hidden" name="status_baru" value="selesai">
                </div>

                <div class="modal-footer">
                    <button type="submit" name="btn_update_status" class="btn btn-success">
                        Ya, Selesaikan
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endwhile; ?>
<?php
mysqli_data_seek($sql, 0);
while ($p = mysqli_fetch_assoc($sql)):
    if ($p['status_pesanan'] !== 'diproses') continue;
?>
    <div class="modal fade" id="modalBatal<?= $p['id_pesanan']; ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="../functions/function_pesanan.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Batalkan Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Yakin ingin membatalkan pesanan:
                    <strong><?= htmlspecialchars($p['no_resi']); ?></strong>?

                    <input type="hidden" name="id_pesanan" value="<?= $p['id_pesanan']; ?>">
                    <input type="hidden" name="status_lama" value="<?= $p['status_pesanan']; ?>">
                    <input type="hidden" name="status_baru" value="batal">
                </div>

                <div class="modal-footer">
                    <button type="submit" name="btn_update_status" class="btn btn-danger">
                        Ya, Batalkan
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endwhile; ?>


<!-- ================= MODAL TAMBAH PESANAN (MITRA) ================= -->
<div class="modal fade" id="modalTambahPesanan">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="../functions/function_pesanan.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- PRODUK -->
                <label class="form-label">Produk</label>
                <select name="id_produk" id="produk" class="form-select" required>
                    <option value="">-- Pilih Produk --</option>
                    <?php while ($pr = mysqli_fetch_assoc($sql_produk)): ?>
                        <option value="<?= $pr['id_produk']; ?>"
                            data-harga="<?= $pr['harga']; ?>"
                            data-stok="<?= $pr['stok']; ?>">
                            <?= htmlspecialchars($pr['nama_produk']); ?>
                            (Stok: <?= $pr['stok']; ?>)
                        </option>
                    <?php endwhile; ?>
                </select>

                <!-- JUMLAH -->
                <label class="form-label mt-2">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah"
                    class="form-control" min="1" required>

                <!-- TOTAL -->
                <label class="form-label mt-2">Total Harga</label>
                <input type="text" id="total_view" class="form-control" readonly>
                <input type="hidden" name="total_harga" id="total">

                <!-- BANK -->
                <label class="form-label mt-2">Bank</label>
                <select name="id_bank" class="form-select" required>
                    <option value="">-- Pilih Bank --</option>
                    <?php while ($b = mysqli_fetch_assoc($sql_bank)): ?>
                        <option value="<?= $b['id_bank']; ?>">
                            <?= htmlspecialchars($b['nama_bank']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <!-- KURIR -->
                <label class="form-label mt-2">Kurir</label>
                <select name="id_kurir" class="form-select" required>
                    <option value="">-- Pilih Kurir --</option>
                    <?php while ($k = mysqli_fetch_assoc($sql_kurir)): ?>
                        <option value="<?= $k['id_kurir']; ?>">
                            <?= htmlspecialchars($k['nama_kurir']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="modal-footer">
                <button type="submit" name="btn_pesan_mitra" class="btn btn-primary">
                    Simpan Pesanan
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const produk = document.getElementById('produk');
    const jumlah = document.getElementById('jumlah');
    const total = document.getElementById('total');
    const view = document.getElementById('total_view');

    function hitungTotal() {
        if (!produk.value || !jumlah.value) {
            total.value = '';
            view.value = '';
            return;
        }
        const harga = parseInt(produk.options[produk.selectedIndex].dataset.harga);
        const stok = parseInt(produk.options[produk.selectedIndex].dataset.stok);
        let qty = parseInt(jumlah.value);

        if (qty > stok) {
            alert('Jumlah melebihi stok tersedia!');
            qty = stok;
            jumlah.value = stok;
        }

        const hasil = harga * qty;
        total.value = hasil;
        view.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(hasil);
    }

    produk.addEventListener('change', hitungTotal);
    jumlah.addEventListener('keyup', hitungTotal);
</script>