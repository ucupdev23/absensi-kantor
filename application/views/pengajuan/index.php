<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h5 style="color:#670F7A;">Pengajuan Cuti / Izin / Sakit</h5>
        <a href="<?= base_url('pengajuan/create'); ?>" class="btn btn-primary-custom">
            + Buat Pengajuan
        </a>
    </div>
</div>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger py-2">
        <?= $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success py-2">
        <?= $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<div class="card card-main">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Alasan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($pengajuan)): $no=1; ?>
                    <?php foreach ($pengajuan as $p): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= ucfirst($p->jenis); ?></td>
                            <td>
                                <?= date('d M Y', strtotime($p->tanggal_mulai)); ?>
                                -
                                <?= date('d M Y', strtotime($p->tanggal_selesai)); ?>
                            </td>
                            <td><?= nl2br(htmlspecialchars($p->alasan)); ?></td>
                            <td>
                                <?php
                                $badge = 'secondary';
                                if ($p->status == 'menunggu') $badge = 'warning';
                                if ($p->status == 'disetujui') $badge = 'success';
                                if ($p->status == 'ditolak')  $badge = 'danger';
                                ?>
                                <span class="badge bg-<?= $badge; ?>">
                                    <?= ucfirst($p->status); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-muted">Belum ada pengajuan.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
