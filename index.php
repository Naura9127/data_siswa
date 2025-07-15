<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #899fb4ff; /* Warna latar belakang baru */
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table thead th {
            background-color: #978ca5ff; /* Warna header tabel baru */
            color: white;
        }
        .btn-action {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Daftar Data Siswa</h2>

        <?php
        // Ambil pesan sukses atau error dari URL
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'tambah_sukses') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Data siswa berhasil ditambahkan!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            } elseif ($_GET['status'] == 'edit_sukses') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Data siswa berhasil diperbarui!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            } elseif ($_GET['status'] == 'hapus_sukses') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Data siswa berhasil dihapus!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            } elseif ($_GET['status'] == 'hapus_gagal') {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Gagal menghapus data siswa!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            } elseif ($_GET['status'] == 'nis_duplikat') {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">NIS sudah terdaftar. Mohon gunakan NIS lain.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            } elseif ($_GET['status'] == 'siswa_tidak_ditemukan') {
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">Data siswa tidak ditemukan!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            } elseif (isset($_GET['pesan_error_koneksi'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Koneksi database bermasalah: ' . htmlspecialchars($_GET['pesan_error_koneksi']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
        }
        ?>

        <div class="mb-3 text-end">
            <a href="tambah.php" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Data</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Jenis Kelamin</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Jurusan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'koneksi.php'; // Hubungkan ke database

                    if (isset($conn) && $conn) {
                        // Query untuk mengambil data siswa, diurutkan berdasarkan NAMA secara ASCENDING (A-Z)
                        $sql = "SELECT * FROM siswa ORDER BY nama ASC"; // Perubahan di sini: dari nis ASC menjadi nama ASC
                        $result = mysqli_query($conn, $sql);

                        if ($result) { // Pastikan query berhasil dieksekusi
                            if (mysqli_num_rows($result) > 0) {
                                $no = 1;
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['jk']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['nis']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['jurusan']) . "</td>";
                                    echo "<td>";
                                    // LINK EDIT MENGGUNAKAN NIS
                                    echo "<a href='edit.php?nis=" . htmlspecialchars($row['nis']) . "' class='btn btn-primary btn-sm btn-action'><i class='fas fa-edit'></i> Edit</a>";
                                    // LINK HAPUS MENGGUNAKAN NIS
                                    echo "<a href='proses_hapus.php?nis=" . htmlspecialchars($row['nis']) . "' class='btn btn-danger btn-sm btn-action' onclick=\"return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')\"><i class='fas fa-trash-alt'></i> Hapus</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>Belum ada data siswa.</td></tr>";
                            }
                        } else {
                            // Error jika query gagal
                            echo "<tr><td colspan='7' class='text-center text-danger'>Error saat mengambil data: " . htmlspecialchars(mysqli_error($conn)) . "</td></tr>";
                        }
                        mysqli_close($conn); // Tutup koneksi hanya jika sudah berhasil dibuat dan digunakan
                    } else {
                        // Pesan error jika koneksi gagal (sudah ditangani di koneksi.php, tapi bisa juga di sini)
                        echo "<tr><td colspan='7' class='text-center text-danger'>Gagal terhubung ke database. Cek file koneksi.php.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
