<!-- login sekretaris -->
<?php
session_start(); //fungsi session adalah untuk mengingat bahwa akun ini sudah login sebelumnya
include 'config/koneksi.php'; //untuk mengambil data koneksi dari file koneksi.php

//disini kita buat dulu untuk logika "jika sudah pernah login, maka langsung pergi ke dashboard"
if (isset($_SESSION['email_sekretaris'])) {
    header("Location: dashboard.php");
    exit();
}

//buat jika terjadi error

$error = '';

//kita tulis logika disini saja, tanpa membuat proses-login.php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //disini fungsinya untuk mengambil hasil inputan yang ada di input 
    $email_input = mysqli_real_escape_string($koneksi, $_POST['email']);
    $pw_input = mysqli_real_escape_string($koneksi, $_POST['password']);
    $kodek_input = mysqli_real_escape_string($koneksi, $_POST['kode']);

    //setelah selesai, kita akan memberikan sebuah perintah untuk mengecek apakah dari ketiga inputan itu ada atau tidak didalam database
    $query = "SELECT * FROM tabel_sekretaris WHERE email = '$email_input' AND password = '$pw_input' AND kode_khusus = '$kodek_input'";
    $hasil = mysqli_query($koneksi, $query);

    //lalu kita akan mengecek email, dimana jika emailnya benar, maka langsung OTW ke dashboard
    if (mysqli_num_rows($hasil) > 0){
        $_SESSION['email_sekretaris'] = $email_input;

        header("Location: dashboard.php");
        exit();
    } else {
        //jika tidak ada, maka muncul peringatan
        $error = "Akses ditolak! Email atau Password atau kode khusus anda salah";
    }
}
?>

<!-- //lanjut kita buat halamannya ygy -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AbsenKu | Login Page</title>
</head>
<body>
<h2>Portal Sekretaris</h2>
<p>Login terlebih dahulu untuk menginput kehadiran siswa/i didalam kelas</p>

<form method="post">
    <label for="email">Email</label> <br>
    <input type="email" name="email" required> <br> <br>
    <label for="password">Password</label> <br>
    <input type="password" name="password" required> <br> <br>
    <label for="kode">Kode Khusus</label> <br>
    <input type="password" name="kode" required> <br> <br>

    <?php if($error != '') { echo "<p>$error</p><br>";} ?>
    <button type="submit">Masuk</button>
</form>
</body>
</html>