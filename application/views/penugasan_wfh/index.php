<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h5 style="color:#670F7A;">Penugasan WFH (Work From Home)</h5>
        <a href="<?= base_url('penugasan_wfh/create'); ?>" class="btn btn-primary-custom">
            + Tambah Penugasan
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
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Pegawai</th>
                        <th>Kode Pegawai</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Keterangan</th>
                        <th style="width:150px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($rows)): $no=1; ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><strong><?= htmlspecialchars($row->nama_lengkap); ?></strong></td>
                            <td><?= htmlspecialchars($row->kode_pegawai); ?></td>
                            <td><?= date('d-m-Y', strtotime($row->tanggal_mulai)); ?></td>
                            <td><?= date('d-m-Y', strtotime($row->tanggal_selesai)); ?></td>
                            <td><?= htmlspecialchars($row->keterangan ? $row->keterangan : '-'); ?></td>
                            <td class="text-end">
                                <a href="<?= base_url('penugasan_wfh/edit/'.$row->id); ?>" class="btn btn-sm btn-outline-secondary">
                                    Edit
                                </a>
                                <a href="<?= base_url('penugasan_wfh/delete/'.$row->id); ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Hapus penugasan WFH ini?');">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted">Belum ada data penugasan WFH.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
