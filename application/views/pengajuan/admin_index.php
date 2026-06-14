<div class="row mb-3">
    <div class="col-12">
        <h5 style="color:#670F7A;">Persetujuan Pengajuan Cuti / Izin / Sakit</h5>
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
                        <th>Pegawai</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th style="width:200px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($pengajuan)): $no=1; ?>
                    <?php foreach ($pengajuan as $p): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <?= htmlspecialchars($p->nama_lengkap); ?><br>
                                <small class="text-muted"><?= htmlspecialchars($p->kode_pegawai); ?></small>
                                <?php if ($p->jenis == 'cuti'): ?>
                                    <br>
                                    <small class="text-info fw-semibold">
                                        Sisa Cuti: <?= $this->Leave_model->get_remaining_leave_quota($p->employee_id, date('Y', strtotime($p->tanggal_mulai))); ?> Hari
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td><?= ucfirst($p->jenis); ?></td>
                            <td>
                                <?= date('d M Y', strtotime($p->tanggal_mulai)); ?>
                                -
                                <?= date('d M Y', strtotime($p->tanggal_selesai)); ?>
                                <br>
                                <span class="badge bg-secondary"><?= $p->jumlah_hari; ?> Hari</span>
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
                            <td class="text-end">
                                <?php if ($p->lampiran_file): ?>
                                    <a href="<?= base_url('uploads/lampiran/'.$p->lampiran_file); ?>" target="_blank" class="btn btn-sm btn-outline-info mb-1">
                                        Lampiran
                                    </a>
                                <?php endif; ?>

                                <?php if ($p->status == 'menunggu'): ?>
                                    <form action="<?= base_url('pengajuan-admin/approve/'.$p->id); ?>" method="post" class="d-inline">
                                        <input type="hidden" name="catatan_admin" value="">
                                        <button type="submit" class="btn btn-sm btn-success"
                                                onclick="return confirm('Setujui pengajuan ini? Absensi akan di-set sebagai <?= ucfirst($p->jenis); ?>.')">
                                            Setujui
                                        </button>
                                    </form>
                                    <form action="<?= base_url('pengajuan-admin/reject/'.$p->id); ?>" method="post" class="d-inline">
                                        <input type="hidden" name="catatan_admin" value="">
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Tolak pengajuan ini?')">
                                            Tolak
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <small class="text-muted">
                                        Diproses pada: <?= $p->tanggal_persetujuan ? date('d M Y H:i', strtotime($p->tanggal_persetujuan)) : '-'; ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted">Belum ada pengajuan.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
