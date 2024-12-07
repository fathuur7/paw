<?php
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "resto";

// Buat koneksi
$conn = new mysqli($localhost, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Query untuk mengambil semua data dari tabel user
$sql_user = "SELECT * FROM user";
$result_user = mysqli_query($conn, $sql_user);

if (!$result_user) {
    die("Query gagal: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Daftar Akun</title>

  <style>
    .table-link {
      color: inherit;
      text-decoration: none;
    }

    .table-link:hover {
      color: black;
      text-decoration: underline;
    }
  </style>

  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
  <?php include "../header.php" ?>

  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 mx-auto mt-2">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title font-weight-bold">Daftar Akun</h3>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-end">
              <a href="tambah_akun.php" class="d-flex justify-content-end"><button class="btn btn-success mb-3">Tambah Akun</button></a>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-bordered mb-0">
                <thead class="table-primary">
                  <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Level</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; ?>
                  <?php while ($row = mysqli_fetch_assoc($result_user)) : ?>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $row["nama_user"]; ?></td>
                        <td><?= $row["username"]; ?></td>
                        <td><?= $row["password"]; ?></td>
                        <td><?= $row["level"]; ?></td>
                        <td>
                            <a href="ubah_akun.php?id=<?= $row["id_user"]; ?>"><button class='btn btn-warning btn-sm mr-2 mb-1'>Ubah</button></a>
                            <a href="hapus_akun.php?id=<?= $row["id_user"]; ?>" onclick="return confirm('Yakin akan dihapus?');"><button class='btn btn-danger btn-sm mb-1'>Hapus</button></a>
                        </td>
                    </tr>
                    <?php $no++ ?>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>

</html>