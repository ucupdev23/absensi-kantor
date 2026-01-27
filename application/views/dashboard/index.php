<div class="row">
    <div class="col-12 col-lg-6 mb-3">
        <div class="card card-main h-100">
            <div class="card-body">
                <h5 class="mb-2" style="color:#670F7A;">
                    Halo, <?= $this->session->userdata('nama_lengkap'); ?> 👋
                </h5>
                <p class="mb-2">
                    Anda login sebagai
                    <span class="badge badge-primary-custom">
                        <?= ucfirst($this->session->userdata('role')); ?>
                    </span>
                </p>
                <p class="text-muted mb-0">
                    Nanti di sini kita tampilkan ringkasan absensi hari ini,
                    shortcut ke menu absen, pengajuan cuti, dan laporan.
                </p>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 mb-3">
        <div class="card card-main h-100">
            <div class="card-body">
                <h6 class="mb-3" style="color:#670F7A;">Menu Cepat</h6>
                <div class="d-grid gap-2">
                    <!-- sementara placeholder, nanti kita isi link beneran -->
                    <button class="btn btn-outline-secondary" type="button" disabled>
                        Absen Masuk / Pulang (coming soon)
                    </button>
                    <button class="btn btn-outline-secondary" type="button" disabled>
                        Pengajuan Cuti / Izin (coming soon)
                    </button>
                    <button class="btn btn-outline-secondary" type="button" disabled>
                        Rekap Absensi (coming soon)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
