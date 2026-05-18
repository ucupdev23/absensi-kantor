<?php
$status_harian = $absen_hari ? $absen_hari->status_harian : null;
$is_libur_pribadi = in_array($status_harian, ['Cuti', 'Izin', 'Sakit', 'Ganti_hari', 'Potong_gaji']);

$jam_masuk = $absen_hari ? $absen_hari->jam_masuk : null;
$jam_pulang = $absen_hari ? $absen_hari->jam_pulang : null;
?>

<style>
:root {
  --primary: #6366f1;
  --primary-light: #818cf8;
  --primary-soft: #e0e7ff;
  --success: #22c55e;
  --warning: #f59e0b;
  --danger: #ef4444;
  --dark: #1e293b;
  --gray: #64748b;
  --light: #f8fafc;
  --border: #e2e8f0;
}

body {
  background: #f1f5f9;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* Card Modern */
.card-modern {
  background: white;
  border: none;
  border-radius: 24px;
  box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.02);
  transition: transform 0.2s, box-shadow 0.2s;
}

.card-modern:hover {
  box-shadow: 0 20px 35px -8px rgba(99, 102, 241, 0.15);
}

/* Badge Status */
.badge-status {
  padding: 0.35rem 0.75rem;
  border-radius: 100px;
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.02em;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
}

.badge-masuk {
  background: #dcfce7;
  color: #166534;
}

.badge-pulang {
  background: #dbeafe;
  color: #1e40af;
}

.badge-libur {
  background: #fef9c3;
  color: #854d0e;
}

/* Button Modern */
.btn-modern {
  padding: 0.875rem 1.5rem;
  border-radius: 16px;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.2s;
  border: none;
  cursor: pointer;
}

.btn-modern:not(:disabled) {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
  box-shadow: 0 8px 20px -8px var(--primary);
}

.btn-modern:not(:disabled):hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 25px -8px var(--primary);
}

.btn-modern:disabled {
  background: var(--light);
  color: var(--gray);
  border: 1px solid var(--border);
  opacity: 0.7;
  cursor: not-allowed;
}

.btn-outline-modern {
  background: white;
  border: 1.5px solid var(--border);
  color: var(--dark);
  padding: 0.75rem 1.25rem;
  border-radius: 14px;
  font-weight: 500;
  transition: all 0.2s;
}

.btn-outline-modern:hover {
  border-color: var(--primary);
  background: var(--primary-soft);
  color: var(--primary);
}

/* Info Grid */
.info-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
  margin: 1.5rem 0;
}

.info-item {
  background: var(--light);
  border-radius: 18px;
  padding: 1.25rem 0.5rem;
  text-align: center;
  border: 1px solid var(--border);
}

.info-label {
  font-size: 0.8rem;
  color: var(--gray);
  margin-bottom: 0.5rem;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}

.info-value {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--dark);
  line-height: 1.2;
}

/* Alert Modern */
.alert-modern {
  border-radius: 16px;
  padding: 1rem 1.25rem;
  border: none;
  margin: 1rem 0;
}

.alert-lapangan {
  background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
  border-left: 4px solid var(--warning);
  color: #92400e;
}

.alert-kantor {
  background: var(--light);
  border: 1px dashed var(--border);
  color: var(--gray);
}

/* Camera Wrapper */
.camera-wrapper {
  background: var(--dark);
  border-radius: 24px;
  overflow: hidden;
  margin: 1rem 0;
  position: relative;
}

