<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Akses Ditolak - 403</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: radial-gradient(circle at top, #f97316 0, #111827 60%);
            color: #e5e7eb;
        }
        .card-403 {
            background: rgba(15, 23, 42, 0.95);
            border-radius: 1.5rem;
            border: 1px solid rgba(248, 250, 252, .2);
            box-shadow: 0 20px 40px rgba(0,0,0,.5);
        }
        .icon-lock {
            font-size: 3rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card card-403 p-4">
                <div class="card-body text-center">
                    <div class="mb-3 icon-lock">🔒</div>
                    <div class="mb-2">
                        <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                            Error 403 · Akses Ditolak
                        </span>
                    </div>
                    <h1 class="h3 fw-bold mb-2 text-white">Anda tidak memiliki hak akses.</h1>
                    <p class="text-secondary mb-4">
                        Halaman ini hanya dapat diakses oleh pengguna dengan hak akses tertentu.
                        Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator.
                    </p>

                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <a href="<?= site_url('dashboard'); ?>" class="btn btn-light">
                            ← Kembali ke Dashboard
                        </a>
                        <a href="<?= site_url('auth/logout'); ?>" class="btn btn-outline-light">
                            Logout & Ganti Akun
                        </a>
                    </div>

                    <p class="small text-warning mt-4 mb-0">
                        Akses dicatat untuk keperluan keamanan sistem.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
