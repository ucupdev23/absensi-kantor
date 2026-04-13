<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-6 col-lg-4">
        <div class="card card-main mt-4">
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <div class="mb-2">
                        <div style="
                            width:72px;height:72px;
                            margin:0 auto;
                            display:flex;align-items:center;justify-content:center;">
                            <img src="<?= base_url('public/logo.png'); ?>"
                                 alt="Logo AK"
                                 style="
                                    width:100%;
                                    height:100%;
                                    object-fit:contain;
                                    display:block;">
                        </div>
                    </div>
                    <h5 class="mb-1" style="color:#670F7A;">Login</h5>
                    <small class="text-muted">Sistem Absensi Berbasis Lokasi</small>
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

                <form action="<?= base_url('auth/do_login'); ?>" method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text"
                               name="username"
                               id="username"
                               class="form-control"
                               placeholder="Masukkan username"
                               required>
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control"
                               placeholder="Masukkan password"
                               required>
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password">
                            <i class="bi bi-eye"></i>
                        </button>
                        </div>
                    </div>

                    <div class="alert alert-light border d-flex align-items-center mt-3 py-2">
                        <i class="bi bi-shield-lock me-2 text-secondary"></i>
                        <small class="flex-grow-1">
                            Lupa password akun?
                            <a href="<?= base_url('auth/forgot_password'); ?>" 
                            class="fw-semibold text-decoration-none"
                            style="color:#670F7A;">
                                Reset di sini
                            </a>
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 mt-2">
                        Masuk
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center mt-3 small text-muted">
            Pegawai dianjurkan login dari HP untuk absen masuk & pulang.
        </p>
    </div>
</div>
