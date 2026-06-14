<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h3 style="text-align:center;">Laporan Absensi Bulan <?= $bulan; ?>/<?= $tahun; ?></h3>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kode Pegawai</th>
                <th>Nama</th>
                <th>Lokasi</th>
                <th>Mode</th>
                <th>Jam Masuk</th>
                <th>Status Masuk</th>
                <th>Jam Pulang</th>
                <th>Status Pulang</th>
                <th>Status Harian</th>
                <th>Total Jam</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($report as $row): ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($row->tanggal)); ?></td>
                <td><?= $row->kode_pegawai; ?></td>
                <td><?= $row->nama_lengkap; ?></td>
                <td><?= $row->nama_lokasi; ?></td>
                <td><?= $row->jam_masuk ? strtoupper($row->mode_absen) : '-'; ?></td>
                <td><?= $row->jam_masuk ? date('H:i', strtotime($row->jam_masuk)) : ''; ?></td>
                <td><?= $row->status_masuk; ?></td>
                <td><?= $row->jam_pulang ? date('H:i', strtotime($row->jam_pulang)) : ''; ?></td>
                <td><?= $row->status_pulang; ?></td>
                <td><?= $row->status_harian; ?></td>
                <td><?= $row->total_jam_kerja; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
