<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Halaman Tidak Ditemukan - 404</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: radial-gradient(circle at top, #1d4ed8 0, #0f172a 60%);
            color: #e5e7eb;
        }
        .card-404 {
            background: rgba(15, 23, 42, 0.9);
            border-radius: 1.5rem;
            border: 1px solid rgba(148, 163, 184, .3);
            box-shadow: 0 20px 40px rgba(0,0,0,.4);
        }
        .btn-light {
            color: #1f2937;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card card-404 p-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                            Error 404 · Halaman Tidak Ditemukan
                        </span>
                    </div>
                    <h1 class="display-4 fw-bold mb-2 text-danger">404</h1>
                    <h2 class="h5 mb-3 text-white">Oops, halaman yang kamu cari tidak tersedia.</h2>
                    <p class="text-secondary mb-4">
                        Bisa jadi alamat yang diketik salah, halaman sudah dipindah,
                        atau sudah tidak digunakan lagi.
                    </p>

                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <!-- langsung arahkan ke base project /absensi_kantor -->
                        <!-- <a href="/absensi_kantor" class="btn btn-light">
                            ← Kembali ke Beranda
                        </a> -->
                        <a href="javascript:history.back();" class="btn btn-outline-light">
                            ⤺ Kembali ke Halaman Sebelumnya
                        </a>
                    </div>

                    <p class="small text-warning mt-4 mb-0">
                        Jika masalah berlanjut, silakan hubungi admin website.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
