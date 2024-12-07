<?php
session_start();
require "functions.php";
if (!isset($_SESSION["login"]) || $_SESSION["login"] != true) {
    header("Location: login.php");
    exit;
}
if ($_SESSION["level"] != 1 && $_SESSION["level"] != 4) {
    header("Location: index.php");
    exit;
}


// Query untuk makanan terlaris
$queryMakananTerlaris = "SELECT 
    m.nama_menu,
    m.jenis_menu,
    SUM(od.jumlah_order) as total_pesanan,
    SUM(od.jumlah_order * m.harga_menu) as total_pendapatan
FROM order_detail od
JOIN menu m ON od.id_menu = m.id_menu
JOIN order_pesanan op ON od.id_order = op.id_order
WHERE m.jenis_menu = 'Makanan'
GROUP BY m.id_menu
ORDER BY total_pesanan DESC
LIMIT 5";

// Query untuk minuman terlaris - Fixed query
$queryMinumanTerlaris = "SELECT 
    m.nama_menu,
    m.jenis_menu,
    SUM(od.jumlah_order) as total_pesanan,
    SUM(od.jumlah_order * m.harga_menu) as total_pendapatan
FROM order_detail od
JOIN menu m ON od.id_menu = m.id_menu
JOIN order_pesanan op ON od.id_order = op.id_order
WHERE m.jenis_menu = 'Minuman'
GROUP BY m.id_menu
ORDER BY total_pesanan DESC
LIMIT 5";

$resultMakanan = mysqli_query($conn, $queryMakananTerlaris);
$resultMinuman = mysqli_query($conn, $queryMinumanTerlaris);

// Validasi hasil query
if (!$resultMakanan) {
    die("Error dalam query makanan: " . mysqli_error($conn));
}
if (!$resultMinuman) {
    die("Error dalam query minuman: " . mysqli_error($conn));
}

// Filter tanggal untuk laporan utama
$where = "";
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start = mysqli_real_escape_string($conn, $_GET['start_date']);
    $end = mysqli_real_escape_string($conn, $_GET['end_date']);
    $where = "WHERE order_pesanan.tanggal_order BETWEEN '$start' AND '$end 23:59:59'";
}

// Query dengan error handling
$keungan = [];
$query = "SELECT 
            order_pesanan.*, 
            order_detail.jumlah_order,
            menu.nama_menu,
            menu.harga_menu,
            (order_detail.jumlah_order * menu.harga_menu) as subtotal
          FROM order_pesanan 
          JOIN order_detail ON order_pesanan.id_order = order_detail.id_order 
          JOIN menu ON order_detail.id_menu = menu.id_menu
          $where
          ORDER BY tanggal_order DESC";

$result = mysqli_query($conn, $query);
// Error handling untuk query
if (!$result) {
    die("Error dalam query: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {
    $keungan[] = $row;
}

// Hitung total pendapatan jika ada data
$total = 0;
if (!empty($keungan)) {
    $total = array_sum(array_column($keungan, 'total_bayar'));
}



function displayBahans() {
    global $conn;
    $sql = "SELECT * FROM bahan";
    $result = $conn->query($sql);
    $total = 0;
    
    echo "<div class='container'>";
    echo "<h2 class='mb-3'>Daftar Bahan</h2>";
    if($_SESSION["level"] === 4){
        echo "<a href='../menu/add_bahan.php' class='btn btn-primary mb-3 none'>Tambah Bahan</a>";
    }
    // echo "<a href='../menu/add_bahan.php' class='btn btn-primary mb-3'>Tambah Bahan</a>";
    
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
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_bahan"]. "</td>";
            echo "<td>" . $row["nama_bahan"]. "</td>";
            echo "<td>" . $row["stok"]. "</td>";
            echo "<td>Rp " . number_format($row["harga_beli"], 2, ',', '.') . "</td>";
            echo "<td>
                    <a href='update_bahan.php?id=" . $row["id_bahan"]. "' class='btn btn-info btn-sm me-2'>Edit</a>
                    <a href='delete_bahan.php?id=" . $row["id_bahan"]. "' class='btn btn-danger btn-sm' 
                       onclick='return confirm(\"Yakin ingin menghapus bahan ini?\");'>Hapus</a>
                  </td>";
            echo "</tr>";
            $total += $row["harga_beli"];
        }
        
        // Tampilkan total di luar loop
        echo "</tbody>";
        echo "<tfoot>";
        echo "<tr class='table-primary'>";
        echo "<td colspan='3' class='text-end'><strong>Total Nilai Bahan:</strong></td>";
        echo "<td colspan='3'><strong>Rp " . number_format($total, 2, ',', '.') . "</strong></td>";
        echo "</tr>";
        echo "</tfoot>";
        
    } else {
        echo "<tr><td colspan='6' class='text-center'>Tidak ada bahan yang tersedia.</td></tr>";
        echo "</tbody>";
    }
    
    echo "</table>";
    echo "</div>";

    return $total;
}


