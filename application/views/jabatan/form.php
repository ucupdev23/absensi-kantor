<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card card-main">
            <div class="card-body">
                <h5 class="mb-3" style="color:#670F7A;">
                    <?= $row ? 'Edit Jabatan' : 'Tambah Jabatan'; ?>
                </h5>

                <form action="<?= $row ? base_url('jabatan/update/'.$row->id) : base_url('jabatan/store'); ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Nama Jabatan</label>
                        <input type="text" name="nama" class="form-control"
                               required
                               value="<?= $row ? htmlspecialchars($row->nama) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3"><?= $row ? htmlspecialchars($row->keterangan) : ''; ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('jabatan'); ?>" class="btn btn-outline-secondary">
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
