<?php
session_start();

require_once "../functions.php";

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Ambil ID menu dari URL
$id_menu = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Cek apakah ID menu valid
if ($id_menu <= 0) {
    echo "<script>alert('ID menu tidak valid.'); window.location.href='../order/chef.php';</script>";
    exit();
}

// Fungsi untuk menghapus menu
function hapusMenu($id_menu, $conn) {
    // Hapus data dari tabel order_detail terlebih dahulu
    $sql_hapus_order = "DELETE FROM order_detail WHERE id_menu = ?";
    $stmt_order = $conn->prepare($sql_hapus_order);
    $stmt_order->bind_param("i", $id_menu);
    $stmt_order->execute();

    // Hapus data dari tabel menu_bahan
    $sql_hapus_bahan = "DELETE FROM menu_bahan WHERE id_menu = ?";
    $stmt_bahan = $conn->prepare($sql_hapus_bahan);
    $stmt_bahan->bind_param("i", $id_menu);
    $stmt_bahan->execute();

    // Hapus data dari tabel menu
    $sql_hapus_menu = "DELETE FROM menu WHERE id_menu = ?";
    $stmt_menu = $conn->prepare($sql_hapus_menu);
    $stmt_menu->bind_param("i", $id_menu);
    return $stmt_menu->execute();
}

// Jika halaman diakses dengan metode GET dan ID menu ada, lakukan penghapusan
if ($id_menu > 0) {
    if (hapusMenu($id_menu, $conn)) {
        echo "<script>alert('Menu dan bahan berhasil dihapus.'); window.location.href='../order/chef.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus menu.'); window.location.href='../order/chef.php';</script>";
    }
}

// Tutup koneksi
$conn->close();
?>