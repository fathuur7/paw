<?php
require_once "./config/midtrans-config.php";
require_once './functions.php';

try {
    $notification = new \Midtrans\Notification();

    $transaction = $notification->transaction_status;
    $type = $notification->payment_type;
    $order_id = $notification->order_id;
    $transaction_id = $notification->transaction_id;
    $fraud = $notification->fraud_status;

    // Log notifikasi untuk debugging
    error_log("Midtrans Notification: " . json_encode($_POST));
    
    // Cek apakah order ID sesuai format
    if (!preg_match('/ORDER-(\d+)/', $order_id, $matches)) {
        error_log("Invalid Order ID format: " . $order_id);
        die("Format order ID tidak valid");
    }
    
    $original_order_id = $matches[1];
    
    // Cek apakah order exists
    $check_query = "SELECT id_order FROM order_pesanan WHERE id_order = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $original_order_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        error_log("Order not found: " . $original_order_id);
        die("Order tidak ditemukan");
    }

    // Update payment status
    $query = "UPDATE order_pesanan SET 
              payment_status = ?,
              payment_type = ?,
              transaction_id = ?
              WHERE id_order = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $transaction, $type, $transaction_id, $original_order_id);
    
    if (!$stmt->execute()) {
        error_log("Failed to update payment status: " . $stmt->error);
        throw new Exception("Gagal update status pembayaran");
    }

    // Update order status jika pembayaran sukses
    if ($transaction == 'settlement' || $transaction == 'capture') {
        if ($fraud == 'accept') {
            $query = "UPDATE order_pesanan SET 
                      status_order = 'Selesai', 
                      payment_status = 'success' 
                      WHERE id_order = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $original_order_id);
            
            if (!$stmt->execute()) {
                error_log("Failed to update order status: " . $stmt->error);
                throw new Exception("Gagal update status order");
            }
        }
    } else if ($transaction == 'cancel' || $transaction == 'deny' || $transaction == 'expire') {
        $query = "UPDATE order_pesanan SET 
                  status_order = 'Batal', 
                  payment_status = 'failed' 
                  WHERE id_order = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $original_order_id);
        $stmt->execute();
    }

    // Response success
    header('HTTP/1.1 200 OK');
    echo json_encode(['status' => 'success']);

} catch (Exception $e) {
    error_log("Midtrans Notification Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>