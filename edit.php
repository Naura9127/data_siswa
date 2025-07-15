<?php
// edit.php

include 'koneksi.php'; // Hubungkan ke database

$nis_siswa = null;
$siswa = null;

if (isset($_GET['nis'])) {
    $nis_siswa = htmlspecialchars($_GET['nis']);

    // Query untuk mengambil data siswa berdasarkan NIS
    $sql = "SELECT * FROM siswa WHERE nis = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $nis_siswa);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $siswa = mysqli_fetch_assoc($result);
        } else {
            // Siswa tidak ditemukan
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: index.php?status=siswa_tidak_ditemukan");
            exit();
        }
        mysqli_stmt_close($stmt);
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
    header("Location: index.php?status=siswa_tidak_ditemukan");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Edit Data Siswa</h2>

        <?php if ($siswa): ?>
        <form action="proses_edit.php" method="POST">
            <input type="hidden" name="nis_lama" value="<?php echo htmlspecialchars($siswa['nis']); ?>">

            <div class="mb-3">
                <label for="nama" class="form-label">Nama:</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($siswa['nama']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="nis" class="form-label">NIS:</label>
                <input type="text" class="form-control" id="nis" name="nis" value="<?php echo htmlspecialchars($siswa['nis']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="jk" class="form-label">Jenis Kelamin:</label>
                <select class="form-select" id="jk" name="jk" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" <?php echo ($siswa['jk'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo ($siswa['jk'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="kelas" class="form-label">Kelas:</label>
                <input type="text" class="form-control" id="kelas" name="kelas" value="<?php echo htmlspecialchars($siswa['kelas']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="jurusan" class="form-label">Jurusan:</label>
                <input type="text" class="form-control" id="jurusan" name="jurusan" value="<?php echo htmlspecialchars($siswa['jurusan']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Data</button>
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </form>
        <?php else: ?>
            <div class="alert alert-warning">Data siswa tidak ditemukan.</div>
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>