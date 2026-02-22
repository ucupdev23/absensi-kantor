<?php
// helper bulan
$nama_bulan = array(
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni',
    '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
);
$nama_hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
?>

<!-- HEADER + EXPORT -->
<div class="row mb-3 align-items-center">
    <div class="col-12 col-md-6">
        <h5 class="mb-0" style="color:#670F7A;">📄 Laporan Absensi Bulanan</h5>
        <small class="text-muted"><?= isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : ''; ?> <?= $tahun; ?></small>
    </div>
    <div class="col-12 col-md-6 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('laporan/excel?' . http_build_query($_GET)); ?>"
           class="btn btn-sm btn-outline-success">📥 Export Excel</a>
        <a href="<?= base_url('laporan/pdf?' . http_build_query($_GET)); ?>"
           class="btn btn-sm btn-outline-danger">📥 Export PDF</a>
    </div>
</div>

<!-- FILTER FORM -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form class="row g-2" method="get" action="<?= base_url('laporan'); ?>">
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Bulan</label>
                <select name="bulan" class="form-select">
                    <?php foreach ($nama_bulan as $num => $nm): ?>
                        <option value="<?= $num; ?>" <?= $bulan == $num ? 'selected' : ''; ?>>
                            <?= $nm; ?>
                        </option>
                    <?php
endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Tahun</label>
                <input type="number" name="tahun" class="form-control" value="<?= $tahun; ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Pegawai</label>
                <select name="pegawai_id" class="form-select">
                    <option value="">Semua Pegawai</option>
                    <?php foreach ($list_pegawai as $p): ?>
                        <option value="<?= $p->id; ?>" <?= $pegawai_id == $p->id ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($p->nama_lengkap . ' (' . $p->kode_pegawai . ')'); ?>
                        </option>
                    <?php
endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Lokasi Kantor</label>
                <select name="lokasi_id" class="form-select">
                    <option value="">Semua Lokasi</option>
                    <?php foreach ($list_lokasi as $l): ?>
                        <option value="<?= $l->id; ?>" <?= $lokasi_id == $l->id ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($l->nama); ?>
                        </option>
                    <?php
endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary-custom w-100">Tampilkan</button>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($rekap)): ?>

<!-- SUMMARY STAT CARDS -->
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #670F7A !important;">
            <div class="card-body py-3">
                <div class="text-muted small">Total Record Absensi</div>
                <div class="h4 mb-0 fw-bold" style="color:#670F7A;"><?= $summary['total_records']; ?></div>
                <div class="text-muted small">hari kerja tercatat</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #198754 !important;">
            <div class="card-body py-3">
                <div class="text-muted small">Tingkat Kehadiran</div>
                <div class="h4 mb-0 fw-bold text-success"><?= $summary['persen_kehadiran']; ?>%</div>
                <div class="text-muted small"><?= $summary['total_hadir']; ?> dari <?= $summary['total_records']; ?> hari</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #fd7e14 !important;">
            <div class="card-body py-3">
                <div class="text-muted small">Total Keterlambatan</div>
                <div class="h4 mb-0 fw-bold" style="color:#fd7e14;"><?= $summary['total_telat']; ?>x</div>
                <div class="text-muted small"><?= number_format($summary['total_menit_telat']); ?> menit total</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #0d6efd !important;">
            <div class="card-body py-3">
                <div class="text-muted small">Izin / Cuti / Sakit</div>
                <div class="h4 mb-0 fw-bold text-primary"><?= $summary['total_izin'] + $summary['total_cuti'] + $summary['total_sakit']; ?></div>
                <div class="text-muted small">
                    📋 <?= $summary['total_izin']; ?>
                    &nbsp;🏖️ <?= $summary['total_cuti']; ?>
                    &nbsp;🏥 <?= $summary['total_sakit']; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PIE CHART -->