function hitungKeuangan() {
    global $conn;
    $keuangan = [];
    $total = displayBahans();

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

    while ($row = mysqli_fetch_assoc($result)) {
        $keuangan[] = $row;
    }

    // Hitung total pendapatan
    $totalPendapatan = 0;
    if (!empty($keuangan)) {
        $totalPendapatan = array_sum(array_column($keuangan, 'total_bayar'));
    }
    
    // Query untuk total pembelian bahan
    $queryBahan = "SELECT SUM(harga_beli * stok) as total_pembelian FROM bahan";
    $resultBahan = mysqli_query($conn, $queryBahan);
    
    if (!$resultBahan) {
        die("Error dalam query bahan: " . mysqli_error($conn));
    }
    
    // Hitung pendapatan bersih
    $pendapatanBersih = $totalPendapatan - $total ;
    
    echo "<div class='container mt-4'>";
    // Tabel Detail Transaksi
 
    // Ringkasan Keuangan
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
    if($totalPendapatan > 0) {
        $persenPembelian = ($total / $totalPendapatan) * 100;
        $persenBersih = 100 - $persenPembelian;
        
        echo "<div class='mt-4'>";
        echo "<h5>Persentase Biaya dan Pendapatan</h5>";
        echo "<div class='progress' style='height: 25px;'>";
        echo "<div class='progress-bar bg-warning' role='progressbar' style='width: {$persenPembelian}%'>";
        echo "Biaya Bahan (" . number_format($persenPembelian, 1) . "%)";
        echo "</div>";
        echo "<div class='progress-bar bg-success' role='progressbar' style='width: {$persenBersih}%'>";
        echo "Pendapatan Bersih (" . number_format($persenBersih, 1) . "%)";
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
    <title>Laporan Keuangan Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .date-filter {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .total-section {
            background: #198754;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            margin-bottom: 20px;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .status-pending {
            background-color: #ffc107;
            color: black;
        }
        .status-selesai {
            background-color: #198754;
            color: white;
        }
        .status-diproses {
            background-color: #0dcaf0;
            color: white;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .card {
                box-shadow: none !important;
            }
            .table {
                border: 1px solid #dee2e6;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark mb-4 no-print">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Laporan Keuangan Restaurant</span>
        </div>
    </nav>
    
    <div class="container">
        <!-- Filter Tanggal -->
        <div class="card date-filter no-print">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" name="start_date" value="<?= isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : '' ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="end_date" value="<?= isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : '' ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="?" class="btn btn-secondary">Reset</a>
                        <button type="button" class="btn btn-success" onclick="window.print()">Print</button>
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Total Pendapatan -->
        <div class="total-section">
            <h4 class="m-0">Total Pendapatan: Rp <?= number_format($total, 0, ',', '.') ?></h4>
        </div>

        <!-- Tabel Laporan -->
        <div class="card">
            <div class="card-body">
                <?php if (empty($keungan)): ?>
                    <div class="alert alert-info">Tidak ada data untuk ditampilkan</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped" id="laporanTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelayan</th>
                                    <th>Menu</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                    <th>Tanggal Order</th>
                                    <th>Tipe Pembayaran</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($keungan as $row): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row["nama_pelayan"]); ?></td>
                                        <td><?= htmlspecialchars($row["nama_menu"]); ?></td>
                                        <td><?= $row["jumlah_order"]; ?></td>
                                        <td>Rp <?= number_format($row["harga_menu"], 0, ',', '.'); ?></td>
                                        <td>Rp <?= number_format($row["subtotal"], 0, ',', '.'); ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($row["tanggal_order"])); ?></td>
                                        <td><?= htmlspecialchars($row["payment_type"]); ?></td>
                                        <td>
                                            <span class="badge status-<?= strtolower($row["status_order"]); ?>">
                                                <?= htmlspecialchars($row["status_order"]); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan="5" class="text-end"><strong>Total Keseluruhan:</strong></td>
                                    <td colspan="3"><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-dark bg-dark mb-4 no-print">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Laporan makanan dan minuman terlaris</span> 
            <span class="text-white">DI Restaurant</span>
        </div>
    </nav>
    
    <div class="container">
        <!-- Menu Statistics -->
        <div class="row menu-stats no-print">
            <!-- Makanan Terlaris -->
            <div class="col-md-6 mb-4">
                <div class="card stats-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Top 5 Makanan Terlaris</h5>
                    </div>
                    <div class="card-body">
                        <?php while ($row = mysqli_fetch_assoc($resultMakanan)) : ?>
                            <div class="menu-item">
                                <div class="menu-name"><?= htmlspecialchars($row['nama_menu']) ?></div>
                                <div class="menu-count">Terjual: <?= $row['total_pesanan'] ?> porsi</div>
                                <div class="menu-income">Pendapatan: Rp <?= number_format($row['total_pendapatan'], 0, ',', '.') ?></div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            
            <!-- Minuman Terlaris -->
            <div class="col-md-6 mb-4">
                <div class="card stats-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">Top 5 Minuman Terlaris</h5>
                    </div>
                    <div class="card-body">
                        <?php while ($row = mysqli_fetch_assoc($resultMinuman)) : ?>
                            <div class="menu-item">
                                <div class="menu-name"><?= htmlspecialchars($row['nama_menu']) ?></div>
                                <div class="menu-count">Terjual: <?= $row['total_pesanan'] ?> porsi</div>
                                <div class="menu-income">Pendapatan: Rp <?= number_format($row['total_pendapatan'], 0, ',', '.') ?></div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <nav class="navbar navbar-dark bg-dark mb-4 no-print">
            <div class="container">
                <span class="navbar-brand mb-0 h1">Laporan Bahan</span>
            </div>
        </nav>
        <?php
            hitungKeuangan()
        ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#laporanTable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "pageLength": 10,
                "order": [[6, "desc"]] // Urutkan berdasarkan tanggal
            });
        });
    </script>
</body>
</html>