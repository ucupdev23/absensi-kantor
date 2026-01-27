<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h5 style="color:#670F7A;">Master Lokasi</h5>
        <a href="<?= base_url('lokasi/create'); ?>" class="btn btn-primary-custom">
            + Tambah Lokasi
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
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Radius (m)</th>
                        <th style="width:140px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($lokasi)): $no=1; ?>
                    <?php foreach ($lokasi as $l): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($l->nama); ?></td>
                            <td><?= nl2br(htmlspecialchars($l->alamat)); ?></td>
                            <td><?= htmlspecialchars($l->latitude); ?></td>
                            <td><?= htmlspecialchars($l->longitude); ?></td>
                            <td><?= htmlspecialchars($l->radius_meter); ?></td>
                            <td class="text-end">
                                <a href="<?= base_url('lokasi/edit/'.$l->id); ?>" class="btn btn-sm btn-outline-secondary">
                                    Edit
                                </a>
                                <a href="<?= base_url('lokasi/delete/'.$l->id); ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Hapus lokasi ini?');">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center text-muted">Belum ada data.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
