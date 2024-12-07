<?php
session_start();

if (isset($_SESSION["login"]) && $_SESSION['login'] == true) {
  header("Location: index.php");
  exit;
}

include "./functions.php";

$error = "";

if (isset($_POST["register"])) {
  // Validasi field tidak boleh kosong
  if (empty(trim($_POST["nama_user"])) || 
      empty(trim($_POST["username"])) || 
      empty(trim($_POST["password1"])) || 
      empty(trim($_POST["password2"]))) {
    $error = "Semua field harus diisi!";
  } 
  // Validasi panjang username
  elseif (strlen($_POST["username"]) < 4 || strlen($_POST["username"]) > 20) {
    $error = "Username harus antara 4-20 karakter!";
  }
  // Validasi username hanya huruf dan angka
  elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $_POST["username"])) {
    $error = "Username hanya boleh berisi huruf, angka, dan underscore!";
  }
  // Validasi panjang nama user
  elseif (strlen($_POST["nama_user"]) < 3 || strlen($_POST["nama_user"]) > 50) {
    $error = "Nama user harus antara 3-50 karakter!";
  }
  // Validasi panjang password
  elseif (strlen($_POST["password1"]) < 6) {
    $error = "Password minimal 6 karakter!";
  }
  // Validasi konfirmasi password
  elseif (strtolower(stripslashes($_POST["password1"])) !== strtolower(stripslashes($_POST["password2"]))) {
    $error = "Password tidak cocok dengan Konfirmasi Password.";
  }
  // Jika semua validasi lolos
  else {
    // Proses registrasi
    if (register($_POST) > 0) {
      echo "<script>
              alert('User baru berhasil disimpan!');
              document.location.href = 'login.php';
            </script>";
    } else {
      echo "<script>
              alert('Data user gagal disimpan!');
              document.location.href = 'register.php';
            </script>";
    }
  }
}
?>

<!-- Sisanya tetap sama dengan kode html sebelumnya -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Restoran</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css">
  <style>
    /* body {
      background: linear-gradient(135deg, #6bffff, #000000);
      height: 100vh;
      display: flex;
      flex-direction: column;
    } */
    .register-container {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-grow: 1;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      overflow: hidden;
    }
    .card-header {
      background: linear-gradient(to right, #6bffff, #4a90e2);
      color: white;
      text-align: center;
      padding: 20px;
    }
    .form-control {
      border-radius: 25px;
      transition: all 0.3s ease;
    }
    .form-control:focus {
      box-shadow: 0 0 10px rgba(107, 255, 255, 0.5);
      border-color: #6bffff;
    }
    .btn-primary {
      background: linear-gradient(to right, #6bffff, #4a90e2);
      border: none;
      border-radius: 25px;
      transition: transform 0.3s ease;
    }
    .btn-primary:hover {
      transform: scale(1.05);
      background: linear-gradient(to right, #4a90e2, #6bffff);
    }
    .navbar {
      background: rgba(0,0,0,0.8) !important;
    }
    .bg-register-image {
      background: #e0f7fa; /* Fallback untuk browser yang tidak mendukung SVG */
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" fill="%236bffff">') +
        '<path d="M40 60 Q100 10 160 60 T280 60" fill="none" stroke="%234a90e2" stroke-width="3"/>' +
        '<circle cx="100" cy="100" r="50" fill="rgba(107,255,255,0.2)"/>' +
        '<circle cx="150" cy="150" r="30" fill="rgba(74,144,226,0.2)"/>' +
        '</svg>' no-repeat center center;
      background-size: cover;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand text-white" href="./index.php">
        <i class="fas fa-utensils mr-2"></i><strong>Restoran</strong>
      </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item mr-3">
            <a class="nav-link font-weight-bold" href="./login.php">
              <i class="fas fa-sign-in-alt mr-1"></i>Login
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" href="./register.php">
              <i class="fas fa-user-plus mr-1"></i>Register
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="register-container"  style="min-height: 100vh; display: flex; align-items: center;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
          <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="row">
              <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
              <div class="col-lg-7">
                <div class="p-5">
                  <div class="text-center mb-4">
                    <h1 class="h4 text-gray-900 font-weight-bold">
                      <i class="fas fa-user-circle mr-2 text-primary"></i>Buat Akun Baru
                    </h1>
                  </div>
                  
                  <!-- Register Form -->
                <?php 
                  if(isset($error)) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                  }
                ?>

                  <form class="user" action="" method="post">
                    <div class="form-group">
                      <label for="nama_user"><i class="fas fa-user mr-2 text-primary"></i>Nama User:</label>
                      <input type="text" name="nama_user" id="nama_user" class="form-control form-control-user" placeholder="Masukkan nama user...">
                    </div>
                    <div class="form-group">
                      <label for="username"><i class="fas fa-users-cog mr-2 text-primary"></i>Username:</label>
                      <input type="text" name="username" id="username" class="form-control form-control-user" placeholder="Masukkan username...">
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-6 mb-3 mb-sm-0">
                        <label for="password1"><i class="fas fa-lock mr-2 text-primary"></i>Password:</label>
                        <input type="password" name="password1" id="password1" class="form-control form-control-user" placeholder="Masukkan password...">
                      </div>
                      <div class="col-sm-6">
                        <label for="password2"><i class="fas fa-lock mr-2 text-primary"></i>Konfirmasi Password:</label>
                        <input type="password" name="password2" id="password2" class="form-control form-control-user" placeholder="Ulangi password...">
                      </div>
                    </div>
                    <button name="register" type="submit" class="btn btn-primary btn-user btn-block mt-4">
                      <i class="fas fa-user-plus mr-2"></i>Daftar
                    </button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <span class="small">Sudah punya akun user? 
                      <a href="login.php" class="text-primary">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                      </a>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
  

      </div>
    </div>

  </div>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>

</html>