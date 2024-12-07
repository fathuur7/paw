<?php
// done.php
require_once '../functions.php';

$order_id = $_GET['order_id']; 
$payment_type = $_GET['payment_type'];

// Simpan data ke database
$query = "UPDATE order_pesanan SET payment_type = ? WHERE id_order = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $payment_type, $order_id);

// Jika query berhasil dijalankan
if ($stmt->execute()) {
    echo "Transaksi selesai";
    header("Location: tambah_order.php");
} else {
    echo "Transaksi gagal: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>