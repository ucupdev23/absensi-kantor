<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h5 style="color:#670F7A;">Master Shift</h5>
        <a href="<?= base_url('shift/create'); ?>" class="btn btn-primary-custom">
            + Tambah Shift
        </a>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success py-2">
        <?= $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger py-2">
        <?= $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<div class="card card-main">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Nama Shift</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Toleransi Telat (menit)</th>
                        <th>Toleransi Pulang Cepat (menit)</th>
                        <th style="width:140px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($shift)): $no=1; ?>
                    <?php foreach ($shift as $s): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($s->nama_shift); ?></td>
                            <td><?= htmlspecialchars($s->jam_masuk); ?></td>
                            <td><?= htmlspecialchars($s->jam_pulang); ?></td>
                            <td><?= htmlspecialchars($s->toleransi_telat_menit); ?></td>
                            <td><?= htmlspecialchars($s->toleransi_pulang_cepat_menit); ?></td>
                            <td class="text-end">
                                <a href="<?= base_url('shift/edit/'.$s->id); ?>" class="btn btn-sm btn-outline-secondary">
                                    Edit
                                </a>
                                <a href="<?= base_url('shift/delete/'.$s->id); ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Hapus shift ini?');">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted">Belum ada data.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
