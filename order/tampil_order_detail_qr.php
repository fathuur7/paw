<?php
session_start();
include "../functions.php";

if (!isset($_SESSION["login"]) && $_SESSION['login'] != true) {
  header("Location: ../login.php");
  exit;
} else {
  $menit = 15;
  $batas_waktu = $menit * 60;
  if (time() - $_SESSION["login_time"] > $batas_waktu) {
    echo "<script>
            alert('Sesi Anda sudah habis. Silahkan login kembali!');
            document.location.href = '../logout.php';
          </script>";
  } 
}

$id_order = $_GET["id_order"];
$data_order = query("SELECT * FROM order_pesanan WHERE id_order = $id_order")[0];
$data_order_detail = query("SELECT * FROM order_detail WHERE id_order = $id_order");
$nama = $_SESSION["nama"];

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Rincian Detail Pesanan</title>

  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <style>
    .modal-confirm .modal-content {
      padding: 20px;
      border-radius: 15px;
      border: none;
    }
    .modal-confirm .modal-header {
      border-bottom: none;   
      position: relative;
      justify-content: center;
      padding: 30px 0 15px;
    }
    .modal-confirm .icon-box {
      width: 80px;
      height: 80px;
      margin: 0 auto;
      border-radius: 50%;
      z-index: 9;
      text-align: center;
      border: 3px solid #82CE34;
    }
    .modal-confirm .icon-box i {
      color: #82CE34;
      font-size: 46px;
      display: inline-block;
      margin-top: 13px;
    }
    .modal-confirm .btn {
      min-width: 100px;
      border-radius: 50px;
      margin: 0 5px;
    }
    .modal-confirm .payment-methods {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin: 20px 0;
    }
    .modal-confirm .payment-methods i {
      font-size: 2rem;
      color: #666;
    }
    .btn-payment {
      min-height: 50px;
      font-size: 1.1rem;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <?php include "../header.php" ?>

  <div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
      <section class="content py-4">
        <div class="container">
          <!-- Order Summary Card -->
          <div class="card card-outline card-primary shadow">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-clipboard-list mr-2"></i>
                Rincian Detail Pesanan
              </h3>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <a href="tambah_order_qr.php?no_meja=<?= $data_order["no_meja"]; ?>" class="btn btn-outline-secondary">
                  <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
              </div>
              
              <!-- Order Info Table -->
              <div class="table-responsive rounded">
                <table class="table table-striped table-bordered mb-0">
                  <thead class="bg-primary text-white">
                    <tr>
                      <th class="align-middle text-center">ID Order</th>
                      <th class="align-middle text-center">Nama Pembeli</th>
                      <th class="align-middle text-center">Tanggal Order</th>
                      <th class="align-middle text-center">Jam Order</th>
                      <th class="align-middle text-center">Pelayan</th>
                      <th class="align-middle text-center">No. Meja</th>
                      <th class="align-middle text-center">Total Bayar</th>
                      <th class="align-middle text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="text-center">
                      <td><?= $id_order; ?></td>
                      <td><?= $nama; ?></td>
                      <td><?= $data_order["tanggal_order"]; ?></td>
                      <td><?= $data_order["jam_order"]; ?></td>
                      <td><?= $data_order["nama_pelayan"]; ?></td>
                      <td><span class="badge badge-info">Meja <?= $data_order["no_meja"]; ?></span></td>
                      <td class="font-weight-bold">Rp<?php echo number_format($data_order["total_bayar"], 0, ',', '.'); ?></td>
                      <td><span class="badge badge-success"><?= $data_order["status_order"]; ?></span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Order Details Table -->
            <div class="card-body border-top">
              <div class="table-responsive rounded">
                <table class="table table-hover table-bordered mb-0" id="myTable">
                  <?php if (!empty($data_order_detail)) : ?>
                    <thead class="bg-primary text-white">
                      <tr class="text-center">
                        <th class="align-middle">No</th>
                        <th class="align-middle">ID Order Detail</th>
                        <th class="align-middle">Nama Menu</th>
                        <th class="align-middle">Harga</th>
                        <th class="align-middle">Jumlah</th>
                        <th class="align-middle">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 1; ?>
                      <?php foreach ($data_order_detail as $row) : ?>
                        <tr class="text-center">
                          <td><?= $no; ?></td>
                          <td><?= $row["id_order_detail"]; ?></td>
                          <?php
                          $id_menu = $row['id_menu'];
                          $menu = query("SELECT * FROM menu WHERE id_menu = $id_menu")[0];
                          $nama_menu = $menu["nama_menu"];
                          $harga_menu = $row["harga"];
                          $jumlah_order = $row["jumlah_order"];
                          $subharga = $harga_menu * $jumlah_order;
                          ?>
                          <td class="text-left"><?= $nama_menu; ?></td>
                          <td>Rp<?php echo number_format($harga_menu, 0, ',', '.'); ?></td>
                          <td><?= $jumlah_order; ?></td>
                          <td class="font-weight-bold">Rp<?php echo number_format($subharga, 0, ',', '.'); ?></td>
                        </tr>
                        <?php $no++; ?>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-light">
                      <tr>
                        <th colspan="5" class="text-right">Total Bayar</th>
                        <th class="text-center font-weight-bold">Rp<?php echo number_format($data_order["total_bayar"], 0, ',', '.'); ?></th>
                      </tr>
                    </tfoot>
                  <?php else : ?>
                    <tr>
                      <td class="text-center font-weight-bold py-4">BELUM ADA MENU YANG DIORDER</td>
                    </tr>
                  <?php endif; ?>
                </table>
              </div>
            </div>
          </div>

          <!-- Payment Methods Card -->
          <div class="card card-outline card-primary shadow mt-4">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-credit-card mr-2"></i>
                Cara Pembayaran
              </h3>
            </div>
            <div class="card-body">
              <div class="row justify-content-center">
                <div class="col-md-6">
                  <div class="d-grid gap-3">
                    <button type="button" class="btn btn-lg btn-primary btn-block btn-payment" data-toggle="modal" data-target="#confirmationModal">
                      <i class="fas fa-money-bill-wave mr-2"></i>
                      Bayar Cash
                    </button>
                    <a href="checkout.php?id_order=<?= $id_order; ?>" class="btn btn-lg btn-info btn-block btn-payment" name="transfer">
                      <i class="fas fa-exchange-alt mr-2"></i>
                      Transfer Bank
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <!-- Modal Konfirmasi -->
  <div class="modal fade modal-confirm" id="confirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="icon-box">
            <i class="fas fa-info-circle"></i>
          </div>
        </div>
        <div class="modal-body text-center">
          <h4 class="modal-title mb-4">Konfirmasi Pembayaran</h4>
          <p class="mb-4">Silahkan melakukan konfirmasi pembayaran ke kasir untuk menyelesaikan pesanan Anda.</p>
          
          <div class="payment-methods">
            <i class="fas fa-money-bill-wave" title="Cash"></i>
            <i class="fas fa-credit-card" title="Card"></i>
            <i class="fas fa-mobile-alt" title="Mobile Payment"></i>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <a href="order_status.php?id_order=<?= $id_order ?>" class="btn btn-success" name="home-icon" id="home-icon">
            <i class="fas fa-home mr-1"></i> Ke Beranda
          </a>
          </a>
        </div>
      </div>
    </div>
  </div>
  
  
  <script>
    // Dalam file JavaScript
  document.getElementById('home-icon').addEventListener('click', () => {
  fetch(`order_status.php?id_order=${orderId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Status pesanan berhasil diperbarui!');
      window.location.href = 'order_status.php';
    } else {
      alert(`Terjadi kesalahan: ${data.error}`);
    }
  })
  .catch(error => {
    alert(`Terjadi kesalahan: ${error}`);
  });
  });
  </script>
  
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>

</html>