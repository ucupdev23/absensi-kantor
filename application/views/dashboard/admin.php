<div class="row mb-3">
    <div class="col-12">
        <h5 style="color:#670F7A;">Dashboard Admin</h5>
        <small class="text-muted">Tanggal: <?= date('d M Y', strtotime($today)); ?></small>
    </div>
</div>

<div class="row g-3">
    <div class="col-6 col-md-3">
        <div class="card card-main">
            <div class="card-body text-center">
                <div class="text-muted small">Total Pegawai</div>
                <div class="h4 mb-0"><?= $total_pegawai; ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-main">
            <div class="card-body text-center">
                <div class="text-muted small">Hadir</div>
                <div class="h4 mb-0"><?= $hadir; ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-main">
            <div class="card-body text-center">
                <div class="text-muted small">Izin / Cuti / Sakit</div>
                <div class="h4 mb-0"><?= $izin + $cuti + $sakit; ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-main">
            <div class="card-body text-center">
                <div class="text-muted small">Belum Absen</div>
                <div class="h4 mb-0"><?= $belum_absen; ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card card-main">
            <div class="card-body">
                <h6 class="mb-3" style="color:#670F7A;">Menu Cepat</h6>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= base_url('laporan'); ?>" class="btn btn-outline-secondary btn-sm">
                        Laporan Absensi
                    </a>
                    <a href="<?= base_url('pengajuan-admin'); ?>" class="btn btn-outline-secondary btn-sm">
                        Pengajuan Cuti/Izin/Sakit
                    </a>
                    <a href="<?= base_url('pegawai'); ?>" class="btn btn-outline-secondary btn-sm">
                        Master Pegawai
                    </a>
                    <a href="<?= base_url('lokasi'); ?>" class="btn btn-outline-secondary btn-sm">
                        Master Lokasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
