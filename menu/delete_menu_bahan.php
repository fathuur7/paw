<?php 
session_start();
include '../functions.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM menu_bahan WHERE id_menu_bahan = $id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Menu-Bahan berhasil dihapus.';
        header("Location: ../order/chef.php");
    } else {
        $_SESSION['status'] = 'failed';
        $_SESSION['message'] = 'Terjadi kesalahan saat menghapus menu-bahan: ' . $conn->error;
    }
}
?>