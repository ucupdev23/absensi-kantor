<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h5 style="color:#670F7A;">Master Jabatan</h5>
        <a href="<?= base_url('jabatan/create'); ?>" class="btn btn-primary-custom">
            + Tambah Jabatan
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
                        <th>Nama Jabatan</th>
                        <th>Keterangan</th>
                        <th style="width:140px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($jabatan)): $no=1; ?>
                    <?php foreach ($jabatan as $j): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($j->nama); ?></td>
                            <td><?= nl2br(htmlspecialchars($j->keterangan)); ?></td>
                            <td class="text-end">
                                <a href="<?= base_url('jabatan/edit/'.$j->id); ?>" class="btn btn-sm btn-outline-secondary">
                                    Edit
                                </a>
                                <a href="<?= base_url('jabatan/delete/'.$j->id); ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Hapus jabatan ini?');">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center text-muted">Belum ada data.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
