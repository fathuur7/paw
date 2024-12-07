<?php
session_start();

if (isset($_SESSION["login"]) && $_SESSION['login'] == true) {
  header("Location: index.php");
  exit;
}

include "./functions.php";

if (isset($_POST["login"]) || isset($_POST["guest"])) {
  $username = isset($_POST["login"]) ? $_POST["username"] : "guest";
  $password = isset($_POST["login"]) ? $_POST["password"] : "guest";
  
  if (empty($username) || empty($password)) {
    $error = "Username dan Password harus diisi.";
  } else {
    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
      $row = mysqli_fetch_assoc($result);
      if (md5($password) == $row["password"]) {
        $nama = $row["nama_user"];
        $_SESSION["login"] = true;
        $_SESSION["level"] = $row["level"];
        $_SESSION["login_time"] = time();
        $_SESSION["nama"] = $nama;
        
        $redirect_url = ($_SESSION["level"] != 3) ? 'index.php' : 'order/tambah_order_qr.php';
        
        echo "<script>
                alert('Selamat datang $nama!');
                document.location.href = '$redirect_url';
              </script>";
      } else {
        $error = "Password tidak cocok dengan Username.";
      }
    } else {
      $error = "Username tidak ditemukan.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
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
        .bg-login-image {
            background: #e0f7fa;
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
    
    <div class="register-container" style="min-height: 100vh; display: flex; align-items: center;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">
                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center mb-4">
                                        <h1 class="h4 text-gray-900 font-weight-bold">
                                            <i class="fas fa-sign-in-alt me-2 text-primary"></i>Login
                                        </h1>
                                    </div>
                                    
                                    <?php if (isset($error)) : ?>
                                        <div class="alert alert-danger text-center" role="alert">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <?= $error; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <form class="user" action="" method="post">
                                        <div class="form-group">
                                            <label for="username">
                                                <i class="fas fa-users-cog me-2 text-primary"></i>Username:
                                            </label>
                                            <input type="text" name="username" id="username" 
                                                   class="form-control form-control-user" 
                                                   placeholder="Masukkan username...">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">
                                                <i class="fas fa-lock me-2 text-primary"></i>Password:
                                            </label>
                                            <input type="password" name="password" id="password" 
                                                   class="form-control form-control-user" 
                                                   placeholder="Masukkan password...">
                                        </div>

                                        <div class="form-group">
                                            <button name="login" type="submit" 
                                                    class="btn btn-primary btn-user btn-block mt-4 mb-3">
                                                <i class="fas fa-sign-in-alt me-2"></i>Login
                                            </button>
                                            <button name="guest" type="submit" 
                                                    class="btn btn-primary btn-user btn-block">
                                                <i class="fas fa-user-friends me-2"></i>Masuk sebagai Guest
                                            </button>
                                        </div>
                            
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <span class="small">Belum punya akun? 
                                            <a href="register.php" class="text-primary">
                                                <i class="fas fa-user-plus me-1"></i>Daftar Sekarang
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
                           