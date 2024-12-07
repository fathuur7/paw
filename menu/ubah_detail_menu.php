<?php
session_start();

include '../functions.php';

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Ambil ID menu bahan dari URL
$id_menu_bahan = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Cek apakah ID bahan valid
if ($id_menu_bahan <= 0) {
    echo "<script>alert('ID bahan tidak valid.'); window.location.href='detail_menu.php';</script>";
    exit();
}

// Query untuk mendapatkan detail bahan
$sql = "SELECT mb.keterangan, mb.jumlah_bahan, b.nama_bahan, m.id_menu
        FROM menu_bahan mb
        JOIN bahan b ON mb.id_bahan = b.id_bahan
        JOIN menu m ON mb.id_menu = m.id_menu
        WHERE mb.id_menu_bahan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_menu_bahan);
$stmt->execute();
$result = $stmt->get_result();
$bahan = $result->fetch_assoc();

if (!$bahan) {
    echo "<script>alert('Bahan tidak ditemukan.'); window.location.href='detail_menu.php';</script>";
    exit();
}

// Tangani form pengubahan data bahan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_bahan = $_POST['nama_bahan'];
    $jumlah_bahan = $_POST['jumlah_bahan'];
    $keterangan = $_POST['keterangan'];

    // Query untuk memperbarui data bahan
    $sql_update = "UPDATE menu_bahan mb
                   JOIN bahan b ON mb.id_bahan = b.id_bahan
                   SET b.nama_bahan = ?, mb.jumlah_bahan = ?, mb.keterangan = ?
                   WHERE mb.id_menu_bahan = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sisi", $nama_bahan, $jumlah_bahan, $keterangan, $id_menu_bahan);

    if ($stmt_update->execute()) {
        echo "<script>alert('Data bahan berhasil diperbarui.'); window.location.href='detail_menu.php?id=" . $bahan['id_menu'] . "';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui data bahan.');</script>";
    }

    $stmt_update->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Bahan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Ubah Data Bahan</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nama_bahan" class="form-label">Nama Bahan</label>
                <input type="text" name="nama_bahan" id="nama_bahan" class="form-control" value="<?php echo htmlspecialchars($bahan['nama_bahan']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="jumlah_bahan" class="form-label">Jumlah Bahan</label>
                <input type="number" name="jumlah_bahan" id="jumlah_bahan" class="form-control" value="<?php echo htmlspecialchars($bahan['jumlah_bahan']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="form-control" required><?php echo htmlspecialchars($bahan['keterangan']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui Data</button>
            <a href="javascript:history.back()" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>