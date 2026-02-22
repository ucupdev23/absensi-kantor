<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="mb-0" style="color:#670F7A;">Detail Penugasan</h4>
    <small class="text-muted">Penugasan #<?=(int)$row->id; ?></small>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= base_url('penugasan_lapangan/edit/' . $row->id); ?>" class="btn btn-outline-primary">Edit</a>
    <a href="<?= base_url('penugasan_lapangan'); ?>" class="btn btn-outline-secondary">Kembali</a>
  </div>
</div>

<div class="card card-main mb-3">
  <div class="card-body">
    <div class="row g-2">
      <div class="col-12 col-md-6">
        <div class="text-muted small">Tanggal</div>
        <div class="fw-semibold"><?= date('d M Y', strtotime($row->tanggal)); ?></div>
      </div>
      <div class="col-12 col-md-6">
        <div class="text-muted small">Jam</div>
        <div class="fw-semibold">
          <?= $row->start_time ? substr($row->start_time, 0, 5) : '--:--'; ?> -
          <?= $row->end_time ? substr($row->end_time, 0, 5) : '--:--'; ?>
        </div>
      </div>

      <div class="col-12">
        <div class="text-muted small">Lokasi</div>
        <div class="fw-semibold"><?= htmlspecialchars($row->lokasi_nama); ?></div>
        <div class="text-muted"><?= nl2br(htmlspecialchars(isset($row->alamat) ? $row->alamat : '')); ?></div>
      </div>

      <div class="col-12 col-md-6">
        <div class="text-muted small">Koordinat</div>
        <div class="fw-semibold"><?= $row->lat; ?>, <?= $row->lng; ?> (<?=(int)$row->radius_meter; ?>m)</div>
      </div>
      <div class="col-12 col-md-6">
        <div class="text-muted small">Status</div>
        <span class="badge bg-<?= $row->status == 'aktif' ? 'success' : ($row->status == 'draft' ? 'secondary' : ($row->status == 'selesai' ? 'primary' : 'danger')); ?>">
          <?= ucfirst($row->status); ?>
        </span>
      </div>

      <div class="col-12">
        <a class="btn btn-sm btn-outline-secondary"
           target="_blank"
           href="https://www.google.com/maps?q=<?= $row->lat; ?>,<?= $row->lng; ?>">
          Buka di Google Maps
        </a>
      </div>
    </div>
  </div>
</div>

<div class="card card-main">
  <div class="card-body">
    <h6 class="mb-3" style="color:#670F7A;">Pegawai Bertugas</h6>

    <?php if (empty($members)): ?>
      <div class="text-muted">Belum ada pegawai.</div>
    <?php
else: ?>
      <ul class="list-group">
        <?php foreach ($members as $m): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold"><?= htmlspecialchars($m->nama_lengkap); ?></div>
              <div class="small text-muted"><?= htmlspecialchars($m->kode_pegawai); ?></div>
            </div>
            <span class="badge bg-<?= $m->status == 'aktif' ? 'success' : 'secondary'; ?>">
              <?= ucfirst($m->status); ?>
            </span>
          </li>
        <?php
  endforeach; ?>
      </ul>
    <?php
endif; ?>
  </div>
</div>
