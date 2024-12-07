<?php
// Koneksi ke database
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "resto";
$conn = new mysqli($localhost, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

$id_user = $_GET['id']; // Mengambil ID dari URL

// Ambil data user berdasarkan ID
$sql_user = "SELECT * FROM user WHERE id_user = $id_user";
$result_user = mysqli_query($conn, $sql_user);

if (!$result_user) {
    die("Query gagal: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result_user);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_user = $_POST['nama_user'];
    $username = $_POST['username'];
    $password = md5($data["password1"]);
    $level = $_POST['level'];

    // Update query
    $sql_update = "UPDATE user SET nama_user = '$nama_user', username = '$username', password = '$password', level = '$level' WHERE id_user = $id_user";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>
                alert('Data berhasil diubah!');
                document.location.href = 'admin.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal diubah!');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Akun - Restoran</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>
<body>
  <div class="container mt-5">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title font-weight-bold">Edit Akun</h3>
      </div>
      <div class="card-body">
        <form action="" method="post">
          <div class="form-group">
            <label for="nama_user">Nama User:</label>
            <input type="text" name="nama_user" id="nama_user" class="form-control" value="<?= $user['nama_user']; ?>" required>
          </div>
          <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" class="form-control" value="<?= $user['username']; ?>" required>
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" value="<?= $user['password']; ?>" required>
          </div>
          <div class="form-group">
            <label for="level">Level:</label>
            <select name="level" id="level" class="form-control" required>
              <option value="1" <?= ($user['level'] == 1) ? 'selected' : ''; ?>>Admin</option>
              <option value="2" <?= ($user['level'] == 2) ? 'selected' : ''; ?>>User</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>
</html>
