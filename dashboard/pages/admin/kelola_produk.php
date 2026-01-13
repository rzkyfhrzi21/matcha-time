<?php
// ================================
// PROTEKSI ROLE ADMIN
// ================================
if (!isset($_SESSION['sesi_role']) || $_SESSION['sesi_role'] !== 'admin') {
    return;
}

// ================================
// DATA PRODUK + SUPPLIER
// ================================
$sql = mysqli_query($koneksi, "
    SELECT 
        p.id_produk,
        p.nama_produk,
        p.deskripsi,
        p.harga,
        p.stok,
        s.nama_supplier
    FROM produk p
    LEFT JOIN supplier s ON p.id_supplier = s.id_supplier
    ORDER BY p.nama_produk ASC
");

// ================================
// DATA SUPPLIER (UNTUK MODAL)
// ================================
$sql_supplier = mysqli_query(
    $koneksi,
    "SELECT id_supplier, nama_supplier FROM supplier ORDER BY nama_supplier ASC"
);
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Produk</h3>
                <p class="text-subtitle text-muted">
                    Kelola produk, harga, dan stok Matcha Time.
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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Produk</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
                <i class="bi bi-plus-circle"></i> Tambah Produk
            </button>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-striped align-middle" id="lengkap1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Produk</th>
                        <th>Supplier</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php while ($p = mysqli_fetch_assoc($sql)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($p['nama_produk']); ?></td>
                            <td><?= htmlspecialchars($p['nama_supplier'] ?? '-'); ?></td>
                            <td>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></td>
                            <td><?= $p['stok']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit<?= $p['id_produk']; ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDelete<?= $p['id_produk']; ?>">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                    <?php if (mysqli_num_rows($sql) === 0): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada produk
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================= MODAL TAMBAH ================= -->
    <div class="modal fade" id="modalTambahProduk">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="../functions/function_produk.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label>Supplier</label>
                    <select name="id_supplier" class="form-select" required>
                        <option value="">-- Pilih Supplier --</option>
                        <?php while ($s = mysqli_fetch_assoc($sql_supplier)): ?>
                            <option value="<?= $s['id_supplier']; ?>">
                                <?= htmlspecialchars($s['nama_supplier']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label class="mt-2">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control"
                        placeholder="Contoh: Matcha Latte 250ml" required>

                    <label class="mt-2">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control"
                        placeholder="Deskripsi produk" rows="2"></textarea>

                    <label class="mt-2">Harga</label>
                    <input type="number" name="harga" class="form-control"
                        placeholder="Contoh: 18000" required>

                    <label class="mt-2">Stok</label>
                    <input type="number" name="stok" class="form-control"
                        placeholder="Jumlah stok" required>
                </div>

                <div class="modal-footer">
                    <button name="btn_add_produk" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL EDIT & DELETE ================= -->
    <?php
    mysqli_data_seek($sql, 0);
    while ($p = mysqli_fetch_assoc($sql)):
    ?>

        <!-- EDIT -->
        <div class="modal fade" id="modalEdit<?= $p['id_produk']; ?>">
            <div class="modal-dialog modal-dialog-centered">
                <form method="post" action="../functions/function_produk.php" class="modal-content">
                    <input type="hidden" name="id_produk" value="<?= $p['id_produk']; ?>">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control"
                            value="<?= htmlspecialchars($p['nama_produk']); ?>" required>

                        <label class="mt-2">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2"><?= htmlspecialchars($p['deskripsi']); ?></textarea>

                        <label class="mt-2">Harga</label>
                        <input type="number" name="harga" class="form-control"
                            value="<?= $p['harga']; ?>" required>

                        <label class="mt-2">Stok</label>
                        <input type="number" name="stok" class="form-control"
                            value="<?= $p['stok']; ?>" required>
                    </div>

                    <div class="modal-footer">
                        <button name="btn_edit_produk" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DELETE -->
        <div class="modal fade" id="modalDelete<?= $p['id_produk']; ?>">
            <div class="modal-dialog modal-dialog-centered">
                <form method="post" action="../functions/function_produk.php" class="modal-content">
                    <input type="hidden" name="id_produk" value="<?= $p['id_produk']; ?>">
                    <div class="modal-body">
                        Yakin ingin menghapus produk
                        <strong><?= htmlspecialchars($p['nama_produk']); ?></strong>?
                    </div>
                    <div class="modal-footer">
                        <button name="btn_delete_produk" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>

    <?php endwhile; ?>
</div>