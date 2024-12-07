<?php
session_start();

include '../functions.php';

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Ambil ID menu dari URL
$id_menu = isset($_GET['id_menu']) ? intval($_GET['id_menu']) : 0;

// Query untuk mendapatkan daftar bahan
$sql = "SELECT * FROM bahan";
$result = $conn->query($sql);

// Tangani form penambahan bahan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_bahan = intval($_POST['id_bahan']);
    $jumlah_bahan = intval($_POST['jumlah_bahan']);
    $keterangan = $_POST['keterangan'];

    // Query untuk menambahkan bahan ke menu
    $sql_insert = "INSERT INTO menu_bahan (id_menu, id_bahan, jumlah_bahan, keterangan) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("iiis", $id_menu, $id_bahan, $jumlah_bahan, $keterangan);

    if ($stmt->execute()) {
        // Update stok bahan setelah berhasil menambahkan
        $sql_update = "UPDATE bahan SET stok = stok - ? WHERE id_bahan = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $jumlah_bahan, $id_bahan);

        if ($stmt_update->execute()) {
            echo "<script>alert('Bahan berhasil ditambahkan dan stok diperbarui.'); window.location.href='detail_menu.php?id=$id_menu';</script>";
        } else {
            echo "<script>alert('Bahan berhasil ditambahkan, tetapi gagal memperbarui stok.');</script>";
        }

        $stmt_update->close();
    } else {
        echo "<script>alert('Terjadi kesalahan saat menambahkan bahan.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Bahan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Tambah Bahan ke Menu</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="id_bahan" class="form-label">Pilih Bahan</label>
                <select name="id_bahan" id="id_bahan" class="form-select" required>
                    <option value="">-- Pilih Bahan --</option>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_bahan']; ?>"><?php echo htmlspecialchars($row['nama_bahan']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="jumlah_bahan" class="form-label">Jumlah Bahan</label>
                <input type="number" name="jumlah_bahan" id="jumlah_bahan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Tambah Bahan</button>
            <a href="detail_menu.php?id=<?php echo $id_menu; ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>