<?php
// done.php
require_once '../functions.php';

$order_id = $_GET['order_id']; 

// Ambil data order
$query = "SELECT * FROM order_pesanan WHERE id_order = $order_id";
$result = $conn->query($query);
$row = $result->fetch_assoc();

// // Cek status order
// if ($row["status_order"] == "Selesai") {
//     echo "Pesanan sudah dibayar";
//     exit;
// } elseif ($row["status_order"] == "Dibatalkan") {
//     echo "Pesanan sudah dibatalkan";
//     exit;
// }

// Update status order
$status = "Selesai";
$payment_type = "kasir";
$update_stmt = $conn->prepare("UPDATE order_pesanan SET status_order = ?, payment_type = ? WHERE id_order = ?");
$update_stmt->bind_param("sis", $status, $payment_type, $order_id);

// Jika query gagal dijalankan
if (!$update_stmt->execute()) {
    echo "Transaksi gagal: " . $update_stmt->error;
    exit;
}

$update_stmt->close();

// Redirect ke halaman tambah_order_qr.php
// Simpan data ke database
$query = "UPDATE order_pesanan SET payment_type = 'kasir' WHERE id_order = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);

// Jika query berhasil dijalankan
if ($stmt->execute()) {
    header("Location: tambah_order_qr.php");
    exit;
} else {
    echo "Error: " . $conn->error;
}