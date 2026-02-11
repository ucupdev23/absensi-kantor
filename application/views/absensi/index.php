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

    <?php if (!empty($penugasan)): ?>
  <div class="alert alert-warning mt-3">
    <div class="fw-semibold">Mode Lapangan Aktif</div>
    <div class="small">
      <b><?= htmlspecialchars($penugasan->lokasi_nama); ?></b>
      (Radius <?= (int)$penugasan->radius_meter; ?>m)
    </div>
    <div class="small text-muted"><?= nl2br(htmlspecialchars($penugasan->alamat ?? '')); ?></div>
    <a class="btn btn-sm btn-outline-dark mt-2" target="_blank"
       href="https://www.google.com/maps?q=<?= $penugasan->lat; ?>,<?= $penugasan->lng; ?>">
      Buka Maps
    </a>
  </div>
<?php else: ?>
  <div class="alert alert-light border mt-3">
    <div class="small text-muted">Tidak ada penugasan lapangan aktif hari ini. Absensi wajib di kantor.</div>
  </div>
<?php endif; ?>


    <div class="mb-3">
  <button type="button" class="btn btn-outline-secondary w-100" onclick="cekPerangkat()">
    Cek Perangkat (Lokasi & Kamera)
  </button>
  <small class="text-muted d-block mt-2">
    Disarankan klik ini dulu agar saat absen kamera lebih cepat.
  </small>
</div>

<div id="camWrap" class="mb-3" style="display:none;">
  <video id="cam" playsinline autoplay muted style="width:100%; max-height:320px; border-radius:12px;"></video>
  <canvas id="snap" style="display:none;"></canvas>
</div>


    <!-- Form absen -->
    <div class="col-12 col-lg-6 mb-3">
        <div class="card card-main">
            <div class="card-body">
                <h6 class="mb-3" style="color:#670F7A;">Aksi Absensi</h6>

                <!-- Absen Masuk -->
<form id="form_masuk" action="<?= base_url('absensi/masuk'); ?>" method="post">
  <input type="hidden" name="latitude" id="lat_masuk">
  <input type="hidden" name="longitude" id="lng_masuk">
  <input type="hidden" name="photo_base64" id="photo_masuk">
</form>

<!-- Absen Pulang -->
<form id="form_pulang" action="<?= base_url('absensi/pulang'); ?>" method="post">
  <input type="hidden" name="latitude" id="lat_pulang">
  <input type="hidden" name="longitude" id="lng_pulang">
  <input type="hidden" name="photo_base64" id="photo_pulang">
</form>


                <div class="d-grid gap-2 mt-2">
                    <button type="button"
                            class="btn btn-primary-custom"
                            onclick="handleAbsen('masuk')"
                        <?= ($jam_masuk || $is_libur_pribadi) ? 'disabled' : ''; ?>>
                        <?= $jam_masuk ? 'Sudah Absen Masuk' : 'Absen Masuk'; ?>
                    </button>

                    <button type="button"
                            class="btn btn-primary-custom"
                            onclick="handleAbsen('pulang')"
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
let streamRef = null;

async function getLocationFast(timeoutMs = 8000) {
  return new Promise((resolve, reject) => {
    if (!navigator.geolocation) return reject(new Error("Geolocation tidak didukung"));
    navigator.geolocation.getCurrentPosition(
      pos => resolve(pos.coords),
      err => reject(err),
      { enableHighAccuracy: true, timeout: timeoutMs, maximumAge: 0 }
    );
  });
}

async function openCameraFront() {
  const wrap = document.getElementById('camWrap');
  const video = document.getElementById('cam');
  wrap.style.display = 'block';

  // stop stream lama biar ga nyangkut
  if (streamRef) {
    streamRef.getTracks().forEach(t => t.stop());
    streamRef = null;
  }

  const constraints = {
    audio: false,
    video: {
      facingMode: { ideal: "user" },   // kamera depan
      width: { ideal: 720 },
      height: { ideal: 720 },
      frameRate: { ideal: 15, max: 20 }
    }
  };

  try {
    streamRef = await navigator.mediaDevices.getUserMedia(constraints);
  } catch (e) {
    // fallback kalau device bandel
    streamRef = await navigator.mediaDevices.getUserMedia({
      audio:false,
      video:{ facingMode:{ ideal:"environment" }, width:{ideal:720}, height:{ideal:720}, frameRate:{ideal:15,max:20} }
    });
  }

  video.srcObject = streamRef;
  await video.play();

  // tunggu sedikit biar exposure/focus settle
  await new Promise(r => setTimeout(r, 350));
}

function captureJpegBase64(quality = 0.75) {
  const video = document.getElementById('cam');
  const canvas = document.getElementById('snap');
  const ctx = canvas.getContext('2d');

  const w = video.videoWidth || 720;
  const h = video.videoHeight || 720;

  canvas.width = w;
  canvas.height = h;
  ctx.drawImage(video, 0, 0, w, h);

  return canvas.toDataURL("image/jpeg", quality);
}

function stopCamera() {
  const wrap = document.getElementById('camWrap');
  const video = document.getElementById('cam');

  if (streamRef) {
    streamRef.getTracks().forEach(t => t.stop());
    streamRef = null;
  }
  video.srcObject = null;
  wrap.style.display = 'none';
}

// tombol optional: “Cek Perangkat”
async function cekPerangkat() {
  try {
    // jalankan paralel (tetap user gesture)
    const locPromise = getLocationFast();
    await openCameraFront();
    await locPromise;

    alert("Perangkat siap ✅ Lokasi & kamera sudah diizinkan.");
  } catch (e) {
    stopCamera();
    alert("Gagal cek perangkat: " + (e.message || e));
  }
}

// tombol absen masuk/pulang
async function handleAbsen(type) {
  const btns = document.querySelectorAll('button');
  btns.forEach(b => b.disabled = true);

  try {
    const locPromise = getLocationFast();
    await openCameraFront();

    const coords = await locPromise;

    const photo = captureJpegBase64(0.75);
    stopCamera();

    if (type === 'masuk') {
      document.getElementById('lat_masuk').value = coords.latitude;
      document.getElementById('lng_masuk').value = coords.longitude;
      document.getElementById('photo_masuk').value = photo;
      document.getElementById('form_masuk').submit();
    } else {
      document.getElementById('lat_pulang').value = coords.latitude;
      document.getElementById('lng_pulang').value = coords.longitude;
      document.getElementById('photo_pulang').value = photo;
      document.getElementById('form_pulang').submit();
    }
  } catch (e) {
    stopCamera();
    alert("Gagal akses kamera/lokasi: " + (e.message || e));
    btns.forEach(b => b.disabled = false);
  }
}
</script>
