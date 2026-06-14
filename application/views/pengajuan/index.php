<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h5 style="color:#670F7A;">Pengajuan Cuti / Izin / Sakit</h5>
        <a href="<?= base_url('pengajuan/create'); ?>" class="btn btn-primary-custom">
            + Buat Pengajuan
        </a>
    </div>
</div>

<!-- Ringkasan Jatah Cuti -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #670F7A !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:rgba(103,15,122,0.1);font-size:1.2rem;">
                        📋
                    </div>
                    <div>
                        <div class="text-muted small">Jatah Cuti Tahunan</div>
                        <div class="h5 mb-0 fw-bold"><?= $pegawai->jatah_cuti; ?> Hari</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #dc3545 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:rgba(220,53,69,0.1);font-size:1.2rem;">
                        📉
                    </div>
                    <div>
                        <div class="text-muted small">Cuti Terpakai (<?= date('Y'); ?>)</div>
                        <div class="h5 mb-0 fw-bold text-danger"><?= $cuti_terpakai; ?> Hari</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #198754 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:42px;height:42px;background:rgba(25,135,84,0.1);font-size:1.2rem;">
                        ✅
                    </div>
                    <div>
                        <div class="text-muted small">Sisa Jatah Cuti</div>
                        <div class="h5 mb-0 fw-bold text-success"><?= $sisa_cuti; ?> Hari</div>
                    </div>
                </div>
            </div>
        </div>
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
                        <th>Durasi</th>
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
                            <td><strong><?= $p->jumlah_hari; ?></strong> Hari</td>
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
                    <tr><td colspan="6" class="text-center text-muted">Belum ada pengajuan.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
