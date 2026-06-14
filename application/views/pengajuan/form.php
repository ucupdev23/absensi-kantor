<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card card-main">
            <div class="card-body">
                <h5 class="mb-3" style="color:#670F7A;">Buat Pengajuan</h5>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger py-2">
                        <?= $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('pengajuan/store'); ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Jenis Pengajuan</label>
                        <select name="jenis" class="form-select" required>
                            <option value="">- Pilih -</option>
                            <option value="cuti">Cuti (Sisa Jatah Cuti: <?= $sisa_cuti; ?> Hari)</option>
                            <option value="ganti_hari">Ganti Hari</option>
                            <option value="potong_gaji">Potong Gaji</option>
                            <option value="sakit">Sakit</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alasan</label>
                        <textarea name="alasan" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Lampiran (opsional, misal surat dokter / bukti lain) – jpg/png/pdf
                        </label>
                        <input type="file" name="lampiran" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <a href="<?= base_url('pengajuan'); ?>" class="btn btn-outline-secondary">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>