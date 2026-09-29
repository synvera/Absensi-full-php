# AbsensiKu

Aplikasi absensi siswa berbasis PHP dan MySQL/MariaDB. Aplikasi ini masih berada pada tahap awal: antarmuka belum mendapatkan desain CSS khusus dan masih berupa HTML sederhana dengan logika PHP. Beberapa elemen memakai style inline dasar, tetapi belum ada sistem desain atau stylesheet untuk tampilan yang konsisten. Aplikasi menyediakan halaman untuk melihat status kehadiran hari ini, mengelola daftar siswa, mencatat absensi, dan mencetak rekapitulasi.

> **Status proyek:** versi awal/prototipe. Baca bagian [Catatan untuk tim](#catatan-untuk-tim-sebelum-digunakan) sebelum menyiapkan lingkungan bersama atau memakai data nyata.

## Daftar Isi

- [Fitur dan alur kerja](#fitur-dan-alur-kerja)
- [Screenshot halaman](#screenshot-halaman)
- [Teknologi dan struktur file](#teknologi-dan-struktur-file)
- [Menjalankan secara lokal](#menjalankan-secara-lokal)
- [Panduan penggunaan](#panduan-penggunaan)
- [Catatan untuk tim sebelum digunakan](#catatan-untuk-tim-sebelum-digunakan)

## Fitur dan alur kerja

1. **Login sekretaris** melalui `index.php` menggunakan email, password, dan kode khusus.
2. **Kelola data siswa** di `data_siswa.php`: tambah siswa dengan NIS dan nama, lihat daftar siswa, atau hapus siswa.
3. **Input absensi** di `input_absen.php`: pilih status Hadir, Sakit, Izin, atau Alpa dan isi keterangan opsional.
4. **Pantau dashboard** di `dashboard.php`: daftar siswa beserta status dan keterangan absensi untuk hari ini. Siswa yang belum memiliki catatan ditampilkan sebagai "Belum diisi".
5. **Cetak rekap** di `laporan_siswa.php`: lihat jumlah Sakit, Izin, dan Alpa per siswa, lalu gunakan tombol **Download Rekap** untuk membuka dialog cetak browser dan memilih **Save as PDF**.

Alur kerja yang disarankan: siapkan daftar siswa terlebih dahulu, input absensi, tinjau dashboard, lalu cetak rekap saat diperlukan.

## Screenshot halaman

Simpan screenshot di folder `docs/screenshots/` dengan nama berikut. Gambar akan tampil otomatis setelah file diletakkan di lokasi yang sesuai.

### Dashboard

File: `docs/screenshots/dashboard.png`

![Screenshot halaman dashboard](docs/screenshots/dashboard.png)

### Input siswa

File: `docs/screenshots/input-siswa.png`

![Screenshot halaman input siswa](docs/screenshots/input-siswa.png)

### Input absensi

File: `docs/screenshots/input-absensi.png`

![Screenshot halaman input absensi](docs/screenshots/input-absensi.png)

### Cetak laporan

File: `docs/screenshots/cetak-laporan.png`

![Screenshot halaman cetak laporan](docs/screenshots/cetak-laporan.png)

Untuk tampilan yang konsisten, ambil gambar setelah halaman selesai dimuat dan gunakan ukuran/zoom browser yang serupa untuk setiap screenshot. Pastikan screenshot tidak menampilkan password, kode khusus, atau data pribadi siswa yang tidak boleh dibagikan.

## Teknologi dan struktur file

- **PHP** dengan ekstensi MySQLi untuk logika halaman dan koneksi database.
- **MySQL/MariaDB** untuk menyimpan akun sekretaris, data siswa, dan catatan absensi.
- **HTML sederhana dan PHP** untuk halaman dan logika aplikasi; belum ada desain CSS khusus atau framework frontend. Beberapa style inline dipakai untuk kebutuhan dasar, dan fitur cetak laporan menggunakan dialog print bawaan browser.

```text
.
|-- index.php                 # Login sekretaris
|-- dashboard.php             # Ringkasan absensi hari ini
|-- data_siswa.php            # Tambah, lihat, dan hapus siswa
|-- input_absen.php           # Pencatatan absensi
|-- laporan_siswa.php         # Rekap dan cetak ke PDF
|-- config/
|   |-- koneksi.php            # Konfigurasi koneksi database
|   `-- database.sql          # Skema dan data awal (perlu koreksi; lihat catatan)
|-- proses/
|   `-- logout.php            # Penghapusan sesi dan kembali ke halaman login
`-- docs/
	`-- screenshots/          # Screenshot dokumentasi
```

## Menjalankan secara lokal

### Prasyarat

- PHP CLI dan ekstensi `mysqli` aktif.
- MySQL atau MariaDB berjalan.
- Browser modern.

### Siapkan database

1. Buat database `absensi_pure_html_php`.
2. Periksa dan koreksi `config/database.sql` sebelum dijalankan. Saat ini file tersebut belum dapat langsung diimpor: komentar `//` bukan komentar SQL yang valid, dan perintah `INSERT` memakai nama kolom `pasword` sedangkan tabel mendefinisikan `password`.
3. Jalankan SQL yang sudah dikoreksi melalui klien MySQL/MariaDB pilihan tim. Skrip tersebut membuat tabel `tabel_sekretaris`, `tabel_siswa`, dan `tabel_absensi`, serta berisi satu akun sekretaris contoh.
4. Sesuaikan host, user database, password database, dan nama database di `config/koneksi.php` dengan lingkungan lokal. Jangan menaruh kredensial pribadi pada repositori bersama.

Akun contoh yang tercantum di skrip SQL adalah `sekretaris@gmail.com`; password contoh dan kode khusus hanya untuk pengembangan lokal. Ganti sebelum aplikasi dipakai bersama, dan jangan gunakan kredensial contoh untuk data nyata.

### Jalankan server PHP

Dari direktori utama proyek:

```bash
php -S localhost:1234
```

Buka [http://localhost:1234](http://localhost:1234) di browser. Halaman login aplikasi adalah `index.php`.

## Panduan penggunaan

### 1. Masuk sebagai sekretaris

Buka `index.php`, lalu isi email, password, dan kode khusus yang cocok dengan data pada tabel `tabel_sekretaris`. Jika berhasil, aplikasi mengarahkan pengguna ke dashboard.

### 2. Tambah atau kelola siswa

Buka **Input data siswa**, isi NIS dan nama lengkap, lalu pilih **Tambah Siswa**. Daftar di bawah formulir menampilkan siswa yang tersimpan. Gunakan **Hapus** hanya setelah memastikan pilihan siswa benar; tindakan ini menghapus data siswa dari database.

### 3. Catat kehadiran

Buka **Input Absensi**, tentukan tanggal dan pilih satu status untuk setiap siswa yang belum tercatat. Keterangan seperti alasan izin atau informasi surat dokter bersifat opsional. Pilih **Simpan Data Absensi** setelah semua pilihan siap.

Pada implementasi saat ini, satu siswa yang sudah memiliki catatan untuk tanggal yang dibaca halaman tidak dapat dipilih ulang dari formulir. Periksa tanggal dan data sebelum menyimpan.

### 4. Tinjau dashboard dan cetak rekap

Dashboard menampilkan catatan untuk tanggal hari ini. Di halaman **Laporan Absensi**, tinjau jumlah Sakit, Izin, dan Alpa per siswa. Pilih **Download Rekap**, lalu pilih printer atau **Save as PDF** pada dialog browser. Tautan dan tombol pada halaman tidak ikut tercetak.

## Catatan untuk tim sebelum digunakan

Temuan berikut terlihat dari kode saat dokumentasi ini dibuat dan perlu ditangani sebelum aplikasi dianggap siap untuk penggunaan bersama:

- **Skrip database perlu diperbaiki.** Selain masalah sintaks dan typo kolom di atas, skema absensi belum membatasi kombinasi siswa dan tanggal agar unik. Logika aplikasi mengandalkan pengecekan sebelum insert, jadi pengiriman bersamaan berpotensi membuat catatan ganda.
- **Tanggal absensi belum konsisten.** Formulir mengirim tanggal yang dipilih, tetapi daftar siswa pada `input_absen.php` saat ini membaca status untuk tanggal hari ini. Ini dapat menampilkan pilihan yang keliru saat mengisi tanggal lain.
- **Tautan autentikasi belum konsisten.** Form login berada di `index.php`, tetapi validasi sesi di `data_siswa.php` mengarahkan ke `login.php`. Selain itu, tautan logout mengarah ke `logout.php` di direktori utama, sementara file yang tersedia adalah `proses/logout.php`.
- **Pemeriksaan sesi belum merata.** `data_siswa.php` memeriksa sesi, tetapi dashboard, input absensi, dan laporan belum melakukan pemeriksaan yang sama. Jangan membuka aplikasi ke jaringan publik sebelum akses tiap halaman dilindungi.
- **Kredensial perlu dikelola ulang.** Konfigurasi koneksi saat ini berisi kredensial database, sedangkan contoh login menyimpan password biasa. Gunakan konfigurasi lokal yang tidak dikomit, hash password, dan ganti semua kredensial contoh sebelum menyimpan data sungguhan.
- **Verifikasi data diperlukan.** Pastikan NIS unik dan tidak kosong jika aturan kelas mengharuskannya; skema saat ini belum menetapkan batasan tersebut.

### Prioritas tindak lanjut

1. Perbaiki dan uji skrip SQL pada database kosong.
2. Samakan rute login/logout dan terapkan pemeriksaan sesi ke semua halaman internal.
3. Gunakan tanggal pilihan pada query daftar absensi, lalu uji input untuk hari ini dan tanggal lain.
4. Tambahkan validasi data dan kebijakan keamanan kredensial sebelum penggunaan dengan data siswa nyata.
5. Ambil empat screenshot untuk folder `docs/screenshots/` setelah alur utama selesai diuji.
