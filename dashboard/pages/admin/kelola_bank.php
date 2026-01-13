<?php
if (!isset($_SESSION['sesi_role']) || $_SESSION['sesi_role'] !== 'admin') {
    return;
}

$sql_bank = mysqli_query($koneksi, "SELECT * FROM bank ORDER BY nama_bank ASC");
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Bank</h3>
                <p class="text-subtitle text-muted">
                    Kelola data bank untuk transaksi pesanan.
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
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Daftar Bank</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBank">
                <i class="bi bi-plus-circle"></i> Tambah Bank
            </button>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-striped" id="lengkap1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Bank</th>
                        <th>Kode Bank</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($b = mysqli_fetch_assoc($sql_bank)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($b['nama_bank']); ?></td>
                            <td><?= htmlspecialchars($b['kode_bank']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#edit<?= $b['id_bank']; ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#delete<?= $b['id_bank']; ?>">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <div class="modal fade" id="modalTambahBank">
        <div class="modal-dialog">
            <form method="post" action="../functions/function_bank.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Bank</h5>
                </div>
                <div class="modal-body">
                    <label>Nama Bank</label>
                    <input type="text" name="nama_bank" class="form-control" required>

                    <label class="mt-2">Kode Bank</label>
                    <input type="text" name="kode_bank" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button name="btn_add_bank" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    mysqli_data_seek($sql_bank, 0);
    while ($b = mysqli_fetch_assoc($sql_bank)):
    ?>

        <!-- EDIT -->
        <div class="modal fade" id="edit<?= $b['id_bank']; ?>">
            <div class="modal-dialog">
                <form method="post" action="../functions/function_bank.php" class="modal-content">
                    <input type="hidden" name="id_bank" value="<?= $b['id_bank']; ?>">
                    <div class="modal-body">
                        <label>Nama Bank</label>
                        <input type="text" name="nama_bank" class="form-control"
                            value="<?= htmlspecialchars($b['nama_bank']); ?>" required>

                        <label class="mt-2">Kode Bank</label>
                        <input type="text" name="kode_bank" class="form-control"
                            value="<?= htmlspecialchars($b['kode_bank']); ?>" required>
                    </div>
                    <div class="modal-footer">
                        <button name="btn_edit_bank" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DELETE -->
        <div class="modal fade" id="delete<?= $b['id_bank']; ?>">
            <div class="modal-dialog">
                <form method="post" action="../functions/function_bank.php" class="modal-content">
                    <input type="hidden" name="id_bank" value="<?= $b['id_bank']; ?>">
                    <div class="modal-body">
                        Yakin hapus <strong><?= htmlspecialchars($b['nama_bank']); ?></strong>?
                    </div>
                    <div class="modal-footer">
                        <button name="btn_delete_bank" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>

    <?php endwhile; ?>
</div>