.camera-overlay {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(10px);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 100px;
  font-size: 0.85rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Animations */
@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-slide-up {
  animation: slideUp 0.5s ease-out forwards;
}
</style>

<div class="container-fluid px-3 py-4" style="max-width: 1400px;">
  <!-- Flash Messages -->
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-modern mb-4" style="background: #fee2e2; color: #991b1b; border-left-color: var(--danger);">
      <div class="d-flex align-items-center gap-2">
        <span>❌</span>
        <span><?= $this->session->flashdata('error'); ?></span>
      </div>
    </div>
  <?php
endif; ?>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-modern mb-4" style="background: #dcfce7; color: #166534; border-left-color: var(--success);">
      <div class="d-flex align-items-center gap-2">
        <span>✅</span>
        <span><?= $this->session->flashdata('success'); ?></span>
      </div>
    </div>
  <?php
endif; ?>

  <!-- Main Grid -->
  <div class="row g-4">
    <!-- Left Column - Info Card -->
    <div class="col-12 col-lg-6">
      <div class="card-modern p-4 animate-slide-up" style="animation-delay: 0.1s;">
        <!-- Header with Date -->
        <div class="d-flex align-items-center justify-content-between mb-4">
          <div class="d-flex align-items-center gap-3">
            <div style="width: 60px; height: 60px; background: var(--primary-soft); border-radius: 18px; display: flex; align-items: center; justify-content: center;">
              <span style="font-size: 2rem;">👤</span>
            </div>
            <div>
              <h5 class="fw-bold mb-1" style="color: var(--dark);">Informasi Pegawai</h5>
              <p class="mb-0" style="color: var(--gray);">Detail shift dan lokasi kerja</p>
            </div>
          </div>
          <div class="badge-status" style="background: var(--primary-soft); color: var(--primary); font-size: 0.9rem; padding: 0.5rem 1rem;">
            📅 <?= date('d M Y', strtotime($today)); ?>
          </div>
        </div>

        <!-- Profile Info -->
        <div class="mb-4">
          <div class="d-flex align-items-center gap-3 mb-3">
            <span style="color: var(--primary);">📋</span>
            <span><strong>Nama:</strong> <?= htmlspecialchars($pegawai->nama_lengkap); ?></span>
          </div>
          <div class="d-flex align-items-center gap-3 mb-3">
            <span style="color: var(--primary);">💼</span>
            <span><strong>Jabatan:</strong> <?= htmlspecialchars($pegawai->nama_jabatan); ?></span>
          </div>
          <div class="d-flex align-items-center gap-3 mb-3">
            <span style="color: var(--primary);">📍</span>
            <span><strong>Kantor:</strong> <?= htmlspecialchars($pegawai->nama_lokasi); ?></span>
          </div>
          <div class="d-flex align-items-center gap-3">
            <span style="color: var(--primary);">⏰</span>
            <span><strong>Shift:</strong> <?= htmlspecialchars($pegawai->nama_shift); ?> (<?= substr($pegawai->jam_masuk, 0, 5); ?> - <?= substr($pegawai->jam_pulang, 0, 5); ?>)</span>
          </div>
        </div>

        <!-- Status Absensi -->
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Jam Masuk</div>
            <div class="info-value"><?= $jam_masuk ? date('H:i', strtotime($jam_masuk)) : '--:--'; ?></div>
            <?php if ($absen_hari): ?>
              <span class="badge-status badge-masuk mt-2"><?= $absen_hari->status_masuk; ?></span>
            <?php
endif; ?>
          </div>
          <div class="info-item">
            <div class="info-label">Jam Pulang</div>
            <div class="info-value"><?= $jam_pulang ? date('H:i', strtotime($jam_pulang)) : '--:--'; ?></div>
            <?php if ($absen_hari): ?>
              <span class="badge-status badge-pulang mt-2"><?= $absen_hari->status_pulang; ?></span>
            <?php
endif; ?>
          </div>
        </div>

        <!-- Status Libur -->
        <?php if ($is_libur_pribadi): ?>
          <div class="alert-modern mt-3" style="background: #fef9c3; border-left-color: var(--warning);">
            <div class="d-flex align-items-center gap-2">
              <span>🏖️</span>
              <span>Hari ini tercatat sebagai <strong><?= $status_harian; ?></strong>, Anda tidak perlu absen.</span>
            </div>
          </div>
        <?php
endif; ?>
      </div>
    </div>

    <!-- Right Column - Action Card -->
    <div class="col-12 col-lg-6">
      <div class="card-modern p-4 animate-slide-up" style="animation-delay: 0.2s;">
        <!-- Header -->
        <div class="d-flex align-items-center gap-3 mb-4">
          <div style="width: 60px; height: 60px; background: var(--primary-soft); border-radius: 18px; display: flex; align-items: center; justify-content: center;">
            <span style="font-size: 2rem;">📸</span>
          </div>
          <div>
            <h5 class="fw-bold mb-1" style="color: var(--dark);">Absensi Hari Ini</h5>
            <p class="mb-0" style="color: var(--gray);">Lakukan absensi dengan foto selfie</p>
          </div>
        </div>

        <!-- Penugasan Info -->
        <?php if (!empty($penugasan)): ?>
          <div class="alert-modern alert-lapangan mb-4">
            <div class="d-flex align-items-center gap-2 mb-2">
              <span>📍</span>
              <span class="fw-semibold">Mode Lapangan Aktif</span>
            </div>
            <p class="mb-2"><strong><?= htmlspecialchars($penugasan->lokasi_nama); ?></strong> (Radius <?=(int)$penugasan->radius_meter; ?>m)</p>
            <p class="small mb-2" style="color: #92400e;"><?= nl2br(htmlspecialchars(isset($penugasan->alamat) ? $penugasan->alamat : '')); ?></p>
            <a href="https://www.google.com/maps?q=<?= $penugasan->lat; ?>,<?= $penugasan->lng; ?>" 
               target="_blank"
               class="btn-outline-modern d-inline-flex align-items-center gap-2">
              <span>🗺️</span>
              <span>Buka Google Maps</span>
            </a>
          </div>
        <?php
else: ?>
          <div class="alert-modern alert-kantor mb-4">
            <div class="d-flex align-items-center gap-2">
              <span>🏢</span>
              <span>Tidak ada penugasan lapangan aktif. Absensi wajib di kantor.</span>
            </div>
          </div>
        <?php
endif; ?>

        <!-- Camera Check Button -->
        <button type="button" class="btn-outline-modern w-100 mb-3" onclick="cekPerangkat()">
          <div class="d-flex align-items-center justify-content-center gap-2">
            <span>📱</span>
            <span>Cek Perangkat (Lokasi & Kamera)</span>
          </div>
        </button>
        <small class="text-muted d-block mb-4" style="font-size: 0.85rem;">
          ⚡ Disarankan klik ini dulu agar saat absen kamera lebih cepat.
        </small>

        <!-- Camera Preview -->
        <div id="camWrap" style="display:none;">
          <div class="camera-wrapper">
            <video id="cam" playsinline autoplay muted style="width:100%; display:block;"></video>
            <div class="camera-overlay">
              <span>📸 Camera Ready</span>
            </div>
          </div>
          <canvas id="snap" style="display:none;"></canvas>
        </div>

        <!-- Action Buttons -->
        <div class="d-grid gap-3">
          <button type="button"
                  class="btn-modern"
                  onclick="handleAbsen('masuk')"
                  <?=($jam_masuk || $is_libur_pribadi) ? 'disabled' : ''; ?>>
            <div class="d-flex align-items-center justify-content-center gap-2">
              <span>📷</span>
              <span><?= $jam_masuk ? 'Sudah Absen Masuk' : 'Absen Masuk'; ?></span>
            </div>
          </button>

          <button type="button"
                  class="btn-modern"
                  onclick="handleAbsen('pulang')"
                  <?=(!$jam_masuk || $jam_pulang || $is_libur_pribadi) ? 'disabled' : ''; ?>>
            <div class="d-flex align-items-center justify-content-center gap-2">
              <span>👋</span>
              <span>
                <?php
if (!$jam_masuk)
  echo 'Absen Pulang (belum absen masuk)';
elseif ($jam_pulang)
  echo 'Sudah Absen Pulang';
else
  echo 'Absen Pulang';
?>
              </span>
            </div>
          </button>
        </div>

        <!-- Info Text -->
        <small class="d-block mt-4 text-muted" style="font-size: 0.85rem; line-height: 1.5;">
          <span class="d-block mb-1">ℹ️ Informasi Penting:</span>
          Saat menekan tombol absen, sistem akan mengambil lokasi Anda dan membuka kamera untuk selfie. 
          Pastikan izin lokasi & kamera sudah diaktifkan.
        </small>
      </div>
    </div>
  </div>
</div>

<!-- Hidden Forms -->
<form id="form_masuk" action="<?= base_url('absensi/masuk'); ?>" method="post" style="display:none;">
  <input type="hidden" name="latitude" id="lat_masuk">
  <input type="hidden" name="longitude" id="lng_masuk">
  <input type="hidden" name="photo_base64" id="photo_masuk">
</form>

<form id="form_pulang" action="<?= base_url('absensi/pulang'); ?>" method="post" style="display:none;">
  <input type="hidden" name="latitude" id="lat_pulang">
  <input type="hidden" name="longitude" id="lng_pulang">
  <input type="hidden" name="photo_base64" id="photo_pulang">
</form>

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

  if (streamRef) {
    streamRef.getTracks().forEach(t => t.stop());
    streamRef = null;
  }

  const constraints = {
    audio: false,
    video: {
      facingMode: { ideal: "user" },
      width: { ideal: 720 },
      height: { ideal: 720 },
      frameRate: { ideal: 15, max: 20 }
    }
  };

  try {
    streamRef = await navigator.mediaDevices.getUserMedia(constraints);
  } catch (e) {
    streamRef = await navigator.mediaDevices.getUserMedia({
      audio:false,
      video:{ facingMode:{ ideal:"environment" }, width:{ideal:720}, height:{ideal:720}, frameRate:{ideal:15,max:20} }
    });
  }

  video.srcObject = streamRef;
  await video.play();
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

async function cekPerangkat() {
  try {
    const locPromise = getLocationFast();
    await openCameraFront();
    await locPromise;
    
    alert("✅ Perangkat siap! Lokasi & kamera sudah diizinkan.");
  } catch (e) {
    stopCamera();
    alert("❌ Gagal cek perangkat: " + (e.message || e));
  }
}

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
    alert("❌ Gagal akses kamera/lokasi: " + (e.message || e));
    btns.forEach(b => b.disabled = false);
  }
}
</script>