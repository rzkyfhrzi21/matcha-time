<?php
// ================================
// DASHBOARD
// ================================
require_once '../functions/function_statistik.php';

$sesi_role = $_SESSION['sesi_role'];
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard</h3>
                <p class="text-subtitle text-muted">
                    Ringkasan statistik dan aktivitas Matcha Time
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- ================= CARD STATISTIK ================= -->
<div class="row">

    <!-- TOTAL PESANAN -->
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon purple mb-2">
                            <i class="iconly-boldDocument"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Total Pesanan</h6>
                        <h6 class="font-extrabold mb-0"><?= $total_pesanan; ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PESANAN HARI INI -->
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon blue mb-2">
                            <i class="iconly-boldTime-Circle"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Hari Ini</h6>
                        <h6 class="font-extrabold mb-0"><?= $pesanan_hari_ini; ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BULAN INI -->
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon green mb-2">
                            <i class="iconly-boldCalendar"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Bulan Ini</h6>
                        <h6 class="font-extrabold mb-0"><?= $pesanan_bulan_ini; ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAHUN INI -->
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon red mb-2">
                            <i class="iconly-boldChart"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Tahun Ini</h6>
                        <h6 class="font-extrabold mb-0"><?= $pesanan_tahun_ini; ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TOTAL PENDAPATAN -->
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon green mb-2">
                            <i class="iconly-boldWallet"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Total Pendapatan</h6>
                        <h6 class="font-extrabold mb-0">
                            Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PENDAPATAN BULAN INI -->
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon blue mb-2">
                            <i class="iconly-boldWallet"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Pendapatan Bulan Ini</h6>
                        <h6 class="font-extrabold mb-0">
                            Rp <?= number_format($pendapatan_bulan_ini, 0, ',', '.'); ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PENDAPATAN TAHUN INI -->
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon purple mb-2">
                            <i class="iconly-boldWallet"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Pendapatan Tahun Ini</h6>
                        <h6 class="font-extrabold mb-0">
                            Rp <?= number_format($pendapatan_tahun_ini, 0, ',', '.'); ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TOTAL PRODUK -->
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon blue mb-2">
                            <i class="iconly-boldBag"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Produk</h6>
                        <h6 class="font-extrabold mb-0"><?= $total_produk; ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($sesi_role === 'admin'): ?>
        <!-- TOTAL MITRA -->
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldUser"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Mitra</h6>
                            <h6 class="font-extrabold mb-0"><?= $total_mitra; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOTAL SUPPLIER -->
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon red mb-2">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Supplier</h6>
                            <h6 class="font-extrabold mb-0"><?= $total_supplier; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<!-- ================= GRAFIK ================= -->
<div class="row mt-4">

    <!-- TREN PESANAN -->
    <div class="col-12 col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4>Tren Pesanan Harian</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">Jumlah pesanan berdasarkan tanggal</p>
                <div id="chartPesanan"></div>
            </div>
        </div>
    </div>

    <!-- STATUS PESANAN -->
    <div class="col-12 col-xl-4">
        <div class="card">
            <div class="card-header">
                <h4>Status Pesanan</h4>
            </div>
            <div class="card-body">
                <?php foreach ($chart_status['status'] as $i => $st): ?>
                    <div class="row mb-2">
                        <div class="col-7">
                            <div class="d-flex align-items-center">
                                <svg class="bi text-primary" width="10" height="10">
                                    <use xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                </svg>
                                <h6 class="mb-0 ms-3 text-capitalize"><?= $st; ?></h6>
                            </div>
                        </div>
                        <div class="col-5">
                            <h6 class="mb-0 text-end"><?= $chart_status['jumlah'][$i]; ?></h6>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div id="chartStatus"></div>
            </div>
        </div>
    </div>

    <!-- TOP PRODUK -->
    <div class="col-12 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4>Top Produk</h4>
            </div>
            <div class="card-body">
                <?php foreach ($chart_produk['produk'] as $i => $pr): ?>
                    <div class="row mb-2">
                        <div class="col-7">
                            <div class="d-flex align-items-center">
                                <svg class="bi text-success" width="10" height="10">
                                    <use xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                </svg>
                                <h6 class="mb-0 ms-3"><?= htmlspecialchars($pr); ?></h6>
                            </div>
                        </div>
                        <div class="col-5">
                            <h6 class="mb-0 text-end"><?= $chart_produk['total'][$i]; ?></h6>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div id="chartProduk"></div>
            </div>
        </div>
    </div>

    <!-- TOP MITRA -->
    <?php if ($sesi_role === 'admin'): ?>
        <div class="col-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4>Top Mitra</h4>
                </div>
                <div class="card-body">
                    <?php foreach ($chart_mitra['mitra'] as $i => $mt): ?>
                        <div class="row mb-2">
                            <div class="col-7">
                                <div class="d-flex align-items-center">
                                    <svg class="bi text-danger" width="10" height="10">
                                        <use xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                    </svg>
                                    <h6 class="mb-0 ms-3"><?= htmlspecialchars($mt); ?></h6>
                                </div>
                            </div>
                            <div class="col-5">
                                <h6 class="mb-0 text-end">
                                    Rp <?= number_format($chart_mitra['total'][$i], 0, ',', '.'); ?>
                                </h6>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div id="chartMitra"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<!-- ================= APEX ================= -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    new ApexCharts(chartPesanan, {
        chart: {
            type: 'line',
            height: 280
        },
        series: [{
            name: 'Pesanan',
            data: <?= json_encode($chart_pesanan_harian['jumlah']) ?>
        }],
        xaxis: {
            categories: <?= json_encode($chart_pesanan_harian['tanggal']) ?>
        }
    }).render();

    new ApexCharts(chartStatus, {
        chart: {
            type: 'donut',
            height: 260
        },
        series: <?= json_encode($chart_status['jumlah']) ?>,
        labels: <?= json_encode($chart_status['status']) ?>
    }).render();

    new ApexCharts(chartProduk, {
        chart: {
            type: 'bar',
            height: 260
        },
        series: [{
            name: 'Total',
            data: <?= json_encode($chart_produk['total']) ?>
        }],
        xaxis: {
            categories: <?= json_encode($chart_produk['produk']) ?>
        }
    }).render();

    <?php if ($sesi_role === 'admin'): ?>
        new ApexCharts(chartMitra, {
            chart: {
                type: 'bar',
                height: 260
            },
            series: [{
                name: 'Transaksi',
                data: <?= json_encode($chart_mitra['total']) ?>
            }],
            xaxis: {
                categories: <?= json_encode($chart_mitra['mitra']) ?>
            }
        }).render();
    <?php endif; ?>
</script>