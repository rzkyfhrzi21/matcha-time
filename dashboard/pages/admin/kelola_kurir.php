<?php
// Proteksi role ADMIN
if (!isset($_SESSION['sesi_role']) || $_SESSION['sesi_role'] !== 'admin') {
    return;
}

$sql = mysqli_query($koneksi, "SELECT * FROM kurir ORDER BY nama_kurir ASC");
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Kurir</h3>
                <p class="text-subtitle text-muted">
                    Kelola data jasa pengiriman pesanan.
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
            <h4 class="card-title">Daftar Kurir</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKurir">
                <i class="bi bi-plus-circle"></i> Tambah Kurir
            </button>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-striped align-middle" id="lengkap1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Kurir</th>
                        <th>Kode Kurir</th>
                        <th>Kontak</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($k = mysqli_fetch_assoc($sql)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($k['nama_kurir']); ?></td>
                            <td><?= htmlspecialchars($k['kode_kurir']); ?></td>
                            <td><?= htmlspecialchars($k['kontak']); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit<?= $k['id_kurir']; ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>

                                <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDelete<?= $k['id_kurir']; ?>">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================= MODAL TAMBAH ================= -->
    <div class="modal fade" id="modalTambahKurir" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="../functions/function_kurir.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kurir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Kurir</label>
                        <input type="text" name="nama_kurir" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Kode Kurir</label>
                        <input type="text" name="kode_kurir" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Kontak</label>
                        <input type="text" name="kontak" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="btn_add_kurir" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL EDIT & DELETE ================= -->
    <?php
    mysqli_data_seek($sql, 0);
    while ($k = mysqli_fetch_assoc($sql)):
    ?>

        <!-- EDIT -->
        <div class="modal fade" id="modalEdit<?= $k['id_kurir']; ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="post" action="../functions/function_kurir.php" class="modal-content">
                    <input type="hidden" name="id_kurir" value="<?= $k['id_kurir']; ?>">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kurir</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Kurir</label>
                            <input type="text" name="nama_kurir" class="form-control"
                                value="<?= htmlspecialchars($k['nama_kurir']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Kode Kurir</label>
                            <input type="text" name="kode_kurir" class="form-control"
                                value="<?= htmlspecialchars($k['kode_kurir']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Kontak</label>
                            <input type="text" name="kontak" class="form-control"
                                value="<?= htmlspecialchars($k['kontak']); ?>" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="btn_edit_kurir" class="btn btn-primary">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DELETE -->
        <div class="modal fade" id="modalDelete<?= $k['id_kurir']; ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="post" action="../functions/function_kurir.php" class="modal-content">
                    <input type="hidden" name="id_kurir" value="<?= $k['id_kurir']; ?>">

                    <div class="modal-header">
                        <h5 class="modal-title text-danger">Hapus Kurir</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p>Yakin ingin menghapus kurir:</p>
                        <strong><?= htmlspecialchars($k['nama_kurir']); ?></strong>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="btn_delete_kurir" class="btn btn-danger">
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>

    <?php endwhile; ?>
</div>