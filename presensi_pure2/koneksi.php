<?php
// Konfigurasi koneksi
$host = "localhost";     // Ganti dengan host database Anda
$user = "root";          // Ganti dengan username database Anda
$pass = "";              // Ganti dengan password database Anda
$dbname = "absenrfid";   // Ganti dengan nama database Anda

// Membuat koneksi menggunakan MySQLi
$konek = new mysqli($host, $user, $pass, $dbname);

// Periksa apakah koneksi berhasil
if ($konek->connect_error) {
    die("Koneksi gagal: " . $konek->connect_error);
}


// Jangan menutup koneksi di sini; biarkan itu terbuka sampai diperlukan.
// Fungsi penutupan koneksi dapat dipanggil dari file lain atau di akhir pemrosesan halaman.
?>
