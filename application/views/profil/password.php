<div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card card-main">
            <div class="card-body">
                <h5 class="mb-3" style="color:#670F7A;">Ubah Password</h5>

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

                <form action="<?= base_url('profil/password/update'); ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <div class="input-group">
                            <input type="password" name="password_lama" id="pwd_lama" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#pwd_lama">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_baru" id="pwd_baru" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#pwd_baru">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_konfirmasi" id="pwd_konf" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#pwd_konf">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('dashboard'); ?>" class="btn btn-outline-secondary">
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
