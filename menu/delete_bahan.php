<?php
    require_once "../functions.php";

    $id = $_GET['id'];
    if($id){
        $sql = "DELETE FROM bahan WHERE id_bahan = $id";
        $query = mysqli_query($conn, $sql);
        if($query){
            echo "
                <script>
                    alert('Berhasil Dihapus');
                    window.location.href = '../order/chef.php';
                </script>
            ";
        } else {
            echo "Gagal Dihapus" . mysqli_error($conn);
        }
    }

?>
<?php
session_start();

require_once "../functions.php";

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Ambil ID bahan dari URL
$id_menu_bahan = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_menu = isset($_GET['id_menu']) ? intval($_GET['id_menu']) : 0; // Ambil ID menu untuk redirect

// Query untuk menghapus bahan dari menu
$sql_delete = "DELETE FROM menu_bahan WHERE id_menu_bahan = ?";
$stmt = $conn->prepare($sql_delete);
$stmt->bind_param("i", $id_menu_bahan);

if ($stmt->execute()) {
    // Jika berhasil, redirect ke halaman detail menu
    header("Location: ../order/chef.php");
    exit();
} else {
    echo "<script>alert('Terjadi kesalahan saat menghapus bahan.');</script>";
}

$stmt->close();
$conn->close();
?>