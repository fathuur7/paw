<?php
session_start();

include "../functions.php";

$data_menu = query("SELECT * FROM menu");

if (isset($_GET["id_order"])) {
  $id_order = $_GET["id_order"];

  $cek_id_order = mysqli_query($conn, "SELECT id_order FROM order_pesanan WHERE id_order = $id_order");
  if (mysqli_num_rows($cek_id_order) == 0) {
    echo "<script>alert('ID Order tidak ditemukan');</script>";
    die;
  }

  if (isset($_POST["selesai"])) {
    if (tambah_order_detail_qr($_POST) > 0) {
      echo "<script>
              alert('Pesanan berhasil ditambahkan! Terima kasih atas pesanannya.');
              document.location.href = 'tampil_order_detail_qr.php?id_order=$id_order';
            </script>
          ";
    } else {
      echo "<script>
              alert('Gagal menambahkan pesanan. Silakan coba lagi atau hubungi pelayan.');
              document.location.href = 'tambah_order_detail_qr.php?id_order=$id_order';
            </script>
          ";
    }
  }

  $data_order = query("SELECT * FROM order_pesanan WHERE id_order = $id_order")[0];
  $data_order_detail = query("SELECT * FROM order_detail WHERE id_order = $id_order");
} else {
  echo "<script>alert('ID Order Invalid!');</script>";
  die;
}

$nama = $_SESSION["nama"];
date_default_timezone_set('Asia/Jakarta');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Tambah Menu Pesanan</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
  <style>
    .menu-card {
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .menu-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .menu-card.selected {
      border: 2px solid #007bff;
      background-color: #f8f9fa;
    }
    .quantity-input {
      width: 80px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <?php include "../header.php" ?>
  
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 mx-auto mt-2">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title font-weight-bold">Tambah Menu Pesanan</h3>
          </div>
          
          <!-- Order Info Table -->
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-bordered mb-0">
                <thead class="table-primary text-center">
                  <tr>
                    <th class="align-middle">ID Order</th>
                    <th class="align-middle">Nama Pembeli</th>
                    <th class="align-middle">Tanggal Order</th>
                    <th class="align-middle">Jam Order</th>
                    <th class="align-middle">Pelayan</th>
                    <th class="align-middle">No. Meja</th>
                    <th class="align-middle">Total Bayar</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><?= $id_order; ?></td>
                    <td><?= $nama; ?></td>
                    <td><?= $data_order["tanggal_order"]; ?></td>
                    <td><?= $data_order["jam_order"]; ?></td>
                    <td><?= $data_order["nama_pelayan"]; ?></td>
                    <td><?= $data_order["no_meja"]; ?></td>
                    <td>
                      <div class="quantity-wrapper">
                        <span id="jumlah" class="font-weight-bold"></span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Menu Selection Form -->
          <form action="" method="post">
            <input type="hidden" name="id_order" value="<?= $id_order; ?>">
            
            <div class="card-body">
              <div class="row" id="menu-container">
                <?php foreach ($data_menu as $menu): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                  <div class="card menu-card h-100" onclick="toggleSelection(this, <?= $menu['id_menu']; ?>)">
                    <div class="card-body text-center">
                      <h5 class="card-title"><?= $menu["nama_menu"]; ?></h5>
                      <p class="card-text text-primary font-weight-bold">
                        Rp<?= number_format($menu["harga_menu"], 0, ',', '.'); ?>
                      </p>
                      <div class="quantity-wrapper" style="display: none;">
                        <div class="form-group">
                          <label>Jumlah:</label>
                          <input type="number" class="form-control quantity-input" 
                                 name="jumlah_order[]" min="1" value="1">
                          <input type="hidden" name="id_menu[]" value="<?= $menu['id_menu']; ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="card-footer text-center">
              <button type="submit" class="btn btn-primary" name="selesai">Selesai</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    function toggleSelection(card, menuId) {
  const quantityWrapper = $(card).find('.quantity-wrapper');
  
  if ($(card).hasClass('selected')) {
    $(card).removeClass('selected');
    quantityWrapper.hide();
    $(card).find('input[name="id_menu[]"]').prop('disabled', true);
    $(card).find('input[name="jumlah_order[]"]').prop('disabled', true);
  } else {
    $(card).addClass('selected');
    quantityWrapper.show();
    $(card).find('input[name="id_menu[]"]').prop('disabled', false);
    $(card).find('input[name="jumlah_order[]"]').prop('disabled', false);
  }
  updateOrderSummary();
}

function updateOrderSummary() {
  let details = [];
  let totalBayar = 0;

  $('.menu-card.selected').each(function() {
    const menuName = $(this).find('.card-title').text();
    const hargaText = $(this).find('.card-text').text();
    const harga = parseInt(hargaText.replace('Rp', '').replace(/\./g, ''));
    const jumlahOrder = parseInt($(this).find('input[name="jumlah_order[]"]').val()) || 0;
    const subtotal = harga * jumlahOrder;

    totalBayar += subtotal;
    
    if (jumlahOrder > 0) {
      details.push(`${menuName} (${jumlahOrder}x - Rp${subtotal.toLocaleString('id-ID')})`);
    }
  });

  const jumlah = document.getElementById('jumlah');
  if (details.length > 0) {
    jumlah.innerHTML = `
      <div>Pesanan baru: ${details.join(', ')}</div>
      <div class="mt-1">Total: Rp${totalBayar.toLocaleString('id-ID')}</div>
    `;
  } else {
    jumlah.innerHTML = '';
  }
}

// Initialize all quantity inputs as disabled and set up event listeners
$(document).ready(function() {
  $('input[name="id_menu[]"]').prop('disabled', true);
  $('input[name="jumlah_order[]"]').prop('disabled', true);

  // Add event listener for quantity changes
  $(document).on('input', 'input[name="jumlah_order[]"]', function() {
    updateOrderSummary();
  });
});
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>
</html>