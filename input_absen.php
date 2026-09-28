<?php
include 'config/koneksi.php';

$pesan = '';
$tanggal_hari_ini = date('Y-m-d'); // Tanggal default hari ini

// Jika tombol "Simpan Data Absensi" ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status'])) {
    $tanggal = $_POST['tanggal'];
    $status_absen = $_POST['status']; 
    $keterangan_absen = $_POST['keterangan']; 

    // Looping HANYA untuk siswa yang datanya dikirim (yang belum diabsen)
    foreach ($status_absen as $id_siswa => $status) {
        
        // Cek database, pastikan siswa ini benar-benar belum absen di tanggal tersebut
        $cek = mysqli_query($koneksi, "SELECT * FROM tabel_absensi WHERE id_siswa = '$id_siswa' AND tanggal = '$tanggal'");
        
        if (mysqli_num_rows($cek) == 0) {
            $keterangan = $keterangan_absen[$id_siswa] ?? '';
            
            mysqli_query($koneksi, "INSERT INTO tabel_absensi (tanggal, id_siswa, status, keterangan) 
                                    VALUES ('$tanggal', '$id_siswa', '$status', '$keterangan')");
        }
    }
    $pesan = "<p style='color:green; padding:10px; background:#e8f8f5;'>Data absensi yang baru berhasil disimpan!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Input Absen</title>
</head>
<body>
    <header style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Input Data Absen siswa</h1>
        <nav style="display: flex; gap: 15px;">
            <a href="dashboard.php">Dashboard Absensi</a>
            <a href="data_siswa.php">Input data siswa</a>
            <a href="laporan_siswa.php">laporan Absensi</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    
    <?php echo $pesan; ?>

    <form method="POST" action="">
        <label>Tanggal Absensi:</label>
        <input type="date" name="tanggal" value="<?php echo $tanggal_hari_ini; ?>" required>
        <br><br>

        <table border="1" style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Status Kehadiran</th>
                    <th>Keterangan (Opsional)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Gunakan LEFT JOIN untuk mencari tahu status siswa HARI INI
                $query = "SELECT tabel_siswa.*, tabel_absensi.status AS status_hari_ini 
                          FROM tabel_siswa 
                          LEFT JOIN tabel_absensi 
                          ON tabel_siswa.id_siswa = tabel_absensi.id_siswa AND tabel_absensi.tanggal = '$tanggal_hari_ini'
                          ORDER BY tabel_siswa.nama_siswa ASC";
                
                $ambil = mysqli_query($koneksi, $query);
                $no = 1;

                while ($siswa = mysqli_fetch_assoc($ambil)) {
                    $id = $siswa['id_siswa'];
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $siswa['nis']; ?></td>
                        <td><?php echo $siswa['nama_siswa']; ?></td>
                        
                        <td>
                            <?php 
                            // LOGIKA KUNCI: Jika status_hari_ini sudah ada isinya, sembunyikan radio button
                            if ($siswa['status_hari_ini'] != null) { 
                                echo "<span style='color: gray; font-weight: bold;'>Sudah Diabsen (".$siswa['status_hari_ini'].")</span>";
                            } else { 
                                // Jika belum absen, tampilkan radio button
                            ?>
                                <label><input type="radio" name="status[<?php echo $id; ?>]" value="Hadir" checked> Hadir</label>
                                <label><input type="radio" name="status[<?php echo $id; ?>]" value="Sakit"> Sakit</label>
                                <label><input type="radio" name="status[<?php echo $id; ?>]" value="Izin"> Izin</label>
                                <label><input type="radio" name="status[<?php echo $id; ?>]" value="Alpa"> Alpa</label>
                            <?php } ?>
                        </td>
                        
                        <td>
                            <?php 
                            // Matikan form keterangan jika sudah absen
                            if ($siswa['status_hari_ini'] != null) { 
                                echo "<i>Selesai</i>"; 
                            } else { ?>
                                <input type="text" name="keterangan[<?php echo $id; ?>]" placeholder="Misal: Surat dokter..." style="width: 90%;">
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
        <button type="submit">Simpan Data Absensi</button>
    </form>
</body>
</html>