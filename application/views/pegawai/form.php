<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card card-main">
            <div class="card-body">
                <h5 class="mb-3" style="color:#670F7A;">
                    <?= $row ? 'Edit Pegawai' : 'Tambah Pegawai'; ?>
                </h5>

                <form action="<?= $row ? base_url('pegawai/update/'.$row->id) : base_url('pegawai/store'); ?>" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" required
                                   value="<?= $row ? htmlspecialchars($row->nama_lengkap) : ''; ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Kode Pegawai</label>
                            <input type="text" name="kode_pegawai" class="form-control" required
                                   value="<?= $row ? htmlspecialchars($row->kode_pegawai) : ''; ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" name="no_wa" class="form-control"
                                   value="<?= $row ? htmlspecialchars($row->no_wa) : ''; ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"><?= $row ? htmlspecialchars($row->alamat) : ''; ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan_id" class="form-select" required>
                                <option value="">- Pilih -</option>
                                <?php foreach ($jabatan as $j): ?>
                                    <option value="<?= $j->id; ?>"
                                        <?= $row && $row->jabatan_id == $j->id ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($j->nama); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Lokasi Kantor</label>
                            <select name="lokasi_id" class="form-select" required>
                                <option value="">- Pilih -</option>
                                <?php foreach ($lokasi as $l): ?>
                                    <option value="<?= $l->id; ?>"
                                        <?= $row && $row->lokasi_id == $l->id ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($l->nama); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Shift</label>
                            <select name="shift_id" class="form-select" required>
                                <option value="">- Pilih -</option>
                                <?php foreach ($shift as $s): ?>
                                    <option value="<?= $s->id; ?>"
                                        <?= $row && $row->shift_id == $s->id ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($s->nama_shift); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jatah Cuti Tahunan (Hari)</label>
                            <input type="number" name="jatah_cuti" class="form-control" required min="0"
                                   value="<?= $row ? htmlspecialchars($row->jatah_cuti) : '12'; ?>">
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-2" style="color:#670F7A;">Akun Login Pegawai</h6>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control"
                                   <?= $row ? 'readonly' : 'required'; ?>
                                   value="<?= $row ? htmlspecialchars($row->username) : ''; ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Password <?= $row ? '(isi jika ganti)' : ''; ?></label>
                            <input type="password" name="password" class="form-control" <?= $row ? '' : 'required'; ?>>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Status User</label>
                            <select name="status_user" class="form-select">
                                <option value="aktif" <?= $row && $row->status_user=='aktif' ? 'selected':''; ?>>Aktif</option>
                                <option value="nonaktif" <?= $row && $row->status_user=='nonaktif' ? 'selected':''; ?>>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Status Pegawai</label>
                            <select name="status_pegawai" class="form-select">
                                <option value="aktif" <?= $row && $row->status=='aktif' ? 'selected':''; ?>>Aktif</option>
                                <option value="nonaktif" <?= $row && $row->status=='nonaktif' ? 'selected':''; ?>>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <a href="<?= base_url('pegawai'); ?>" class="btn btn-outline-secondary">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