<div class="row g-3 mb-3">
    <div class="col-12 col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="mb-3" style="color:#670F7A;">📊 Komposisi Kehadiran</h6>
                <div style="max-width:280px;margin:0 auto;">
                    <canvas id="chartKomposisi"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="mb-3" style="color:#670F7A;">📋 Rincian Status</h6>
                <table class="table table-sm mb-0">
                    <tr>
                        <td><span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:#198754;"></span> Hadir</td>
                        <td class="fw-bold text-end"><?= $summary['total_hadir']; ?></td>
                        <td class="text-end text-muted"><?= $summary['total_records'] > 0 ? round($summary['total_hadir'] / $summary['total_records'] * 100, 1) : 0; ?>%</td>
                    </tr>
                    <tr>
                        <td><span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:#fd7e14;"></span> Telat</td>
                        <td class="fw-bold text-end"><?= $summary['total_telat']; ?></td>
                        <td class="text-end text-muted"><?= $summary['total_records'] > 0 ? round($summary['total_telat'] / $summary['total_records'] * 100, 1) : 0; ?>%</td>
                    </tr>
                    <tr>
                        <td><span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:#0d6efd;"></span> Izin</td>
                        <td class="fw-bold text-end"><?= $summary['total_izin']; ?></td>
                        <td class="text-end text-muted"><?= $summary['total_records'] > 0 ? round($summary['total_izin'] / $summary['total_records'] * 100, 1) : 0; ?>%</td>
                    </tr>
                    <tr>
                        <td><span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:#ffc107;"></span> Cuti</td>
                        <td class="fw-bold text-end"><?= $summary['total_cuti']; ?></td>
                        <td class="text-end text-muted"><?= $summary['total_records'] > 0 ? round($summary['total_cuti'] / $summary['total_records'] * 100, 1) : 0; ?>%</td>
                    </tr>
                    <tr>
                        <td><span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:#dc3545;"></span> Sakit</td>
                        <td class="fw-bold text-end"><?= $summary['total_sakit']; ?></td>
                        <td class="text-end text-muted"><?= $summary['total_records'] > 0 ? round($summary['total_sakit'] / $summary['total_records'] * 100, 1) : 0; ?>%</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- REKAP PER PEGAWAI -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <h6 class="mb-3" style="color:#670F7A;">👥 Rekap Per Pegawai</h6>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Pegawai</th>
                        <th>Lokasi</th>
                        <th class="text-center">Hadir</th>
                        <th class="text-center">Telat</th>
                        <th class="text-center">Izin</th>
                        <th class="text-center">Cuti</th>
                        <th class="text-center">Sakit</th>
                        <th class="text-center">Telat (menit)</th>
                        <th style="min-width:140px;">% Kehadiran</th>
                        <th class="text-center">Rata-rata Jam/Hari</th>
                        <th class="text-center">Total Jam</th>
                    </tr>
                </thead>
                <tbody>
                <?php
    $no = 1;
    $grand_total_jam = 0;
    $grand_total_hadir = 0;
    $grand_total_telat = 0;
    $grand_total_menit = 0;
    foreach ($rekap as $r):
        $grand_total_jam += $r['total_jam'];
        $grand_total_hadir += $r['hadir'];
        $grand_total_telat += $r['telat'];
        $grand_total_menit += $r['total_menit_telat'];

        // Progress bar color
        $bar_color = '#198754'; // green
        if ($r['persen_hadir'] < 70)
            $bar_color = '#dc3545'; // red
        elseif ($r['persen_hadir'] < 85)
            $bar_color = '#fd7e14'; // orange
        elseif ($r['persen_hadir'] < 95)
            $bar_color = '#ffc107'; // yellow
