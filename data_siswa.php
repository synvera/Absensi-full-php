<?php
session_start();

// Validasi Keamanan
if (!isset($_SESSION['email_sekretaris'])) {
    header("Location: index.php");
    exit();
}

include 'config/koneksi.php';
$pesan = '';

// Jika tombol "Tambah Siswa" ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_siswa'])) {
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama_siswa = mysqli_real_escape_string($koneksi, $_POST['nama_siswa']);

    // Simpan ke database
    $query_insert = "INSERT INTO tabel_siswa (nis, nama_siswa) VALUES ('$nis', '$nama_siswa')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        $pesan = "<p>Berhasil menambahkan $nama_siswa ke dalam data kelas!</p>";
    } else {
        $pesan = "<p>Gagal menambahkan data.</p>";
    }
}

// Logika untuk menghapus siswa
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    
    // TAHAP 1: Hapus terlebih dahulu semua riwayat absensi siswa ini di tabel_absensi
    mysqli_query($koneksi, "DELETE FROM tabel_absensi WHERE id_siswa = '$id_hapus'");
    
    // TAHAP 2: Setelah absennya bersih, baru hapus nama siswanya di tabel_siswa
    mysqli_query($koneksi, "DELETE FROM tabel_siswa WHERE id_siswa = '$id_hapus'");
    
    // Refresh halaman agar tabel terupdate
    header("Location: data_siswa.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Siswa</title>
</head>
<body>
    <header style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Input Data Siswa Absenku</h1>
        <nav style="display: flex; gap: 15px;">
            <a href="dashboard.php">Dashboard Absensi</a>
            <a href="input_absen.php">Input Absensi</a>
            <a href="laporan_siswa.php">laporan Absensi</a>
            <a href="proses/logout.php">Logout</a>
        </nav>
    </header>
    <div class="container">

        
        <?php echo $pesan; ?>

        <!-- Form Tambah Siswa -->
        <div>
            <h3>Tambah Siswa Baru</h3>
            <form method="POST" action="">
                <input type="text" name="nis" placeholder="Nomor Induk Siswa (NIS)" required>
                <input type="text" name="nama_siswa" placeholder="Nama Lengkap Siswa" required>
                <button type="submit" name="tambah_siswa" class="btn-tambah">+ Tambah Siswa</button>
            </form>
        </div>

        <!-- Tabel Daftar Siswa -->
        <h3>Daftar Siswa (Total: <?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tabel_siswa")); ?> Siswa)</h3>
        <table style="width: 100%;" border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query_tampil = mysqli_query($koneksi, "SELECT * FROM tabel_siswa ORDER BY nama_siswa ASC");
                $no = 1;
                while ($row = mysqli_fetch_assoc($query_tampil)) {
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['nis']; ?></td>
                        <td><?php echo $row['nama_siswa']; ?></td>
                        <td>
                            <!-- Tombol Hapus dengan konfirmasi -->
                            <a href="data_siswa.php?hapus=<?php echo $row['id_siswa']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus <?php echo $row['nama_siswa']; ?> dari kelas?');">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>