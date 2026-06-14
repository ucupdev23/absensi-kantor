<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Absensi Kantor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


    <style>
        :root {
            --primary-color: #670F7A;
            --secondary-color: #B894C0;
            --light-color: #ffffff;
        }

        body {
            background-color: #f4f4f7;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background-color: #670F7A;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #ffffff !important;
            font-weight: 500;
        }

        .navbar-custom .navbar-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
        }

        .navbar-logo {
            width: 34px;
            height: 34px;
            object-fit: contain;
            display: block;
            flex-shrink: 0;
        }

        .navbar-custom .nav-link.active {
            text-decoration: underline;
        }

        .app-container {
            flex: 1 0 auto;
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .card-main {
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            background-color: #ffffff;
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--light-color);
            border-radius: 999px;
            font-weight: 500;
        }

        .btn-primary-custom:hover {
            background-color: #4c0b58;
            border-color: #4c0b58;
            color: #fff;
        }

        .badge-primary-custom {
            background-color: var(--secondary-color);
            color: #fff;
        }

        footer {
            flex-shrink: 0;
        }

        .footer-text {
            font-size: 0.8rem;
            color: #777;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?= base_url('dashboard'); ?>">
            <img src="<?= base_url('public/logo.png'); ?>" alt="Logo AK" class="navbar-logo">
            <span>Absensi Kantor</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
    <?php if ($this->session->userdata('logged_in')): ?>
        <?php if ($this->session->userdata('role') == 'admin'): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="masterDropdown"
                   role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Master Data
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="masterDropdown">
                    <li><a class="dropdown-item" href="<?= base_url('jabatan'); ?>">Jabatan</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('lokasi'); ?>">Lokasi Kantor</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('shift'); ?>">Shift</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('pegawai'); ?>">Pegawai</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('penugasan_lapangan'); ?>">Penugasan Lapangan</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('penugasan_wfh'); ?>">Penugasan WFH</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('pengajuan-admin'); ?>">Pengajuan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('laporan'); ?>">Laporan</a>
            </li>
        <?php else: ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('absensi'); ?>">Absensi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('pengajuan'); ?>">Pengajuan</a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('profil/password'); ?>">Ubah Password</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('logout'); ?>">Logout</a>
        </li>
    <?php else: ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('login'); ?>">Login</a>
        </li>
    <?php endif; ?>
</ul>

        </div>
    </div>
</nav>

<div class="container app-container">
