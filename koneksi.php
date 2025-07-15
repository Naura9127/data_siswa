<?php
// config/database.php ATAU koneksi.php

$host = 'localhost';
$user = 'root';
$pass = '210919'; // <-- PERBAIKI INI
$db_name = 'data_siswa'; // <-- PASTIKAN INI SAMA DENGAN NAMA DATABASE ANDA DI PHPMyAdmin

// Buat koneksi
$conn = mysqli_connect($host, $user, $pass, $db_name);

// Cek koneksi (tetap ada)
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>