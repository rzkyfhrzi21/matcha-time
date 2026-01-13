<?php
// Proteksi MITRA
if (!isset($_SESSION['sesi_role']) || $_SESSION['sesi_role'] !== 'mitra') {
    return;
}

$sql = mysqli_query($koneksi, "
    SELECT nama_produk, harga, stok 
    FROM produk
    ORDER BY nama_produk ASC
");
?>

<div class="page-heading">
    <h3>Daftar Produk</h3>
    <p class="text-subtitle text-muted">
        Informasi produk tersedia (read-only)
    </p>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped align-middle" id="lengkap1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($p = mysqli_fetch_assoc($sql)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($p['nama_produk']); ?></td>
                        <td>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></td>
                        <td><?= $p['stok']; ?></td>
                        <td>
                            <?php if ($p['stok'] > 0): ?>
                                <span class="badge bg-success">Tersedia</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Habis</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>