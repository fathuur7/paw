<?php
session_start();

if (!isset($_SESSION["login"]) && $_SESSION['login'] != true) {
  header("Location: login.php");
  exit;
} else {
  $menit = 15;
  $batas_waktu = $menit * 60;
  if (time() - $_SESSION["login_time"] > $batas_waktu) {
    echo "<script>
            alert('Sesi Anda sudah habis. Silahkan login kembali!');
            document.location.href = 'logout.php';
          </script>";
  } else {
    if ($_SESSION["level"] == 3) {
      header("Location: order/tambah_order_qr.php");
      exit;
    }
  }
}

include "./functions.php";

$menu = query("SELECT * FROM menu");
$order = query("SELECT * FROM order_pesanan");
$jumlah_menu = count($menu);
$jumlah_order = count($order);

$test = "session level : " . $_SESSION["level"];

$role = "";
if($_SESSION["level"] == 1){
  $role = "admin";
}
elseif($_SESSION["level"] == 2){
  $role = "kasir";
}
elseif($_SESSION["level"] == 4){
  $role = "manager";
}
elseif($_SESSION["level"] == 5){
  $role = "chef";
}

// $nama = $_SESSION["nama"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
      background-attachment: fixed;
      min-height: 100vh;
    }
    
    .navbar {
      background: rgba(0, 0, 0, 0.8) !important;
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .navbar-brand {
      font-size: 1.5rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .nav-link {
      text-transform: uppercase;
      font-size: 0.9rem;
      letter-spacing: 1px;
      padding: 0.5rem 1rem !important;
      margin: 0 0.2rem;
      border-radius: 5px;
      transition: all 0.3s ease;
    }

    .nav-link:hover {
      background: rgba(255,255,255,0.1);
      transform: translateY(-2px);
    }

    .jumbotron {
      background: rgba(255, 255, 255, 0.1) !important;
      backdrop-filter: blur(10px);
      border-radius: 20px !important;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      border: 1px solid rgba(255,255,255,0.2);
      margin-bottom: 2rem;
    }

    .display-5 {
      font-weight: 700;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .lead {
      font-weight: 400;
      letter-spacing: 0.5px;
    }

    .small-box {
      border-radius: 15px;
      overflow: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }

    .small-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 40px rgba(0,0,0,0.2);
    }

    .small-box .icon {
      top: 5px;
      right: 10px;
      font-size: 70px;
      opacity: 0.3;
    }

    .small-box .inner {
      padding: 20px;
    }

    .small-box h3 {
      font-size: 38px;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .small-box p {
      font-size: 1.1rem;
      font-weight: 500;
    }

    .small-box .small-box-footer {
      background: rgba(0,0,0,0.1);
      padding: 8px;
      font-weight: 500;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }

    .small-box .small-box-footer:hover {
      background: rgba(0,0,0,0.2);
      padding-right: 25px;
    }

    .bg-primary {
      background: linear-gradient(45deg, #4e54c8, #8f94fb) !important;
    }

    .bg-success {
      background: linear-gradient(45deg, #11998e, #38ef7d) !important;
    }

    @media (max-width: 768px) {
      .jumbotron {
        padding: 2rem 1rem;
      }
      
      .display-5 {
        font-size: 1.8rem;
      }
      
      .lead {
        font-size: 1rem;
      }
    }
  </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand text-white" href="./index.php">
        <i class="fas fa-utensils me-2"></i>
        <strong>Restoran</strong>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <?php if ($_SESSION["level"] == 1) : ?>
            <li class="nav-item">
              <a class="nav-link font-weight-bold" href="./menu/tampil_menu.php">
                <i class="fas fa-book-open me-1"></i> Menu
              </a>
            </li>
            <li>
              <a class="nav-link font-weight-bold" href="keterangan.php">
                <i class="fas fa-chart-line me-1"></i> Cek Keuangan
              </a>
            </li>
            <li>
              <a href="./order/chef.php" class="nav-link font-weight-bold">
                <i><i class="fas fa-user-tie me-1"></i> Chef</i>
              </a>
            </li>
            <li>
              <a href="./admin/admin.php" class="nav-link font-weight-bold">
                <i><i class="fas fa-user-tie me-1"></i> Admin</i>
              </a>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION["level"] == "4") : ?>
            <li class="nav-item">
              <a class="nav-link font-weight-bold" href="keterangan.php">
                <i class="fas fa-chart-line me-1"></i> Cek Keuangan
              </a>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION["level"] == "5") : ?>
            <!-- <li class="nav-item">
              <a class="nav-link font-weight-bold" href="./menu/tampil_menu.php">
                <i class="fas fa-book-open me-1"></i> Menu
              </a>
            </li> -->
            <li>
            <li>
              <a href="./order/chef.php" class="nav-link font-weight-bold">
                <i><i class="fas fa-user-tie me-1"></i> Chef</i>
              </a>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION["level"] == 3) : ?>
            <li class="nav-item">
              <a class="nav-link font-weight-bold" href="./order/tambah_order_qr.php">
                <i class="fas fa-shopping-cart me-1"></i> Order
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link font-weight-bold" href="./order/order_qr.php">
                <i class="fas fa-qrcode me-1"></i> QR Code
              </a>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" href="./logout.php">
              <i class="fas fa-sign-out-alt me-1"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div style="height: 80px;"></div>

  <section class="content">
    <div class="container">
      <div class="jumbotron text-center text-white pt-5 pb-5">
        <h1 class="display-5">Selamat Datang <?= $_SESSION["nama"]; ?> di Restoran</h1>
        <p class="lead">Nikmati menu lezat kami dan pesan sekarang.</p>
        <?php if ($_SESSION["level"] == 1) : ?>
          <p class="lead font-weight-bold">Level Anda adalah Admin</p>
          <hr class="my-4">
          <p>Jangan lupa untuk menjelajahi daftar menu dan pesanan yang sudah ada.</p>
        <?php else : ?>
          <p class="lead font-weight-bold">Anda Sebagai <?= $role; ?> </p>
          <hr class="my-4">
          <p>Jangan lupa untuk menjelajahi daftar pesanan yang sudah ada.</p>
        <?php endif; ?>
      </div>

      <div class="row justify-content-center">
        <?php if ($_SESSION["level"] == 1) : ?>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3><?= $jumlah_menu; ?></h3>
                <p>Daftar Menu</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="./menu/tampil_menu.php" class="small-box-footer">
                Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
        <?php endif; ?>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?= $jumlah_order; ?></h3>
              <p>Data Order</p>
            </div>
            <div class="icon">
              <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="./order/tampil_order.php" class="small-box-footer">
              Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>

</html>