<?php
$isEdit = isset($row) && $row !== null;
$action = $isEdit ? base_url('penugasan_wfh/update/'.$row->id) : base_url('penugasan_wfh/store');
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card card-main">
            <div class="card-body">
                <h5 class="mb-3" style="color:#670F7A;"><?= $isEdit ? 'Edit' : 'Tambah'; ?> Penugasan WFH</h5>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger py-2">
                        <?= $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <form action="<?= $action; ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Pilih Pegawai *</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">- Pilih Pegawai -</option>
                            <?php foreach ($employees as $e): ?>
                                <option value="<?= $e->id; ?>" <?= $isEdit && $row->employee_id == $e->id ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($e->nama_lengkap); ?> (<?= htmlspecialchars($e->kode_pegawai); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai *</label>
                            <input type="date" name="tanggal_mulai" class="form-control" 
                                   value="<?= $isEdit ? $row->tanggal_mulai : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai *</label>
                            <input type="date" name="tanggal_selesai" class="form-control" 
                                   value="<?= $isEdit ? $row->tanggal_selesai : ''; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan / Alasan WFH</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Misal: Penugasan WFH karena kondisi kesehatan / kedinasan"><?= $isEdit ? htmlspecialchars($row->keterangan) : ''; ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <a href="<?= base_url('penugasan_wfh'); ?>" class="btn btn-outline-secondary">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            <?= $isEdit ? 'Perbarui' : 'Simpan'; ?> Penugasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
