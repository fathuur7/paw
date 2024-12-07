<?php
// payment-success.php
require_once '../functions.php';

// Ambil data dari URL
$order_id = $_GET['order_id'];
$payement_id = $_GET['payment_type'];

// Simpan data ke database
$query = "UPDATE order_pesanan SET status_order = 'Selesai' , payment_type = ? WHERE id_order = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is",$payement_id, $order_id);

// Jika query berhasil dijalankan
if ($stmt->execute()) {
    echo "Transaksi selesai";
    header("Location: done.php?order_id=". $order_id . "&payment_type=" . $payement_id);
} else {
    echo "Transaksi gagal: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>