<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h5 style="color:#670F7A;">Master Pegawai</h5>
        <a href="<?= base_url('pegawai/create'); ?>" class="btn btn-primary-custom">
            + Tambah Pegawai
        </a>
    </div>
</div>

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
                        <th>Nama</th>
                        <th>Kode</th>
                        <th>Jabatan</th>
                        <th>Lokasi</th>
                        <th>Shift</th>
                        <th>Status</th>
                        <th style="width:150px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($pegawai)): $no=1; ?>
                    <?php foreach ($pegawai as $p): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($p->nama_lengkap); ?></td>
                            <td><?= htmlspecialchars($p->kode_pegawai); ?></td>
                            <td><?= htmlspecialchars($p->nama_jabatan); ?></td>
                            <td><?= htmlspecialchars($p->nama_lokasi); ?></td>
                            <td><?= htmlspecialchars($p->nama_shift); ?></td>
                            <td>
                                <span class="badge <?= $p->status == 'aktif' ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?= $p->status; ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?= base_url('pegawai/edit/'.$p->id); ?>" class="btn btn-sm btn-outline-secondary">
                                    Edit
                                </a>
                                <a href="<?= base_url('pegawai/delete/'.$p->id); ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Hapus pegawai ini beserta akun user-nya?');">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center text-muted">Belum ada data.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
