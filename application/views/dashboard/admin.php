<!-- HEADER: Jam Live + Greeting -->
<div class="row mb-3 align-items-center">
    <div class="col-12 col-md-8">
        <h5 class="mb-0" style="color:#670F7A;">Dashboard Admin</h5>
        <small class="text-muted">Tanggal: <?= date('l, d F Y', strtotime($today)); ?></small>
    </div>
    <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
        <div id="liveClock" style="font-size:1.6rem;font-weight:700;color:#670F7A;letter-spacing:1px;font-variant-numeric:tabular-nums;">--:--:--</div>
        <small class="text-muted">Waktu Server (WIB)</small>
    </div>
</div>

<!-- STAT CARDS (7 cards in 2 rows) -->
<div class="row g-3 mb-3">
    <!-- Total Pegawai -->
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #670F7A !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:rgba(103,15,122,0.1);">
                        <span style="font-size:1.2rem;">👥</span>
                    </div>
                    <div>
                        <div class="text-muted small">Total Pegawai</div>
                        <div class="h4 mb-0 fw-bold"><?= $total_pegawai; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hadir -->
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #198754 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:rgba(25,135,84,0.1);">
                        <span style="font-size:1.2rem;">✅</span>
                    </div>
                    <div>
                        <div class="text-muted small">Hadir</div>
                        <div class="h4 mb-0 fw-bold text-success"><?= $hadir; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Telat -->
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #fd7e14 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:rgba(253,126,20,0.1);">
                        <span style="font-size:1.2rem;">⏰</span>
                    </div>
                    <div>
                        <div class="text-muted small">Telat</div>
                        <div class="h4 mb-0 fw-bold" style="color:#fd7e14;"><?= $telat; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Belum Absen -->
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #6c757d !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:rgba(108,117,125,0.1);">
                        <span style="font-size:1.2rem;">❓</span>
                    </div>
                    <div>
                        <div class="text-muted small">Belum Absen</div>
                        <div class="h4 mb-0 fw-bold text-secondary"><?= $belum_absen; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Ganti Hari -->
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #0dcaf0 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:rgba(13,202,240,0.1);">
                        <span style="font-size:1.2rem;">🔄</span>
                    </div>
                    <div>
                        <div class="text-muted small">Ganti Hari</div>
                        <div class="h4 mb-0 fw-bold text-info"><?= $ganti_hari; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Potong Gaji -->
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #6610f2 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:rgba(102,16,242,0.1);">
                        <span style="font-size:1.2rem;">📉</span>
                    </div>
                    <div>
                        <div class="text-muted small">Potong Gaji</div>
                        <div class="h4 mb-0 fw-bold" style="color:#6610f2;"><?= $potong_gaji; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cuti -->
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #ffc107 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:rgba(255,193,7,0.15);">
                        <span style="font-size:1.2rem;">🏖️</span>
                    </div>
                    <div>
                        <div class="text-muted small">Cuti</div>
                        <div class="h4 mb-0 fw-bold" style="color:#d4a017;"><?= $cuti; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sakit -->
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #dc3545 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:rgba(220,53,69,0.1);">
                        <span style="font-size:1.2rem;">🏥</span>
                    </div>
                    <div>
                        <div class="text-muted small">Sakit</div>
                        <div class="h4 mb-0 fw-bold text-danger"><?= $sakit; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ALERT ROW: Pengajuan Pending + Penugasan Hari Ini -->
<div class="row g-3 mb-3">
    <!-- Pengajuan Pending -->
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0" style="color:#670F7A;">📩 Pengajuan Pending</h6>
                    <a href="<?= base_url('pengajuan-admin'); ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <?php if ($pending_count > 0): ?>
                    <div class="d-flex align-items-center">
                        <div class="display-6 fw-bold text-warning me-3"><?= $pending_count; ?></div>
                        <div class="text-muted">pengajuan menunggu<br>persetujuan Anda</div>
                    </div>
                <?php
else: ?>
                    <div class="text-muted"><span class="text-success">✔</span> Tidak ada pengajuan yang pending</div>
                <?php
