<?php
$status_harian = $absen_hari ? $absen_hari->status_harian : null;
$is_libur_pribadi = in_array($status_harian, ['Cuti','Izin','Sakit']);

$jam_masuk  = $absen_hari ? $absen_hari->jam_masuk : null;
$jam_pulang = $absen_hari ? $absen_hari->jam_pulang : null;
?>

<div class="row">
    <div class="col-12 col-lg-6 mb-3">
        <div class="card card-main">
            <div class="card-body">
                <h5 class="mb-1" style="color:#670F7A;">Absensi Hari Ini</h5>
                <small class="text-muted"><?= date('d M Y', strtotime($today)); ?></small>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger py-2 mt-3">
                        <?= $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success py-2 mt-3">
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <div class="mt-3">
                    <p class="mb-1"><strong>Nama:</strong> <?= htmlspecialchars($pegawai->nama_lengkap); ?></p>
                    <p class="mb-1"><strong>Jabatan:</strong> <?= htmlspecialchars($pegawai->nama_jabatan); ?></p>
                    <p class="mb-1"><strong>Kantor:</strong> <?= htmlspecialchars($pegawai->nama_lokasi); ?></p>
                    <p class="mb-0"><strong>Shift:</strong>
                        <?= htmlspecialchars($pegawai->nama_shift); ?>
                        (<?= substr($pegawai->jam_masuk,0,5); ?> - <?= substr($pegawai->jam_pulang,0,5); ?>)
                    </p>
                </div>

                <hr>

                <div class="row text-center">
                    <div class="col-6">
                        <div class="mb-1 text-muted">Jam Masuk</div>
                        <div class="fw-bold">
                            <?= $jam_masuk ? date('H:i', strtotime($jam_masuk)) : '--:--'; ?>
                        </div>
                        <small class="text-muted">
                            <?= $absen_hari ? $absen_hari->status_masuk : '-'; ?>
                        </small>
                    </div>
                    <div class="col-6">
                        <div class="mb-1 text-muted">Jam Pulang</div>
                        <div class="fw-bold">
                            <?= $jam_pulang ? date('H:i', strtotime($jam_pulang)) : '--:--'; ?>
                        </div>
                        <small class="text-muted">
                            <?= $absen_hari ? $absen_hari->status_pulang : '-'; ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form absen -->
    <div class="col-12 col-lg-6 mb-3">
        <div class="card card-main">
            <div class="card-body">
                <h6 class="mb-3" style="color:#670F7A;">Aksi Absensi</h6>

                <!-- Absen Masuk -->
                <form id="form_masuk" action="<?= base_url('absensi/masuk'); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="latitude" id="lat_masuk">
                    <input type="hidden" name="longitude" id="lng_masuk">
                    <input type="file" name="foto" id="foto_masuk" accept="image/*" capture="user" class="d-none">
                </form>

                <!-- Absen Pulang -->
                <form id="form_pulang" action="<?= base_url('absensi/pulang'); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="latitude" id="lat_pulang">
                    <input type="hidden" name="longitude" id="lng_pulang">
                    <input type="file" name="foto" id="foto_pulang" accept="image/*" capture="user" class="d-none">
                </form>

                <div class="d-grid gap-2 mt-2">
                    <button type="button"
                            class="btn btn-primary-custom"
                            onclick="prepareAbsen('masuk')"
                        <?= ($jam_masuk || $is_libur_pribadi) ? 'disabled' : ''; ?>>
                        <?= $jam_masuk ? 'Sudah Absen Masuk' : 'Absen Masuk'; ?>
                    </button>

                    <button type="button"
                            class="btn btn-primary-custom"
                            onclick="prepareAbsen('pulang')"
                        <?= (!$jam_masuk || $jam_pulang || $is_libur_pribadi) ? 'disabled' : ''; ?>>
                        <?php
                        if (!$jam_masuk) echo 'Absen Pulang (belum absen masuk)';
                        elseif ($jam_pulang) echo 'Sudah Absen Pulang';
                        else echo 'Absen Pulang';
                        ?>
                    </button>
                </div>

                <?php if ($is_libur_pribadi): ?>
    <div class="alert alert-info py-2 mt-3">
        Hari ini tercatat sebagai <strong><?= $status_harian; ?></strong>, Anda tidak perlu absen.
    </div>
<?php endif; ?>


                <small class="d-block mt-3 text-muted">
                    Saat menekan tombol absen, sistem akan mengambil <strong>lokasi</strong> dan
                    membuka kamera HP untuk mengambil <strong>foto</strong>. Pastikan izin lokasi & kamera sudah diaktifkan.
                </small>
            </div>
        </div>
    </div>
</div>

<script>
function prepareAbsen(type) {
    if (!navigator.geolocation) {
        alert('Browser tidak mendukung lokasi. Gunakan browser lain.');
        return;
    }

    navigator.geolocation.getCurrentPosition(function (position) {
        if (type === 'masuk') {
            document.getElementById('lat_masuk').value = position.coords.latitude;
            document.getElementById('lng_masuk').value = position.coords.longitude;
            document.getElementById('foto_masuk').click();
        } else {
            document.getElementById('lat_pulang').value = position.coords.latitude;
            document.getElementById('lng_pulang').value = position.coords.longitude;
            document.getElementById('foto_pulang').click();
        }
    }, function (error) {
        alert('Gagal mengambil lokasi: ' + error.message);
    });
}

// auto submit setelah foto dipilih
document.getElementById('foto_masuk').addEventListener('change', function () {
    if (this.files.length > 0) {
        document.getElementById('form_masuk').submit();
    }
});

document.getElementById('foto_pulang').addEventListener('change', function () {
    if (this.files.length > 0) {
        document.getElementById('form_pulang').submit();
    }
});
</script>
