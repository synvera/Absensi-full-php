CREATE DATABASE absensi_pure_html_php; //buat terlebih dahulu nama/databasenya 
use absensi_pure_html_php; //gunakan databasenya

CREATE TABLE tabel_sekretaris (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    kode_khusus VARCHAR(50) NOT NULL
);
// buat tabel untuk login sekretarisnyaa

INSERT INTO tabel_sekretaris (email, pasword, kode_khusus)
VALUES ('sekretaris@gmail.com', 'sekretaris123', '534412mkh');

//fungsinya untuk mengisi data didalam tabel sekretaris

//kita buat tabel baru untuk siswa dan absennya

CREATE TABLE tabel_siswa (
    id_siswa INT AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(20),
    nama_siswa VARCHAR(100) NOT NULL
);

CREATE TABLE tabel_absensi (
    id_absen INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    id_siswa INT NOT NULL,
    status ENUM('Hadir', 'Sakit', 'Izin', 'Alpa') NOT NULL,
    keterangan VARCHAR(255), -- Opsional, misal surat dokter
    FOREIGN KEY (id_siswa) REFERENCES tabel_siswa(id_siswa)
);