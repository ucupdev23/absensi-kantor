<?php
$jam_masuk  = $absen_hari ? $absen_hari->jam_masuk : null;
$jam_pulang = $absen_hari ? $absen_hari->jam_pulang : null;
$status_harian = $absen_hari ? $absen_hari->status_harian : null;
?>

<div class="row mb-3">
    <div class="col-12">
        <h5 style="color:#670F7A;">Halo, <?= htmlspecialchars($pegawai->nama_lengkap); ?> 👋</h5>
        <small class="text-muted">
            <?= htmlspecialchars($pegawai->nama_jabatan); ?> — <?= htmlspecialchars($pegawai->nama_lokasi); ?>
        </small>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-md-6">
        <div class="card card-main">
            <div class="card-body">
                <h6 class="mb-2" style="color:#670F7A;">Status Hari Ini (<?= date('d M Y', strtotime($today)); ?>)</h6>
                <p class="mb-1">
                    Jam Masuk:
                    <strong><?= $jam_masuk ? date('H:i', strtotime($jam_masuk)) : '--:--'; ?></strong>
                    <?php if ($absen_hari && $absen_hari->status_masuk): ?>
                        <small class="text-muted">(<?= $absen_hari->status_masuk; ?>)</small>
                    <?php endif; ?>
                </p>
                <p class="mb-1">
                    Jam Pulang:
                    <strong><?= $jam_pulang ? date('H:i', strtotime($jam_pulang)) : '--:--'; ?></strong>
                    <?php if ($absen_hari && $absen_hari->status_pulang): ?>
                        <small class="text-muted">(<?= $absen_hari->status_pulang; ?>)</small>
                    <?php endif; ?>
                </p>
                <p class="mb-1">
                    Status Harian:
                    <strong><?= $status_harian ?: ($jam_masuk ? 'Hadir' : '-'); ?></strong>
                </p>
                <p class="mb-0">
                    Total Jam:
                    <strong><?= $absen_hari && $absen_hari->total_jam_kerja ? $absen_hari->total_jam_kerja.' jam' : '-'; ?></strong>
                </p>

                <div class="d-grid gap-2 mt-3">
                    <a href="<?= base_url('absensi'); ?>" class="btn btn-primary-custom">
                        Buka Halaman Absensi
                    </a>
                    <a href="<?= base_url('pengajuan'); ?>" class="btn btn-outline-secondary">
                        Pengajuan Cuti / Izin / Sakit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card card-main">
            <div class="card-body">
                <h6 class="mb-2" style="color:#670F7A;">Riwayat 7 Hari Terakhir</h6>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Tgl</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($riwayat)): ?>
                            <?php foreach ($riwayat as $r): ?>
                                <tr>
                                    <td><?= date('d/m', strtotime($r->tanggal)); ?></td>
                                    <td><?= $r->jam_masuk ? date('H:i', strtotime($r->jam_masuk)) : '-'; ?></td>
                                    <td><?= $r->jam_pulang ? date('H:i', strtotime($r->jam_pulang)) : '-'; ?></td>
                                    <td><?= $r->status_harian ?: ($r->jam_masuk ? 'Hadir' : '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-muted text-center">Belum ada data.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