?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <?= htmlspecialchars($r['nama']); ?><br>
                            <small class="text-muted"><?= htmlspecialchars($r['kode_pegawai']); ?></small>
                        </td>
                        <td><?= htmlspecialchars($r['nama_lokasi']); ?></td>
                        <td class="text-center"><span class="badge bg-success"><?= $r['hadir']; ?></span></td>
                        <td class="text-center">
                            <?php if ($r['telat'] > 0): ?>
                                <span class="badge bg-warning text-dark"><?= $r['telat']; ?></span>
                            <?php
        else: ?>
                                <span class="text-muted">0</span>
                            <?php
        endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($r['izin'] > 0): ?>
                                <span class="badge bg-primary"><?= $r['izin']; ?></span>
                            <?php
        else: ?>
                                <span class="text-muted">0</span>
                            <?php
        endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($r['cuti'] > 0): ?>
                                <span class="badge" style="background:#d4a017;color:#fff;"><?= $r['cuti']; ?></span>
                            <?php
        else: ?>
                                <span class="text-muted">0</span>
                            <?php
        endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($r['sakit'] > 0): ?>
                                <span class="badge bg-danger"><?= $r['sakit']; ?></span>
                            <?php
        else: ?>
                                <span class="text-muted">0</span>
                            <?php
        endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($r['total_menit_telat'] > 0): ?>
                                <span class="text-danger fw-bold"><?= $r['total_menit_telat']; ?></span>
                            <?php
        else: ?>
                                <span class="text-success">0</span>
                            <?php
        endif; ?>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:8px;">
                                    <div class="progress-bar" style="width:<?= $r['persen_hadir']; ?>%;background:<?= $bar_color; ?>;"></div>
                                </div>
                                <small class="fw-bold" style="min-width:35px;"><?= $r['persen_hadir']; ?>%</small>
                            </div>
                        </td>
                        <td class="text-center"><?= number_format($r['rata_jam'], 1, ',', '.'); ?></td>
                        <td class="text-center fw-bold"><?= number_format($r['total_jam'], 2, ',', '.'); ?></td>
                    </tr>
                <?php
    endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td colspan="3" class="text-end">TOTAL</td>
                        <td class="text-center"><?= $grand_total_hadir; ?></td>
                        <td class="text-center"><?= $grand_total_telat; ?></td>
                        <td colspan="3" class="text-center">—</td>
                        <td class="text-center"><?= number_format($grand_total_menit); ?> menit</td>
                        <td></td>
                        <td></td>
                        <td class="text-center"><?= number_format($grand_total_jam, 2, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php
else: ?>
    <div class="alert alert-info">Belum ada data absensi pada periode ini.</div>
<?php
endif; ?>

<!-- DETAIL HARIAN -->
<?php if (!empty($report)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0" style="color:#670F7A;">📅 Detail Harian</h6>
            <small class="text-muted"><?= count($report); ?> records</small>
        </div>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Pegawai</th>
                        <th>Lokasi</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Status Harian</th>
                        <th>Total Jam</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($report as $row): ?>
                    <?php $day_num = date('w', strtotime($row->tanggal)); ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($row->tanggal)); ?></td>
                        <td><small><?= $nama_hari[$day_num]; ?></small></td>
                        <td>
                            <?= htmlspecialchars($row->nama_lengkap); ?><br>
                            <small class="text-muted"><?= htmlspecialchars($row->kode_pegawai); ?></small>
                        </td>
                        <td><?= htmlspecialchars($row->nama_lokasi); ?></td>
                        <td>
                            <?php if ($row->jam_masuk): ?>
                                <?= date('H:i', strtotime($row->jam_masuk)); ?>
                                <br>
                                <?php if ($row->status_masuk == 'Telat'): ?>
                                    <span class="badge bg-warning text-dark" style="font-size:0.65rem;">Telat</span>
                                <?php
            else: ?>
                                    <span class="badge bg-success" style="font-size:0.65rem;">Tepat Waktu</span>
                                <?php
            endif; ?>
                                <?php if ($row->foto_masuk): ?>
                                    <a href="<?= base_url('uploads/absensi/' . $row->foto_masuk); ?>" target="_blank" class="badge bg-secondary" style="font-size:0.6rem;">Foto</a>
                                <?php
            endif; ?>
                                <?php if ($row->lokasi_masuk_lat && $row->lokasi_masuk_lng): ?>
                                    <a href="https://www.google.com/maps?q=<?= $row->lokasi_masuk_lat; ?>,<?= $row->lokasi_masuk_lng; ?>" target="_blank" class="badge bg-info text-dark" style="font-size:0.6rem;">Map</a>
                                <?php
            endif; ?>
                            <?php
        else: ?>
                                <span class="text-muted">-</span>
                            <?php
        endif; ?>
                        </td>
                        <td>
                            <?php if ($row->jam_pulang): ?>
                                <?= date('H:i', strtotime($row->jam_pulang)); ?>
                                <br>
                                <small class="text-muted"><?= $row->status_pulang; ?></small>
                                <?php if ($row->foto_pulang): ?>
                                    <a href="<?= base_url('uploads/absensi/' . $row->foto_pulang); ?>" target="_blank" class="badge bg-secondary" style="font-size:0.6rem;">Foto</a>
                                <?php
            endif; ?>
                                <?php if ($row->lokasi_pulang_lat && $row->lokasi_pulang_lng): ?>
                                    <a href="https://www.google.com/maps?q=<?= $row->lokasi_pulang_lat; ?>,<?= $row->lokasi_pulang_lng; ?>" target="_blank" class="badge bg-info text-dark" style="font-size:0.6rem;">Map</a>
                                <?php
            endif; ?>
                            <?php
        else: ?>
                                <span class="text-muted">-</span>
                            <?php
        endif; ?>
                        </td>
                        <td>
                            <?php
        $sh = $row->status_harian ? $row->status_harian : '-';
        $sh_color = 'secondary';
        if ($sh == 'Hadir')
            $sh_color = 'success';
        elseif ($sh == 'Izin')
            $sh_color = 'primary';
        elseif ($sh == 'Cuti')
            $sh_color = 'warning';
        elseif ($sh == 'Sakit')
            $sh_color = 'danger';
?>
                            <span class="badge bg-<?= $sh_color; ?> <?= $sh == 'Cuti' ? 'text-dark' : ''; ?>"><?= $sh; ?></span>
                        </td>
                        <td>
                            <?= $row->total_jam_kerja ? number_format($row->total_jam_kerja, 2, ',', '.') : '-'; ?>
                        </td>
                    </tr>
                <?php
    endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
endif; ?>

<?php if (!empty($rekap)): ?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function() {
    var ctx = document.getElementById('chartKomposisi');
    if (!ctx) return;

    var hadir = <?= $summary['total_hadir'] - $summary['total_telat']; ?>;
    if (hadir < 0) hadir = 0;

    new Chart(ctx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Hadir (Tepat Waktu)', 'Telat', 'Izin', 'Cuti', 'Sakit'],
            datasets: [{
                data: [
                    hadir,
                    <?= $summary['total_telat']; ?>,
                    <?= $summary['total_izin']; ?>,
                    <?= $summary['total_cuti']; ?>,
                    <?= $summary['total_sakit']; ?>
                ],
                backgroundColor: [
                    '#198754',
                    '#fd7e14',
                    '#0d6efd',
                    '#ffc107',
                    '#dc3545'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 12, usePointStyle: true, pointStyle: 'circle' }
                }
            },
            cutout: '55%'
        }
    });
})();
</script>
<?php
endif; ?>
