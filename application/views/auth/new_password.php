<div class="row justify-content-center">
    <div class="col-12 col-md-4">
        <div class="card card-main">
            <div class="card-body">
                <div class="text-center mb-3">
                <h5 class="mb-1" style="color:#670F7A;">Reset Password</h5>
                    <p class="text-muted small mb-0">
                        Masukkan <strong>Password Baru</strong> Anda dua kali untuk konfirmasi. Setelah disimpan, Anda dapat menggunakan password baru ini untuk login.
                    </p>
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

                <form action="<?= base_url('auth/forgot_password/new_password_process'); ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_baru" id="rp_pwd_baru" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#rp_pwd_baru">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_konfirmasi" id="rp_pwd_konf" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#rp_pwd_konf">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100 mb-2">
                        Simpan Password Baru
                    </button>
                </form>

                <div class="text-center mt-2">
                    <a href="<?= base_url('auth/login'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
