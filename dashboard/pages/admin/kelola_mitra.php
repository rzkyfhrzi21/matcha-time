<?php
// ================================
// PROTEKSI ROLE ADMIN
// ================================
if (!isset($_SESSION['sesi_role']) || $_SESSION['sesi_role'] !== 'admin') {
    return;
}

/*
JOIN:
- users (data akun)
- mitra (data mitra)
- pesanan (rekap total pesanan & total nilai)
*/
$sql = mysqli_query($koneksi, "
    SELECT 
        m.id_mitra,
        m.id_user,
        u.nama,
        u.username,
        u.no_hp,
        m.alamat_tinggal,
        m.lokasi_stand,
        m.link_gmaps_stand,
        COUNT(p.id_pesanan) AS total_pesanan,
        IFNULL(SUM(p.total_harga), 0) AS total_transaksi
    FROM mitra m
    LEFT JOIN users u ON m.id_user = u.id_user
    LEFT JOIN pesanan p ON p.id_mitra = m.id_mitra
    GROUP BY 
        m.id_mitra,
        m.id_user,
        u.nama,
        u.username,
        u.no_hp,
        m.alamat_tinggal,
        m.lokasi_stand,
        m.link_gmaps_stand
    ORDER BY u.nama ASC
");

// ================================
// USER MITRA YANG BELUM TERDAFTAR DI TABEL MITRA
// ================================
$sql_user = mysqli_query($koneksi, "
    SELECT u.id_user, u.nama
    FROM users u
    LEFT JOIN mitra m ON m.id_user = u.id_user
    WHERE u.role = 'mitra'
      AND m.id_user IS NULL
    ORDER BY u.nama ASC
");
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Mitra</h3>
                <p class="text-subtitle text-muted">
                    Kelola data mitra dan aktivitas pemesanan.
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
            <h4 class="card-title">Daftar Mitra</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahMitra">
                <i class="bi bi-plus-circle"></i> Tambah Mitra
            </button>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-striped align-middle" id="lengkap1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Mitra</th>
                        <th>No HP</th>
                        <th>Lokasi Stand</th>
                        <th>Link Maps</th>
                        <th>Total Pesanan</th>
                        <th>Total Transaksi</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php while ($m = mysqli_fetch_assoc($sql)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($m['nama']); ?></td>
                            <td><?= htmlspecialchars($m['no_hp']); ?></td>
                            <td><?= htmlspecialchars($m['lokasi_stand']); ?></td>
                            <td>
                                <?php if (!empty($m['link_gmaps_stand'])): ?>
                                    <a href="<?= htmlspecialchars($m['link_gmaps_stand']); ?>"
                                        target="_blank"
                                        class="btn btn-outline-info btn-sm">
                                        <i class="bi bi-geo-alt-fill"></i> Maps
                                    </a>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Tidak ada</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $m['total_pesanan']; ?></td>
                            <td>Rp <?= number_format($m['total_transaksi'], 0, ',', '.'); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit<?= $m['id_mitra']; ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>

                                <?php if ($m['total_pesanan'] > 0): ?>
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="bi bi-lock-fill"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDelete<?= $m['id_mitra']; ?>">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                    <?php if (mysqli_num_rows($sql) === 0): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Belum ada data mitra
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================= MODAL TAMBAH MITRA ================= -->
    <div class="modal fade" id="modalTambahMitra">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="../functions/function_mitra.php" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mitra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label>User Mitra</label>
                    <select name="id_user" class="form-select" required>
                        <option value="">-- Pilih User Mitra --</option>
                        <?php while ($u = mysqli_fetch_assoc($sql_user)): ?>
                            <option value="<?= $u['id_user']; ?>">
                                <?= htmlspecialchars($u['nama']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label class="mt-2">Alamat Tinggal</label>
                    <input type="text"
                        name="alamat_tinggal"
                        class="form-control"
                        placeholder="Contoh: Jl. Teuku Umar No. 5"
                        required>

                    <label class="mt-2">Lokasi Stand</label>
                    <input type="text"
                        name="lokasi_stand"
                        class="form-control"
                        placeholder="Contoh: Stand Matcha - Mall Boemi Kedaton"
                        required>

                    <label class="mt-2">Link Google Maps</label>
                    <input type="url"
                        name="link_gmaps_stand"
                        class="form-control"
                        placeholder="https://maps.google.com/...">
                </div>

                <div class="modal-footer">
                    <button name="btn_add_mitra" class="btn btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- ================= MODAL EDIT & DELETE ================= -->
<?php
mysqli_data_seek($sql, 0);
while ($m = mysqli_fetch_assoc($sql)):
?>

    <!-- EDIT -->
    <div class="modal fade" id="modalEdit<?= $m['id_mitra']; ?>">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="../functions/function_mitra.php" class="modal-content">
                <input type="hidden" name="id_mitra" value="<?= $m['id_mitra']; ?>">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Mitra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label>Alamat Tinggal</label>
                    <input type="text"
                        name="alamat_tinggal"
                        class="form-control"
                        placeholder="Alamat tempat tinggal mitra"
                        value="<?= htmlspecialchars($m['alamat_tinggal']); ?>"
                        required>

                    <label class="mt-2">Lokasi Stand</label>
                    <input type="text"
                        name="lokasi_stand"
                        class="form-control"
                        placeholder="Lokasi / nama stand"
                        value="<?= htmlspecialchars($m['lokasi_stand']); ?>"
                        required>

                    <label class="mt-2">Link Google Maps</label>
                    <input type="url"
                        name="link_gmaps_stand"
                        class="form-control"
                        placeholder="Link Google Maps stand"
                        value="<?= htmlspecialchars($m['link_gmaps_stand']); ?>">
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <a href="?page=Users&id=<?= $m['id_user']; ?>"
                        class="btn btn-outline-secondary">
                        <i class="bi bi-person-gear"></i> Edit User
                    </a>
                    <button name="btn_edit_mitra" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE -->
    <div class="modal fade" id="modalDelete<?= $m['id_mitra']; ?>">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="../functions/function_mitra.php" class="modal-content">
                <input type="hidden" name="id_mitra" value="<?= $m['id_mitra']; ?>">
                <div class="modal-body">
                    Yakin ingin menghapus mitra
                    <strong><?= htmlspecialchars($m['nama']); ?></strong>?
                </div>
                <div class="modal-footer">
                    <button name="btn_delete_mitra" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>

<?php endwhile; ?>