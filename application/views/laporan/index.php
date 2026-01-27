<?php
// helper bulan
$nama_bulan = [
    '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
    '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
];
?>

<div class="row mb-3">
    <div class="col-6">
        <h5 style="color:#670F7A;">Laporan Absensi Bulanan</h5>
    </div>
    <div class="col-6 text-end">
    <a href="<?= base_url('laporan/excel?'.http_build_query($_GET)); ?>"
       class="btn btn-sm btn-outline-success">
        Export Excel
    </a>
    <a href="<?= base_url('laporan/pdf?'.http_build_query($_GET)); ?>"
       class="btn btn-sm btn-outline-danger">
        Export PDF
    </a>
    </div>

</div>

<div class="card card-main mb-3">
    <div class="card-body">
        <form class="row g-2" method="get" action="<?= base_url('laporan'); ?>">
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Bulan</label>
                <select name="bulan" class="form-select">
                    <?php foreach ($nama_bulan as $num => $nm): ?>
                        <option value="<?= $num; ?>" <?= $bulan == $num ? 'selected' : ''; ?>>
                            <?= $nm; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Tahun</label>
                <input type="number" name="tahun" class="form-control"
                       value="<?= $tahun; ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Pegawai</label>
                <select name="pegawai_id" class="form-select">
                    <option value="">Semua Pegawai</option>
                    <?php foreach ($list_pegawai as $p): ?>
                        <option value="<?= $p->id; ?>" <?= $pegawai_id == $p->id ? 'selected':''; ?>>
                            <?= htmlspecialchars($p->nama_lengkap.' ('.$p->kode_pegawai.')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Lokasi Kantor</label>
                <select name="lokasi_id" class="form-select">
                    <option value="">Semua Lokasi</option>
                    <?php foreach ($list_lokasi as $l): ?>
                        <option value="<?= $l->id; ?>" <?= $lokasi_id == $l->id ? 'selected':''; ?>>
                            <?= htmlspecialchars($l->nama); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex align-items-end">
                <!-- <a href="<?= base_url('laporan'); ?>" class="btn btn-secondary">
                    Reset
                </a> -->
                <button type="submit" class="btn btn-primary-custom w-100">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($rekap)): ?>
<div class="card card-main mb-3">
    <div class="card-body">
        <h6 class="mb-3" style="color:#670F7A;">Rekap Per Pegawai</h6>
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pegawai</th>
                        <th>Lokasi</th>
                        <th>Hadir</th>
                        <th>Telat</th>
                        <th>Izin</th>
                        <th>Cuti</th>
                        <th>Sakit</th>
                        <th>Total Jam</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach ($rekap as $r): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <?= htmlspecialchars($r['nama']); ?><br>
                            <small class="text-muted"><?= htmlspecialchars($r['kode_pegawai']); ?></small>
                        </td>
                        <td><?= htmlspecialchars($r['nama_lokasi']); ?></td>
                        <td><?= $r['hadir']; ?></td>
                        <td><?= $r['telat']; ?></td>
                        <td><?= $r['izin']; ?></td>
                        <td><?= $r['cuti']; ?></td>
                        <td><?= $r['sakit']; ?></td>
                        <td><?= number_format($r['total_jam'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
    <div class="alert alert-info">Belum ada data absensi pada periode ini.</div>
<?php endif; ?>
<?php if (!empty($report)): ?>
<div class="card card-main">
    <div class="card-body">
        <h6 class="mb-3" style="color:#670F7A;">Detail Harian</h6>
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
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
                    <tr>
                        <td><?= date('d/m/Y', strtotime($row->tanggal)); ?></td>
                        <td>
                            <?= htmlspecialchars($row->nama_lengkap); ?><br>
                            <small class="text-muted"><?= htmlspecialchars($row->kode_pegawai); ?></small>
                        </td>
                        <td><?= htmlspecialchars($row->nama_lokasi); ?></td>
                        <td>
                            <?php if ($row->jam_masuk): ?>
                                <?= date('H:i', strtotime($row->jam_masuk)); ?>
                                <br>
                                <small class="text-muted"><?= $row->status_masuk; ?></small>
                                <?php if ($row->foto_masuk): ?>
                                    <br>
                                    <a href="<?= base_url('uploads/absensi/'.$row->foto_masuk); ?>" target="_blank" class="badge bg-secondary">
                                        Foto
                                    </a>
                                <?php endif; ?>
                                <?php if ($row->lokasi_masuk_lat && $row->lokasi_masuk_lng): ?>
                                    <?php
                                    $url = 'https://www.google.com/maps?q='
                                        .$row->lokasi_masuk_lat.','.$row->lokasi_masuk_lng;
                                    ?>
                                    <a href="<?= $url; ?>" target="_blank" class="badge bg-info text-dark mt-1">
                                        Map
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row->jam_pulang): ?>
                                <?= date('H:i', strtotime($row->jam_pulang)); ?>
                                <br>
                                <small class="text-muted"><?= $row->status_pulang; ?></small>
                                <?php if ($row->foto_pulang): ?>
                                    <br>
                                    <a href="<?= base_url('uploads/absensi/'.$row->foto_pulang); ?>" target="_blank" class="badge bg-secondary">
                                        Foto
                                    </a>
                                <?php endif; ?>
                                <?php if ($row->lokasi_pulang_lat && $row->lokasi_pulang_lng): ?>
                                    <?php
                                    $url2 = 'https://www.google.com/maps?q='
                                        .$row->lokasi_pulang_lat.','.$row->lokasi_pulang_lng;
                                    ?>
                                    <a href="<?= $url2; ?>" target="_blank" class="badge bg-info text-dark mt-1">
                                        Map
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= $row->status_harian ?: '-'; ?></td>
                        <td><?= $row->total_jam_kerja ? number_format($row->total_jam_kerja, 2, ',', '.') : '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
