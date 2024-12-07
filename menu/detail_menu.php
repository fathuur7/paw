<?php
session_start();

require_once "../functions.php";

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Ambil ID menu dari URL
$id_menu = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mendapatkan detail menu dan bahan
$sql = "SELECT m.nama_menu, b.nama_bahan, mb.jumlah_bahan, mb.keterangan, mb.id_menu_bahan
        FROM menu_bahan mb
        JOIN menu m ON mb.id_menu = m.id_menu
        JOIN bahan b ON mb.id_bahan = b.id_bahan
        WHERE m.id_menu = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_menu);
$stmt->execute();
$result = $stmt->get_result();

// Query untuk mendapatkan nama menu
$sql_menu = "SELECT nama_menu FROM menu WHERE id_menu = ?";
$stmt_menu = $conn->prepare($sql_menu);
$stmt_menu->bind_param("i", $id_menu);
$stmt_menu->execute();
$result_menu = $stmt_menu->get_result();
$menu = $result_menu->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Menu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
        <h2>Detail Menu: <?php echo htmlspecialchars($menu['nama_menu']); ?></h2>
        <a href="add_bahann.php?id_menu=<?php echo $id_menu; ?>" class="btn btn-primary mb-3">Tambah Bahan</a>
        
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Bahan</th>
                        <th>Jumlah Bahan</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama_bahan']); ?></td>
                            <td><?php echo htmlspecialchars($row['jumlah_bahan']); ?></td>
                            <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                            <td>
                                <a href="ubah_detail_menu.php?id=<?php echo $row['id_menu_bahan']; ?>" class="btn btn-warning btn-sm">Ubah</a>
                                <a href="delete_bahan.php?id=<?php echo $row['id_menu_bahan']; ?>&id_menu=<?php echo $id_menu; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus bahan ini?');">Hapus</a>
                            </td>
                            </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada bahan yang digunakan untuk menu ini.</p>
        <?php endif; ?>
        <a href="../order/chef.php" class="btn btn-secondary">Kembali</a>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>