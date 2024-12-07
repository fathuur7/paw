<?php
session_start();

$localhost = "localhost";
$username = "root";
$password = "fathur123";
$dbname = "resto";

// Buat koneksi
$conn = new mysqli($localhost, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Autentikasi pengguna
if (!isset($_SESSION["login"]) || $_SESSION['login'] != true) {
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
    } else {
        if ($_SESSION["level"] == 2) {
            header("Location: ../index.php");
            exit;
        }
        if ($_SESSION["level"] == 3) {
            header("Location: ../order/tambah_order_qr.php");
            exit;
        }
    }
}

// Fungsi untuk menampilkan daftar menu
function displayMenu() {
    global $conn;
    $sql = "SELECT m.*, GROUP_CONCAT(mb.keterangan) AS keterangan
            FROM menu m
            LEFT JOIN menu_bahan mb ON m.id_menu = mb.id_menu
            GROUP BY m.id_menu";
    $result = $conn->query($sql);
    
    ?>
    <div class="container mt-4">
        <h2 class="mb-3">Daftar Menu</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Menu</th>
                    <th>Jenis Menu</th>
                    <th>Nama Menu</th>
                    <th>Harga Menu</th>
                    <th>Stok</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["id_menu"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["jenis_menu"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["nama_menu"]) . "</td>";
                        echo "<td>Rp " . number_format($row["harga_menu"], 2, ',', '.') . "</td>";
                        echo "<td>" . htmlspecialchars($row["stok"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["keterangan"]) . "</td>";
                        echo "<td>
                                <a href='../menu/ubah_menu.php?id=" . $row["id_menu"] . "' class='btn btn-warning btn-sm'>Ubah</a>
                                <a href='../menu/hapus_menu.php?id=" . $row["id_menu"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus menu ini?\");'>Hapus</a>
                                <a href='../menu/detail_menu.php?id=" . $row["id_menu"] . "' class='btn btn-info btn-sm'>Lihat</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Tidak ada menu yang tersedia.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Fungsi untuk menampilkan daftar menu-bahan
function displayMenuBahans() {
    global $conn;
    $sql = "SELECT mb.id_menu_bahan, m.nama_menu, b.nama_bahan, mb.jumlah_bahan, mb.keterangan
            FROM menu_bahan mb
            JOIN menu m ON mb.id_menu = m.id_menu
            JOIN bahan b ON mb.id_bahan = b.id_bahan";
    $result = $conn->query($sql);
    ?>
    
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-3xl font-bold leading-7 text-gray-900 sm:text-4xl sm:truncate">
                            Daftar Menu-Bahan
                        </h2>
                        <p class="mt-2 text-sm text-gray-500">
                            Kelola daftar menu dan bahan baku yang tersedia
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="../menu/add_menu_bahan.php" 
                           class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            Tambah Menu-Bahan
                        </a>
                    </div>
                </div>
            </div>

            <!-- <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>ID Menu Bahan</th>
                            <th>Nama Menu</th>
                            <th>Nama Bahan</th>
                            <th>Jumlah Bahan</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='hover:bg-gray-50 transition-colors duration-200'>";
                                echo "<td>" . htmlspecialchars($row["id_menu_bahan"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["nama_menu"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["nama_bahan"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["jumlah_bahan"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["keterangan"]) . "</td>";
                                echo "<td><a href='../menu/delete_menu_bahan.php?id=" . $row["id_menu_bahan"] . "' onclick=\"return confirm('Apakah Anda yakin ingin menghapus item ini?');\">Hapus</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-4'>Tidak ada menu bahan yang tersedia.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div> -->
        <!-- </div>
    </div> -->
    <?php
}

// Fungsi untuk menampilkan daftar bahan
function displayBahans() {
    global $conn;
    $sql = "SELECT * FROM bahan";
    $result = $conn->query($sql);
    $total = 0;

    echo "<div class='container mt-4'>";
    echo "<h2 class='mb-3'>Daftar Bahan</h2>";
    echo "<a href='../menu/add_bahan.php' class='btn btn-primary mb-3'>Tambah Bahan</a>";

    echo "<table class='table table-striped'>";
    echo "<thead>";
    echo "<tr>
            <th>ID Bahan</th>
            <th>Nama Bahan</th>
            <th>Stok</th>
            <th>Harga Beli</th>
            <th>Aksi</th>
          </tr>";
    echo "</thead>";
    echo "<tbody>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_bahan"] . "</td>";
            echo "<td>" . $row["nama_bahan"] . "</td>";
            echo "<td>" . $row["stok"] . "</td>";
            echo "<td>Rp " . number_format($row["harga_beli"], 2, ',', '.') . "</td>";
            echo "<td>
                    <a href='../menu/edit_bahan.php?id=" . $row["id_bahan"] . "' class='btn btn-info btn-sm me-2'>Edit</a>
                    <a href='../menu/delete_bahan.php?id=" . $row["id_bahan"] . "' class='btn btn-danger btn-sm' 
                       onclick='return confirm(\"Yakin ingin menghapus bahan ini?\");'>Hapus</a>
                  </td>";
            echo "</tr>";
            $total += $row["harga_beli"];
        }
        
        echo "<tr class='table-primary'>";
        echo "</tbody>";
        echo "<tfoot>";
        echo "<tr class='table-primary'>";
        echo "<td colspan='3' class='text-end'><strong>Total Nilai Bahan:</strong></td>";
        echo "<td colspan='3'><strong>Rp " . number_format($total, 2, ',', '.') . "</strong></td>";
        echo "</tr>";
        echo "</tfoot>";
        
    } else {
        echo "<tr><td colspan='5' class='text-center'>Tidak ada bahan yang tersedia.</td></tr>";
        echo "</tbody>";
    }
    
    echo "</table>";
    echo "</div>";

    return $total;
}

// Fungsi untuk menghitung keuangan
function hitungKeuangan() {
    global $conn;
    $total = displayBahans(); // Ambil total dari bahan

    // Query untuk pendapatan
    $query = "SELECT 
                order_pesanan.*, 
                order_detail.jumlah_order,
                menu.nama_menu,
                menu.harga_menu,
                (order_detail.jumlah_order * menu.harga_menu) as subtotal
            FROM order_pesanan 
            JOIN order_detail ON order_pesanan.id_order = order_detail.id_order 
            JOIN menu ON order_detail.id_menu = menu.id_menu
            ORDER BY tanggal_order DESC";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error dalam query pendapatan: " . mysqli_error($conn));
    }

    $keuangan = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $keuangan[] = $row;
    }

    // Hitung total pendapatan
    $totalPendapatan = 0;
    if (!empty($keuangan)) {
        $totalPendapatan = array_sum(array_column($keuangan, 'total_bayar'));
    }

    // Hitung pendapatan bersih
    $pendapatanBersih = $totalPendapatan - $total;

    // Tampilkan ringkasan keuangan
    echo "<div class='container mt-4'>";
    echo "<div class='card'>";
    echo "<div class='card-header bg-success text-white'>";
    echo "<h3 class='card-title mb-0'>Ringkasan Keuangan</h3>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<table class='table table-bordered'>";
    
    echo "<tr class='table-info'>";
    echo "<td><strong>Total Pendapatan</strong></td>";
    echo "<td class='text-end'><strong>Rp " . number_format($totalPendapatan, 0, ',', '.') . "</strong></td>";
    echo "</tr>";
    
    echo "<tr class='table-warning'>";
    echo "<td><strong>Total Pembelian Bahan</strong></td>";
    echo "<td class='text-end'><strong>Rp " . number_format($total, 0, ',', '.') . "</strong></td>";
    echo "</tr>";
    
    $warnaClass = $pendapatanBersih >= 0 ? 'table-success' : 'table-danger';
    echo "<tr class='$warnaClass'>";
    echo "<td><strong>Pendapatan Bersih</strong></td>";
    echo "<td class='text-end'><strong>Rp " . number_format($pendapatanBersih, 0, ',', '.') . "</strong></td>";
    echo "</tr>";
    
    echo "</table>";

    // Progress bar persentase
    if ($totalPendapatan > 0) {
        $persenPembelian = ($total / $totalPendapatan) * 100;
        $persenBersih = 100 - $persenPembelian;
        
        echo "<div class='mt-4'>";
        echo "<h5>Persentase Biaya dan Pendapatan</h5>";
        echo "<div class='progress' style='height: 25px;'>";
        echo "<div class='progress-bar bg-success' role='progressbar' style='width: {$persenBersih}%'>";
        echo "Pendapatan Bersih (" . number_format($persenBersih, 1) . "%)";
        echo "</div>";
        echo "<div class='progress-bar bg-warning' role='progressbar' style='width: {$persenPembelian}%'>";
        echo "Biaya Bahan (" . number_format($persenPembelian, 1) . "%)";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }

    echo "</div>"; // card-body
    echo "</div>"; // card
    echo "</div>"; // container
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran - Daftar Menu dan Keuangan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .table-link {
            color: inherit;
            text-decoration: none;
        }
        .table-link:hover {
            color: black;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include "../header.php"; ?>

    <div class="container">
        <?php
        displayMenuBahans();
        displayMenu();
        hitungKeuangan();
        ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>