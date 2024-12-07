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

// Query untuk menghapus data
$sql_delete = "DELETE FROM user WHERE id_user = $id_user";

if (mysqli_query($conn, $sql_delete)) {
    echo "<script>
            alert('Akun berhasil dihapus!');
            document.location.href = 'admin.php';
          </script>";
} else {
    echo "<script>
            alert('Akun gagal dihapus!');
          </script>";
}
?>
