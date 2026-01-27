<table border="1" cellspacing="0" cellpadding="4">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Kode Pegawai</th>
            <th>Nama</th>
            <th>Lokasi</th>
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
            <td><?= $row->tanggal; ?></td>
            <td><?= $row->kode_pegawai; ?></td>
            <td><?= $row->nama_lengkap; ?></td>
            <td><?= $row->nama_lokasi; ?></td>
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
