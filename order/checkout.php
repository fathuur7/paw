<?php
require_once '../functions.php';
require_once '../config/midtrans-config.php';
session_start();

// Ambil order_id dari parameter URL
$order_id = isset($_GET['id_order']) ? (int)$_GET['id_order'] : null;
if (!$order_id) {
    die("ID order tidak ditemukan.");
}

try {
    // Ambil data pesanan
    $query = "SELECT op.*, od.*, m.nama_menu 
              FROM order_pesanan op 
              JOIN order_detail od ON op.id_order = od.id_order 
              JOIN menu m ON od.id_menu = m.id_menu 
              WHERE op.id_order = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die("Pesanan tidak ditemukan.");
    }
    
    $order_items = [];
    $total_bayar = 0;
    $customer_details = null; 
    
    $nama = $_SESSION["nama"];
    
    // Simpan data dari row pertama
    $first_row = $result->fetch_assoc();
    
    // Reset pointer result
    $result->data_seek(0);
    
    // Proses semua item
    while ($row = $result->fetch_assoc()) {
        $order_items[] = [
            'id' => $row['id_menu'],
            'price' => $row['harga'],
            'quantity' => $row['jumlah_order'],
            'name' => $row['nama_menu']
        ];
    }
    
    // Ambil total bayar dari row pertama
    $total_bayar = $first_row['total_bayar'];
    
    // Set customer details
    $customer_details = [
        'nama_pelayan' => $nama,
        'no_meja' => $first_row['no_meja']
    ];
    
    try {
        $snap_token = createMidtransTransaction($order_id, $order_items, $total_bayar, $customer_details);
        
        // Update snap_token di database
        $update_query = "UPDATE order_pesanan SET snap_token = ? WHERE id_order = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $snap_token, $order_id);
        $update_stmt->execute();
    
    } catch (Exception $e) {
        error_log("Checkout Error: " . $e->getMessage());
        die("Terjadi kesalahan: " . $e->getMessage());
    }

} catch (Exception $e) {
    error_log("Checkout Error: " . $e->getMessage());
    die("Terjadi kesalahan: " . $e->getMessage());
}

function createMidtransTransaction($order_id, $items, $total_bayar, $customer_details) {
    try {
        // Format order ID dengan prefix
        $midtrans_order_id = 'ORDER-' . $order_id . '-' . time();
        
        // Siapkan item details
        $item_details = array_map(function($item) {
            return [
                'id' => $item['id'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'name' => $item['name']
            ];
        }, $items);
        
        // Set parameter transaksi
        $transaction_params = [
            'transaction_details' => [
                'order_id' => $midtrans_order_id,
                'gross_amount' => (int)$total_bayar
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => $customer_details['nama_pelayan'],
                'email' => 'customer@test.com',
                'phone' => '082xxxxxxx',
                'billing_address' => [
                    'first_name' => $customer_details['nama_pelayan'],
                    'phone' => '082xxxxxxx',
                    'address' => 'Meja No. ' . $customer_details['no_meja']
                ]
            ]
        ];
        
        // Get Snap Token
        $snap_token = \Midtrans\Snap::getSnapToken($transaction_params);
        return $snap_token;
    
    } catch (Exception $e) {
        error_log("Create Midtrans Transaction Error: " . $e->getMessage());
        throw new Exception("Gagal membuat transaksi: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout Pesanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-Dqxei2uOKx9vP2Fd"></script>
    <style>
        .container { padding: 20px; }
        table { width: 100%; margin-bottom: 20px; }
        .btn-primary { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Detail Pesanan</h2>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                            <td><strong>Rp <?= number_format($total_bayar, 0, ',', '.') ?></strong></td>
                        </tr>
                    </tbody>
                </table>
                <h4 class="mb-4">Detail Pembeli</h4>
                <button id="pay-button" class="btn btn-primary w-100">Bayar Sekarang</button>
            </div>
        </div>
    </div>

    <script>
        let payButton = document.getElementById('pay-button');
        payButton.onclick = function() {
            snap.pay('<?= $snap_token ?>', {
                onSuccess: function(result) {
                    console.log('success');
                    console.log(result);
                    window.location.href = 'success-page.php?' + 
                        'order_id=<?= $order_id ?>&' + 
                        'transaction_id=' + result.transaction_id + '&' +
                        'status=success' + '&' + 'payment_type=' + result.payment_type;
                },
                onPending: function(result) {
                    console.log('pending');
                    console.log(result);
                    window.location.href = 'payment-pending.php?' + 
                        'order_id=<?= $order_id ?>&' + 
                        'transaction_id=' + result.transaction_id + '&' +
                        'status=pending';
                },
                onError: function(result) {
                    console.log('error');
                    console.log(result);
                    window.location.href = 'payment-error.php?' + 
                        'order_id=<?= $order_id ?>&' +
                        'status=error';
                },
                onClose: function() {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        };
    </script>
</body>
</html>