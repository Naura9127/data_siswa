<?php
// proses_hapus.php

include 'koneksi.php'; // Hubungkan ke database

if (isset($_GET['nis'])) {
    $nis_siswa = htmlspecialchars($_GET['nis']);

    // Query untuk menghapus data
    $sql = "DELETE FROM siswa WHERE nis = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $nis_siswa);
        if (mysqli_stmt_execute($stmt)) {
            // Berhasil menghapus data
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: index.php?status=hapus_sukses");
            exit();
        } else {
            // Gagal menghapus data
            $error_message = mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: index.php?status=hapus_gagal&pesan_error=" . urlencode($error_message));
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
    // Jika tidak ada NIS di URL
    mysqli_close($conn);
    header("Location: index.php?status=hapus_gagal&pesan_error=" . urlencode("NIS tidak ditemukan untuk dihapus."));
    exit();
}
?>