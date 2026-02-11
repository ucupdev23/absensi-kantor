<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="mb-0" style="color:#670F7A;">Riwayat Penugasan Lapangan</h4>
    <small class="text-muted">Pantau kapan saja ada pemasangan / tugas</small>
  </div>
  <a href="<?= base_url('penugasan_lapangan'); ?>" class="btn btn-outline-secondary">Kembali</a>
</div>

<div class="card card-main mb-3">
  <div class="card-body">
    <form class="row g-2" method="get" action="<?= base_url('penugasan_lapangan/history'); ?>">
      <div class="col-6 col-md-3">
        <label class="form-label">Dari</label>
        <input type="date" name="start" class="form-control" value="<?= htmlspecialchars($q['start']); ?>">
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label">Sampai</label>
        <input type="date" name="end" class="form-control" value="<?= htmlspecialchars($q['end']); ?>">
      </div>
      <div class="col-12 col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">Semua</option>
          <?php foreach (['draft','aktif','selesai','batal'] as $st): ?>
            <option value="<?= $st; ?>" <?= ($q['status']===$st)?'selected':''; ?>><?= ucfirst($st); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 col-md-3">
        <label class="form-label">Pegawai</label>
        <select name="employee_id" class="form-select">
          <option value="">Semua</option>
          <?php foreach($employees as $e): ?>
            <option value="<?= (int)$e->id; ?>" <?= ((string)$q['employee_id']===(string)$e->id)?'selected':''; ?>>
              <?= htmlspecialchars($e->nama_lengkap); ?> (<?= htmlspecialchars($e->kode_pegawai); ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary-custom w-100">Filter</button>
        <a class="btn btn-outline-secondary w-100" href="<?= base_url('penugasan_lapangan/history'); ?>">Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="card card-main">
  <div class="card-body table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Lokasi</th>
          <th>Jam</th>
          <th>Anggota</th>
          <th>Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($rows)): ?>
          <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data.</td></tr>
        <?php endif; ?>

        <?php foreach($rows as $r): ?>
          <tr>
            <td><?= date('d M Y', strtotime($r->tanggal)); ?></td>
            <td>
              <div class="fw-semibold"><?= htmlspecialchars($r->lokasi_nama); ?></div>
              <div class="small text-muted"><?= htmlspecialchars($r->alamat ?? ''); ?></div>
            </td>
            <td>
              <?= $r->start_time ? substr($r->start_time,0,5) : '--:--'; ?>
              -
              <?= $r->end_time ? substr($r->end_time,0,5) : '--:--'; ?>
            </td>
            <td style="min-width:260px;">
              <div class="small"><?= htmlspecialchars($r->anggota ?? '-'); ?></div>
            </td>
            <td>
              <span class="badge bg-<?= $r->status=='aktif'?'success':($r->status=='draft'?'secondary':($r->status=='selesai'?'primary':'danger')); ?>">
                <?= ucfirst($r->status); ?>
              </span>
            </td>
            <td class="text-end">
              <a href="<?= base_url('penugasan_lapangan/detail/'.$r->id); ?>" class="btn btn-sm btn-outline-secondary">Detail</a>
              <a target="_blank" class="btn btn-sm btn-outline-dark"
                 href="https://www.google.com/maps?q=<?= $r->lat; ?>,<?= $r->lng; ?>">
                Maps
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
