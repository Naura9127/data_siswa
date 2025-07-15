<?php
// proses_edit.php

include 'koneksi.php'; // Hubungkan ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nis_lama = htmlspecialchars($_POST['nis_lama']); // NIS yang lama untuk identifikasi
    $nama = htmlspecialchars($_POST['nama']);
    $nis_baru = htmlspecialchars($_POST['nis']); // NIS yang baru
    $jk = htmlspecialchars($_POST['jk']);
    $kelas = htmlspecialchars($_POST['kelas']);
    $jurusan = htmlspecialchars($_POST['jurusan']);

    // Validasi: Cek apakah NIS baru sudah ada di database, kecuali jika NIS tidak berubah
    if ($nis_lama !== $nis_baru) {
        $check_nis_sql = "SELECT nis FROM siswa WHERE nis = ?";
        $stmt_check = mysqli_prepare($conn, $check_nis_sql);
        mysqli_stmt_bind_param($stmt_check, "s", $nis_baru);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            // NIS baru sudah ada, arahkan kembali dengan pesan error
            mysqli_stmt_close($stmt_check);
            mysqli_close($conn);
            header("Location: index.php?status=nis_duplikat");
            exit();
        }
        mysqli_stmt_close($stmt_check);
    }

    // Query untuk memperbarui data
    $sql = "UPDATE siswa SET nama = ?, nis = ?, jk = ?, kelas = ?, jurusan = ? WHERE nis = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssss", $nama, $nis_baru, $jk, $kelas, $jurusan, $nis_lama);
        if (mysqli_stmt_execute($stmt)) {
            // Berhasil memperbarui data
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: index.php?status=edit_sukses");
            exit();
        } else {
            // Gagal memperbarui data
            $error_message = mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: index.php?status=edit_gagal&pesan_error=" . urlencode($error_message));
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
    header("Location: index.php");
    exit();
}
?>