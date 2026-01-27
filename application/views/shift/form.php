<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card card-main">
            <div class="card-body">
                <h5 class="mb-3" style="color:#670F7A;">
                    <?= $row ? 'Edit Shift' : 'Tambah Shift'; ?>
                </h5>

                <form action="<?= $row ? base_url('shift/update/'.$row->id) : base_url('shift/store'); ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Nama Shift</label>
                        <input type="text" name="nama_shift" class="form-control"
                               required
                               value="<?= $row ? htmlspecialchars($row->nama_shift) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="form-control"
                               required
                               value="<?= $row ? htmlspecialchars($row->jam_masuk) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jam Pulang</label>
                        <input type="time" name="jam_pulang" class="form-control"
                               required
                               value="<?= $row ? htmlspecialchars($row->jam_pulang) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Toleransi Telat (menit)</label>
                        <input type="number" name="toleransi_telat_menit" class="form-control"
                               required
                               value="<?= $row ? htmlspecialchars($row->toleransi_telat_menit) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Toleransi Pulang Cepat (menit)</label>
                        <input type="number" name="toleransi_pulang_cepat_menit" class="form-control"
                               required
                               value="<?= $row ? htmlspecialchars($row->toleransi_pulang_cepat_menit) : ''; ?>">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('shift'); ?>" class="btn btn-outline-secondary">
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
