<?php
include 'config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi Siswa</title>
    <style>
        /* Gaya standar untuk tabel */
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { border: 1px solid black; padding: 8px; }
        
        /* CSS Khusus PDF: Semua yang memiliki class "no-print" akan disembunyikan di PDF */
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <header style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Input Data Absen siswa</h1>
        <nav style="display: flex; gap: 15px;">
            <a href="dashboard.php">Dashboard Absensi</a>
            <a href="data_siswa.php">Input data siswa</a>
            <a href="input_absen.php">Input Absensi</a>
            <a href="proses/logout.php">Logout</a>
        </nav>
    </header>
    <!-- Area tombol yang akan dihilangkan saat menjadi PDF -->
    <div class="no-print">
        <a href="dashboard.php">← Kembali ke Dashboard</a>
        <br><br>
        <button onclick="window.print()">Download Rekap</button>
    </div>

    <!-- memanfaatkan teknologi print, namun kita alihkan untuk save as pdf -->

    <h2>Laporan Rekapitulasi Absensi Keseluruhan</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alfa</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT tabel_siswa.nis, tabel_siswa.nama_siswa,
                      SUM(CASE WHEN tabel_absensi.status = 'Sakit' THEN 1 ELSE 0 END) AS total_sakit,
                      SUM(CASE WHEN tabel_absensi.status = 'Izin' THEN 1 ELSE 0 END) AS total_izin,
                      SUM(CASE WHEN tabel_absensi.status = 'Alpa' THEN 1 ELSE 0 END) AS total_alfa
                      FROM tabel_siswa
                      LEFT JOIN tabel_absensi ON tabel_siswa.id_siswa = tabel_absensi.id_siswa
                      GROUP BY tabel_siswa.id_siswa
                      ORDER BY tabel_siswa.nama_siswa ASC";
            
            $ambil = mysqli_query($koneksi, $query);
            $no = 1;

            while ($data = mysqli_fetch_assoc($ambil)) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $data['nis']; ?></td>
                    <td><?php echo $data['nama_siswa']; ?></td>
                    <td style="text-align: center;"><b><?php echo $data['total_sakit']; ?></b></td>
                    <td style="text-align: center;"><b><?php echo $data['total_izin']; ?></b></td>
                    <td style="text-align: center;"><b><?php echo $data['total_alfa']; ?></b></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>