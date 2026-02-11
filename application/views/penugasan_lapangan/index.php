<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="mb-0" style="color:#670F7A;">Penugasan Lapangan</h4>
    <small class="text-muted">1 penugasan bisa untuk banyak pegawai</small>
  </div>
  <a href="<?= base_url('penugasan_lapangan/create'); ?>" class="btn btn-primary-custom">
    + Tambah Penugasan
  </a>
</div>

<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<div class="card card-main mb-3">
  <div class="card-body">
    <form class="row g-2" method="get" action="<?= base_url('penugasan_lapangan'); ?>">
      <div class="col-12 col-md-4">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($q['tanggal'] ?? ''); ?>">
      </div>
      <div class="col-12 col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">Semua</option>
          <?php foreach (['draft','aktif','selesai','batal'] as $st): ?>
            <option value="<?= $st; ?>" <?= (($q['status'] ?? '') === $st) ? 'selected' : ''; ?>>
              <?= ucfirst($st); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 col-md-4 d-flex align-items-end gap-2">
        <button class="btn btn-primary-custom w-100">Filter</button>
        <a class="btn btn-outline-secondary w-100" href="<?= base_url('penugasan_lapangan'); ?>">Reset</a>
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
          <th>Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($rows)): ?>
        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada penugasan.</td></tr>
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
          <td>
            <span class="badge bg-<?= $r->status=='aktif'?'success':($r->status=='draft'?'secondary':($r->status=='selesai'?'primary':'danger')); ?>">
              <?= ucfirst($r->status); ?>
            </span>
          </td>
          <td class="text-end">
            <a href="<?= base_url('penugasan_lapangan/detail/'.$r->id); ?>" class="btn btn-sm btn-outline-secondary">Detail</a>
            <a href="<?= base_url('penugasan_lapangan/edit/'.$r->id); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <a href="<?= base_url('penugasan_lapangan/delete/'.$r->id); ?>"
               class="btn btn-sm btn-outline-danger"
               onclick="return confirm('Hapus penugasan ini?');">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
