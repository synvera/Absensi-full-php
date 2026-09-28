<!-- Dashboard untuk melihat siswa -->
<?php
include 'config/koneksi.php';
$tanggal_hari_ini = date('Y-m-d'); // Ambil tanggal hari ini untuk query
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Dashboard absenku</h1>
        <nav style="display: flex; gap: 15px;">
            <a href="data_siswa.php">Input data siswa</a>
            <a href="input_absen.php">Input Absensi</a>
            <a href="laporan_siswa.php">laporan Absensi</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
<main>
    <table style="width: 100%;" border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Status kehadiran</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // MENGGUNAKAN LEFT JOIN:
                // Menampilkan semua siswa, lalu mencari data absen hari ini (jika ada)
                $query = "SELECT tabel_siswa.nis, tabel_siswa.nama_siswa, tabel_absensi.status, tabel_absensi.keterangan 
                          FROM tabel_siswa 
                          LEFT JOIN tabel_absensi 
                          ON tabel_siswa.id_siswa = tabel_absensi.id_siswa 
                          AND tabel_absensi.tanggal = '$tanggal_hari_ini' 
                          ORDER BY tabel_siswa.nama_siswa ASC";
                
                $ambil = mysqli_query($koneksi, $query);
                $no = 1;

                while ($siswa = mysqli_fetch_assoc($ambil)){
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $siswa['nis'];?></td>
                <td><?php echo $siswa['nama_siswa'];?></td>
                
                <!-- Jika status kosong (belum diabsen), tampilkan tulisan 'Belum diisi' -->
                <td><?php echo $siswa['status'] ? $siswa['status'] : 'Belum diisi'; ?></td>
                
                <td><?php echo $siswa['keterangan'] ? $siswa['keterangan'] : '-'; ?></td>
            </tr>
            <?php
                } 
            ?>
        </tbody>
    </table>
</main>
</body>
</html>