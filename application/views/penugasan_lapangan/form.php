<?php
$isEdit = isset($row);
$action = $isEdit ? base_url('penugasan_lapangan/update/'.$row->id) : base_url('penugasan_lapangan/store');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="mb-0" style="color:#670F7A;"><?= $isEdit ? 'Edit' : 'Tambah'; ?> Penugasan Lapangan</h4>
    <small class="text-muted">Pilih banyak pegawai dalam satu penugasan</small>
  </div>
  <a href="<?= base_url('penugasan_lapangan'); ?>" class="btn btn-outline-secondary">Kembali</a>
</div>

<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<form method="post" action="<?= $action; ?>">
  <div class="row g-3">
    <div class="col-12 col-md-4">
      <label class="form-label">Tanggal *</label>
      <input type="date" name="tanggal" class="form-control"
             value="<?= htmlspecialchars($isEdit ? $row->tanggal : set_value('tanggal')); ?>" required>
    </div>
    <div class="col-6 col-md-4">
      <label class="form-label">Jam Mulai (opsional)</label>
      <input type="time" name="start_time" class="form-control"
             value="<?= htmlspecialchars($isEdit ? $row->start_time : set_value('start_time')); ?>">
    </div>
    <div class="col-6 col-md-4">
      <label class="form-label">Jam Selesai (opsional)</label>
      <input type="time" name="end_time" class="form-control"
             value="<?= htmlspecialchars($isEdit ? $row->end_time : set_value('end_time')); ?>">
    </div>

    <div class="col-12 col-md-6">
      <label class="form-label">Nama Lokasi *</label>
      <input type="text" name="lokasi_nama" class="form-control" placeholder="Contoh: Site Pemasangan A"
             value="<?= htmlspecialchars($isEdit ? $row->lokasi_nama : set_value('lokasi_nama')); ?>" required>
    </div>

    <div class="col-12 col-md-6">
      <label class="form-label">Jenis</label>
      <select name="jenis" class="form-select">
        <?php
          $jenisVal = $isEdit ? $row->jenis : set_value('jenis','lainnya');
          foreach(['pemasangan','service','survey','lainnya'] as $j):
        ?>
          <option value="<?= $j; ?>" <?= ($jenisVal==$j)?'selected':''; ?>><?= ucfirst($j); ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-12">
      <label class="form-label">Alamat</label>
      <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($isEdit ? $row->alamat : set_value('alamat')); ?></textarea>
    </div>

    <div class="col-6 col-md-3">
      <label class="form-label">Latitude *</label>
      <input type="text" name="lat" class="form-control" placeholder="-7.xxx"
             value="<?= htmlspecialchars($isEdit ? $row->lat : set_value('lat')); ?>" required>
    </div>
    <div class="col-6 col-md-3">
      <label class="form-label">Longitude *</label>
      <input type="text" name="lng" class="form-control" placeholder="110.xxx"
             value="<?= htmlspecialchars($isEdit ? $row->lng : set_value('lng')); ?>" required>
    </div>
    <div class="col-6 col-md-3">
      <label class="form-label">Radius (meter)</label>
      <input type="number" name="radius_meter" class="form-control"
             value="<?= htmlspecialchars($isEdit ? $row->radius_meter : set_value('radius_meter',200)); ?>">
    </div>
    <div class="col-6 col-md-3">
      <label class="form-label">Status</label>
      <?php $stVal = $isEdit ? $row->status : set_value('status','draft'); ?>
      <select name="status" class="form-select">
        <?php foreach(['draft','aktif','selesai','batal'] as $st): ?>
          <option value="<?= $st; ?>" <?= ($stVal==$st)?'selected':''; ?>><?= ucfirst($st); ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-12">
      <label class="form-label">Catatan</label>
      <textarea name="catatan" class="form-control" rows="2"><?= htmlspecialchars($isEdit ? $row->catatan : set_value('catatan')); ?></textarea>
    </div>

    <div class="col-12">
      <label class="form-label">Pilih Pegawai *</label>

      <div class="d-flex gap-2 mb-2">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectAll(true)">Pilih Semua</button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectAll(false)">Kosongkan</button>
      </div>

      <div class="border rounded p-2" style="max-height:320px; overflow:auto;">
        <?php foreach($employees as $e): ?>
          <?php $checked = in_array((int)$e->id, $selected_members) ? 'checked' : ''; ?>
          <div class="form-check">
            <input class="form-check-input memberCheck" type="checkbox" name="members[]"
                   value="<?= (int)$e->id; ?>" id="emp<?= (int)$e->id; ?>" <?= $checked; ?>>
            <label class="form-check-label" for="emp<?= (int)$e->id; ?>">
              <span class="fw-semibold"><?= htmlspecialchars($e->nama_lengkap); ?></span>
              <span class="text-muted small">— <?= htmlspecialchars($e->kode_pegawai); ?></span>
            </label>
          </div>
        <?php endforeach; ?>
      </div>

      <small class="text-muted d-block mt-2">
        Tips: gunakan pencarian browser (Ctrl+F) untuk mencari nama pegawai cepat.
      </small>
    </div>

    <div class="col-12 d-grid gap-2">
      <button class="btn btn-primary-custom"><?= $isEdit ? 'Update' : 'Simpan'; ?></button>
    </div>
  </div>
</form>

<script>
function selectAll(state){
  document.querySelectorAll('.memberCheck').forEach(ch => ch.checked = state);
}
</script>
