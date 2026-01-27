<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card card-main">
            <div class="card-body">
                <h5 class="mb-3" style="color:#670F7A;">
                    <?= $row ? 'Edit Lokasi' : 'Tambah Lokasi'; ?>
                </h5>

                <form action="<?= $row ? base_url('lokasi/update/'.$row->id) : base_url('lokasi/store'); ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Nama Lokasi</label>
                        <input type="text" name="nama" class="form-control"
                               required
                               value="<?= $row ? htmlspecialchars($row->nama) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3"><?= $row ? htmlspecialchars($row->alamat) : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" class="form-control"
                               required
                               value="<?= $row ? htmlspecialchars($row->latitude) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" class="form-control"
                               required
                               value="<?= $row ? htmlspecialchars($row->longitude) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Radius (m)</label>
                        <input type="number" name="radius_meter" class="form-control"
                               required
                               value="<?= $row ? htmlspecialchars($row->radius_meter) : ''; ?>">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('lokasi'); ?>" class="btn btn-outline-secondary">
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
