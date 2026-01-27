<div class="row justify-content-center">
    <div class="col-12 col-md-4">
        <div class="card card-main">
            <div class="card-body">
                 <div class="text-center mb-3">
                    <h5 class="mb-1" style="color:#670F7A;">Lupa Password</h5>
                    <p class="text-muted small mb-0">
                        Masukkan <strong>username</strong> Anda. Sistem akan mengirimkan
                        kode OTP ke WhatsApp yang terdaftar.
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

                <form action="<?= base_url('auth/forgot_password/process'); ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100 mb-2">
                        Kirim OTP ke WhatsApp
                    </button>
                </form>

                <div class="text-center mt-2">
                    <a href="<?= base_url('auth/login'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Login
                    </a>
                </div>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        Pastikan nomor WhatsApp sudah benar di data pegawai / user.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
