<?php
if (!isset($_SESSION['sesi_role']) || $_SESSION['sesi_role'] !== 'admin') {
    return;
}

$sql = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY nama_supplier ASC");
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Profil</h3>
                <p class="text-subtitle text-muted">
                    Hi, Perbarui data anda dengan hati-hati.
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                        <li class="breadcrumb-item active text-capitalize" aria-current="page">
                            <?= $page; ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Supplier</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSupplier">
                <i class="bi bi-plus-circle"></i> Tambah Supplier
            </button>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-striped align-middle" id="lengkap1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Supplier</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($s = mysqli_fetch_assoc($sql)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($s['nama_supplier']); ?></td>
                            <td><?= htmlspecialchars($s['kontak']); ?></td>
                            <td><?= htmlspecialchars($s['alamat']); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit<?= $s['id_supplier']; ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>

                                <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDelete<?= $s['id_supplier']; ?>">
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
    <div class="modal fade" id="modalTambahSupplier" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="../functions/function_supplier.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Supplier</label>
                        <input type="text" name="nama_supplier" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Kontak</label>
                        <input type="text" name="kontak" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="btn_add_supplier" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL EDIT & DELETE ================= -->
    <?php
    mysqli_data_seek($sql, 0);
    while ($s = mysqli_fetch_assoc($sql)):
    ?>

        <!-- EDIT -->
        <div class="modal fade" id="modalEdit<?= $s['id_supplier']; ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="post" action="../functions/function_supplier.php" class="modal-content">
                    <input type="hidden" name="id_supplier" value="<?= $s['id_supplier']; ?>">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Supplier</label>
                            <input type="text" name="nama_supplier" class="form-control"
                                value="<?= htmlspecialchars($s['nama_supplier']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Kontak</label>
                            <input type="text" name="kontak" class="form-control"
                                value="<?= htmlspecialchars($s['kontak']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2" required><?= htmlspecialchars($s['alamat']); ?></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="btn_edit_supplier" class="btn btn-primary">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DELETE -->
        <div class="modal fade" id="modalDelete<?= $s['id_supplier']; ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="post" action="../functions/function_supplier.php" class="modal-content">
                    <input type="hidden" name="id_supplier" value="<?= $s['id_supplier']; ?>">

                    <div class="modal-header">
                        <h5 class="modal-title text-danger">Hapus Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p>Yakin ingin menghapus supplier:</p>
                        <strong><?= htmlspecialchars($s['nama_supplier']); ?></strong>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="btn_delete_supplier" class="btn btn-danger">
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endwhile; ?>
</div>