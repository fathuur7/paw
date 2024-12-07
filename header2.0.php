<?php
if (!isset($_SESSION)) {
  session_start();
}
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<!-- Background gradient -->
<div class="bg-gradient-to-br from-cyan-400 to-blue-800">

  <!-- Navigation -->
  <nav class="fixed top-0 left-0 right-0 z-50 bg-gray-900/95 backdrop-blur-sm shadow-lg">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between h-16">
        
        <!-- Logo -->
        <a href="../index.php" class="flex items-center space-x-2 text-cyan-400 hover:text-white transition-colors duration-200">
          <i class="fas fa-utensils"></i>
          <span class="text-xl font-bold">Restoran</span>
        </a>

        <!-- Mobile menu button -->
        <button class="lg:hidden text-white hover:text-cyan-400 focus:outline-none" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" 
                aria-controls="navbarNav" 
                aria-expanded="false" 
                aria-label="Toggle navigation">
          <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Navigation links -->
        <div class="hidden lg:flex items-center space-x-4" id="navbarNav">
          <?php if ($_SESSION["level"] != 3) : ?>
            <a href="../index.php" 
               class="text-gray-300 hover:text-white hover:bg-gray-800/50 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
              <i class="fas fa-home mr-2"></i>Beranda
            </a>
            
            <?php if ($_SESSION["level"] == 1 || $_SESSION["level"] == 4) : ?>
              <a href="../menu/tampil_menu.php" 
                 class="text-gray-300 hover:text-white hover:bg-gray-800/50 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                <i class="fas fa-book-open mr-2"></i>Menu
              </a>
            <?php endif; ?>
            
            <a href="../order/tampil_order.php" 
               class="text-gray-300 hover:text-white hover:bg-gray-800/50 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
              <i class="fas fa-clipboard-list mr-2"></i>Order
            </a>
            
            <a href="../order/order_qr.php" 
               class="text-gray-300 hover:text-white hover:bg-gray-800/50 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
              <i class="fas fa-qrcode mr-2"></i>QR Code
            </a>
          <?php endif; ?>
          
          <a href="../logout.php" 
             class="text-gray-300 hover:text-white hover:bg-red-600/50 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
            <i class="fas fa-sign-out-alt mr-2"></i>Logout
          </a>
        </div>

        <!-- Mobile menu -->
        <div class="lg:hidden collapse" id="navbarNav">
          <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-900 rounded-md mt-2">
            <?php if ($_SESSION["level"] != 3) : ?>
              <a href="../index.php" 
                 class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                <i class="fas fa-home mr-2"></i>Beranda
              </a>
              
              <?php if ($_SESSION["level"] == 1 || $_SESSION["level"] == 4) : ?>
                <a href="../menu/tampil_menu.php" 
                   class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                  <i class="fas fa-book-open mr-2"></i>Menu
                </a>
              <?php endif; ?>
              
              <a href="../order/tampil_order.php" 
                 class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                <i class="fas fa-clipboard-list mr-2"></i>Order
              </a>
              
              <a href="../order/order_qr.php" 
                 class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                <i class="fas fa-qrcode mr-2"></i>QR Code
              </a>
            <?php endif; ?>
            
            <a href="../logout.php" 
               class="text-gray-300 hover:text-white hover:bg-red-600/50 block px-3 py-2 rounded-md text-base font-medium">
              <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </a>
          </div>
        </div>

      </div>
    </div>
  </nav>

  <!-- Spacer to prevent content from hiding under fixed navbar -->
  <div class="h-16"></div>

</div>