<?php
session_start();
include "../functions.php";

// Ambil ID order dari parameter URL
$id_order = isset($_GET["id_order"]) ? (int)$_GET["id_order"] : null; 

// Validasi ID order
if (empty($id_order)) {
  echo json_encode(['success' => false, 'error' => 'ID order tidak valid']);
  exit;
}
// Ambil data order
$query = "SELECT * FROM order_pesanan WHERE id_order = $id_order";
$result = $conn->query($query);
$row = $result->fetch_assoc();

// Cek status order
if ($row["status_order"] == "Selesai") {
  echo json_encode(['success' => false, 'error' => 'Pesanan sudah dibayar']);
  exit;
} elseif ($row["status_order"] == "Dibatalkan") {
  echo json_encode(['success' => false, 'error' => 'Pesanan sudah dibatalkan']);
  exit;
}

$status = "Selesai";
$payment_type = "kasir";
$update_stmt = $conn->prepare("UPDATE order_pesanan SET status_order = ?, payment_type = ? WHERE id_order = ?");
$update_stmt->bind_param("sis", $status, $payment_type, $id_order);

if ($update_stmt->execute()) {
  echo json_encode(['success' => true]);
  header("Location: done_cash.php?order_id=" . $id_order);
  exit;
} else {
  echo json_encode(['success' => false, 'error' => $update_stmt->error]);
}

$update_stmt->close();
$conn->close();
?>