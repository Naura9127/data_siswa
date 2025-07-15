<?php
// proses_tambah.php

include 'koneksi.php'; // Hubungkan ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST['nama']);
    $nis = htmlspecialchars($_POST['nis']);
    $jk = htmlspecialchars($_POST['jk']);
    $kelas = htmlspecialchars($_POST['kelas']);
    $jurusan = htmlspecialchars($_POST['jurusan']);

    // Validasi: Cek apakah NIS sudah ada di database
    $check_nis_sql = "SELECT nis FROM siswa WHERE nis = ?";
    $stmt_check = mysqli_prepare($conn, $check_nis_sql);
    mysqli_stmt_bind_param($stmt_check, "s", $nis);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        // NIS sudah ada, arahkan kembali dengan pesan error
        mysqli_stmt_close($stmt_check);
        mysqli_close($conn);
        header("Location: index.php?status=nis_duplikat");
        exit();
    }
    mysqli_stmt_close($stmt_check);

    // Query untuk memasukkan data
    $sql = "INSERT INTO siswa (nama, nis, jk, kelas, jurusan) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssss", $nama, $nis, $jk, $kelas, $jurusan);
        if (mysqli_stmt_execute($stmt)) {
            // Berhasil menambahkan data
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: index.php?status=tambah_sukses");
            exit();
        } else {
            // Gagal menambahkan data
            $error_message = mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: index.php?status=tambah_gagal&pesan_error=" . urlencode($error_message));
            exit();
        }
    } else {
        // Error saat menyiapkan statement
        $error_message = mysqli_error($conn);
        mysqli_close($conn);
        header("Location: index.php?status=query_error&pesan_error=" . urlencode($error_message));
        exit();
    }
} else {
    // Jika diakses langsung tanpa POST request
    header("Location: tambah.php");
    exit();
}
?>