endif; ?>
            </div>
        </div>
    </div>
    <!-- Penugasan Lapangan Hari Ini -->
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0" style="color:#670F7A;">📍 Penugasan Lapangan Hari Ini</h6>
                    <a href="<?= base_url('penugasan_lapangan'); ?>" class="btn btn-sm btn-outline-primary">Kelola</a>
                </div>
                <?php if (!empty($tugas_hari_ini)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <?php foreach ($tugas_hari_ini as $t): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($t->lokasi_nama); ?></strong></td>
                                <td class="text-end">
                                    <span class="badge bg-info text-dark"><?=(int)$t->jml_anggota; ?> orang</span>
                                    <span class="text-muted small">
                                        <?= $t->start_time ? substr($t->start_time, 0, 5) : '--:--'; ?>
                                        -
                                        <?= $t->end_time ? substr($t->end_time, 0, 5) : '--:--'; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php
    endforeach; ?>
                        </table>
                    </div>
                <?php
else: ?>
                    <div class="text-muted">Tidak ada penugasan aktif hari ini</div>
                <?php
endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- CHART: Tren Kehadiran 7 Hari Terakhir -->
<div class="row g-3 mb-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="mb-3" style="color:#670F7A;">📊 Tren Kehadiran 7 Hari Terakhir</h6>
                <div style="height:260px;position:relative;">
                    <canvas id="chartKehadiran"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ABSENSI REALTIME + LEADERBOARD -->
<div class="row g-3 mb-3">
    <!-- Absensi Realtime Hari Ini -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="mb-3" style="color:#670F7A;">🟢 Absensi Masuk Hari Ini</h6>
                <?php if (!empty($absensi_realtime)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Pegawai</th>
                                    <th>Jam Masuk</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($absensi_realtime as $a): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($a->nama_lengkap); ?></div>
                                        <div class="small text-muted"><?= htmlspecialchars($a->kode_pegawai); ?></div>
                                    </td>
                                    <td><?= date('H:i', strtotime($a->jam_masuk)); ?></td>
                                    <td>
                                        <?php if ($a->status_masuk == 'Telat'): ?>
                                            <span class="badge bg-warning text-dark">Telat</span>
                                        <?php
        else: ?>
                                            <span class="badge bg-success">Tepat Waktu</span>
                                        <?php
        endif; ?>
                                    </td>
                                </tr>
                            <?php
    endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php
else: ?>
                    <div class="text-muted text-center py-3">Belum ada yang absen hari ini</div>
                <?php
endif; ?>
            </div>
        </div>
    </div>

    <!-- Leaderboard Column -->
    <div class="col-12 col-lg-6">
        <!-- TOP RAJIN -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 style="color:#670F7A;">🏆 Paling Rajin Bulan Ini</h6>
                <table class="table table-sm mt-2 mb-0">
                    <thead>
                        <tr><th>#</th><th>Nama</th><th>Total Hadir</th></tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($top_rajin)):
    $no = 1; ?>
                            <?php foreach ($top_rajin as $row): ?>
                                <tr>
                                    <td>
                                        <?php if ($no == 1): ?><span style="font-size:1.1rem;">🥇</span>
                                        <?php
        elseif ($no == 2): ?><span style="font-size:1.1rem;">🥈</span>
                                        <?php
        elseif ($no == 3): ?><span style="font-size:1.1rem;">🥉</span>
                                        <?php
        else: ?><?= $no; ?>
                                        <?php
        endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row->nama_lengkap); ?></td>
                                    <td><strong><?= $row->total_hadir; ?></strong> hari</td>
                                </tr>
                            <?php $no++;
    endforeach; ?>
                        <?php
else: ?>
                            <tr><td colspan="3" class="text-center text-muted">Belum ada data</td></tr>
                        <?php
endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TOP TELAT -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 style="color:#670F7A;">⏰ Paling Sering Telat</h6>
                <table class="table table-sm mt-2 mb-0">
                    <thead>
                        <tr><th>#</th><th>Nama</th><th>Total Telat</th></tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($top_telat)):
    $no = 1; ?>
                            <?php foreach ($top_telat as $row): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row->nama_lengkap); ?></td>
                                    <td><span class="text-danger fw-bold"><?= $row->total_telat; ?>x</span></td>
                                </tr>
                            <?php
    endforeach; ?>
                        <?php
else: ?>
                            <tr><td colspan="3" class="text-center text-muted">Belum ada data</td></tr>
                        <?php
endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MENU CEPAT -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="mb-3" style="color:#670F7A;">⚡ Menu Cepat</h6>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= base_url('laporan'); ?>" class="btn btn-outline-secondary btn-sm">📄 Laporan Absensi</a>
                    <a href="<?= base_url('pengajuan-admin'); ?>" class="btn btn-outline-secondary btn-sm">📩 Pengajuan Cuti/Izin</a>
                    <a href="<?= base_url('pegawai'); ?>" class="btn btn-outline-secondary btn-sm">👥 Master Pegawai</a>
                    <a href="<?= base_url('lokasi'); ?>" class="btn btn-outline-secondary btn-sm">📍 Master Lokasi</a>
                    <a href="<?= base_url('penugasan_lapangan'); ?>" class="btn btn-outline-secondary btn-sm">🗺️ Penugasan Lapangan</a>
                    <a href="<?= base_url('penugasan_lapangan/history'); ?>" class="btn btn-outline-secondary btn-sm">📜 Riwayat Penugasan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN + Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
// === Live Clock ===
function updateClock() {
    var now = new Date();
    var h = String(now.getHours()).padStart(2, '0');
    var m = String(now.getMinutes()).padStart(2, '0');
    var s = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('liveClock').textContent = h + ':' + m + ':' + s;
}
setInterval(updateClock, 1000);
updateClock();

// === Chart Kehadiran 7 Hari ===
(function() {
    var ctx = document.getElementById('chartKehadiran').getContext('2d');
    var labels = <?= json_encode(array_map(function ($d) {
    return $d['label']; }, $weekly_trend)); ?>;
    var dataHadir = <?= json_encode(array_map(function ($d) {
    return $d['hadir']; }, $weekly_trend)); ?>;
    var dataTelat = <?= json_encode(array_map(function ($d) {
    return $d['telat']; }, $weekly_trend)); ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Hadir',
                    data: dataHadir,
                    backgroundColor: 'rgba(25,135,84,0.75)',
                    borderRadius: 4,
                    barPercentage: 0.6
                },
                {
                    label: 'Telat',
                    data: dataTelat,
                    backgroundColor: 'rgba(253,126,20,0.75)',
                    borderRadius: 4,
                    barPercentage: 0.6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
})();
</script>
